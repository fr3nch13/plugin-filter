<?php

class FilterBehavior extends ModelBehavior 
{
	public $settings = array();
	
	private $_defaults = array();
	
	// list of the available filters
	public $filter_options = array();
	
	public function setup(Model $Model, $config = array()) 
	{
		// merge the default settings with the model specific settings
		$this->settings[$Model->alias] = array_merge($this->_defaults, $config);
		
		$this->filter_options[$Model->alias] = array();
		
		$this->Filter_setFilterOptions($Model);
	}
	
	public function Filter_getFilterOptions(Model $Model, $model_alias = false)
	{
		$filter_options = $this->filter_options[$Model->alias];
		
		if(!$model_alias)
			return $filter_options;
		
		if(isset($filter_options[$model_alias]))
			return $filter_options[$model_alias];
		
		return false;
	}
	
	public function Filter_setFilterOptions(Model $Model)
	{
		$this->filter_options[$Model->alias] = array();
		
		if(!$Model->belongsTo)
		{
			return;
		}
		
		foreach($Model->belongsTo as $model_alias => $model_settings)
		{
			if(!isset($model_settings['plugin_filter']))
				continue;
			
			$plugin_filter_settings = $model_settings;
			
			if(!is_array($plugin_filter_settings['plugin_filter']))
			{
				if(is_string($plugin_filter_settings['plugin_filter']))
				{
					$name = $plugin_filter_settings['plugin_filter'];
				}
				elseif(is_bool($plugin_filter_settings['plugin_filter']))
				{
					$name = Inflector::humanize($model_alias);
				}
				$plugin_filter_settings['plugin_filter'] = array(
					'name' => $name,
				);
			}
			
			if(!isset($plugin_filter_settings['plugin_filter']['options']))
			{
				$plugin_filter_settings['plugin_filter']['options'] = array();
			}
			
			if(!isset($plugin_filter_settings['plugin_filter']['options']['fields']))
			{
				$plugin_filter_settings['plugin_filter']['options']['fields'] = array(
					$model_alias.'.'.$Model->{$model_alias}->primaryKey,
					$model_alias.'.'.$Model->{$model_alias}->displayField,
				);
			}
			
			if(!isset($plugin_filter_settings['plugin_filter']['values']))
			{
				$plugin_filter_settings['plugin_filter']['values'] = array();
			}
			
			$this->filter_options[$Model->alias][$model_alias] = $plugin_filter_settings;
		}
	}
	
	public function Filter_getLists(Model $Model)
	{
		$filter_options = $this->Filter_getFilterOptions($Model);
		
		foreach($filter_options as $model_alias => $model_settings)
		{
			// have the first option be 0, as not assigned.
			$values = array(0 => __('[ Not assigned to a %s ]', $model_settings['plugin_filter']['name']));
			$values = array_merge($values, $Model->{$model_alias}->find('list', $model_settings['plugin_filter']['options']));
			$filter_options[$model_alias]['plugin_filter']['values'] = $values;
		}
		return $filter_options;
	}
	
	public function Filter_conditions(Model $Model, $conditions = array(), $passedArgs = array())
	{
		if(!$passedArgs)
			return $conditions;
		
		// fix the conditions
		foreach($conditions as $k => $v)
		{
			if(stripos($k, '.') === false)
			{
				// stopwords
				$stop_k = trim(strtoupper($k));
				if(in_array($stop_k, array('AND', 'OR', 'NOT', 'XOR', 'LIKE', 'ILIKE', 'RLIKE')))
					continue;
				
				$conditions[$Model->alias.'.'.$k] = $v;
				unset($conditions[$k]);
			}
		}
		
		$invert = false;
		if(isset($passedArgs['Filter.invert']))
		{
			if($passedArgs['Filter.invert'])
				$invert = true;
			unset($passedArgs['Filter.invert']);
		}
		
		//get just the filter parts from the passed args
		$filters = array();
		foreach($passedArgs as $k => $v)
		{
			if(!preg_match('/^Filter\./', $k))
				continue;
			list($blah, $assoc_model) = explode('.', $k);
			
			$model_settings = $this->Filter_getFilterOptions($Model, $assoc_model);
			
			$filter_key = $Model->alias.'.'.$model_settings['foreignKey'];
			
			if($invert)
				$filter_key .= ' NOT';
			
			$filters[$filter_key] = $v;
		}
		return array_merge($conditions, $filters);
	}
}