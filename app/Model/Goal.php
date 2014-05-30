<?php

class Goal extends AppModel {

	public $name = 'Goal';
	public $actsAs = array('Containable');
	public $belongsTo = array('Match', 'Team');

	public function getGoals() {

		$sql = 'SELECT
						G.id,
						G.team_id,
						G.scorer,
						G.`type`,
						G.time,
						G.tao,
						M.id,
						M.`date`,
						TA.name,
						TB.name,
						TA.id,
						TB.id
						FROM goals G
						INNER JOIN matches M ON G.match_id = M.id
						INNER JOIN teams TA ON M.teama_id = TA.id
						INNER JOIN teams TB ON M.teamb_id = TB.id';
		$db = $this->getDataSource();
		$res = $db->fetchAll($sql);

		$goals = [];
		foreach ($res as $g) {
			$gid = $g['G']['id'];
			$goals[$gid] = [
				'scorer' => $g['G']['scorer'],
				'time' => $g['G']['time'] . '\'',
				'type' => $g['G']['type'],
				'team' => ($g['G']['team_id'] == $g['TA']['id']) ? $g['TA']['name'] : $g['TB']['name'],
				'oppo' => ($g['G']['team_id'] == $g['TB']['id']) ? $g['TA']['name'] : $g['TB']['name'],
				'mid' => $g['M']['id'],
				'tid' => ($g['G']['team_id'] == $g['TA']['id']) ? $g['TA']['id'] : $g['TB']['id'],
				'oid' => ($g['G']['team_id'] == $g['TB']['id']) ? $g['TA']['id'] : $g['TB']['id']
			];
			$dt = new DateTime($g['M']['date']);
			$dt->add(new DateInterval(__('PT%sM', $g['G']['time'])));
			if (!is_null($g['G']['tao'])) {
				$dt->add(new DateInterval(__('PT%sM', $g['G']['tao'])));
				$goals[$gid]['time'] .= __('+%s\'', $g['G']['tao']);
			}
			$goals[$gid]['ranktime'] = $dt->format('Y-m-d h:i');
			if ($g['G']['type'] == 'P') {
				$goals[$gid]['time'] .= ' (p)';
			}
		}

		uasort($goals, function($x, $y) {
			return ($x['ranktime'] < $y['ranktime']) ? -1 : 1 ;
		});

		return $goals;

	} // end getGoals

} // end class
