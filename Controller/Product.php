<?php
class Controller_Product extends Controller_Core_Action
{
	public function gridAction()
	{
		try {
			$this->getMessage()->getSession()->start();
 	
			$query = "SELECT * FROM `product` ORDER BY `product_id` DESC";
			$products = Ccc::getModel('Product_Resource')->fetchAll($query);

			if (!$products) {
				throw new Exception("Error Processing Request", 1);
			}

			$this->getView()
			->setTemplate('product/grid.phtml')
			->setData(['products' => $products]);
			$this->render();
			
		} catch (Exception $e) {
			$this->getMessage()->addMessage('Currently products not avilable',Model_Core_Message :: FAILURE);
			
		}
	   
	}


	public function addAction()
	{
		try {
        	$this->getMessage()->getSession()->start();
        	$add = new Block_Product_Edit();

			$this->getLayout()->addChild('content',$add);
			$this->getLayout()->render();

		} catch (Exception $e) {
			$this->getMessage()->addMessage('Product not showed.',Model_Core_Message :: FAILURE);
		}
		
	}

	public function editAction()
	{
		try {
	        $this->getMessage()->getSession()->start();
			$edit = new Block_Product_Edit();

			$this->getLayout()->addChild('content',$edit);
			$this->getLayout()->render();

		} catch (Exception $e) {
			$this->getMessage()->addMessage('Product not showed.',Model_Core_Message :: FAILURE);
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

			$postData = $this->getRequest()->getpost('product');


			if (!$postData) {
				throw new Exception("Invalid data posted.", 1);
			}

			if ($id = (int)$this->getRequest()->getParam('product_id')) {
				$product = Ccc::getModel('Product_Row')->load($id);

				if (!$product) {
					throw new Exception("Invalid id.", 1);
				}
			$product->updated_at = date("Y-m-d H:i:s");
			}
			else{
				$product = Ccc::getModel('Product_Row');
				$product->created_at = date("Y-m-d H:i:s");
			}
			$product->setData($postData);

			if (!$product->save()) {
				throw new Exception("Unable to save product.", 1);
			}
		    $this->getMessage()->addMessage('Product has been sucessfully.',Model_Core_Message :: SUCCESS);


		} catch (Exception $e) {
		    $this->getMessage()->addMessage('Invalid.',Model_Core_Message :: FAILURE);
			
		}
		$this->redirect('product','grid',null,true);
	}

	public function deleteAction()
	{
		try {
			$this->getMessage()->getSession()->start();
			if (!($id = (int) $this->getRequest()->getParam('product_id'))) {
			throw new Exception("Error Processing Request", 1);
			
		}
		$product = Ccc::getModel('Product_Row')->load($id);

		if (!$product) {
			throw new Exception("Error Processing Request", 1);
		}

		$product->delete();

	    $this->getMessage()->addMessage('Product deleted sucessfully.',Model_Core_Message :: SUCCESS);


	}catch(Exception $e){
		$this->getMessage()->addMessage('Product not deleted.',Model_Core_Message :: FAILURE);
	}
		$this->redirect('product','grid',null,true);
	}





}
