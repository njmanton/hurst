<?php

class GoalsController extends AppController {

	public $name = 'Goals';
	public $helpers = array('Html','Form');

	public function index() {

		$this->set('goals', $this->Goal->getGoals());

	} // end index

	public function scorers() {

		$arr = array(
			'fields' => array('scorer', 'Team.name', 'Team.id', 'COUNT(Goal.id) AS goals', 'COUNT(Goal.type="P") AS pens'),
			'group' => array('scorer', 'Team.name'),
			'order' => array('COUNT(Goal.id) DESC', 'scorer ASC', 'Team.name ASC'),
			'conditions' => array('Goal.type IS NULL OR Goal.type != "O"')
		);

		$this->set('scorers', $this->Goal->find('all', $arr));

	} // end scorers

	public function beforeFilter() {
		// set up permitted views when not logged in
		parent::beforeFilter();
		$this->Auth->allow('scorers', 'index');
	}


} // end class
