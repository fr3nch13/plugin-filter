<?php
/**
 * The Filter plugin.
 */

class FilterComponent extends Component 
{
	public $Controller = null;
	public $Model = null;
	
	public $objectName = false;
	public $objectsName = false;

	public function initialize(Controller $Controller) 
	{
		$this->Controller = & $Controller;
		$this->Model = & $this->Controller->{$this->Controller->modelClass};
	}
	
	public function Filter()
	{
		$this->Controller->set('plugin_filter', true); // allows the filter element to be shown
		
		// run the queries and get the list of filters for the form
		$plugin_filter_lists = $this->Model->Filter_getLists();
		$this->Controller->set('plugin_filter_lists', $plugin_filter_lists);
		
		if(!isset($this->Controller->paginate['conditions']))
			$this->Controller->paginate['conditions'] = array();
		
		$this->Controller->paginate['conditions'] = $this->Model->Filter_conditions($this->Controller->paginate['conditions'], $this->Controller->passedArgs);
	}
}