<?php

class LeaguesController extends AppController {

	public $name = 'Leagues';
	public $helpers = array('Html','Form');

	public function index() {

		// post request if administration done
		if ($this->request->is('post')) {

			// get the posted data
			$data = $this->request->data;
			// count for flash message
			$cnt = 0;
			// email vars
			$from = array(SENDER => APP_NAME);
			$subject = APP_NAME . ' League Application';

			// loop through each processed league
			foreach ($data as $k=>$d) {

				// get the league details
				$l = $this->League->findById($k);
				// common email members
				try {
					$email = new CakeEmail('default');
					$email->from($from)
								->to($l['User']['email'])
								->subject($subject)
								->viewVars(array(
									'user' => $l['User']['username'],
									'league' => $l['League']['name'],
									'id' => $l['League']['id']
								));
				} catch (SocketException $e) {
					$this->log(__('Error in sending email to %s', $l['User']['id']), 'admin');
				}
				
				// accepted
				if ($d == 'a') {

					if ($this->League->save(array('League' => array('id' => $k, 'pending' => 0)))) {
						$cnt++;
						// LOG
						$sent = $email->template('league_create_accept')->send();
						$tosave = array('LeagueUser' => array(
							'user_id' => $l['User']['id'],
							'league_id' => $l['League']['id'],
							'confirmed' => 1
						));
						// save the organiser into that league
						$this->League->LeagueUser->save($tosave);
					}
				// rejected
				} elseif ($d == 'r') {

					if ($this->League->delete($k, false)) {
						$cnt++;
						// LOG
						$sent = $email->template('league_create_reject')->send();
					}
				}
			}
		}

		$this->set('leagues', $this->League->getLeagues());

		if ($this->Auth->user('admin')) {

			$this->set('pending', $this->League->find('all', array(
				'fields' => array('name', 'id', 'organiser', 'description'),
				'conditions' => array('pending' => 1),
				'contain' => array('User.username')
			)));

		}

	} // end index

	public function join($id = null) {

		if (empty($id)) {
			$this->flash(__('No league selected'), '/leagues/', 2);
		} else {

			$user = $this->Auth->user();
			$res = $this->League->processJoin($id, $user);

			$this->Session->setFlash($res['msg'], 'custom-flash', array('class' => $res['class']));
			$this->redirect('/leagues/');

		}

	} // end join

	public function view($id = null) {

		if ($this->request->params['id']) {
			$lid = $this->request->params['id'];
		} elseif ($id) {
			$lid = $id;
		} else {
			$this->flash('No such league', '/leagues/' ,2);
		}

		$user = $this->Auth->user();
		$this->League->id = $lid;
		$l = $this->League->read();

		if ($this->request->is('post')) {
			// post request is organiser accepting/rejecting applications

			foreach ($this->request->data as $k=>$d) {

				$applicant = $this->League->User->findById($k);
				$subject = APP_NAME . ' league application';
				$email = new CakeEmail('default');
				$email->from(array(SENDER => APP_NAME))
							->to($applicant['User']['email'])
							->subject($subject)
							->viewVars(array(
								'lid' => $l['League']['id'],
								'league' => $l['League']['name'],
								'applicant' => $applicant['User']['username']
							));

				if ($d['decision'] == 'a') {
				// application was accepted
					if ($this->League->LeagueUser->save(array('LeagueUser' => array('id' => $d['pid'], 'confirmed' => 1)))) {
						// LOG
						$sent = $email->template('league_join_accept')->send();
					} else {
						// LOG
					}

				}	elseif ($d['decision'] == 'r') {

					if ($this->League->LeagueUser->delete($d['pid'], false)) {
						// LOG
						$sent = $email->template('league_join_reject')->send();
					} else {
						// LOG
					}

				}
			}
		}

		// if it's not a valid league, redirect away
		if (is_null($lid) || empty($l)) {
			$this->flash(__('No such league'), '/leagues/', 2);
		} elseif ($l['League']['pending'] == 1) {
			// don't show a league that's created but still pending
			$this->Session->setFlash(__('Sorry, that league has not yet been confirmed by an administrator'), 'custom-flash', ['class' => 'info']);
			$this->redirect('/leagues/');
		}	else {

			// determine whether logged in user is a member
			$memberOf = false;
			foreach ($l['LeagueUser'] as $u) {
				if (($u['user_id'] == $user['id']) && $u['confirmed']) {
					$memberOf = true;
					break;
				}
			}
			$organiser = ($l['League']['organiser'] == $user['id'] || $user['admin'] == 1);

			$this->set('league', $l);
			$this->set('organiser', $organiser);
			$this->set('member', $memberOf);
			$this->set('table', $this->League->User->standings($lid));
		}

		// if organiser, pass back the pending applications for the league
		if ($organiser || $user['admin']) {
			$arr = array(
				'conditions' => array(
					'confirmed' => '0',
					'league_id' => $lid
				),
				'recursive' => 0
			);

			$this->set('pending', $this->League->LeagueUser->find('all', $arr));
		}

	} // end view

	public function add() {

		$uid = $this->Auth->user('id');
		$tosave = $this->request->data;
		$tosave['League']['organiser'] = $uid;
		$tosave['League']['pending'] = 1;
		if ($this->request->is('post')) {

			if ($this->League->save($tosave)) {
				$response = 'Your request for a new league has successfully been sent for approval';
				$this->Session->setFlash($response, 'custom-flash', array('class' => 'success'));
			} else {
				$response = 'Sorry, there was a problem processing your request. Please try again later';
				$this->Session->setFlash($response, 'custom-flash', array('class' => 'warning'));
			}

		}

	} // end view

	public function pending() {
		/*----------------------------------------------------
		Function:		pending
		Desc:				provides details of pending league users
		Params:			none
		Returns:		array (league, league_id, count pending)
		Date:				23/04/14
		----------------------------------------------------*/

		if ($this->request->is('requested')) {
			$res = [];

			if ($this->Auth->user('admin')) {
				$arr = [
					'fields' => ['name', 'id'],
					'conditions' => ['League.pending' => 1],
					'recursive' => 0
				];
				$res['leagues'] = $this->League->find('all', $arr);
			}

			$organiser = $this->Auth->user('id');
			$sql = 'SELECT
							L.name,
							L.id,
							COUNT(L.id) AS cnt
							FROM league_users LU
							JOIN leagues L ON LU.league_id = L.id
							WHERE L.organiser = ? AND LU.confirmed = 0
							GROUP BY L.name';

			$db = $this->League->getDataSource();
			$res['users'] = $db->fetchAll($sql, array($organiser));

			return $res;

		} else {
			throw new MethodNotAllowedException();
		}

	} // end pending

} // end class
