<?php

class UsersController extends AppController {

	public $name = 'Users';
	public $helpers = array('Html','Form');

	public function index() {

		if ($this->request->is('requested')) {
			return $this->User->standings();
		} else {
			$this->set('standings', $this->User->standings());
		}

	} // end index

	public function view($id = null) {

		// the logged in user
		$user = $this->Auth->user();

		// get the selected user parameter (could be from /users/x or /users/view/x)
		if ($this->request->params['id']) {
			$uid = $this->request->params['id'];
		} elseif ($id) {
			$uid = $id;
		} else {
			$this->flash('No such user', '/', 2);
		}

		// the selected user (not necessarily the logged-in user)
		$seluser = $this->User->findByIdAndValidated($uid, 1);
		$this->set('selected', $seluser['User']);

		// if no parameter passed, or the user doesn't exist or isn't validated, redirect the page
		if (is_null($uid) || empty($seluser)) {
			$this->flash(__('No such user'), '/', 2);
		}

		$self = ($user['id'] == $uid);
		$this->set('preds', $this->User->getPreds($uid, $self));
		$this->set('leagues', $this->User->getLeagues($user['id']));

		// if logged in user is viewing own profile then add some additional options
		$this->set('self', $self);

		// loop through the first teams in each table, and calculate the table positions
		$y = 1;
		for ($x = 1; $x < 32; $x += 4) {
			$gp = chr(64 + $y++);
			$predleagues[$gp] = $this->User->Prediction->Match->Goal->Team->getTable($x, $uid);
		}
		$this->set('predleagues', $predleagues);

	} // end view

	public function send() {

		// kick out non-admins
		if (!$this->Auth->user('admin')) {
			$this->flash(__('You must be an admin to send emails to users'), $this->referer, 2);
		}

		if ($this->request->is('post')) {
			// form has been submitted
			$response = $this->User->processSend($this->request->data);
			if (is_null($response)) {
				$this->Session->setFlash(__('There may have been a problem in sending that email'), 'custom-flash', array('class' => 'warning'));
			} else {
				$this->Session->setFlash(__('email sent to %s user(s)', $response), 'custom-flash', array('class' => 'success'));
			}
		}

	} // end send

	public function invites() {

		if ($this->request->is('requested')) {
			return $this->User->find('list', array(
				'fields' => array('email', 'validated'),
				'conditions' => array('referredby' => $this->Auth->user('id')),
			));
		} else {
			throw new MethodNotAllowedException();
		}

	} // end invites

	public function check($un = null) {

		if ($this->request->is('ajax')) {
			$this->autoRender = false;
			$cnt = $this->User->findByUsername($un);

			return ($cnt) ? json_encode(true) : json_encode(false);
		} else {
			throw new MethodNotAllowedExeption();
		}

	} // end check

	public function payment() {

		if ($this->Auth->user('admin') != 1) {
			throw new ForbiddenException();
		}

		// if data has been posted back via ajax (i.e. someone was marked as paid)
		if ($this->request->is('ajax')) {

			$this->autoRender = false;
			$data = $this->request->data;
			$user = $this->User->findById($data['id']);

			$tosave = array('User' => array(
				'id' => $data['id'],
				'paid' => 1
			));

			$email = new CakeEmail('default');

			if ($this->User->save($tosave)) {
				try {
					$email->from(array(SENDER => APP_NAME))
								->to($user['User']['email'])
								->subject('Goalmine payment processed')
								->template('payment_made')
								->viewVars(array(
									'user' => $user['User']['username']
								))
								->send();
				} catch (SocketException $e) {
					return json_encode(array('success' => false, 'msg' => 'Unable to send confirmation email'));
				}
				return json_encode(array('success' => true, 'msg' => null));
			} else {
				return json_encode(array('success' => false, 'msg' => 'Unable to update user record'));
			}

		} else {

			$arr = array(
				'fields' => array('User.username', 'User.paid', 'User.id'),
				'conditions' => array('User.validated' => 1),
				'recursive' => 0
			);
			$this->set('players', $this->User->find('all', $arr));

		}

	} // end payment

	public function invite() {

		// has data been submitted from the form
		if ($this->request->is('post')) {

			$response = $this->User->createInvite($this->data['Invite'], $this->Auth->user());
			$this->Session->setFlash($response[1], 'custom-flash', array('class' => $response[0]));

		}

		$arr = [
			'fields' => ['User.email', 'User.validated'],
			'recursive' => 0,
			'conditions' => ['User.referredby' => $this->Auth->user('id')]
		];
		$this->set('invitees', $this->User->find('all', $arr));

	} // end invite

