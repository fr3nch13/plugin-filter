<?php 
// File: app/View/SavedFilters/index.ctp


$page_options = array(

);
// content
$th = array(
	'SavedFilter.name' => array('content' => __('Name'), 'options' => array('sort' => 'SavedFilter.name')),
	'SavedFilter.description' => array('content' => __('Description')),
	'SavedFilter.created' => array('content' => __('Created'), 'options' => array('sort' => 'SavedFilter.created')),
	'actions' => array('content' => __('Actions'), 'options' => array('class' => 'actions')),
);

$td = array();
foreach ($savedFilters as $i => $savedFilter)
{
	$td[$i] = array(
		$this->Html->link($savedFilter['SavedFilter']['name'], $savedFilter['SavedFilter']['url']),
		$savedFilter['SavedFilter']['description'],
		$this->Wrap->niceTime($savedFilter['SavedFilter']['created']),
		array(
			$this->Html->link(__('Edit'), array('action' => 'edit', $savedFilter['SavedFilter']['id'])),
			array('class' => 'actions'),
		),
	);
}

echo $this->element('Utilities.page_index', array(
	'page_title' => __('Saved Filters'),
	'search_placeholder' => __('Saved Filters'),
	'page_options' => $page_options,
	'th' => $th,
	'td' => $td,
));