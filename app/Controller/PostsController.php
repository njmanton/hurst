<?php

class PostsController extends AppController {
	public $name = 'Posts';
	public $helpers = array('Html','Form');
	
	public function index($limit = null) {

		// set up parameters
		$arr = array(
			'order' => array('sticky DESC', 'created DESC'),
			'contain' => array('User.username')
			
		);

		// set the limit for number of posts returned
		if (is_null($limit)) {
			$arr['limit'] = 9999;
			$arr['conditions'] = array('datediff(now(), created) < 30');
		} else {
			$arr['limit'] = $limit;
		}

		$data = $this->Post->find('all', $arr);

		if ($this->request->is('requested')) {
			return $data;
		} else {
			// push returned data to the view
		$this->set('posts', $data);
		}

	} // end index

	public function add() {

		// only admins can edit posts	
		if (!$this->Auth->user('admin')) {
			$this->flash('Sorry, only admins can create posts.', $this->referer(), 2);
		}

		if ($this->request->is('post')) {
			$tags = '<p><a><img><strong><span><strike><em><ul><li><ol>';
			$this->request->data['Post']['body'] = strip_tags($this->request->data['Post']['body'], $tags);
			if ($this->Post->save($this->request->data)) {
				$this->Session->setFlash(__('Your post has been saved'), 'custom-flash', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Unable to save your post'), 'custom-flash', array('class' => 'warning'));
			}
		}

	} // end add

	public function edit($id = null) {

		// only admins can edit posts	
		if (!$this->Auth->user('admin')) {
			$this->flash('Sorry, only admins can edit posts.', $this->referer(), 2);
		}

		$this->Post->id = $id;
		if ($this->request->is('get')) {
			$this->request->data = $this->Post->read();
		} else {
			$tags = '<p><a><img><strong><span><strike><em><ul><li><ol>';
			$this->request->data['Post']['body'] = strip_tags($this->request->data['Post']['body'], $tags);
			if ($this->Post->save($this->request->data)) {
				$this->Session->setFlash(__('Your post has been updated'), 'custom-flash', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Unable to update your post'), 'custom-flash', array('class' => 'warning'));
			}
		}

	} // end edit

	public function delete($id = null) {
		
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}

		if ($this->Auth->user('admin')) {
			if ($this->Post->delete($id)) {
				$this->Session->setFlash(__('The post with id: %s has been deleted', $id), 'custom-flash', array('class' => 'alert-info'));
				$this->redirect(array('action' => 'index'));
			}
		} else {
			// shouldn't get this far, as non-admins should not see the link. But just in case
			$this->flash(__('Only admin users may delete posts'), $this->referer, 2);
		}
		
	} // end delete

	public function beforeFilter() {

		parent::beforeFilter();
		$this->Auth->allow();
		
	}

} // end class