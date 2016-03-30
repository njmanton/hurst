<?php

class VenuesController extends AppController {

	public $name = 'Venues';
	public $helpers = array('Html', 'Form');

	public function index() {

		/*$this->set('venues', $this->Venue->find('all', array(
			'recursive' => 1,
			'order' => array('Venue.capacity DESC')
		)));*/

	} // end index

	public function view($id = null) {

		if ($this->request->params['id']) {
			$vid = $this->request->params['id'];
		} elseif ($id) {
			$vid = $id;
		}

		$this->Venue->id = $vid;
		if ($this->Venue->exists()) {
			$this->set('venue', $this->Venue->getVenue($vid));
		} else {
			throw new NotFoundException();
		}

	} // end view

	public function api_index() {
		// also used as source for venues google map

		$this->autoRender = false;
		return json_encode($this->Venue->find('all', array('recursive' => 0)));

	} // end api_index

	// auth stuff below
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index');
	}

} // end class
