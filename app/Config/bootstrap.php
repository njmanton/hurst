<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

// Setup a 'default' cache configuration for use in the application.
Cache::config('default', array('engine' => 'File'));

CakePlugin::loadAll();

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'Model'                     => array('/path/to/models/', '/next/path/to/models/'),
 *     'Model/Behavior'            => array('/path/to/behaviors/', '/next/path/to/behaviors/'),
 *     'Model/Datasource'          => array('/path/to/datasources/', '/next/path/to/datasources/'),
 *     'Model/Datasource/Database' => array('/path/to/databases/', '/next/path/to/database/'),
 *     'Model/Datasource/Session'  => array('/path/to/sessions/', '/next/path/to/sessions/'),
 *     'Controller'                => array('/path/to/controllers/', '/next/path/to/controllers/'),
 *     'Controller/Component'      => array('/path/to/components/', '/next/path/to/components/'),
 *     'Controller/Component/Auth' => array('/path/to/auths/', '/next/path/to/auths/'),
 *     'Controller/Component/Acl'  => array('/path/to/acls/', '/next/path/to/acls/'),
 *     'View'                      => array('/path/to/views/', '/next/path/to/views/'),
 *     'View/Helper'               => array('/path/to/helpers/', '/next/path/to/helpers/'),
 *     'Console'                   => array('/path/to/consoles/', '/next/path/to/consoles/'),
 *     'Console/Command'           => array('/path/to/commands/', '/next/path/to/commands/'),
 *     'Console/Command/Task'      => array('/path/to/tasks/', '/next/path/to/tasks/'),
 *     'Lib'                       => array('/path/to/libs/', '/next/path/to/libs/'),
 *     'Locale'                    => array('/path/to/locales/', '/next/path/to/locales/'),
 *     'Vendor'                    => array('/path/to/vendors/', '/next/path/to/vendors/'),
 *     'Plugin'                    => array('/path/to/plugins/', '/next/path/to/plugins/'),
 * ));
 *
 */

/**
 * Custom Inflector rules can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. Make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); //Loads a single plugin named DebugKit
 *
 */

/**
 * You can attach event listeners to the request lifecycle as Dispatcher Filter. By default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 *		'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *		'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 * 		array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 *		array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));
CakeLog::config('admin', array(
	'engine' => 'File',
	'file' => 'admin'
));
CakeLog::config('pred', array(
	'engine' => 'File',
	'file' => 'pred'
));

const APP_NAME = 'World Cup Goalmine';
const APP_VERSION = 'v0.1';
const APP_CODENAME = 'hurst';
const SENDER = 'noreply@worldcup.goalmine.eu';
const DTF = 'd M H:i';
const DF = 'l d F';
const TF = 'H:i';

function sgn($a) {

	if ($a > 0) {
		return 1;
	} elseif ($a < 0) {
		return -1;
	} else {
		return 0;
	}

} // end sgn

function calcScore($m, $p, $j) {

	if (is_null($m) || is_null($p)) {
		return 0;
	}

	list($sa, $sb) = explode('-', $m);
	list($pa, $pb) = explode('-', $p);

	$score = 0;
	if (($sa == $pa) && ($sb == $pb)) {
		$score = 5;
	} elseif (($sa - $sb) == ($pa - $pb)) {
		$score = 3;
	} elseif (sgn($sa - $sb) == sgn($pa - $pb)) {
		$score = 1;
	}
	$score *= (1 + $j);
	return $score;

} // end calcScore

/*----------------------------------------------------
Function:		positions
Desc:				calculates ordinal positions for array
						TODO: generalise $absrank
Params:			array
Returns:		array with additional rank element
Date:				18/12/13
----------------------------------------------------*/

/*
UPDATE 20/5/14
removed the checks on whether user has paid or not. If not paid then
user doesn't appear in main league table at all, but does appear in
any relevant user leagues
*/

function positions($arr) {

		$row = 0; // which row are we on
		$rank = 1; // what is the rank of the player
		$absrank = 0; // what is abs score
		$prevrank = 0; // rank of row n-1

		foreach ($arr as $k=>&$t) {
			if ($t['paid'] == 1) {
				if ($t['sortorder'] == $prevrank) {
					$row++;
					$equal = '=';
				} else {
					//$rank = ($t['paid'] == 1) ? ++$row : 0;
					$rank = ++$row;
					$equal = '';
				}
				// if the row is top 3 or bottom 3, set 'show' flag for short table
				if (($row < 4) || ($row > count($arr) - 3)) {
					$t['show'] = 1;
				}
				// if row is third place, set the css flag to show a bottom 'tear' effect
				if ($row == 3) {
					$t['class'] = 'btear';
				// conversely show a top tear effect for the 3rd bottom
				} elseif ($row == count($arr)-2) {
					$t['class'] = 'ttear';
				}
				//$t['rank'] = ($t['paid'] == 1) ? $rank . $equal : 'n/a';
				$t['rank'] = $rank . $equal;
				$prevrank = $t['sortorder'];
			}
		}

		return $arr;

} // end positions

function getNum($s = null) {

	if (is_null($s)) {
		return false;
	}

	preg_match("/([0-9]+[\.,]?)+/",$s,$m);
	return $m[0];

} // end getNum

//--------------------------------------------------------------------------
//	name:				generate_random_password
//	desc:				returns a randomly created password
//	arguments:	length - default of 8
//	returns:		string
//--------------------------------------------------------------------------
function generate_random_password($length = 8) {

	$pass = '';
	$possible = '2346789abcdefghjkmnpqrtuvwxyzABCDEFGHJKLMNPQRTUVWXYZ';
	for ($p=0; $p<$length; $p++) {
		$string .= $possible[mt_rand(0, strlen($possible))];
	}

	return $string;

} // end generate_random_password
