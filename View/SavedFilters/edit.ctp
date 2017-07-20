<?php 
// File: plugins/Filter/View/SavedFilters/edit.ctp
?>
<div class="top">
	<h1><?php echo __('Edit %s', __('Saved Filter')); ?></h1>
</div>
<div class="center">
	<div class="form">
		<?php echo $this->Form->create();?>
		    <fieldset>
		    	<?php
					echo $this->Form->input('id');
					echo $this->Form->input('name', array(
						'label' => __('Name of this %s', __('Saved Filter')),
						'div' => array('class' => 'half'),
					));
					
					echo $this->Form->input('description', array(
						'type' => 'textarea',
						'label' => __('A description of this %s', __('Saved Filter')),
					));
		    	?>
		    </fieldset>
		<?php echo $this->Form->end(__('Update %s', __('Saved Filter'))); ?>
	</div>
</div>