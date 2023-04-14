<?php
				
class Block_Admin_Edit extends Block_Core_Template
{
	
	function __construct()
	{
		parent::__construct();
		$this->setTemplate('admin/edit.phtml');
	}

	public function getAdmin()
	{
		$adminId = Ccc::getModel('Core_Request')->getParam('id');
		if (!$adminId) 
		{
			$admin = Ccc::getModel('Admin');
		}
		else
		{
			$admin = Ccc::getModel('Admin')->load($adminId);
		}
		return $admin;
	}
}