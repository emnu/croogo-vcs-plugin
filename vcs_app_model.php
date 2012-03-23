<?php

class VcsAppModel extends AppModel {

	public function switchLive() {
		$this->table = $this->tableLive;
		$this->useTable = $this->tableLive;
	}

	public function switchDev() {
		$this->table = $this->tableDev;
		$this->useTable = $this->tableDev;
	}

	function revision($id, $user_id, $a, $b, $commited_data, $remark='') {
		$a = explode("\n", $a);
		$b = explode("\n", $b);

//		require_once dirname(__FILE__).'/../lib/Diff.php';
		App::import('Vendor', 'Cvs.Diff', array('file' => 'php_diff'.DS.'lib'.DS.'Diff.php'));
		$diff = new Diff($a, $b);

//		require_once dirname(__FILE__).'/../lib/Diff/Renderer/Html/Inline.php';
		App::import('Vendor', 'Cvs.Inline', array('file' => 'php_diff'.DS.'lib'.DS.'Diff'.DS.'Renderer'.DS.'Html'.DS.'Inline.php'));
		$renderer = new Diff_Renderer_Html_Inline;
		$output = $diff->render($renderer);
//pr($output); die();
		$Revision = ClassRegistry::init('Vcs.VcsRevision');
		$data = array(
					'model' => $this->alias,
					'model_id' => $id,
					'diff' => $output,
					'remark' => $remark,
					'user_id' => $user_id,
					'data' => serialize($commited_data),
					'commit_id' => $_SESSION['Auth']['User']['id'],
				);
		$Revision->create();
		$Revision->save($data);
	}

	function dataToStr($data) {
		$str = '';
		foreach ($data as $key=>$value) {
			$str .= str_pad(('*****' . $key), 40, '*', STR_PAD_RIGHT) . "\n";
			$str .= $value . "\n";
		}
		return $str;
	}

	function clearHtmlCache() {
		// clear cache
		App::import('core', 'Folder');
		$Folder = new Folder();
		$Folder->delete(WWW_ROOT . 'cache');
		$Folder->delete(WWW_ROOT . 'mobile');
//		Cache::clear();
	}

}

?>