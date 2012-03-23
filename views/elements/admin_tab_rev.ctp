<?php
reset($this->data);
$rmodel = key($this->data);
$rid = $this->data[$rmodel]['id'];
//pr($this->data);
?>
<script type="text/javascript">
var url = '<?php echo $this->Html->url(array('action'=>'edit', 'admin'=>true, $this->data[$rmodel]['id'])) ?>';
$(document).ready(function() {
	$.ajax({
		url: '<?php echo $this->Html->url(array('plugin'=>'vcs', 'controller'=>'vcs_revisions', 'action'=>'index', $rmodel, $rid)) ?>',
		cache: false,
		beforeSend: function(){$('#vcs_revision_ajax').html('<?php echo $this->Html->image('/vcs/img/ajax-loader-hor-w.gif') ?>');},
		success:	function(data) {
			$('#vcs_revision_ajax').html(data);
		}
	});
});
</script>
<div id="vcs_revision_ajax">
	
</div>
