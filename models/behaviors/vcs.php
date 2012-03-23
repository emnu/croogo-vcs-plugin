<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
 
class VcsBehavior extends ModelBehavior {

	public function setup(&$Model, $config = array()) {
		$this->tableLive = $Model->table;
		$this->tableDev = $Model->table . '_dev';
		if(isset($GLOBALS['Dispatcher']->params['admin']) && $GLOBALS['Dispatcher']->params['admin']) {
			$Model->table = $this->tableDev;
			$Model->useTable = $this->tableDev;
		}
		parent::setup($model, $config);
	}

	public function afterSave(&$model, $created) {
		parent::afterSave($model, $created);

		if(!empty ($model->data)) {
			$tmp = $model->read(null, $model->id);
			unset($tmp[$model->alias]['created']);
			unset($tmp[$model->alias]['modified']);
			unset($tmp[$model->alias]['updated']);
			$checksum = md5(serialize($tmp));
			$VcsCache = ClassRegistry::init('Vcs.VcsCache');

			$data = $VcsCache->find('first', array('conditions'=>array('VcsCache.model'=>$model->alias, 'VcsCache.model_id'=>$model->id)));
			if(!isset($data['VcsCache']['checksum']) || $checksum != $data['VcsCache']['checksum']) {
				$data['VcsCache']['model'] = $model->alias;
				$data['VcsCache']['model_id'] = $model->id;
				$data['VcsCache']['title'] = $model->data[$model->alias][$model->displayField];
				if($created || (isset($data['VcsCache']['operation']) && $data['VcsCache']['operation'] == 'add' && $data['VcsCache']['commit'] === NULL)){
					$data['VcsCache']['operation'] = 'add';
				}
				else {
					$data['VcsCache']['operation'] = 'update';
				}
				$data['VcsCache']['commit'] = NULL;
				$data['VcsCache']['checksum'] = $checksum;
				$data['VcsCache']['user_id'] = $_SESSION['Auth']['User']['id'];
				if(isset($data['VcsCache']['modified'])) unset($data['VcsCache']['modified']);
				$VcsCache->save($data);
			}
		}
	}

	public function  beforeDelete(&$model, $cascade = true) {
		parent::beforeDelete($model, $cascade);

		$this->deleteTitle = $model->field($model->displayField);
	}

	public function  afterDelete(&$model) {
		parent::afterDelete($model);

		$VcsCache = ClassRegistry::init('Vcs.VcsCache');

		$data = $VcsCache->find('first', array('conditions'=>array('VcsCache.model'=>$model->alias, 'VcsCache.model_id'=>$model->id)));
		$data['VcsCache']['model'] = $model->alias;
		$data['VcsCache']['model_id'] = $model->id;
		$data['VcsCache']['title'] = $this->deleteTitle;
		$data['VcsCache']['operation'] = 'delete';
		$data['VcsCache']['commit'] = NULL;
		$data['VcsCache']['user_id'] = $_SESSION['Auth']['User']['id'];
		if(isset($data['VcsCache']['modified'])) unset($data['VcsCache']['modified']);
		$VcsCache->save($data);
	}
}
?>
