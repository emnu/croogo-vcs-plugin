<?php
/**
 * Menu
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
class Menu extends VcsAppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'Menu';

	public $tableLive = 'menus';
	public $tableDev = 'menus_dev';

/**
 * Behaviors used by the Model
 *
 * @var array
 * @access public
 */
    public $actsAs = array(
        'Cached' => array(
            'prefix' => array(
                'link_',
                'menu_',
                'croogo_menu_',
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
        'Link' => array(
            'className' => 'Sync.Link',
            'foreignKey' => 'menu_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => 'Link.lft ASC',
            'limit' => '',
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
		$menu = $this->read(null, $id);
//		pr($menu);
		$this->switchLive();
		$_menu = $this->read(null,$id);
//		pr($_menu); die();
		$remark = $operation . ' menu ' . ' id: ' . $id . ' - ' . $menu['Menu']['title'];
		$this->revision($id, $user_id, (isset($_menu['Menu'])?($this->dataToStr($_menu['Menu'])):''), $this->dataToStr($menu['Menu']), $menu, $remark);

		$data = array();
		$this->create();
		$data['Menu'] = $menu['Menu'];
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
		$menu = $this->read(null, $id);
		$remark = 'delete menu ' . ' id: ' . $id . ' - ' . $menu['Menu']['title'];
		$this->revision($id, $user_id, $this->dataToStr($menu['Menu']), '', $menu, $remark);
		$this->delete($id);
	}
	
	public function revert($id) {
		$this->Behaviors->detach('Vcs.Vcs');
		$this->recursive = -1;
		$this->switchLive();
		$menu = $this->read(null, $id);
		
		$data = array();
		$this->create();
		$data['Menu'] = $menu['Menu'];
		$this->switchDev();
		$this->save($data);
	}
}
?>