<?php 

class Block_Product_Edit extends Block_Core_Template
{
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('product/edit.phtml');
	}

	public function getProduct()
	{
		$productId = Ccc::getModel('Core_Request')->getParam('product_id');
		if (!$productId) {
		$product = Ccc::getModel('Product');
		}
		else
		{
		$product = Ccc::getModel('Product')->load($productId);
		}
		return $product;
	}
}