	public function verify($code = null) {

		$user = $this->Auth->user();
		// if a user is already logged in, redirect them away
		if ($user) {
			$this->redirect(array('controller' => 'users', 'action' => 'view', $user['id']));
		}

		// find the temp user by the code
		$tempuser = $this->User->find('first', array(
			'conditions' => array('User.validated' => 0, 'User.username' => $code),
			'recursive' => 0
		));

		// if data has been submitted
		if ($this->request->is('post')) {

			$u = $this->data;
			$u['User']['validated'] = 1;
			if ($this->User->save($u)) {
				$response = 'Account now Verified! You can now log in and start making predictions';
				$this->Session->setFlash($response, 'custom-flash', array('class' => 'success'));
				$this->redirect(array('controller' => 'users', 'action' => 'index'));
			} else {
				$response = 'Sorry, the account wasn\'t able to be verified at this time';
				$this->Session->setFlash($response, 'custom-flash', array('class' => 'warning'));
				$this->redirect(array('controller' => 'users', 'action' => 'index'));
			}

		} else {
			// if no code was submitted, or no temp record was found
			if (!$code || empty($tempuser)) {
				$response = 'Sorry, that does not appear to be a valid verification code. Please check the code and try again';
				$this->Session->setFlash($response, 'custom-flash', array('class' => 'warning'));
				$this->redirect('/users/');
			} else {
				$this->set('tempuser', $tempuser);
			}

		}

	} // end verify

	public function options() {

		$user = $this->Auth->user();
		if ($this->request->is('post')) {
			$data = $this->request->data;
			$data['User']['id'] = $user['id'];

			// make sure the current password submitted is correct
			if ($data['User']['password']) {
				$correct_password = $this->User->find('count', array(
					'conditions' => array(
						'User.password' => AuthComponent::password($data['User']['password']),
						'User.id' => $user['id']
					)
				));
			}

			// do some server-side validation, in case js isn't working for any reason
			if ($data['User']['newpwd'] && strlen($data['User']['newpwd']) < 6) {
				$this->Session->setFlash(__('Password must be at least six characters'), 'custom-flash', array('class' => 'warning'));
			} elseif ($data['User']['newpwd'] !== $data['User']['rptpwd']) {
				$this->Session->setFlash(__('Passwords don\'t match'), 'custom-flash', array('class' => 'warning'));
			} elseif (!empty($data['User']['password']) && !$correct_password) {
				$this->Session->setFlash(__('The current password is incorrect'), 'custom-flash', array('class' => 'warning'));
			} else {
				//$this->User->id = $user['id'];
				//$this->User->saveField('password', $data['User']['rptpwd']);
				$tosave = ['User' => [
					'id' => $user['id'],
					'email' => $data['User']['email'],
					'utc_offset' => $data['User']['utc_offset']
				]];
				if ($data['User']['newpwd']) {
					$tosave['User']['password'] = $data['User']['newpwd'];
				}
				$this->User->save($tosave);
				$this->Session->setFlash(__('Your account has been updated. You may need to log out and back in to see changes'), 'custom-flash', array('class' => 'success'));
				$this->redirect(__('/users/%s', $user['id']));
			}

		}

	} // end options

	// auth stuff below
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('view', 'index', 'verify', 'check', 'forgot', 'reset');
	}

	public function login() {

		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$now = date('Y-m-d H:i:s');
				$user = $this->Auth->user();
				$this->User->id = $user['id'];
				$this->User->saveField('lastlogin',$now);
				$this->User->saveField('resetpwd', null);
				$this->redirect($this->Auth->redirect());
			} else {
				$this->Session->setFlash('Invalid username or password', 'custom-flash', array('class' => 'warning'));
			}
		}

	} // end login

	public function logout() {

		$this->redirect($this->Auth->logout());

	} // end logout

	public function forgot() {

		$user = $this->Auth->user();
		$response = '';
		// if logged in, redirect away from the forgot password page
		if ($user) {
			$this->redirect(array('controller' => 'users', 'action' => 'view', $user['id']));
		}

		if ($this->request->is('post')) {
		// form has been submitted
			$response = $this->User->processForgot($this->request->data);
			$this->Session->setFlash($response['msg'], 'custom-flash', array('class' => $response['class']));
		}

	} // end forgot

	public function reset($code = null) {

		// check logged in or not
		if ($this->Auth->user()) {
			$this->redirect(array('controller' => 'users', 'action' => 'view', $this->Auth->user('id')));
		}

		$u = $this->User->findByresetpwd($code);

		// check code variable
		if (!$code || empty($u)) {
			$this->flash(__('Sorry, that doesn\'t appear to be a valid code'), '/users/', 2);
		}

		if ($this->request->is('post')) {

			// check it's the right user and passwords are ok (don't rely on javascript)
			$data = $this->request->data['Reset'];


			if ($data['email'] !== $u['User']['email'] || ($data['pwd'] !== $data['rpt']) || (strlen($data['rpt']) < 6)) {
				$response = array(
					'msg' => __('Sorry, there was something wrong with that reset attempt'),
					'class' => 'warning'
				);
			} else {
				$tosave = array('User' => array(
					'id' => $u['User']['id'],
					'password' => $data['pwd'],
					'resetpwd' => null
				));
				if ($this->User->save($tosave)) {
					$response = array(
						'msg' => __('Your password has been updated. You can now <a href="/users/login">log in</a>'),
						'class' => 'success'
					);
				} else {
					$response = array(
						'msg' => __('Sorry, your changes couldn\t be saved'),
						'class' => 'warning'
					);
				}
			}

			$this->Session->setFlash($response['msg'], 'custom-flash', array('class' => $response['class']));
			$this->redirect('/users/');

		}

	} // end reset

} // end class
