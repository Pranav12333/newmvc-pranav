<?php


class Controller_Salesman extends Controller_Core_Action
{
	public function gridAction()
	{
		try {
			$this->getMessage()->getSession()->start();

			$query = "SELECT * FROM `salesman` ORDER BY `salesman_id` DESC";
			$salesmen = Ccc::getModel('Salesman_Resource')->fetchAll($query);

			if (!$salesmen) {
				throw new Exception("Error Processing Request", 1);
			}

			$this->getView()
			->setTemplate('salesman/grid.phtml')
			->setData(['salesmen' => $salesmen]);
			$this->render();
			
		} catch (Exception $e) {
			$this->getMessage()->addMessage('Currently salesmen not avilable',Model_Core_Message :: FAILURE);
			
		}
	}

	public function addAction()
	{
		try {
	        	$this->getMessage()->getSession()->start();

				$salesman = Ccc::getModel('Salesman_Row');

				if (!$salesman) {
					throw new Exception("Invalid id.", 1);
				}

				$salesmanAddress = Ccc::getModel('Salesman_Address_Row');

				if (!$salesmanAddress) {
					throw new Exception("Invalid id.", 1);
				}
				$this->getView()
				     ->setTemplate('salesman/edit.phtml')
				     ->setData(['salesman' => $salesman,'salesmanAddress' => $salesmanAddress]);
				     $this->render();
			} catch (Exception $e) {
				
			}
	}

	public function editAction()
	{

		try {
				$this->getMessage()->getSession()->start();

				if (!$id = (int)$this->getRequest()->getParam('salesman_id'))
				{
					throw new Exception("Invalid request.", 1);
					
				}
				$salesman = Ccc::getModel('Salesman_Row')->load($id);
				// echo "<pre>";
				// print_r($salesman);
				// die();

				if (!$salesman) {
					throw new Exception("Error Processing Request", 1);
				}

				// $query = "SELECT * FROM `salesman_address` WHERE `salesman_id` = {$id}";
				$salesmanAddress = Ccc::getModel('Salesman_Address_Row')->load($id);


				if (!$salesmanAddress) {
					throw new Exception("Error Processing Request", 1);
				}

				$this->getView()
				     ->setTemplate('salesman/edit.phtml')
				     ->setData(['salesman' => $salesman,'salesmanAddress' => $salesmanAddress]);
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

				$postData = $this->getRequest()->getpost('salesman');
				if (!$postData) {
					throw new Exception("Invalid data posted.", 1);
				}
				$postDataAddress = $this->getRequest()->getpost('address');
				if (!$postDataAddress) {
					throw new Exception("Invalid data posted.", 1);
				}
				// print_r($postData);


				if ($id = (int)$this->getRequest()->getParam('salesman_id')) {
					$salesman = Ccc::getModel('Salesman_Row')->load($id);
					$salesmanAddress = Ccc::getModel('Salesman_Address_Row')->load($id);

					if (!$salesman) {
						throw new Exception("Invalid id.", 1);
					}
				$salesman->updated_at = date("Y-m-d H:i:s");
				}
				else{
					$salesman = Ccc::getModel('Salesman_Row');
					$salesmanAddress = Ccc::getModel('Salesman_Address_Row');
					$salesman->created_at = date("Y-m-d H:i:s");
				}
				$salesman->setData($postData);
				if (!$salesman->save()) {
					throw new Exception("Unable to save salesman.", 1);
				}
				$salesmanAddress->setData($postDataAddress);
				if (!$salesmanAddress->save()) {
					throw new Exception("Unable to save salesman.", 1);
				}

			    $this->getMessage()->addMessage('Salesman updeted sucessfully.',Model_Core_Message :: SUCCESS);


			} catch (Exception $e) {
			    $this->getMessage()->addMessage('Invalid.',Model_Core_Message :: FAILURE);
				
			}
			$this->redirect('salesman','grid',null,true);
		}

	public function deleteAction()
	{
	    try {
	    	$this->getMessage()->getSession()->start();
				if (!($id = (int) $this->getRequest()->getParam('salesman_id'))) {
				throw new Exception("Error Processing Request", 1);
				
			}
			$salesman = Ccc::getModel('Salesman_Row')->load($id);

			if (!$salesman) {
				throw new Exception("Error Processing Request", 1);
			}

			$salesman->delete();

			$this->getMessage()->addMessage('Salesman updeted sucessfully.',Model_Core_Message :: SUCCESS);
			}catch(Exception $e){
				$this->getMessage()->addMessage('salesman not deleted.',Model_Core_Message :: FAILURE);
			}
			$this->redirect('salesman','grid',null,true);

	}

}
