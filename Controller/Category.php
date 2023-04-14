<?php

class Controller_Category extends Controller_Core_Action
{
	public function gridAction()
	{
		try {
			Ccc::getModel('Core_Session')->start();

			$category = Ccc::getModel('Category_Resource');
			$query = "SELECT * FROM `{$category->getTable()->getTableName()}` WHERE `parent_id` > 0 ORDER BY `path` ASC;";
			$categories = $category->fetchAll($query);
			if (!$categories) {
				throw new Exception("Data not found.", 1);
			}

			$this->getView()
				->setTemplate('category/grid.phtml')
				->setData([
					'categories' => $categories
				]);
			$this->render();
			
		} catch (Exception $e) {
			$this->getMessage()->addMessage('Currently category not avilable',Model_Core_Message :: FAILURE);
		}
	}
   
	public function addAction()
	{
		try {
			Ccc::getModel('Core_Session')->start();
			$category = Ccc::getModel('Category_Row');
			if (!$category) {
				throw new Exception("Data not found.", 1);
			}

			$this->getView()
				->setTemplate('category/edit.phtml')
				->setData([
					'category' => $category
				]);
			$this->render();
		} catch (Exception $e) {
			$this->getMessage()->addMessage('data not showed',Model_Core_Message :: FAILURE);
		}
	}

	public function editAction()
	{
		try {
			Ccc::getModel('Core_Session')->start();
			$categoryId = Ccc::getModel('Core_Request')->getParam('category_id');
			if (!$categoryId) {
				throw new Exception("ID not found.", 1);
			}
				
			$category = Ccc::getModel('Category_Row')->load($categoryId);
			if (!$category) {
				throw new Exception("Data not founded.", 1);
			}

			$this->getView()
				->setTemplate('category/edit.phtml')
				->setData([
					'category' => $category,
				]);
			$this->render();

		} catch (Exception $e) {
			 $this->getMessage()->addMessage('data not showed',Model_Core_Message :: FAILURE);
		}
	}

	public function saveAction()
	{
		try {
			Ccc::getModel('Core_Session')->start();
			if (!Ccc::getModel('Core_Request')->isPost()) {
				throw new Exception("Invalid request.", 1);
			}

			$categoryPost = Ccc::getModel('Core_Request')->getPost('category');
			if (!$categoryPost) {
				throw new Exception("Data not found.", 1);
			}

			if ($id = (int) Ccc::getModel('Core_Request')->getParam('category_id')) {
				$category = Ccc::getModel('Category_Row')->load($id);
				if (!$category) {
					throw new Exception("Data not found.", 1);
				}
				$category->updated_at = date('Y-m-d h-i-sA');
			} else {
				$category = Ccc::getModel('Category_Row');
				$category->created_at = date('Y-m-d h-i-sA');
			}

			$category->setData($categoryPost);

			if (!$category->save()) {
				throw new Exception("Category data not saved.", 1);
			} else {
				$category->updatePath();
				$this->getMessage()->addMessage('Category data saved Successfully.',Model_Core_Message :: SUCCESS);
			}

		} catch (Exception $e) {
			 $this->getMessage()->addMessage('Invalid.',Model_Core_Message :: FAILURE);
		}

		$this->redirect('category','grid',null,true);

	}

	public function deleteAction()
	{
		try {
			Ccc::getModel('Core_Session')->start();
			$categoryId = Ccc::getModel('Core_Request')->getParam('category_id');
			if (!$categoryId) {
				throw new Exception("ID not found.", 1);
			}

			$category = Ccc::getModel('Category_Row')->load($categoryId);
			$delete = $category->delete();
			if (!$delete) {
		  		throw new Exception("Category data not deleted.", 1);
			} else{
			  	$this->getMessage()->addMessages('Category data deleted Successfully.',Model_Core_Message :: SUCCESS);
			}
		} catch (Exception $e) {
			$this->getMessage()->addMessage('data not deleted',Model_Core_Message :: FAILURE);
		}

		$this->redirect('category','grid',null,true);

	}
}