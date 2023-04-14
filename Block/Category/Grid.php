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
	

	public function getCategories()
	{
		$sql = "SELECT * FROM `category` WHERE 1";
		$categories = Ccc::getModel('Category')->fetchAll($sql);
		return $categories;
	}
}