<?php

class Team extends AppModel {

	public $name = 'Team';
	public $hasMany = array(
		'homeTeam' => array(
			'className' => 'Match',
			'foreignKey' => 'teama_id'
		),
		'awayTeam' => array(
			'className' => 'Match',
			'foreignKey' => 'teamb_id'
		),
		'History',
		'Goal'
	);
	public $actsAs = array('Containable');

	/*----------------------------------------------------
	Function:		getMatches
	Desc:				for given team, return array containing
							all matches for that team, including
							opponent
	Params:			$id - team id
	Returns:		array
	Date:				11/12/13
	----------------------------------------------------*/
	public function getMatches($id = null) {

		// for a given team id, return an array showing that team's matches

		$sql = 'SELECT
						M.id,
						M.date,
						M.result,
						M.`group`,
						M.stage,
						M.teama_id,
						M.teamb_id,
						M.winner_id,
						M.winmethod,
						TA.name,
						TB.name,
						V.city,
						V.utc_offset AS tz,
						M.venue_id
						FROM matches M
						LEFT JOIN teams TA ON TA.id = M.teama_id
						LEFT JOIN teams TB ON TB.id = M.teamb_id
						INNER JOIN venues V ON V.id = M.venue_id
						WHERE teama_id = ? OR teamb_id = ?';
		$db = $this->getDataSource();
		$res = $db->fetchAll($sql, array($id, $id));

		foreach ($res as $m) {

			$stage = $m['M']['stage'];
			$mid = $m['M']['id'];
			$matches[$stage][$mid] = array(
				'date' => new DateTime($m['M']['date']),
				'tz' => $m['V']['tz'],
				'result' => ($id == $m['M']['teama_id']) ? $m['M']['result'] : strrev($m['M']['result']),
				'group' => $m['M']['group'],
				'stage' => $m['M']['stage'],
				'venue' => $m['V']['city'],
				'venue_id' => $m['M']['venue_id'],
				'oppo' => ($id == $m['M']['teama_id']) ? $m['TB']['name'] : $m['TA']['name'],
				'oppo_id' => ($id == $m['M']['teama_id']) ? $m['M']['teamb_id'] : $m['M']['teama_id']
			);
			if ($m['M']['winner_id'] == $id) {
				$matches[$stage][$mid]['oppo'] = __('<strong>%s</strong>', $matches[$stage][$mid]['oppo']);
			}
			if (!empty($m['M']['winmethod'])) {
				$suffix = __('<span class="winm">%s</span>', $m['M']['winmethod']);
				if ($m['M']['winner_id'] == $mid) {
					$matches[$stage][$mid]['result'] =  $suffix . $m['M']['result'];
				} elseif ($m['M']['winner_id'] == $m['M']['teamb_id']) {
					$matches[$stage][$mid]['result'] .= $suffix;
				}
			}
		}

		return $matches;

	} // end getMatches

	/*----------------------------------------------------
	Function:		getTable
	Desc:				for selected team, get all match details
							and calculate the group standings
	Params:			$id - team
	Returns:		array
	Date:				11/12/13
	----------------------------------------------------*/
	public function getTable($tid = null, $uid = null) {

		if (is_null($tid)) {
			return false;
		}

		$sql = 'SELECT
						TA.id,
						TB.id,
						TA.name,
						TB.name,
						M.result,
						P.prediction
						FROM matches M
						LEFT JOIN predictions P ON (M.id = P.match_id AND P.user_id = ?)
						INNER JOIN teams TA ON TA.id = M.teama_id
						INNER JOIN teams TB ON TB.id = M.teamb_id
						WHERE M.`group` = (SELECT `group` FROM teams T WHERE T.id = ?)';

		$db = $this->getDataSource();
		$res = $db->fetchAll($sql, array($uid, $tid));

		$standings = array();
		foreach ($res as $m) {
			// loop through each match. If the team(s) playing haven't been
			// encountered yet, initialise an array to hold their results
			// otherwise check the result and assign W/D/L etc.

			$h = $m['TA']['id'];
			$a = $m['TB']['id'];

			if (!array_key_exists($h, $standings)) {
				$standings[$h] = array('name' => $m['TA']['name'], 'P' => 0, 'W' => 0, 'D' => 0, 'L' => 0, 'GF' => 0, 'GA' => 0, 'GD' => 0, 'PTS' => 0);
			}
			if (!array_key_exists($a, $standings)) {
				$standings[$a] = array('name' => $m['TB']['name'], 'P' => 0, 'W' => 0, 'D' => 0, 'L' => 0, 'GF' => 0, 'GA' => 0, 'GD' => 0, 'PTS' => 0);
			}

			if (!is_null($m['M']['result']) || !is_null($m['P']['prediction'])) {

				if (is_null($m['M']['result'])) {
					list($hs, $as) = explode('-', $m['P']['prediction']);
				} else {
					list($hs, $as) = explode('-', $m['M']['result']);
				}

				$standings[$h]['P']++;
				$standings[$a]['P']++;
				$standings[$h]['GF'] += $hs;
				$standings[$a]['GA'] += $hs;
				$standings[$h]['GA'] += $as;
				$standings[$a]['GF'] += $as;

				if ($hs > $as) { // 'home' win

					$standings[$h]['W']++;
					$standings[$a]['L']++;
					@$standings[$h][$m['TB']['name']] = 'W'; // hold an additional dimension for each team for H2H calcs
					@$standings[$a][$m['TA']['name']] = 'L';

				} elseif ($hs < $as) { // 'away' win

					$standings[$a]['W']++;
					$standings[$h]['L']++;
					@$standings[$a][$m['TA']['name']] = 'W';
					@$standings[$h][$m['TB']['name']] = 'L';

				} elseif ($hs == $as) { // draw

					$standings[$h]['D']++;
					$standings[$a]['D']++;
					@$standings[$h][$m['TA']['name']] = 'D';
					@$standings[$a][$m['TB']['name']] = 'D';

				}
			}

		}

		foreach ($standings as &$s) {

			$s['PTS'] = $s['W']*3 + $s['D'];
			$s['GD'] = $s['GF'] - $s['GA'];

		}

		// user-defined sort on the table. Sort by PTS > GD > GF > H2H > alpha
		uasort($standings, function($x, $y) {

			if ($x['PTS'] == $y['PTS']) {
				if ($x['GD'] == $y['GD']) {
					if ($x['GF'] == $y['GF']) {
						if ($x[$y['name']] == $y[$x['name']]) {
							return ($x['name'] > $y['name']) ? -1 : 1; // if all else fails, tie is broken by lots, so here just do it alpha
						} else return ($x[$y['name']] == 'W') ? -1 : 1;
					} else return ($x['GF'] > $y['GF']) ? -1 : 1;
				} else return ($x['GD'] > $y['GD']) ? -1 : 1;
			} else return ($x['PTS'] > $y['PTS']) ? -1 : 1;

		});

		return $standings;

	} // end getTable

} // end class
