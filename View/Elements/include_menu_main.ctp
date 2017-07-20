<?php ?>
<li>
	<?php echo $this->Html->link(__('Saved Filters'), array('controller' => 'saved_filters', 'action' => 'index', 'admin' => false, 'plugin' => 'filter'), array('class' => 'top')); ?>
	<?php echo $this->element('Utilities.menu_items', array(
		'request_url' => array('controller' => 'saved_filters', 'action' => 'index', 'admin' => false, 'plugin' => 'filter'),
	)); ?>
</li>