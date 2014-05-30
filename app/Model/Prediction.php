<?php

class Prediction extends AppModel {

	public $name = 'Prediction';
	public $belongsTo = array('Match', 'User');
	public $actsAs = array('Containable');

	public $validate = array(
		'prediction' => array(
			'rule' => '/^[0-9]{1,2}-[0-9]{1,2}$/',
			'message' => 'each score must be between 0 and 99'
		)
	);

	/*-----------------------------------------------
	Function:	prededit
	Desc:			for a given user, find all the preds
						to populate the predictions/edit view
	Params:		$uid - user id
	Returns:	array
	Date:			11/12/13
	-----------------------------------------------*/
	public function prededit($uid = null) {

		if (is_null($uid)) {
			return false;
		}

		$sql = 'SELECT
						P.id,
						M.id,
						M.`date`,
						M.stage,
						M.`group`,
						M.stage_order_kofirst,
						M.teama_id,
						M.teamb_id,
						TA.id,
						TB.id,
						TA.name,
						TB.name,
						P.joker,
						P.prediction,
						M.result,
						V.id,
						V.city,
						V.utc_offset AS tz
						FROM matches M
						LEFT JOIN predictions P ON (M.id = P.match_id AND P.user_id = ?)
						INNER JOIN venues V ON V.id = M.venue_id
						INNER JOIN teams TA ON TA.id = M.teama_id
						INNER JOIN teams TB ON TB.id = M.teamb_id';
		$db = $this->getDataSource();
		$res = $db->fetchAll($sql, array($uid));

		// sort the predictions by group and then id
		usort($res, function($x, $y) {
			if ($x['M']['stage_order_kofirst'] == $y['M']['stage_order_kofirst']) {
				return ($x['M']['id'] < $y['M']['id']) ? -1 : 1;
			} else {
				return ($x['M']['stage_order_kofirst'] > $y['M']['stage_order_kofirst']) ? -1 : 1;
			}
		});

		$now = new DateTime();

		// loop through each prediction
		foreach ($res as $r) {

			// get the UTC datetime for each game
			$md = new DateTime($r['M']['date']);
			// only include matches that have both teams assigned
			if (is_numeric($r['M']['teama_id']) && (is_numeric($r['M']['teamb_id']))) {
				
				$x = $r['M']['id'];
				$group = ($x > 48) ? $r['M']['stage'] : ('Group ' . $r['M']['group']);
				$preds[$group][$x] = [
					'pid' => $r['P']['id'],
					'prediction' => $r['P']['prediction'],
					'result' => $r['M']['result'],
					'date' => $md,
					'tz' => $r['V']['tz'],
					'venue' => __('<a href="/venues/%s">%s</a>', $r['V']['id'], $r['V']['city']),
					'teama' => __('<a href="/teams/%s">%s</a>', $r['TA']['id'], $r['TA']['name']),
					'teamb' => __('<a href="/teams/%s">%s</a>', $r['TB']['id'], $r['TB']['name']),
					'exp' => ($now > $md) || $r['M']['result'],
					'joker' => $r['P']['joker']
				];
			}			
		}
		return $preds;

	} // end prededit

} //end class
