<?php

class Controller_Shipping extends Controller_Core_Action
{

	public function gridAction()
	{
		try {
			$this->getMessage()->getSession()->start();

			$query = "SELECT * FROM `shipping method` ORDER BY `shipping_id` DESC";
			$shippings = Ccc::getModel('Shipping_Resource')->fetchAll($query);

			if (!$shippings) {
				throw new Exception("Error Processing Request", 1);
			}
			$this->getView()
				 ->setTemplate('shipping_method/grid.phtml')
				 ->setData(['shippings' => $shippings]);
				 $this->render();

		} catch (Exception $e) {
	    	$this->getMessage()->addMessage('Currently Shippings not avilable',Model_Core_Message :: FAILURE);
			
		}
        	
	}


	public function addAction()
	{
		try {
        	$this->getMessage()->getSession()->start();

			$shipping = Ccc::getModel('Shipping_Row');

			if (!$shipping) {
				throw new Exception("Error Processing Request", 1);
			}

			$this->getView()
			     ->setTemplate('shipping_method/edit.phtml')
			     ->setData(['shipping' => $shipping]);
			     $this->render();
		} catch (Exception $e) {
			$this->getMessage()->addMessage('Shipping not showed.',Model_Core_Message :: FAILURE);
		}
		
	}

	public function editAction()
	{
		try {
	        $this->getMessage()->getSession()->start();

			if (!$id = (int)$this->getRequest()->getParam('shipping_id'))
			{
				throw new Exception("Invalid request.", 1);
				
			}
			$shipping = Ccc::getModel('Shipping_Row')->load($id);

			if (!$shipping) {
				throw new Exception("Invalid id.", 1);
			}

			$this->getView()
			     ->setTemplate('shipping_method/edit.phtml')
			     ->setData(['shipping' => $shipping]);
			     $this->render();
		} catch (Exception $e) {
			$this->getMessage()->addMessage('Shipping not showed.',Model_Core_Message :: FAILURE);
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

			$postData = $this->getRequest()->getpost('shipping');



			if (!$postData) {

				throw new Exception("Invalid data posted.", 1);
			}
			if ($id = (int)$this->getRequest()->getParam('shipping_id')) {
				$shipping = Ccc::getModel('Shipping_Row')->load($id);

				if (!$shipping) {
					throw new Exception("Invalid id.", 1);
				}
			$shipping->updated_at = date("Y-m-d H:i:s");
			}
			else{
				$shipping = Ccc::getModel('Shipping_Row');
				$shipping->created_at = date("Y-m-d H:i:s");
			}
			$shipping->setData($postData);
			if (!$shipping->save()) {
				throw new Exception("Unable to save shipping.", 1);
			}
			// die();
		    $this->getMessage()->addMessage('Shipping updeted sucessfully.',Model_Core_Message :: SUCCESS);


		} catch (Exception $e) {
		    $this->getMessage()->addMessage('Invalid.',Model_Core_Message :: FAILURE);
			
		}
		$this->redirect('shipping','grid',null,true);
	}

	

	

	public function deleteAction()

		{
		try {
			$this->getMessage()->getSession()->start();
			if (!($id = (int) $this->getRequest()->getParam('shipping_id'))) {
			throw new Exception("Error Processing Request", 1);
			
		}
		$shipping = Ccc::getModel('Shipping_Row')->load($id);

		if (!$shipping) {
			throw new Exception("Error Processing Request", 1);
		}

		$shipping->delete();

		$this->redirect('shipping','grid',null,true);

		$this->getMessage()->addMessage('Shipping deleted sucessfully.',Model_Core_Message :: SUCCESS);

	}catch(Exception $e){
		$this->getMessage()->addMessage('Shipping not deleted.',Model_Core_Message :: FAILURE);
	}
	}
}



