<?php
/**
 * Block
 *
 * PHP version 5
 *
 * @category Model
 * @package  Croogo
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class Block extends VcsAppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'Block';

	public $tableLive = 'blocks';
	public $tableDev = 'blocks_dev';
/**
 * Behaviors used by the Model
 *
 * @var array
 * @access public
 */
    public $actsAs = array(
        'Cached' => array(
            'prefix' => array(
                'block_',
                'blocks_',
                'croogo_blocks_',
            ),
        ),
    );

/**
 * Model associations: belongsTo
 *
 * @var array
 * @access public
 */
    public $belongsTo = array(
        'Region' => array(
            'className' => 'Sync.Region',
            'foreignKey' => 'region_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true,
            'counterScope' => array('Block.status' => 1),
        ),
    );

	public function commit($id, $user_id, $operation = 'add') {
		$this->Behaviors->detach('Vcs.Vcs');
		$this->recursive = -1;
		$this->switchDev();
		$block = $this->read(null, $id);
//		pr($block);
		$this->switchLive();
		$_block = $this->read(null,$id);
//		pr($_block); die();
		$remark = $operation . ' block ' . ' id: ' . $id . ' - ' . $block['Block']['title'];
		$this->revision($id, $user_id, (isset($_block['Block'])?($this->dataToStr($_block['Block'])):''), $this->dataToStr($block['Block']), $block, $remark);

		$data = array();
		$this->create();
		$data['Block'] = $block['Block'];
		if($operation == 'add') {
			$this->save($data);
		}
		else {
			$this->save($data);
		}

		//clear cache
		$this->clearHtmlCache();
	}

	public function commitDel($id, $user_id) {
		$this->Behaviors->detach('Vcs.Vcs');
		$this->recursive = -1;
		$this->switchLive();
		$block = $this->read(null, $id);
		$remark = 'delete block ' . ' id: ' . $id . ' - ' . $block['Block']['title'];
		$this->revision($id, $user_id, $this->dataToStr($block['Block']), '', $block, $remark);
		$this->delete($id);
	}
	
	public function revert($id) {
		$this->Behaviors->detach('Vcs.Vcs');
		$this->recursive = -1;
		$this->switchLive();
		$block = $this->read(null, $id);
		
		$data = array();
		$this->create();
		$data['Block'] = $block['Block'];
		$this->switchDev();
		$this->save($data);
	}
}
?>