<?php

class TeamsController extends AppController {

	public $name = 'Teams';
	public $helpers = array('Html','Form');

	public function index() {

		$this->set('teams', $this->Team->find('all', array('recursive' => 0)));

	} // end index

	public function view($id = null) {

		if ($this->request->params['id']) {
			$tid = $this->request->params['id'];
		} elseif ($id) {
			$tid = $id;
		} else {
			$this->flash('No such team', '/teams/', 2);
		}

		$team = $this->Team->findById($tid);

		if (empty($team)) {
			throw new NotFoundException();
		} else {
			$this->set('team', $team);
		$this->set('fixtures', $this->Team->getMatches($tid));
		$this->set('table', $this->Team->getTable($tid));
		$this->set('history', $this->Team->History->getHistory($tid));
		}
		

	} // end view

	public function api_index() {

		$this->autoRender = false;
		echo json_encode($this->Team->find('all', array('recursive' => 0)), JSON_UNESCAPED_UNICODE);

	} // end api_index

	public function head() {

		if ($this->request->is('requested')) {

			$arr = ['fields' => ['id', 'name', 'sname'], 'recursive' => 0];
			return $this->Team->find('all', $arr);

		} else {
			throw new MethodNotAllowedException();
		}

	} // end head

	public function beforeFilter() {
		// set up permitted views when not logged in
		parent::beforeFilter();
		$this->Auth->allow('head', 'index');
	}

} // end class
