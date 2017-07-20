<?php

App::uses('FilterAppController', 'Filter.Controller');

class SavedFiltersController extends FilterAppController 
{
	public function index() 
	{
		$this->Prg->commonProcess();
		
		$conditions = array(
			'SavedFilter.user_id' => AuthComponent::user('id'),
		);
		
		$this->paginate['order'] = array('SavedFilter.created' => 'desc');
		$this->paginate['conditions'] = $this->SavedFilter->conditions($conditions, $this->passedArgs); 
		
		if ($this->request->is('requested')) 
		{
			$savedFilters = $this->SavedFilter->find('all', $this->paginate);
			
			// format for the menu_items
			$items = array();
				$items[] = array(
					'title' => __('All'),
					'url' => array('controller' => 'saved_filters', 'action' => 'index', 'admin' => false, 'plugin' => 'filter'),
				);
			foreach($savedFilters as $savedFilter)
			{
				$items[] = array(
					'title' => $savedFilter['SavedFilter']['name'],
					'url' => $savedFilter['SavedFilter']['url'],
				);
			}
			return $items;
		}
		else
		{
			$this->set('savedFilters', $this->paginate());
		}
		
	}
	
	public function add() 
	{
		if($this->request->is('post') || $this->request->is('put')) 
		{
			$this->request->data['SavedFilter']['user_id'] = AuthComponent::user('id');
			if($this->SavedFilter->save($this->request->data)) 
			{
				$this->Session->setFlash(__('The %s has been added', __('Saved Filter')));
				return $this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('The %s was not added. Please, try again.', __('Saved Filter')));
			}
		}
	}

	public function edit($id = null) 
	{
		$this->SavedFilter->id = $id;
		if (!$savedFilter = $this->SavedFilter->read(null, $this->SavedFilter->id))
		{
			throw new NotFoundException(__('Invalid %s', __('Saved Filter')));
		}
		
		if($this->request->is('post') || $this->request->is('put')) 
		{
			if ($this->SavedFilter->save($this->request->data))
			{
				$this->Session->setFlash(__('The %s has been updated', __('Saved Filter')));
				return $this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('The %s could not be updated. Please, try again.', __('Saved Filter')));
			}
		}
		else
		{
			$this->request->data = $savedFilter;
		}
	}

//
	public function delete($id = null) 
	{
		$this->SavedFilter->id = $id;
		if (!$this->SavedFilter->exists()) {
			throw new NotFoundException(__('Invalid %s', __('Saved Filter')));
		}
		if ($this->SavedFilter->delete()) {
			$this->Session->setFlash(__('%s deleted', __('Saved Filter')));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('%s was not deleted', __('Saved Filter')));
		return $this->redirect(array('action' => 'index'));
	}
}