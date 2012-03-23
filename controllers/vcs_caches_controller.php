<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class VcsCachesController extends VcsAppController {

	/**
	 * Controller name
	 *
	 * @var string
	 * @access public
	 */
	public $name = 'VcsCaches';

	public $uses = array(
			'Vcs.VcsCache',
			'Vcs.VcsRevision',
			'Vcs.Block',
			'Vcs.Link',
			'Vcs.Menu',
			'Vcs.Node',
			'Vcs.Region',
		);

	public function admin_index() {
		$this->set('title_for_layout', __('Changes', true));
		
		if(!empty($this->data)) { //pr($this->data); die();
			$ids = array();
			foreach ($this->data['VcsCache'] as $id => $value) {
				if ($id != 'action' && $value['id'] == 1) {
					$ids[] = $id;
				}
			}
			
			if($this->data['VcsCache']['action'] == 'commit') {
				$this->admin_commit($ids);
			}
			elseif($this->data['VcsCache']['action'] == 'revert') {
				$this->admin_revert($ids);
			}
		}
		
		$this->VcsCache->recursive = 0;
		$this->set('vcsCaches', $this->VcsCache->find('all', array('conditions'=>array('VcsCache.commit'=>NULL))));
	}

	public function admin_commit($ids) {
		$changes = $this->VcsCache->find('all', array('conditions'=>array('VcsCache.id'=>$ids)));
		foreach($changes as $change) {
			switch ($change['VcsCache']['model']) {
				case 'Block' :
					if(in_array($change['VcsCache']['operation'], array('add', 'update'))) {
						$this->Block->commit($change['VcsCache']['model_id'], $change['VcsCache']['user_id'], $change['VcsCache']['operation']);
					}
					else {
						$this->Block->commitDel($change['VcsCache']['model_id'], $change['VcsCache']['user_id']);
					}
					break;
				case 'Link' :
					if(in_array($change['VcsCache']['operation'], array('add', 'update'))) {
						$this->Link->commit($change['VcsCache']['model_id'], $change['VcsCache']['user_id'], $change['VcsCache']['operation']);
					}
					else {
						$this->Link->commitDel($change['VcsCache']['model_id'], $change['VcsCache']['user_id']);
					}
					break;
				case 'Menu' :
					if(in_array($change['VcsCache']['operation'], array('add', 'update'))) {
						$this->Menu->commit($change['VcsCache']['model_id'], $change['VcsCache']['user_id'], $change['VcsCache']['operation']);
					}
					else {
						$this->Menu->commitDel($change['VcsCache']['model_id'], $change['VcsCache']['user_id']);
					}
					break;
				case 'Node' :
					if(in_array($change['VcsCache']['operation'], array('add', 'update'))) {
						$this->Node->commit($change['VcsCache']['model_id'], $change['VcsCache']['user_id'], $change['VcsCache']['operation']);
					}
					else {
						$this->Node->commitDel($change['VcsCache']['model_id'], $change['VcsCache']['user_id']);
					}
					break;
				case 'Region' :
					if(in_array($change['VcsCache']['operation'], array('add', 'update'))) {
						$this->Region->commit($change['VcsCache']['model_id'], $change['VcsCache']['user_id'], $change['VcsCache']['operation']);
					}
					else {
						$this->Region->commitDel($change['VcsCache']['model_id'], $change['VcsCache']['user_id']);
					}
					break;
			}
			$this->VcsCache->id = $change['VcsCache']['id'];
			$this->VcsCache->saveField('commit', 1);
		}
	}

	function admin_reorder_link() {
		$linkCnt = $this->VcsCache->find('count', array('conditions'=>array('VcsCache.model'=>'Link', 'VcsCache.commit'=>null)));
//		pr($linkCnt); die();
		if($linkCnt === 0) {
			$this->Link->reorder();
			$this->Session->setFlash(__('Reorder Success', true));
		}
		else {
			$this->Session->setFlash(__('Reorder Failed', true));
		}

		$this->redirect(array('action'=>'index'));
	}
	
	function admin_diff($model, $model_id) {
		switch($model) {
			case 'Block' :
				$Model = &$this->Block;
				break;
			case 'Link' :
				$Model = &$this->Link;
				break;
			case 'Menu' :
				$Model = &$this->Menu;
				break;
			case 'Node' :
				$Model = &$this->Node;
				break;
			case 'Region' :
				$Model = &$this->Region;
				break;
		}
		$Model->switchDev();
		$data = $Model->read(null, $model_id);
//		pr($block);
		$Model->switchLive();
		$_data = $Model->read(null,$model_id);
//		pr($_block); die();

		$a = explode("\n", isset($_data[$model])?($Model->dataToStr($_data[$model])):'');
		$b = explode("\n", isset($data[$model])?($Model->dataToStr($data[$model])):'');

		App::import('Vendor', 'Cvs.Diff', array('file' => 'php_diff'.DS.'lib'.DS.'Diff.php'));
		$diff = new Diff($a, $b);

		App::import('Vendor', 'Cvs.Inline', array('file' => 'php_diff'.DS.'lib'.DS.'Diff'.DS.'Renderer'.DS.'Html'.DS.'Inline.php'));
		$renderer = new Diff_Renderer_Html_Inline;
		$output = $diff->render($renderer);
//		pr($output); die();
		
		$this->layout = 'ajax';
		
		$this->set(compact('output'));
	}
	
	function admin_revert($ids) {
		$changes = $this->VcsCache->find('all', array('conditions'=>array('VcsCache.id'=>$ids)));
		foreach($changes as $change) {
			switch ($change['VcsCache']['model']) {
				case 'Block' :
					$this->Block->revert($change['VcsCache']['model_id']);
					break;
				case 'Link' :
					$this->Link->revert($change['VcsCache']['model_id']);
					break;
				case 'Menu' :
					$this->Menu->revert($change['VcsCache']['model_id']);
					break;
				case 'Node' :
					$this->Node->revert($change['VcsCache']['model_id']);
					break;
				case 'Region' :
					$this->Region->revert($change['VcsCache']['model_id']);
					break;
			}
			$this->VcsCache->id = $change['VcsCache']['id'];
			$this->VcsCache->saveField('commit', 1);
		}
	}
}
?>