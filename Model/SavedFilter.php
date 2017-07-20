<?php
App::uses('AppModel', 'Model');

class SavedFilter extends AppModel 
{
	public $actsAs = array(
		'Snapshot.Stat' => array(
			'entities' => array(
				'all' => array(),
				'created' => array(),
				'modified' => array(),
			),
		),
	);
	
	public function beforeSave($options = array()) 
	{
		if(isset($this->data[$this->alias]))
			$this->data[$this->alias]['modified'] = date('Y-m-d H:i:s');
		
		return parent::beforeSave($options);
	}
}