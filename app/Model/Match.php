<?php

class Match extends AppModel {

	public $name = 'Match';
	public $hasMany = array('Prediction', 'Goal');
	public $belongsTo = array(
		'TeamA' => array(
			'className' => 'Team',
			'foreignKey' => 'teama_id'
		),
		'TeamB' => array(
			'className' => 'Team',
			'foreignKey' => 'teamb_id'
		),
		'Venue'
	);
	public $actsAs = array('Containable');

	public function getBracket() {

		$data = $this->find('all', ['conditions' => 'Match.id > 48']);
		$brackets = [];

		foreach ($data as $m) {
			$mid = $m['Match']['id'];
			$h = $a = '-';
			if ($m['Match']['result']) {
				list($h,$a) = explode('-', $m['Match']['result']);
			}
			$brackets[$mid] = [
				'teama' => (is_numeric($m['Match']['teama_id'])) ? $m['TeamA']['name'] : $m['Match']['teama_id'],
				'teamb' => (is_numeric($m['Match']['teamb_id'])) ? $m['TeamB']['name'] : $m['Match']['teamb_id'],
				'scorea' => $h,
				'scoreb' => $a,
				'teama_id' => (is_numeric($m['Match']['teama_id'])) ? $m['Match']['teama_id'] : null,
				'teamb_id' => (is_numeric($m['Match']['teamb_id'])) ? $m['Match']['teamb_id'] : null
			];
		}

		return $brackets;

	} // end getBracket

	public function remaining($uid = null) {
		/*----------------------------------------------------
		Function:		remaining
		Desc:				calculates which matches haven't got a
								prediction for a given user. only counts
								'available' matches
		Params:			$uid - user id
		Returns:		count
		Date:				2/3/14
		----------------------------------------------------*/

		$matches = $this->find('all');

		$remaining = 0;
		$now = new DateTime();
		foreach ($matches as $m) {
			$md = new DateTime($m['Match']['date']);
			// if match is available (ie isn't in the past and has two teams allocated), add one
			if (($now < $md) && is_null($m['Match']['result']) && (is_numeric($m['Match']['teama_id'])) && (is_numeric($m['Match']['teamb_id']))) {
				$remaining++;
				foreach ($m['Prediction'] as $p) {
					// then subtract one if there's a matching prediction for that game and user
					if (($p['user_id'] == $uid) && (!is_null($p['prediction']))) {
						$remaining--;
					}
				}
			}
		}

		return $remaining;

	} // end remaining

	public function processResult($data) {
		/*----------------------------------------------------
		Function:		processResult
		Desc:				saves match result and updates goals
		Params:			$data - form data from /matches/result
		Returns:		boolean
		Date:				2/3/14
		----------------------------------------------------*/

		// process goals data
		$maxtime = 0; // latest goal scored (to determine if ET is played)

		$this->Goal->deleteAll(array('match_id' => $data['Match']['id']));
		if (!empty($data['Goal'])) {
			foreach ($data['Goal'] as $g) {
				$maxtime = max($maxtime, $g['time']);
				$tosave = array('Goal' => $g);
				$tosave['Goal']['match_id'] = $data['Match']['id'];
				$this->Goal->create();
				if ($this->Goal->save($tosave)) {
					// TODO log
				} else {
					// TODO log
				}
			}
		}

		// if the last goal in a ko game was scored in ET, then winmethod is E
		// this is overwritten if it's a draw
		if ($maxtime > 90 && ($data['Match']['id'] > 48)) {
			$winmethod = 'E';
		}

		// get match details
		list($hs, $as) = explode('-', $data['Match']['result']);
		// if it's a draw..
		if ($hs == $as) {
			if ($data['Match']['id'] > 48) {
				// if a ko game and a draw, then must be penalties
				$winner = $data['Match']['shootout'];
				$winmethod = 'P';
			} else {
				// group game and draw, then no winner
				$winner = null; $winmethod = null;
			}
		} elseif ($hs > $as) {
			$winner = $data['Match']['teama'];
		} elseif ($hs < $as) {
			$winner = $data['Match']['teamb'];
		}

		$tosave = array('Match' => array(
			'id' => $data['Match']['id'],
			'result' => $data['Match']['result'],
			'winner_id' => $winner,
			'winmethod' => $winmethod,
			'attendance' => $data['Match']['attendance']
		));

		if ($this->save($tosave)) {
			$res = $this->updateKO($tosave);
			$res = $this->updatePreds($tosave) && $res;
		} else {
			$res = false;
		}
		return $res;

	} // end processResult

