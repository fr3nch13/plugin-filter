<?php
// The form object that sits above the table we're filtering
// to use the plugin_filter
$plugin_filter = (isset($plugin_filter)?$plugin_filter:false);
if(!$plugin_filter) return;

// the lists for the filters
$plugin_filter_lists = (isset($plugin_filter_lists)?$plugin_filter_lists:array());
if(!$plugin_filter_lists) return;

$plugin_filter_id = (isset($plugin_filter_id)?$plugin_filter_id:'plugin_filter_'.rand(1, 1000));

?>
<div class="clearb"> </div>
<div class="plugin_filter" id="<?php echo $plugin_filter_id; ?>">
	<form class="plugin_filter">
		<h4><?php echo __('Filters'); ?></h4>
		<table><tr>
		<?php
			$out = array();
			$oneSet = false;
			foreach($plugin_filter_lists as $model_alias => $filter_settings)
			{
				$value = (isset($passedArgs['Filter.'. $model_alias])?$passedArgs['Filter.'. $model_alias]:'');
				if($value)
					$oneSet = true;
				
				$class = false;
				if($value != '')
					$class = 'highlight';
				
				$input = $this->Form->input('Filter.'. $model_alias, array(
					'options' => $filter_settings['plugin_filter']['values'],
					'empty' => __('- %s -', $filter_settings['plugin_filter']['name']),
					'div' => false,
					'label' => false,
					'type' => 'select',
					'value' => $value,
					'class' => $class,
				));
				
				$out[] = $this->Html->tag('td', $input, array('class' => 'plugin-filter-holder'));
				
			}
			
			// the invert option
			$value = (isset($passedArgs['Filter.invert'])?$passedArgs['Filter.invert']:false);
				
			if(!$oneSet)
				$value = '';
				
			$include_options = array(
				'empty' => __('- %s -', __('Include?')),
				'options' => array('0' => __('Include'), '1' => __('Exclude')),
				'div' => false,
				'label' => false,
				'type' => 'select',
				'value' => $value,
				'class' => $class,
			);
				
			$class = false;
			if($value != '')
				$class = 'highlight';
			
			$input = $this->Form->input('Filter.invert', $include_options);
			
			$out[] = $this->Html->tag('td', $input, array('class' => 'plugin-filter-holder', 'style' => 'min-width:85px'));
			
			$save_input = $this->Form->input('Filter.save_input.'.rand(0,100), array(
				'div' => false,
				'label' => false,
				'placeholder' => __('Filter Name'),
				'type' => 'text',
				'class' => 'plugin-filter-save-text',
			));
			$save_button = $this->Html->tag('input', '', array(
				'div' => false,
				'label' => false,
				'type' => 'submit',
				'value' => __('Save Filter'),
				'class' => 'plugin-filter-save-button',
			));
			$out[] = $this->Html->tag('td', $save_input.$save_button, array('class' => 'plugin-filter-save'));
			
			echo implode("\n", $out);
		?>
		</tr></table>
	</form>
</div>
<?php
		
$here = $this->Html->urlHere();
$here['page'] = 1;
$urlHere = Router::url($here);
$saveUrl = $this->Html->url(array(
	'controller' => 'saved_filters',
	'action' => 'add',
	'plugin' => 'filter',
	'admin' => false,
	'prefix' => false,
));
?>

<script type="text/javascript">
//<![CDATA[
$(document).ready(function ()
{
	var filterOptions = {};
<?php 
		if($this->request->is('ajax'))
		{
?>
	filterOptions['ajaxLoaded'] = true;
<?php
		}
?>
	filterOptions['urlHere'] = '<?php echo $urlHere; ?>';
	filterOptions['saveUrl'] = '<?php echo $saveUrl; ?>';
	$('#<?php echo $plugin_filter_id?>').pluginFilter(filterOptions);
});
//]]>
</script>