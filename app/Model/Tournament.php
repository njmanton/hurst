<?php

class Tournament extends AppModel {

	public $name = 'Tournament';
	public $actsAs = array('Containable');
	public $hasMany = array('History');

}