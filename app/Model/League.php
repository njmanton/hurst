<?php

class League extends AppModel {

	public $name = 'League';
	public $actsAs = array('Containable');
	public $hasMany = array('LeagueUser');
	public $belongsTo = array('User' => array('foreignKey' => 'organiser'));

	public function getLeagues() {

		$sql = 'SELECT
						L.id,
						L.name,
						L.public,
						U.id,
						U.username AS organiser,
						COUNT(LU.id) AS members
						FROM leagues L
						LEFT JOIN league_users LU ON L.id = LU.league_id
						LEFT JOIN users U ON U.id = L.organiser
						WHERE L.pending = 0
						GROUP BY
						L.id, L.description, L.public, U.id, U.username';
		$db = $this->getDataSource();
		$res = $db->fetchAll($sql);

		return $res;

	} // end getLeagues

	public function processJoin($id, $user) {

		$league = $this->findById($id);
		if (empty($league)) {

			$res = array('msg' => 'Sorry, that league does not exist', 'class' => 'warning');

		} elseif ($this->in_league($user['id'], $id, 1)) {

			$res = array('msg' => 'You are already a member of that league', 'class' => 'info');

		} elseif ($this->in_league($user['id'], $id, 0)) {
			//
			$res = array('msg' => 'You already have a pending application to join that league', 'class' => 'info');

		} else {

			$public = ($league['League']['public']);
			$tosave = array('LeagueUser' => array(
				'user_id' => $user['id'],
				'league_id' => $id,
				'confirmed' => $public // if public league, confirmed = 1 automatically
			));

			if ($this->LeagueUser->save($tosave)) {

				if ($public) {
					$res = array('msg' => __('You have now joined \'%s\' ', $league['League']['name']), 'class' => 'success');
				}	else {
					$res = array('msg' => __('Your application to join \'%s\' has been sent to the league organiser', $league['League']['name']), 'class' => 'success');
					// email organiser
					$to = $league['User']['email'];
					$subject = 'New League Application';
					try {
						$email = new CakeEmail('default');
						$email->to($to)
									->from(array(SENDER => APP_NAME))
									->subject($subject)
									->template('league_join')
									->viewVars(array(
										'organiser' => $league['User']['username'],
										'user' => $user['username'],
										'league' => $league['League']['name'],
										'lid' => $league['League']['id']
									));
						$sent = $email->send();
						$res['email'] = $sent;
					} catch (SocketException $e) {
						$this->log(__('Error in sending mail to $s', $to), 'admin');
					}
					
				}

			} else {

				$res = array('msg' => 'Sorry, your request was not able to be processed at this time', 'class' => 'warning');

			}

		}

		return $res;

	} // end processJoin

	/*----------------------------------------------------
	Function:		in_league
	Desc:				determines if user in a league or not
	Params:			user id, league id, 0/1 confirmed
	Returns:		bool - in league or not

	Date:				4/1/14
	----------------------------------------------------*/
	private function in_league($uid, $lid, $conf) {

		if ($conf == 1) {
			$sql = 'SELECT COUNT(id) AS cnt FROM league_users WHERE confirmed = 1 AND league_id = ? AND user_id = ?';
		} else {
			$sql = 'SELECT COUNT(id) AS cnt FROM league_users WHERE confirmed = 0 AND league_id = ? AND user_id = ?';
		}
		$db = $this->getDataSource();
		$res = $db->fetchAll($sql, array($lid, $uid));

		return ($res[0][0]['cnt']);

	} // end in_league

} // end class
