<?php echo $this->Html->css(array('/vcs/css/styles')); ?>
<div class="vcs_revisions view">
<h3>[<?php echo $vcsRevision['VcsRevision']['id']; ?>] : <?php echo $vcsRevision['VcsRevision']['model']; ?> : <?php echo $vcsRevision['VcsRevision']['model_id']; ?></h3>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modify By'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $vcsRevision['User']['username']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Commit By'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $vcsRevision['Commit']['username']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Date'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $vcsRevision['VcsRevision']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Diff'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $vcsRevision['VcsRevision']['diff']; ?>
			&nbsp;
		</dd>
	</dl>
</div>