<?php

class Controller_Payment extends Controller_Core_Action
{
	public function gridAction()
	{
		try {

			$this->getMessage()->getSession()->start();

			$query = "SELECT * FROM `payment method` ORDER BY `payment_method_id` DESC";
			$payments = Ccc::getModel('Payment_Resource')->fetchAll($query);
			if (!$payments) {
				throw new Exception("Error Processing Request", 1);
			}
			$this->getView()
			->setTemplate('payment_method/grid.phtml')
			->setData(['payments' => $payments]);
			$this->render();

		} catch (Exception $e) {
		    $this->getMessage()->addMessage('Payments not avilable',Model_Core_Message :: FAILURE);
			
		}
		
	}

	public function addAction()
	{
		try {
        	$this->getMessage()->getSession()->start();

			$payment = Ccc::getModel('Payment_Row');

			if (!$payment) {
				throw new Exception("Invalid id.", 1);
			}

			$this->getView()
			     ->setTemplate('payment_method/edit.phtml')
			     ->setData(['payment' => $payment]);
			     $this->render();
		} catch (Exception $e) {
			$this->getMessage()->addMessage('Payment not showed.',Model_Core_Message :: FAILURE);
		}
		
	}

	public function editAction()
	{
		try {
	        $this->getMessage()->getSession()->start();

			if (!$id = (int)$this->getRequest()->getParam('payment_method_id'))
			{
				throw new Exception("Invalid request.", 1);
				
			}
			$payment = Ccc::getModel('Payment_Row')->load($id);

			if (!$payment) {
				throw new Exception("Invalid id.", 1);
			}

			$this->getView()
			     ->setTemplate('payment_method/edit.phtml')
			     ->setData(['payment' => $payment]);
			     $this->render();
		} catch (Exception $e) {
			$this->getMessage()->addMessage('Payment not showed.',Model_Core_Message :: FAILURE);
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

			$postData = $this->getRequest()->getpost('payment');

			if (!$postData) {
				throw new Exception("Invalid data posted.", 1);
			}

			if ($id = (int)$this->getRequest()->getParam('payment_method_id')) {
				$payment = Ccc::getModel('Payment_Row')->load($id);

				if (!$payment) {
					throw new Exception("Invalid id.", 1);
				}
			$payment->updated_at = date("Y-m-d H:i:s");
			}
			else{
				$payment = Ccc::getModel('Payment_Row');
				$payment->created_at = date("Y-m-d H:i:s");
			}
			$payment->setData($postData);

			if (!$payment->save()) {
				throw new Exception("Unable to save payment.", 1);
			}
		    $this->getMessage()->addMessage('Payment updeted sucessfully.',Model_Core_Message :: SUCCESS);


		} catch (Exception $e) {
		    $this->getMessage()->addMessage('Invalid.',Model_Core_Message :: FAILURE);
			
		}
		$this->redirect('payment','grid',null,true);
	}

	public function deleteAction()
	{
		try {
			$this->getMessage()->getSession()->start();
			if (!($id = (int) $this->getRequest()->getParam('payment_method_id'))) {
			throw new Exception("Error Processing Request", 1);
			
			}
			$payment = Ccc::getModel('Payment_Row')->load($id);

			if (!$payment) {
				throw new Exception("Error Processing Request", 1);
			}

			$payment->delete();
			$this->getMessage()->addMessage('Payment updeted sucessfully.',Model_Core_Message :: SUCCESS);

		}catch(Exception $e){
			$this->getMessage()->addMessage('Payment not deleted.',Model_Core_Message :: FAILURE);
		}
			$this->redirect('payment','grid',null,true);
	}





}
