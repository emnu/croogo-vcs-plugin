<div class="vcs_revisions index">
	<h2>Revisions : 
		<?php if($model && $id): ?>
		<?php echo $vcsCache['VcsCache']['model'] ?> : <?php echo $vcsCache['VcsCache']['title'] ?>
		<?php elseif($model): ?>
		<?php echo $vcsCache['VcsCache']['model'] ?>
		<?php endif; ?>
	</h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th>Id</th>
			<th>Remark</th>
			<th>Modify By</th>
			<th>Commit By</th>
			<th>Date</th>
			<th>Actions</th>
	</tr>
	<?php
	$i = 0;
	foreach ($vcsRevisions as $vcsRevision):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $this->Html->link('['.$vcsRevision['VcsRevision']['id'].']', array('action'=>'view', $vcsRevision['VcsRevision']['id']), array('class' => 'thickbox')); ?>&nbsp;</td>
		<td><?php echo $vcsRevision['VcsRevision']['remark']; ?>&nbsp;</td>
		<td><?php echo $vcsRevision['User']['username']; ?>&nbsp;</td>
		<td><?php echo $vcsRevision['Commit']['username']; ?>&nbsp;</td>
		<td><?php echo $vcsRevision['VcsRevision']['created']; ?>&nbsp;</td>
		<td><?php echo $this->Html->link('Use This Revision', array('controller'=>$revCtrl[$vcsRevision['VcsRevision']['model']], 'action'=>'admin_edit', $vcsRevision['VcsRevision']['model_id'],'rev'=>$vcsRevision['VcsRevision']['id'], 'plugin'=>null)) ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>