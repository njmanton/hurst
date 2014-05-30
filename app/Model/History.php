<?php

class History extends AppModel {

	public $name = 'History';
	public $actsAs = array('Containable');
	public $belongsTo = array('Tournament', 'Team');

	public function getHistory($id = null) {

		if (is_null($id)) {
			return false;
		} else {

			$arr = array(
				'conditions' => array('team_id' => $id),
				'fields' => array('History.result', 'Tournament.year')
			);
			$h = $this->find('all', $arr);

			return $h;
		}

	} // end getHistory

} // end class