<script type="text/javascript">
$(document).ready(function() {
	tb_init("#vcs_revision_ajax a.thickbox");

	$('.rev-id').click(function(){
		window.location = url + '/' + this.hash.slice(1);
		return false;
	});

	$('.rev-id').click(function(){
		window.location = url + '/' + this.hash.slice(1);
		return false;
	});

	$('.rev-paging a').click(function () {
		$.ajax({
			url: this.href,
			cache: false,
			beforeSend: function(){$('#vcs_revision_ajax').html('<?php echo $this->Html->image('/vcs/img/ajax-loader-hor-w.gif') ?>');},
			success:	function(data) {
				$('#vcs_revision_ajax').html(data);
			}
		})
		return false;
	})
});
</script>
<div class="vcs_revisions index">
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
		<td><?php echo $this->Html->link('['.$vcsRevision['VcsRevision']['id'].']', Router::url(array('action'=>'view', $vcsRevision['VcsRevision']['id'], 'ajax'=>1), true) . '?KeepThis=true&TB_iframe=true&height=500&width=800', array('class' => 'thickbox')); ?>&nbsp;</td>
		<td><?php echo $vcsRevision['VcsRevision']['remark']; ?>&nbsp;</td>
		<td><?php echo $vcsRevision['User']['username']; ?>&nbsp;</td>
		<td><?php echo $vcsRevision['Commit']['username']; ?>&nbsp;</td>
		<td><?php echo $vcsRevision['VcsRevision']['created']; ?>&nbsp;</td>
		<td><?php echo $this->Html->link('Use This Revision', '#rev:'.$vcsRevision['VcsRevision']['id'], array('class'=>'rev-id')) ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging rev-paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>