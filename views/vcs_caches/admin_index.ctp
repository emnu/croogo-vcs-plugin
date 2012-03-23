<div class="vcs_caches index">
	<h2>Changes</h2>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Reorder Link', true), array('action' => 'reorder_link')); ?></li>
	</ul>
</div>
<?php echo $this->Form->create('VcsCache', array('url'=>array('plugin'=>'vcs', 'controller'=>'vcs_caches', 'action'=>'admin_index'))) ?>
<!--	<h3>New Records</h3>-->
	<table cellpadding="0" cellspacing="0">
	<tr>
		<th>&nbsp;</th>
		<th>Id</th>
		<th>Model</th>
		<th>Title</th>
		<th>status</th>
		<th>Modify By</th>
		<th>Last Modified</th>
		<th>Actions</th>
	</tr>
	<?php
	$i = 0;
	foreach ($vcsCaches as $vcsCache):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $this->Form->checkbox('VcsCache.'.$vcsCache['VcsCache']['id'].'.id') ?>&nbsp;</td>
		<td><?php echo $vcsCache['VcsCache']['id']; ?>&nbsp;</td>
		<td><?php echo $vcsCache['VcsCache']['model']; ?>&nbsp;</td>
		<td><?php echo $vcsCache['VcsCache']['title']; ?>&nbsp;</td>
		<td><?php echo $vcsCache['VcsCache']['operation']; ?>&nbsp;</td>
		<td><?php echo $vcsCache['User']['username']; ?>&nbsp;</td>
		<td><?php echo $vcsCache['VcsCache']['modified']; ?>&nbsp;</td>
		<td><?php echo $this->Html->link(__('Difference', true), Router::url(array('action' => 'diff',$vcsCache['VcsCache']['model'], $vcsCache['VcsCache']['model_id']), true) . '?KeepThis=true&TB_iframe=true&height=500&width=800', array('class' => 'thickbox'));?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
	</table>
    <div class="bulk-actions">
    <?php
        echo $this->Form->input('VcsCache.action', array(
            'label' => false,
            'options' => array(
                'commit' => __('Commit', true),
                'revert' => __('Revert', true),
            ),
            'empty' => true,
        ));
        echo $this->Form->submit(__('Submit', true));
    ?>
    </div>
<?php echo $this->Form->end() ?>
</div>