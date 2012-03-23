<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class VcsRevisionsController extends VcsAppController {

	/**
	 * Controller name
	 *
	 * @var string
	 * @access public
	 */
	public $name = 'VcsRevisions';

	public $uses = array(
			'Vcs.VcsRevision',
			'Vcs.VcsCache',
		);

	public function admin_index($model = null, $id = null) {
		$this->set('title_for_layout', __('Revisions', true));
		$conditions = array();
		$revCtrl = array('Block'=>'blocks', 'Link'=>'links', 'Menu'=>'menus', 'Node'=>'nodes', 'Region'=>'regions');
		
		if($this->RequestHandler->isAjax()) {
			$this->paginate['limit'] = 10;
		}

		$this->paginate['order'] = 'VcsRevision.created DESC';
		if($model) {
			$this->paginate['conditions']['VcsRevision.model'] = $model;
		}
		if($id) {
			$this->paginate['conditions']['VcsRevision.model_id'] = $id;
		}

		if($model && $id) {
			$this->set('vcsCache', $this->VcsCache->find('first', array('conditions'=>array('VcsCache.model'=>$model, 'VcsCache.model_id'=>$id))));
		}
		elseif($model) {
			$this->set('vcsCache', $this->VcsCache->find('first', array('conditions'=>array('VcsCache.model'=>$model))));
		}

		$this->VcsRevision->recursive = 0;
		$this->set('vcsRevisions', $this->paginate());
		$this->set(compact('model', 'id', 'revCtrl'));
		
		if($this->RequestHandler->isAjax()) {
			$this->render('admin_index_ajax');
		}
	}

	public function admin_view($id) {
		if (isset($this->params['named']['ajax'])) {
			$this->layout = 'ajax';
		}
		
		$this->set('vcsRevision', $this->VcsRevision->read(null, $id));
	}
}
?>