	public function preds($mid = null, $uid = null) {
		/*------------------------------------
		For match number $id, find all of the
		user predictions for matches/view/x
		view
		------------------------------------*/

		if (is_null($mid)) {
			return false;
		}

		$sql = 'SELECT
						P.prediction,
						P.points,
						M.`date`,
						M.id,
						P.joker,
						U.username,
						U.id
						FROM predictions P
						LEFT JOIN users U ON U.id = P.user_id
						INNER JOIN matches M ON M.id = P.match_id
						WHERE P.match_id = ?';
		$db = $this->getDataSource();
		$res = $db->fetchAll($sql, array($mid));

		$now = new DateTime();
		$ret = [];
		foreach ($res as $r) {

			$midday = new DateTime($r['M']['date']);
			$hide = (($midday->setTime(12,0) > $now) && ($uid != $r['U']['id']) && ($r['M']['id'] > 48));
			$ret[] = [
				'user' => $r['U']['username'],
				'uid' => $r['U']['id'],
				'prediction' => ($hide) ? 'X-X' : $r['P']['prediction'],
				'joker' => $r['P']['joker'],
				'points' => $r['P']['points']
			];

		}

		return $ret;

	} // end preds

	public function getMatches() {

		$arr = array(
			'order' => array('Match.stage_order', 'Match.id', 'Match.date'),
			'contain' => array('TeamA.id', 'TeamA.name', 'TeamB.id', 'TeamB.name', 'Venue.id', 'Venue.city', 'Venue.utc_offset')
		);
		$data = $this->find('all', $arr);

		foreach ($data as $m) {

			$x = $m['Match']['id'];
			$match[$x] = array(
				'order' => $m['Match']['stage_order'],
				'date' => new DateTime($m['Match']['date']),
				'tz' => $m['Venue']['utc_offset'],
				'venue' => __('<a href="/venues/%s">%s</a>', $m['Venue']['id'], $m['Venue']['city']),
				'caption' => $m['Match']['stage'],
				'result' => $m['Match']['result'],
				'teama' => (is_numeric($m['Match']['teama_id'])) ? __('<a href="/teams/%s">%s</a>', $m['TeamA']['id'], $m['TeamA']['name']) : $m['Match']['teama_id'],
				'teamb' => (is_numeric($m['Match']['teamb_id'])) ? __('<a href="/teams/%s">%s</a>', $m['TeamB']['id'], $m['TeamB']['name']) : $m['Match']['teamb_id']
			);
			if ($x <= 48) {
				$match[$x]['caption'] .= (' ' . $m['Match']['group']);
			}
			if ($m['Match']['winner_id'] == $m['Match']['teama_id']) {
				$match[$x]['teama'] = __('<strong>%s</strong>', $match[$x]['teama']);
			} elseif ($m['Match']['winner_id'] == $m['Match']['teamb_id']) {
				$match[$x]['teamb'] = __('<strong>%s</strong>', $match[$x]['teamb']);
			}
			if (!empty($m['Match']['winmethod'])) {
				$suffix = __('<span class="winm">%s</span>', $m['Match']['winmethod']);
				if ($m['Match']['winner_id'] == $m['Match']['teama_id']) {
					$match[$x]['result'] =  $suffix . $m['Match']['result'];
				} elseif ($m['Match']['winner_id'] == $m['Match']['teamb_id']) {
					$match[$x]['result'] .= $suffix;
				}
			}
		}

		return $match;

	} // end getMatches

