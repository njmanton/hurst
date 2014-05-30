<?php

App::uses('Controller', 'Controller');

class AppController extends Controller {

	public $components = array(
		'DebugKit.Toolbar',
		'Session',
		'Auth' => array(
			'loginRedirect'  => array('controller'=>'users','action'=>'view'),
			'logoutRedirect' => array('controller'=>'users','action'=>'index')
		)
	);

	public function beforeFilter() {
		// set up permitted views when not logged in
		$this->Auth->allow('index', 'view', 'api_index', 'admin_edit');
		// set user variable so available in all views (and layouts)
		$this->set('user',$this->Auth->user());
	}

	public function beforeRender() {
		$this->set('refer', $this->referer());
	}

} // end class
