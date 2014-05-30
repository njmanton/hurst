<?php
// There are no views or actions for the LeagueUser model
class LeagueUser extends AppModel {

	public $name = 'LeagueUser';
	public $belongsTo = array('League', 'User');

}
