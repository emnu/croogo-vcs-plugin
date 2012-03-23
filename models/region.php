<?php
/**
 * Region
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
class Region extends VcsAppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'Region';

	public $tableLive = 'regions';
	public $tableDev = 'regions_dev';

/**
 * Behaviors used by the Model
 *
 * @var array
 * @access public
 */
    public $actsAs = array(
        'Cached' => array(
            'prefix' => array(
                'region_',
                'croogo_regions',
                'block_',
                'croogo_blocks_',
            ),
        ),
    );
/**
 * Model associations: hasMany
 *
 * @var array
 * @access public
 */
    public $hasMany = array(
        'Block' => array(
            'className' => 'Sync.Block',
            'foreignKey' => 'region_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '3',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ),
    );

	public function commit($id, $user_id, $operation = 'add') {
		$this->Behaviors->detach('Vcs.Vcs');
		$this->recursive = -1;
		$this->switchDev();
		$region = $this->read(null, $id);
//		pr($region);
		$this->switchLive();
		$_region = $this->read(null,$id);
//		pr($_region); die();
		$remark = $operation . ' region ' . ' id: ' . $id . ' - ' . $region['Region']['title'];
		$this->revision($id, $user_id, (isset($_region['Region'])?($this->dataToStr($_region['Region'])):''), $this->dataToStr($region['Region']), $region, $remark);

		$data = array();
		$this->create();
		$data['Region'] = $region['Region'];
		unset($data['Region']['block_count']);
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
		$region = $this->read(null, $id);
		$remark = 'delete node ' . ' id: ' . $id . ' - ' . $region['Region']['title'];
		$this->revision($id, $user_id, $this->dataToStr($region['Region']), '', $region, $remark);
		$this->delete($id);
	}
	
	public function revert($id) {
		$this->Behaviors->detach('Vcs.Vcs');
		$this->recursive = -1;
		$this->switchLive();
		$region = $this->read(null, $id);

		$data = array();
		$this->create();
		$data['Region'] = $region['Region'];
		unset($data['Region']['block_count']);
		$this->switchDev();
		$this->save($data);
	}
}
?>