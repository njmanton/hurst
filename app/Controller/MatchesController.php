<?php

class MatchesController extends AppController {

	public $name = 'Matches';
	public $helpers = array('Html','Form');

	public function view($id = null) {

		// the logged-in user (to mask predictinos where necessary)
		$uid = $this->Auth->user('id');
		// handle routing parameter /view/x -> /x
		if (is_null($id)) {
			if (is_null($this->request->params['id'])) {
				$this->flash('No such match', '/matches/', 2);
			} else {
				$mid = $this->request->params['id'];
			}
		} else {
			$mid = $id;
		}

		// push data to view
		$match = $this->Match->getMatch($mid);

		if (empty($match)) {
			//$this->flash('No such match', '/matches/', 2);
			throw new NotFoundException();
		} else {

			$this->set('match', $match);
			$this->set('preds', $this->Match->preds($mid, $uid));

		}

	} // end view

	public function result($mid = null) {

		if (is_null($mid)) {
			$this->redirect('/matches/');
		}

		if (!$this->Auth->user('admin')) {
			$this->flash('You must be an administrator to edit goals', ('/matches/' . $mid), 2);
		}

		if ($this->request->is('post')) {
			if ($this->Match->processResult($this->request->data)) {
				$this->Session->setFlash('Match updated', 'custom-flash', array('class' => 'success'));
				$this->redirect(__('/matches/%s', $mid));
			} else {
				$this->Session->setFlash('Not everything updated', 'custom-flash', array('class' => 'warning'));
			}
		}

		$m = $this->Match->findById($mid);
		if (!$m) {
			throw new NotFoundException(__('No such match'));
		} else {
			$this->set('match', $m);
		}

	} // end result

	public function index() {

		$this->set('matches', $this->Match->getMatches());

	} // end index

	public function bracket() {

		$this->set('title_for_layout', 'Matches | KO');
		$this->set('matches', $this->Match->getBracket());

	} // end bracket

	public function remaining($uid = null) {

		if ($this->request->is('requested')) {
			return $this->Match->remaining($uid);
		} else {
			throw new MethodNotAllowedException();
		}

	} // end remaining

	public function head() {

		if ($this->request->is('requested')) {
			$arr = [
				'fields' => ['id', 'stage', 'group', 'result'],
				'contain' => ['TeamA.tname', 'TeamB.tname'],
				'order' => ['group', 'date'],
				'conditions' => ['Match.id < 49']
			];

			return $this->Match->find('all', $arr);

		} else {
			throw new MethodNotAllowedException();
		}


	} // end header

	public function date() {

		$ms = $this->Match->getMatches();

		uasort($ms, function($x, $y) {
			return ($x['date'] < $y['date']) ? -1 : 1;
		});

		$matchesByDate = [];
		foreach ($ms as $m) {
			$date = $m['date']->format(DF);
			$matchesByDate[$date][] = $m;
		}
		$this->set('matches', $matchesByDate);

	} // end by_date

	public function api_index() {
		// only for api GET access

		$this->autoRender = false;
		return $this->Match->getApiIndex();

	} // end api_index

	public function hc_data($id = null) {

		if ($this->request->is('ajax')) {

			$this->autoRender = false;
			$arr = [
				'fields' => ['points', 'joker', 'COUNT(Prediction.id) AS cnt'],
				'group' => ['points', 'joker'],
				'recursive' => 0,
				'conditions' => ['match_id' => $id]
			];
			$preds = $this->Match->Prediction->find('all', $arr);
			$json = [];
			foreach ($preds as $p) {
				switch ($p['Prediction']['points']) {
					case 0:
						$pts = 'W';
						break;
					case 1:
					case 2:
						$pts = 'CR';
						break;
					case 3:
					case 6:
						$pts = 'CD';
						break;
					case 5:
					case 10:
						$pts = 'CS';
						break;
				}
				if ($p['Prediction']['joker']) {
					$json[$pts]['J'] += (int)($p[0]['cnt']);
				} else {
					$json[$pts]['N'] += (int)($p[0]['cnt']);
				}

			}

			echo json_encode($json);

		} else {
			throw new MethodNotAllowedException();
		}

	} // end hc_data

	public function beforeFilter() {
		// set up permitted views when not logged in
		parent::beforeFilter();
		$this->Auth->allow('head', 'remaining', 'date');
	}

} // end class
