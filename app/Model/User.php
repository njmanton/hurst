<?php

App::uses('AuthComponent', 'Controller/Component');
App::uses('CakeEmail', 'Network/Email');

class User extends AppModel {
	public $name = 'User';
	public $hasMany = array(
		'Prediction',
		'LeagueUser',
		'Post',
		'League' => array(
			'className'  => 'League',
			'foreignKey' => 'organiser'
		),
		'Invitees' => [
			'className' => 'User',
			'foreignKey' => 'referredby'
		]
	);
	public $belongsTo = [
		'Referer' => [
			'className' => 'User',
			'foreignKey' => 'referredby'
		]
	];

	public $actsAs = array('Containable');

	/*----------------------------------------------------
	Function:		getPreds
	Desc:				Get all predictions into array for given
							user id
	Params:			user $id
	Returns:		array of predictions
	Date:				15/12/13
	----------------------------------------------------*/
	public function getPreds($id = null, $self = null) {

		$sql = 'SELECT
							P.id,
							M.id,
							M.`date`,
							M.teama_id,
							M.teamb_id,
							TA.name,
							TB.name,
							M.result,
							M.`group`,
							M.stage,
							P.joker,
							P.prediction,
							P.points,
							V.utc_offset AS tz
							FROM matches M
							LEFT JOIN predictions P on (M.id = P.match_id AND P.user_id = ?)
							INNER JOIN venues V ON V.id = M.venue_id
							INNER JOIN teams TA ON TA.id = M.teama_id
							INNER JOIN teams TB ON TB.id = M.teamb_id
							ORDER BY M.stage_order_kofirst DESC, M.date';
		$db = $this->getDataSource();
		$res = $db->fetchAll($sql, array($id));

		$arr = [];
		$now = new DateTime();
		foreach ($res as $r) {

			$gid = ($r['M']['id'] > 48) ? $r['M']['stage'] : __('Group %s', $r['M']['group']);
			$mid = $r['M']['id'];
			if (is_numeric($r['M']['teama_id']) && is_numeric($r['M']['teamb_id'])) {
				$arr[$gid][$mid] = array(
					'pid' => $r['P']['id'],
					'date' => new DateTime($r['M']['date']),
					'tz' => $r['V']['tz'],
					'result' => $r['M']['result'],
					'pred' => $r['P']['prediction'],
					'joker' => $r['P']['joker'],
					'pts' => $r['P']['points'],
					'caption' => ($r['M']['group'] == 'Group') ? __('Group %s', $r['M']['group']) : $r['M']['stage'],
					'teama' => __('<a href="/teams/%s">%s</a>', $r['M']['teama_id'], $r['TA']['name']),
					'teamb' => __('<a href="/teams/%s">%s</a>', $r['M']['teamb_id'], $r['TB']['name'])
				);
				// obscure the predictions for knockout games, before the deadline, who isn't the logged in user
				$midday = new DateTime($r['M']['date']);
				if (($midday->setTime(12,0) > $now) && !$self && ($mid > 48)) {
					$arr[$gid][$mid]['pred'] = 'X-X';
				}

			}

		}
		return $arr;

	} // end get Preds

