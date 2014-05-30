<?php

class TournamentsController extends AppController {

	public $name = 'Tournaments';
	public $helpers = array('Html');

	public function rnd($tid = null) {
		// gets random tournament data

		if ($this->request->is('requested')) {
			$t = $this->Tournament->findById(mt_rand(1,19));	
			return $t['Tournament'];
		} else {
			throw new MethodNotAllowedException();
		}
		
	} // end rnd

	// auth stuff below
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('rnd');
	} // end beforeFilter

} // end class
