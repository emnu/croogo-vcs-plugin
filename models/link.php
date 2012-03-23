<?php
/**
 * Link
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
class Link extends VcsAppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'Link';

	public $tableLive = 'links';
	public $tableDev = 'links_dev';
/**
 * Behaviors used by the Model
 *
 * @var array
 * @access public
 */
    public $actsAs = array(
        'Tree',
        'Cached' => array(
            'prefix' => array(
                'link_',
                'menu_',
                'croogo_menu_',
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
        'Menu' => array(
					'className' => 'Sync.Menu',
					'counterCache' => true
				)
    );

	public function commit($id, $user_id, $operation = 'add') {
		$this->Behaviors->detach('Vcs.Vcs');
		$this->recursive = -1;
		$this->switchDev();
		$link = $this->read(null, $id);
//		pr($link);
		$this->switchLive();
		$_link = $this->read(null,$id);
//		pr($_link); die();
		$remark = $operation . ' link ' . ' id: ' . $id . ' - ' . $link['Link']['title'];
		$this->revision($id, $user_id, (isset($_link['Link'])?($this->dataToStr($_link['Link'])):''), $this->dataToStr($link['Link']), $link, $remark);

		$data = array();
		$this->create();
		$data['Link'] = $link['Link'];
//		$id = $data['Link']['id'];
		unset($data['Link']['lft']);
		unset($data['Link']['rght']);
		$this->Behaviors->attach('Tree', array(
			'scope' => array(
				'Link.menu_id' => $data['Link']['menu_id'],
			),
		));
		if($operation == 'add') {
			unset($data['Link']['id']);
			if($this->save($data)) {
				if($this->id != $id) {
					$this->query("UPDATE `links` AS `Link` SET `Link`.`id` = $id WHERE `Link`.`id` = " . $this->id);
				}
			}
		}
		else {
			$this->save($data);
		}
	}

	public function commitDel($id, $user_id) {
		$this->Behaviors->detach('Vcs.Vcs');
		$this->recursive = -1;
		$this->switchLive();
		$link = $this->read(null, $id);
		$remark = 'delete link ' . ' id: ' . $id . ' - ' . $link['Link']['title'];
		$this->revision($id, $user_id, $this->dataToStr($link['Link']), '', $link, $remark);
		$this->delete($id);
	}

	public function reorder() {
		$this->switchLive();
		do {
			$links = $this->find('all', array(
						'joins'=>array(
							array(
								'table' => 'links_dev',
								'alias' => 'LinkDev',
								'type' => 'LEFT',
								'conditions' => array(
									"LinkDev.id = Link.id",
								)
							),
						),
						'fields' => array(
							'Link.id',
							'Link.menu_id',
							'Link.parent_id',
							'LinkDev.parent_id',
							'Link.lft',
							'Link.rght',
							'LinkDev.lft',
							'LinkDev.rght',
						),
						'conditions' => array(
							'OR' => array(
								'Link.lft != LinkDev.lft',
								'Link.rght != LinkDev.rght',
							)
						),
						'order' => 'LinkDev.lft, LinkDev.rght',
					));
//pr($links); die();
			if(!empty($links)) {
				$this->Behaviors->attach('Tree', array(
					'scope' => array(
						'Link.menu_id' => $links[0]['Link']['menu_id'],
					),
				));
				if($links[0]['Link']['lft'] > $links[0]['LinkDev']['lft']) {
					$this->moveUp($links[0]['Link']['id'], 1);
				}
				else {
					$this->moveDown($links[0]['Link']['id'], 1);
				}
			}
//			pr($links);
		} while (!empty($links));
	}
	
	public function revert($id) {
		$this->Behaviors->detach('Vcs.Vcs');
		$this->recursive = -1;
		$this->switchLive();
		$link = $this->read(null, $id);

		$data = array();
		$this->create();
		$data['Link'] = $link['Link'];
		unset($data['Link']['lft']);
		unset($data['Link']['rght']);
		$this->Behaviors->attach('Tree', array(
			'scope' => array(
				'Link.menu_id' => $data['Link']['menu_id'],
			),
		));
		$this->switchDev();
		$this->save($data);
	}
}
?>