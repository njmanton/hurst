<?php

class MatchesController extends AppController {

	public $name = 'Matches';
	public $helpers = array('Html','Form');

	public function view($id = null) {

		// the logged-in user (to mask predictions where necessary)
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

	public function analytics($visualisation = null) {

		// anything to go here? probably pull in data by ajax
		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			
			if ($visualisation == 'scorebytime') {
				$sql = 'SELECT
								scorer, 
								`type`, 
								`time`, 
								tao,
								M.id,
								CASE WHEN (team_id = Home.id) THEN Home.name ELSE Away.name END AS team,
								CASE WHEN (team_id = Home.id) THEN Away.name ELSE Home.name END AS oppo
								FROM goals G
								INNER JOIN matches M ON M.id = G.match_id
								INNER JOIN teams Home ON M.teama_id = Home.id
								INNER JOIN teams Away ON M.teamb_id = Away.id
								ORDER BY time, tao ASC';
				$db = $this->Match->getDataSource();
				$res = $db->fetchAll($sql);
				$data = [];

				foreach ($res as $r) {
					if ($r['G']['type'] == 'P') {
						$color = 'rgb(0,0,128)';
					} elseif ($r['G']['type'] == 'O') {
						$color = 'rgb(0,128,0)';
					} else {
						$color = null;
					}
					if ($r['G']['time'] + $r['G']['tao'] == $previoustime) {
						$yaxis += 0.1;
					} else {
						$yaxis = 0.9;
					}
					$data[] = [
						'x' => $r['G']['time'] + $r['G']['tao'],
						'y' => $yaxis,
						'match' => $r['M']['id'],
						'color' => $color,
						'scorer' => $r['G']['scorer'],
						'team' => $r[0]['team'],
						'oppo' => $r[0]['oppo'],
						'type' => $r['G']['type']
					];
					$previoustime = ($r['G']['time'] + $r['G']['tao']);
				}
				return (json_encode($data));
			} elseif ($visualisation == 'pointsbymatch') {

				$sql = 'SELECT
								M.id,
								CONCAT(TA.name, " v ",TB.name) AS mt,
								SUM(P.joker = 1) AS jokers,
								SUM(P.points) AS points

								FROM predictions P 
								INNER JOIN matches M ON P.match_id = M.id
								INNER JOIN teams TA ON M.teama_id = TA.id
								INNER JOIN teams TB ON M.teamb_id = TB.id

								WHERE M.result IS NOT NULL
								GROUP BY M.id
								ORDER BY 4 DESC';
				$db = $this->Match->getDataSource();
				$res = $db->fetchAll($sql);
				foreach($res as $r) {
					$data['labels'][] = $r[0]['mt'];
					$data['jokers'][] = (int)$r[0]['jokers'];
					$data['points'][] = [
						'id' => $r['M']['id'] ,
						'y' => (int)$r[0]['points']
					];
				}
				return (json_encode($data));

			} elseif ($visualisation == 'confed') {

				$sql = 'SELECT T.conference,  
								SUM(CASE WHEN (M.winner_id IS NULL) THEN 1 ELSE
									(CASE WHEN (M.winner_id = T.id) THEN 3 ELSE 0 END) 
								END) / COUNT(M.id) AS pts
								FROM teams T
								INNER JOIN matches M ON (T.id = M.teama_id OR T.id = M.teamb_id)
								WHERE M.result IS NOT NULL
								GROUP BY conference
								ORDER BY 2 DESC';
				$db = $this->Match->getDataSource();
				$res = $db->fetchAll($sql);
				$data = [];
				foreach ($res as $r) {
					$data['pts'][] = (float) $r[0]['pts'];
					$data['labels'][] = $r['T']['conference'];
				}		
				return (json_encode($data));

			} elseif ($visualisation == 'cumulative') {

				$data = [];
				$arr = [
					'fields' => ['result'],
					'order' => ['Match.date ASC'],
					'conditions' => ['Match.result IS NOT NULL']
				];
				$res = $this->Match->find('list', $arr);
				$x = 0;
				foreach ($res as $r) {
					list($a, $b) = explode('-', $r);
					$x += ($a + $b);
					$data[] = $x;
				}
				return (json_encode($data));

			}
			
		} else {
			throw new MethodNotAllowedException();
		}

	} // end analytics

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
		$this->Auth->allow('head', 'remaining', 'date', 'analytics', 'index');
	}

} // end class
