<?php
/**
*
*/
class Block_Category_Grid extends Block_Core_Template
{
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('category/grid.phtml');
	}
	

	public function getCollection()
	{
		$sql = "SELECT * FROM `customer` WHERE 1";
		$customer = Ccc::getModel('Customer')->fetchAll($sql);
		return $categories;
	}
}