	/*----------------------------------------------------
	Function:		standings
	Desc:				calulates the overall standings so far
	Params:			$league - league id (optional)
	Returns:		array
	Date:				16/12/13
	----------------------------------------------------*/
	public function standings($league = null) {

		// get the total number of goals scored
		$sql = 'SELECT SUM(LEFT(result,1)) + SUM(RIGHT(result,1)) AS cnt FROM matches';
		$db = $this->getDataSource();
		$res = $db->fetchAll($sql);
		$goals = $res[0][0]['cnt'];

		$sql = 'SELECT
						U.username,
						U.id,
						U.paid,
						M.result,
						P.prediction,
						P.joker,
						P.points
						FROM users U
						LEFT JOIN predictions P ON U.id = P.user_id
						LEFT JOIN matches M ON M.id = P.match_id
						WHERE U.validated = 1';

		$db = $this->getDataSource();
		$res = $db->fetchAll($sql);

		// if the optional league parameter is passed, get the members of that league
		$league_members = $this->League->LeagueUser->find('list', array(
			'conditions' => array(
				'LeagueUser.league_id' => $league,
				'LeagueUser.confirmed' => 1
			),
			'fields' => array('LeagueUser.user_id'),
			'recursive' => 0
		));

		foreach ($res as $p) {

			// include if league_members is empty (ie main league)
			if (empty($league_members) || in_array($p['U']['id'], $league_members)) {

				$u = $p['U']['username'];
				$standings[$u]['id'] = $p['U']['id'];
				$standings[$u]['paid'] = $p['U']['paid'];
				if ($p['M']['result']) {
					$score = $p['P']['points'];
					$standings[$u]['PTS'] += $score;
					$standings[$u]['G'] += array_sum(explode('-', $p['P']['prediction']));
					

					switch ($score) {
						case 5:
						case 10:
							$standings[$u]['CS']++;
							break;
						case 3:
						case 6:
							$standings[$u]['CD']++;
							break;
						case 1:
						case 2:
							$standings[$u]['CR']++;
							break;
					}

					$standings[$u]['sortorder'] = $standings[$u]['PTS']							* 10000000000 +
																				$standings[$u]['CS']							* 100000000 +
																				$standings[$u]['CD']							* 1000000 +
																				$standings[$u]['CR']							* 10000 +
																				(99-abs($standings[$u]['delta']))	* 100 +
																				$standings[$u]['paid'];
				}
			}
		}

		if (is_null($standings)) {
			return null;
		}

		// custom sort
		uasort($standings, function($x, $y) {
			//sort order: PTS > CS > CD > CR > goal delta > id (default)
			if ($x['sortorder'] == $y['sortorder']) {
				return ($x['id'] > $y['id']) ? -1 : 1;
			} else return ($x['sortorder'] > $y['sortorder']) ? -1 : 1;
		});

		$pos = positions($standings);
		return $pos;

	} // end standings

	/*----------------------------------------------------
	Function:		getLeagues
	Desc:				gets all leagues of which user is a member
	Params:			$id - user id
	Returns:		array
	Date:				5/1/14
	----------------------------------------------------*/
	public function getLeagues($id) {

		$sql = 'SELECT
						L.name,
						L.id,
						L.organiser
						FROM leagues L
						INNER JOIN league_users LU ON L.id = LU.league_id
						WHERE LU.user_id = ?';
		$db = $this->getDataSource();
		$res = $db->fetchAll($sql, array($id));

		return $res;


	} // end getLeagues

	/*----------------------------------------------------
	Function:		createInvite
	Desc:				creates an unverified user account and
							sends an email to invitee
	Params:			$data - form data
							$referer - user who sent invite
	Returns:		$response - array(type, message)
	Date:				1/1/14
	----------------------------------------------------*/
	public function createInvite($data, $referer) {

		// set up email variables
		$from = array(SENDER => APP_NAME);
		$subject = 'Invite to play World Cup Goalmine';
		$temp = generate_random_password(12);
		$message = $data['message'] . '

		http://worldcup.goalmine.eu/users/verify/' . $temp;

		// create a temporary account
		$tempuser = array('User' => array(
			'username' => $temp,
			'email' => $data['email'],
			'password' => $temp,
			'referredby' => $referer['id'],
			'validated' => 0
		));

		$email = new CakeEmail('default');
		try {
			$email->from($from)
						->to($data['email'])
						->subject($subject);
			try {
				if ($data['copy'] == 1) {
					$email->cc($referer['email']);
				}
				$email->send($message);
				if ($this->save($tempuser)) {
					$this->log(__('invite email sent to %s', $data['email']), 'admin');
					$response = array('success', __('An invite to %s been successfully sent. If you requested a copy, you should find it in your inbox shortly.', $data['email']));
				} else {
					$this->log(__('invite email NOT sent to %s, but tempuser created', $data['email']), 'admin');
					$response = array('warning', __('The email has been sent, but there was a problem creating the user. Please contact support'));
				}

			} catch (SocketException $e) {
				$this->log(__('Problem sending invite email to %s', $data['email']), 'admin');
				$response = array('warning', 'There was a problem in sending the invite email. Please try again later.');
			}

		} catch (SocketException $e) {
			$this->log(__('Problem sending invite email to %s', $data['email']), 'admin');
			$response = array('warning', __('Sorry, that email couldn\'t be sent to %s. Please check the sender and retry', $data['email']));
		}


		return $response;

	} // end createInvite

