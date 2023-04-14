<?php

class Controller_Customer extends Controller_Core_Action
{
	public function gridAction()
	{
		try {
			$this->getMessage()->getSession()->start();

			$query = "SELECT * FROM `customer` ORDER BY `customer_id` DESC";
			$customers = Ccc::getModel('Customer_Resource')->fetchAll($query);

			if (!$customers) {
				throw new Exception("Error Processing Request", 1);
			}

			$this->getView()
			->setTemplate('customer/grid.phtml')
			->setData(['customers' => $customers]);
			$this->render();
			
		} catch (Exception $e) {
			$this->getMessage()->addMessage('Currently customers not avilable',Model_Core_Message :: FAILURE);
			
		}
	   
	}

	public function addAction()
	{
		try {
        	$this->getMessage()->getSession()->start();

			$customer = Ccc::getModel('Customer_Row');

			if (!$customer) {
				throw new Exception("Invalid id.", 1);
			}

			$customerAddress = Ccc::getModel('Customer_Address_Row');

			if (!$customerAddress) {
				throw new Exception("Invalid id.", 1);
			}
			$this->getView()
			     ->setTemplate('customer/edit.phtml')
			     ->setData(['customer' => $customer,'customerAddress' => $customerAddress]);
			     $this->render();
		} catch (Exception $e) {
			$this->getMessage()->addMessage('data not showed',Model_Core_Message :: FAILURE);
		}
	}

	public function editAction()
	{
		try {
			$this->getMessage()->getSession()->start();

			if (!$id = (int)$this->getRequest()->getParam('customer_id'))
			{
				throw new Exception("Invalid request.", 1);
				
			}
			$customer = Ccc::getModel('Customer_Row')->load($id);

			if (!$customer) {
				throw new Exception("Error Processing Request", 1);
			}

			// $query = "SELECT * FROM `customer_address` WHERE `customer_id` = {$id}";
			$customerAddress = Ccc::getModel('Customer_Address_Row')->load($id);


			if (!$customerAddress) {
				throw new Exception("Error Processing Request", 1);
			}

			$this->getView()
			     ->setTemplate('customer/edit.phtml')
			     ->setData(['customer' => $customer,'customerAddress' => $customerAddress]);
			     $this->render();
			
		} catch (Exception $e) {
			 $this->getMessage()->addMessage('data not showed',Model_Core_Message :: FAILURE);
		}
		
		
	}

	public function saveAction()
	{
		try {
       		$this->getMessage()->getSession()->start();

			if (!$this->getRequest()->isPost()) {
				// echo "111";
				throw new Exception("Invalid request.", 1);
			}

			$postData = $this->getRequest()->getpost('customer');
			if (!$postData) {
				throw new Exception("Invalid data posted.", 1);
			}
			$postDataAddress = $this->getRequest()->getpost('address');
			if (!$postDataAddress) {
				throw new Exception("Invalid data posted.", 1);
			}
			// print_r($postData);


			if ($id = (int)$this->getRequest()->getParam('customer_id')) {
				$customer = Ccc::getModel('Customer_Row')->load($id);
				$customerAddress = Ccc::getModel('Customer_Address_Row')->load($id);

				if (!$customer) {
					throw new Exception("Invalid id.", 1);
				}
			$customer->updated_at = date("Y-m-d H:i:s");
			}
			else{
				$customer = Ccc::getModel('Customer_Row');
				$customerAddress = Ccc::getModel('Customer_Address_Row');
				$customer->created_at = date("Y-m-d H:i:s");
			}
			$customer->setData($postData);
			if (!$customer->save()) {
				throw new Exception("Unable to save customer.", 1);
			}
			$customerAddress->setData($postDataAddress);
			if (!$customerAddress->save()) {
				throw new Exception("Unable to save customer.", 1);
			}

		    $this->getMessage()->addMessage('Customer updeted sucessfully.',Model_Core_Message :: SUCCESS);


		} catch (Exception $e) {
		    $this->getMessage()->addMessage('Invalid.',Model_Core_Message :: FAILURE);
			
		}
		$this->redirect('customer','grid',null,true);
	}

	public function deleteAction()
	{
	    try {
	    	$this->getMessage()->getSession()->start();
			if (!($id = (int) $this->getRequest()->getParam('customer_id'))) {
			throw new Exception("Error Processing Request", 1);
			
		}
		$customer = Ccc::getModel('Customer_Row')->load($id);

		if (!$customer) {
			throw new Exception("Error Processing Request", 1);
		}

		$customer->delete();
		$this->getMessage()->addMessage('Customer updeted sucessfully.',Model_Core_Message :: SUCCESS);

		}catch(Exception $e){
			$this->getMessage()->addMessage('data not deleted',Model_Core_Message :: FAILURE);
		}
		$this->redirect('customer','grid',null,true);
	}
	}

