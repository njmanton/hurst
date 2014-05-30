<?php

class Venue extends AppModel {
	
	public $name = 'Venue';
	public $actsAs = array('Containable');
	public $hasMany = array('Match');

	public function getVenue($vid = null) {

		$arr = array(
			'recursive' => 2,
			'conditions' => array('id' => $vid),
			'contain' => array(
				'Match',
				'Match.TeamA.name',
				'Match.TeamB.name',
			)
		);
		$data = $this->find('first', $arr);

		foreach ($data['Match'] as $d) {
			$x = $d['id'];
			$match[$x] = array(
				'result' => $d['result'] ?: '&#8208;',
				'caption' => $d['stage'],
				'date' => new DateTime($d['date']),
				'tz' => $d['utc_offset'],
				'teama' => (is_numeric($d['teama_id'])) ? __('<a href="/teams/%s">%s</a>', $d['teama_id'], $d['TeamA']['name']) : $d['teama_id'],
				'teamb' => (is_numeric($d['teamb_id'])) ? __('<a href="/teams/%s">%s</a>', $d['teamb_id'], $d['TeamB']['name']) : $d['teamb_id']
			);
			if ($x <= 48) {
				$match[$x]['caption'] .= (' ' . $d['group']);
			}
			if ($d['winner_id'] == $d['teama_id']) {
				$match[$x]['teama'] = __('<strong>%s</strong>', $match[$x]['teama']);
			} elseif ($d['winner_id'] == $d['teamb_id']) {
				$match[$x]['teamb'] = __('<strong>%s</strong>', $match[$x]['teamb']);
			}
			if (!empty($d['winmethod'])) {
				$suffix = __('<span class="winm">%s</span>', $d['winmethod']);
				if ($d['winner_id'] == $d['teama_id']) {
					$match[$x]['result'] = $suffix . $d['result'];
				} elseif ($d['winner_id'] == $d['teamb_id']) {
					$match[$x]['result'] .= $suffix;
				}
			}
		}

		return array(
			'Venue' => $data['Venue'],
			'Match' => $match
		);

	} // end getVenue

}