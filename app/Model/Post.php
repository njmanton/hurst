<?php

class Post extends AppModel {
	public $name = 'Post';
	public $belongsTo = array('User');
	public $actsAs = array('Containable');
	
} // end class