<?php
/**
 * Node
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
class Node extends VcsAppModel {
/**
 * Model name
 *
 * @var string
 * @access public
 */
    public $name = 'Node';

	public $tableLive = 'nodes';
	public $tableDev = 'nodes_dev';
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
                'node_',
                'nodes_',
                'croogo_nodes_',
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
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
    );
/**
 * Model associations: hasMany
 *
 * @var array
 * @access public
 */
    public $hasMany = array(
        'Comment' => array(
            'className' => 'Comment',
            'foreignKey' => 'node_id',
            'dependent' => false,
            'conditions' => array('Comment.status' => 1),
            'fields' => '',
            'order' => '',
            'limit' => '5',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ),
        'Meta' => array(
            'className' => 'Meta',
            'foreignKey' => 'foreign_key',
            'dependent' => false,
            'conditions' => array('Meta.model' => 'Node'),
            'fields' => '',
            'order' => 'Meta.key ASC',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ),
    );
/**
 * Model associations: hasAndBelongsToMany
 *
 * @var array
 * @access public
 */
    public $hasAndBelongsToMany = array(
        'Taxonomy' => array(
            'className' => 'Taxonomy',
            'with' => 'NodesTaxonomy',
            'joinTable' => 'nodes_taxonomies',
            'foreignKey' => 'node_id',
            'associationForeignKey' => 'taxonomy_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => '',
        ),
    );


	public function commit($id, $user_id, $operation = 'add') {
		$this->Behaviors->detach('Vcs.Vcs');
		$this->recursive = -1;
		$this->switchDev();
		$node = $this->read(null, $id);
//		echo "node $id";
//		pr($node);
		$this->switchLive();
		$_node = $this->read(null,$id);
//		echo "_node $id";
//		pr($_node);
		$remark = $operation . ' node ' . ' id: ' . $id . ' - ' . $node['Node']['title'];
		$this->revision($id, $user_id, (isset($_node['Node'])?($this->dataToStr($_node['Node'])):''), $this->dataToStr($node['Node']), $node, $remark);

		$data = array();
		$this->create();
		$data['Node'] = $node['Node'];
//		$id = $data['Node']['id'];
		unset($data['Node']['lft']);
		unset($data['Node']['rght']);
		unset($data['Node']['comment_count']);
		$this->Behaviors->attach('Tree', array(
			'scope' => array(
				'Node.type' => $data['Node']['type'],
			),
		));
		if($operation == 'add') {
			unset($data['Node']['id']);
			if($this->save($data)) {
				if($this->id != $id) {
					$this->query("UPDATE `nodes` AS `Node` SET `Node`.`id` = $id WHERE `Node`.`id` = " . $this->id);
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
		$node = $this->read(null, $id);
		$remark = 'delete menu ' . ' id: ' . $id . ' - ' . $node['Node']['title'];
		$this->revision($id, $user_id, $this->dataToStr($node['Node']), '', $node, $remark);
		$this->delete($id);
	}
	
	public function revert($id) {
		$this->Behaviors->detach('Vcs.Vcs');
		$this->recursive = -1;
		$this->switchLive();
		$node = $this->read(null, $id);

		$data = array();
		$this->create();
		$data['Node'] = $node['Node'];
		unset($data['Node']['lft']);
		unset($data['Node']['rght']);
		unset($data['Node']['comment_count']);
		$this->Behaviors->attach('Tree', array(
			'scope' => array(
				'Node.type' => $data['Node']['type'],
			),
		));
		$this->switchDev();
		$this->save($data);
	}
}
?>