<?php

class Controller_Admin extends Controller_Core_Action
{
	public function gridAction()
	{

		try {
			$query = "SELECT * FROM `admin` ORDER BY `admin_id` DESC;";


		$admins = Ccc::getModel('Admin_Resource')->fetchALl($query);


		$this->getView()
			 ->setTemplate('admin/grid.phtml')
			 ->setData([
			 	'admins' =>$admins
			 ])
			 ->render();
			
		} catch (Exception $e) {
			
		}
		
		
	}

	public function addAction()
	{
		try{

		$admin = Ccc::getModel('Admin_Row');

		if (!$admin) {
			throw new Exception("Error Processing Request", 1);
		}
		$this->getView()
			 ->setTemplate('admin/edit.phtml')
			 ->setData([
			 	'admin' =>$admin
			 ])
			 ->render();
		
	}
		catch (Exception $e) {
			
		}
	}

	public function editAction()
	{
		try {
			if (!($id = (int) $this->getRequest()->getParam('id'))) {
			throw new Exception("Error Processing Request", 1);
			
		}
		$admin = Ccc::getModel('Admin_Row')->load($id);

		if (!$admin) {
			throw new Exception("Error Processing Request", 1);
		}
		$this->getView()
			 ->setTemplate('admin/edit.phtml')
			 ->setData([
			 	'admin' =>$admin
			 ])
			 ->render();
		
	}
		catch (Exception $e) {
			
		}
		
			
		
	}

	public function saveAction()
	{
		try {
			if (!$this->getRequest()->isPost()) {
				throw new Exception("Error Processing Request", 1);
			}

		$postData = $this->getRequest()->getPost('admin');
		if (!$postData) {
			throw new Exception("Error Processing Request", 1);
		}


		if ($id = (int) $this->getRequest()->getParam('id')) {
		$admin = Ccc::getModel('Admin_Row')->load($id);
			
		if (!$admin) {
			throw new Exception("Error Processing Request", 1);
			// echo "111";
		}
		$admin->updated_at = date("Y-m-d H:i:s");
		}
		else
		{
			$admin = Ccc::getModel('Admin_Row');
			$admin->created_at = date("Y-m-d H:i:s");
		}
		$admin->setData($postData);

		if (!$admin->save()) {
			throw new Exception("unable to save admin",1);
		}
	
		} catch (Exception $e) {
			
		}
		$this->redirect('admin','grid',null,true);
	}

	public function deleteAction()
	{
		try {
			if (!($id = (int) $this->getRequest()->getParam('id'))) {
			throw new Exception("Error Processing Request", 1);
			
		}
		$admin = Ccc::getModel('Admin_Row')->load($id);

		if (!$admin) {
			throw new Exception("Error Processing Request", 1);
		}

		$admin->delete();

		$this->redirect('admin','grid',null,true);

	}catch(Exception $e){

	}
	}


}






