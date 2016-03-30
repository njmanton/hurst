<?php

class PredictionsController extends AppController {

	public $name = 'Predictions';
	public $helpers = array('Html', 'Form');

	public $pred = [];

	public function index() {

		$user = $this->Auth->user();
		$uid = $user['id'];

		// on form submission
		if ($this->request->is('post')) {

			// create array of jokers to change
			$jokers = array();
			if ($this->data['Joker']) {
				foreach($this->data['Joker'] as $j) {
					$jokers[$j] = $j;
				}
			}

			// loop through submitted predictions
			foreach($this->data['Prediction'] as $k=>$p) {
				// if the 'dirty' flag is set and a prediction
				// has been entered, save the results
				if ($p['dirty'] != 0 && $p['pred'] != '') {
					$tosave = array('Prediction' => array(
						'prediction' => $p['pred'],
						'match_id' => $k,
						'user_id' => $uid,
						'joker' => (int) in_array($k, $jokers)
					));
					// if it's a db update, rather than insert
					// include the prediction PK
					if (isset($p['pid'])) {
						$tosave['Prediction']['id'] = $p['pid'];
					}
					if ($this->Prediction->save($tosave)) {
						if ($p['dirty'] == 1) {
							$upd[] = $k;
							$log[] = $tosave;
						}
					}
				}
			}

			// log the predictions made
			$this->log(json_encode($log), 'pred');
			// pass the updated fields back to the view
			$this->set('updates', $upd);
		}

		// get the predictions details to pass to the view
		$preds = $this->Prediction->prededit($uid);
		$this->set('preds', $preds);

	} // end edit

	public function update() {

		$this->autoRender = false;
		if ($this->request->is('ajax')) {
			$uid = $this->Auth->user('id');
			$data = $this->request->data;

			$tosave = array('Prediction' => array(
				'prediction' => $data['pred'] ?: null,
				'match_id' => $data['mid'],
				'user_id' => $uid,
			));
			// if a pid was passed via ajax, use that
			if ($data['pid']) {

				$tosave['Prediction']['id'] = $data['pid'];

			} else {
				// if it wasn't, can we find the pid from the match_id_user_id key?
				$arr = array(
					'conditions' => array(
						'match_id' => $data['mid'],
						'user_id' => $uid
					)
				);
				$res = $this->Prediction->find('first', $arr);
				$tosave['Prediction']['id'] = $res['Prediction']['id'];

			}

			if ($this->Prediction->save($tosave)) {
				$response = $this->Prediction->id;
				$this->log(json_encode($tosave), 'pred');
			} else {
				$response = false;
			}
			return json_encode($response);
		} else {
			throw new MethodNotAllowedException();
		}

	} // end update

	public function updatej() {

		// updates the jokers for a given group
		// list of prediction ids is passed by POST
		$this->autoRender = false;
		if ($this->request->is('ajax')) {

			$data = $this->request->data;
			$res = 0;
			// loop through each prediction, setting the joker to the correct value
			// 1 if it's the selected prediction, 0 otherwise
			foreach ($data['pids'] as $p) {
				$this->Prediction->id = $p;
				if ($p) {
					$res += (int)$this->Prediction->saveField('joker', ($p == $data['sel']));
					$this->log(json_encode($tosave), 'pred');
				}
			}
			// return the number of updated records
			return $res;

		} else {
			throw new MethodNotAllowedException();
		}

	} // end updatej

	public function bubble() {

		if ($this->request->is('ajax')) {
			$this->autoRender = false;
			$arr = [
				'fields' => ['LEFT(prediction,1) AS x', 'RIGHT(prediction,1) AS y', 'COUNT(Prediction.id) AS z'],
				'group' => ['LEFT(prediction,1)', 'RIGHT(prediction,1)'],
				'recursive' => 0
			];
			$data = $this->Prediction->find('all', $arr);
			foreach ($data as $d) {
				$pred['PredTotal'] += $d[0]['z'];
				$pred['Pred'][] = [
					'x' => (int) $d[0]['x'],
					'y' => (int) $d[0]['y'],
					'z' => (int) $d[0]['z']
				];
			}

			foreach ($pred['Pred'] as &$p) {
				$p['z'] /= ($pred['PredTotal'] / 100);
			} 

			$arr = [
				'fields' => ['LEFT(result,1) AS x', 'RIGHT(result,1) AS y', 'COUNT(Match.id) AS z'],
				'group' => ['LEFT(result,1)', 'RIGHT(result,1)'],
				'recursive' => 0
			];
			$data2 = $this->Prediction->Match->find('all', $arr);
			foreach ($data2 as $d) {
				if (!is_null($d[0]['x'])) {
					$pred['ResultTotal'] += $d[0]['z'];
					$pred['Result'][] = [
						'x' => (int) $d[0]['x'],
						'y' => (int) $d[0]['y'],
						'z' => (int) $d[0]['z']
					];
				}
			}

			foreach ($pred['Result'] as &$r) {
				$r['z'] /= ($pred['ResultTotal'] / 100);
			}

			return (json_encode($pred));

		} else {
			throw new MethodNotAllowedException();
		}

	} // end bubble

	public function beforeFilter() {
		// set up permitted views when not logged in
		parent::beforeFilter();
		$this->Auth->allow('bubble');
	}

} // end class