	public function processSend($data) {
	/*----------------------------------------------------
	Function:		processSend
	Desc:				sends bulk email to users
	Params:			$data - form data
	Returns:		flash response
	Date:				7/6/14
	----------------------------------------------------*/

		$subject = (isset($data['subject'])) ? $data['subject'] : 'World Cup Goalmine update';
		$onlypaid = (isset($data['onlypaid'])) ? $data['onlypaid'] : 0;

		$arr = [
			'fields' => ['User.email'],
			'recursive' => 0,
			'conditions' => ['User.email != ""', 'User.validated = 1']
		];

		if ($onlypaid) {
			$arr['conditions'][] = ['User.paid' => 1];
		}

		$users = $this->find('list', $arr);

		$res = null;

		try {
			$email = new CakeEmail('default');
			$email->to(SENDER)
						->from(SENDER)
						->bcc($users)
						->subject($subject);
			$res = $email->send($data['body']);
			$this->log(__('email sent to: %s', json_encode($users)), 'admin');

		} catch (SocketException $e) {
			$this->log(__('Error in sending email to %s', $l['User']['id']), 'admin');
		}
		return (is_null($res)) ? null : count($users);

	} // end processSend

	public function processForgot($data) {
	/*----------------------------------------------------
	Function:		processForgot
	Desc:				resets password and sends reminder email
	Params:			$data - form data
	Returns:		flash response
	Date:				27/1/14
	----------------------------------------------------*/

		$un = $data['User']['username'];
		$email = $data['User']['email'];

		$arr = array(
			'recursive' => 0,
			'conditions' => array(
				'User.username' => $un,
				'User.email' => $email
			)
		);

		$res = $this->find('first', $arr);

		if (empty($res)) {
			$response = array(
				'msg' => 'No account was found matching those details. Please check and try again',
				'class' => 'warning'
			);
		} else {
			// add a reset code to account
			$uid = $res['User']['id'];
			$this->id = $uid;
			$reset_code = generate_random_password(16);
			$this->saveField('resetpwd', $reset_code);

			// create an email to user and send it
			$subject = 'World Cup Goalmine - password reset request';
			$now = new DateTime();
			$email = new CakeEmail('default');
			$email->from(array(SENDER => APP_NAME))
						->to($res['User']['email'])
						->subject($subject)
						->template('reset_request')
						->viewVars(array(
							'user' => $res['User']['username'],
							'date' => $now->format('jS M, H:i'),
							'code' => $reset_code
						));
			$sent = $email->send();

			if ($sent) {
				$response = array(
					'msg' => __('An email with instructions for resetting your password has been sent to <strong>%s</strong>', $res['User']['email']),
					'class' => 'success'
				);
			} else {
				$this->log(__('Could not send reset email to %s', $res['User']['email']), 'admin');
				$response = array(
					'msg' => 'Sorry, there was a problem sending your reset email',
					'class' => 'warning'
				);
			}
		}

		return $response;

	} // end processForgot

	public function processUpdate($data, $uid) {



	} // end processUpdate

	// auth stuff below
	public $validate = array(
		'username' => array(
			'rule-1' => array(
				'rule' => array('notEmpty'),
				'message' => 'A username is required'
			),
			'rule-2' => array(
				'rule' => 'isUnique',
				'message' => 'That username is already taken'
			)
		),
		'password' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'A password is required'
			)
		)
	);

	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		return true;
	}

}
