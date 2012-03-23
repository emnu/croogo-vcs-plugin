<?php
/**
 * VcsComponent class
 *
 * @uses          Object
 * @package       vcs
 * @subpackage    vcs.controllers.components
 */
class VcsComponent extends Object {

	public function beforeRender(&$controller) {
		if($controller->action == 'admin_edit' && isset($controller->params['named']['rev']) && $controller->params['named']['rev']) {
			$VcsRevision = ClassRegistry::init('Vcs.VcsRevision');
			
			$tmp = $VcsRevision->read('data', $controller->params['named']['rev']);
			$tmp = unserialize($tmp['VcsRevision']['data']);
			$model = key($tmp); 
			if(isset($controller->data[$model])) {
				foreach($controller->data[$model] as $k=>$v) {
					if(isset($tmp[$model][$k])) {
						$controller->data[$model][$k] = $tmp[$model][$k];
					}
				}
			}
			else {
				$controller->data[$model] = $tmp[$model];
			}
		}
	}
}