	public function getMatch($mid) {

		$arr = array(
			'conditions' => array('Match.id' => $mid),
			'contain' => array(
				'Goal' => array(
					'fields' => array('time', 'tao', 'type', 'scorer', 'team_id'),
					'order' => array('time ASC', 'tao ASC')
				),
				'TeamA' => array('fields' => array('id', 'name')),
				'TeamB' => array('fields' => array('id', 'name')),
				'Venue' => array('fields' => array('id', 'stadium', 'utc_offset'))
			)
		);
		$data = $this->find('first', $arr);
		
		if (empty($data)) {
			return null;
		}

		$match = [
			'date' => new DateTime($data['Match']['date']),
			'tz' => $data['Venue']['utc_offset'],
			'mid' => $data['Match']['id'],
			'venue' => __('<a href="/venues/%s">%s</a>', $data['Venue']['id'], $data['Venue']['stadium']),
			'aid' => $data['Match']['teama_id'],
			'bid' => $data['Match']['teamb_id'],
			'result' => $data['Match']['result'],
			'teama_id' => $data['Match']['teama_id'],
			'teamb_id' => $data['Match']['teamb_id'],
			'teama' => (is_numeric($data['Match']['teama_id'])) ? $data['TeamA']['name'] : $data['Match']['teama_id'],
			'teamb' => (is_numeric($data['Match']['teamb_id'])) ? $data['TeamB']['name'] : $data['Match']['teamb_id'],
			'editable' => (is_numeric($data['Match']['teama_id']) && is_numeric($data['Match']['teamb_id'])),
			'winner' => $data['Match']['winner_id'],
			'winmethod' => $data['Match']['winmethod']
		];
		foreach($data['Goal'] as $g) {
			if (!is_null($g['scorer']) && !is_null($g['time'])) {
				$t = ($g['team_id'] == $data['Match']['teama_id']) ? $data['TeamA']['id'] : $data['TeamB']['id'] ;
			$scorer = $g['scorer'];
			if ($g['type'] == 'P') {
				$scorer .= ' (p)';
			}
			if ($g['type'] == 'O') {
				$scorer .= ' (o.g.)';
			}
			$scorer .= __(' %s\'', $g['time']);
			if ($g['tao']) {
				$scorer .= __('+%s\'', $g['tao']);
			}
			$scorer .= '<br />';
			$match['Goals'][$t] .= $scorer;
			}
		}

		return $match;

	} // end getMatch

	/*----------------------------------------------------
	Function:		updatePreds
	Desc:				updates the prediction table with scores
	Params:			$res - array of match id and result
	Returns:		int - number of records updated
	Date:				21/1/14
	----------------------------------------------------*/
	public function updatePreds($res) {

		$arr = array(
			'recursive' => 0,
			'conditions' => array('match_id' => $res['Match']['id']),
			'fields' => array('id', 'prediction', 'joker')
		);
		$preds = $this->Prediction->find('all', $arr);

		$count = 0;
		foreach ($preds as $p) {
			$this->Prediction->id = $p['Prediction']['id'];
			if ($this->Prediction->saveField('points', calcScore($res['Match']['result'], $p['Prediction']['prediction'], $p['Prediction']['joker']))) {
				$count++;
			}
		}

		return $count;


	} // end updatePreds

	public function updateKO($match) {
	/*----------------------------------------------------
	Function:	updateKO
	Desc:			automatically updates QF matches with teams
	Params:		$match - the previous match
	Returns:	true/false
	Date:			9/2/14
	----------------------------------------------------*/

		if ($match['Match']['id'] < 49) {
			return true;
		}

		$winner = $match['Match']['winner_id'];

		// find the match that winner will progress to (from Match.group field)
		// note the strange escaping of MySQL '%'
		$arr = array(
			'conditions' => __('Match.group LIKE "%%%s%%"', $match['Match']['id']),
			'recursive' => 0,
			'fields' => array('Match.id', 'Match.group')
		);
		if ($match['Match']['id'] == 61 || $match['Match']['id'] == 62) {
			$next = $this->findById(64);
		} else {
			$next = $this->find('first', $arr);
		}

		list($a, $b) = explode(' v ' , $next['Match']['group']);
		$tosave = array('Match' => array('id' => $next['Match']['id']));
		if (substr($a, 1) == $match['Match']['id']) {
			$tosave['Match']['teama_id'] = $winner;
		} elseif (substr($b, 1) == $match['Match']['id']) {
			$tosave['Match']['teamb_id'] = $winner;
		}

		if ($this->save($tosave)) {
			$ret = true;
		} else {
			$ret = false;
		}

		return $ret;

	} // end updateKO

	public function getApiIndex() {
		/*----------------------------------------------------
		Function:		getApiIndex
		Desc:				creates an object of all matches for API
								access
		Params:			none
		Returns:		JSON
		Date:				2/3/14
		----------------------------------------------------*/
		$arr= array(
			'fields' => array('id', 'date', 'result', 'stage', 'group'),
			'contain' => array('TeamA.name', 'TeamB.name', 'Venue.stadium', 'Venue.city')
		);
		$res = $this->find('all', $arr);

		$new = array();
		foreach ($res as $r) {

			$x = $r['Match']['id'];
			$new[$x] = array(
				'date' => $r['Match']['date'],
				'stage' => $r['Match']['stage'],
				'group' => $r['Match']['group'],
				'result' => $r['Match']['result'],
				'team1' => $r['TeamA']['name'],
				'team2' => $r['TeamB']['name'],
				'stadium' => $r['Venue']['stadium'],
				'city' => $r['Venue']['city']
			);

		}

		return json_encode($new);

	} // end getApiIndex

} // end class
