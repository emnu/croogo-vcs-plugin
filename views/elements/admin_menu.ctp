<?php echo $this->Html->link(__('Versioning', true), '#'); ?>
<ul>
    <li><?php echo $this->Html->link(__('Changes', true), array('plugin'=>'vcs', 'controller'=>'vcs_caches', 'action'=>'index', 'admin'=>true)); ?></li>
    <li><?php echo $this->Html->link(__('Revision Log', true), array('plugin'=>'vcs', 'controller'=>'vcs_revisions', 'action'=>'index', 'admin'=>true)); ?></li>
</ul>