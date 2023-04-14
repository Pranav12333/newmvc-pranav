<?php

class Controller_Product_Media extends Controller_Core_Action
{

	protected $medias = null;
	// protected $mediaUrl = null;
	protected $mediaId = null;
	protected $productId = null;
	protected $mediaModel = null;
	protected $mediaRow = null;


	public function setMediaUrl($mediaUrl)
	{
		$this->mediaUrl = $mediaUrl;
		return $this;
	}

	public function getMediaUrl()
	{
		if($this->mediaUrl != null){
			return $this->mediaUrl;
		}

		$mediaUrl = new Model_Core_Url();
		$this->setMediaUrl($mediaUrl);
		return $mediaUrl;
	}

	
    public function setMedias($medias){
        $this->medias = $medias;
        return $this;
    }

    public function getMedias(){
        return $this->medias;
    }

	public function setMediaModel($mediaModel)
	{
		$this->mediaModel = $mediaModel;
		return $this;
	}

	public function getMediaModel()
	{
		if($this->mediaModel != null){
			return $this->mediaModel;
		}

		$mediaModel = new Model_Product_Media();
		$this->setMediaModel($mediaModel);
		return $mediaModel;
	}

	public function setMediaId($mediaId)
	{
		$this->mediaId = $mediaId;
		return $this;
	}

	public function getMediaId()
	{
		return $this->mediaId;
	}
	
	public function setProductId($productId)
	{
		$this->productId = $productId;
		return $this;
	}

	public function getProductId()
	{
		return $this->productId;
	}

	public function setMediaRow($mediaRow)
	{
		$this->mediaRow = $mediaRow;
		return $this;
	}

	public function getMediaRow()
	{
		if($this->mediaRow != null){
			return $this->mediaRow;
		}

		$mediaRow = new Model_Product_Media_Row();
		$this->setMediaRow($mediaRow);
		return $mediaRow;
	}


	public function gridAction(){
		try {
			$this->getMessage()->getSession()->start();

		
			$productId = (int) $this->getRequest()->getParam('product_id');
			$this->setProductId($productId);

			$query = "SELECT * FROM `media` WHERE `product_id` = $productId";
			$medias = $this->getMediaRow()->fetchAll($query);

			if (!$medias) {
				throw new Exception("Error Processing Request", 1);
				
			}

			$this->setMedias($medias);
       		 $this->getTemplate("product_media/grid.phtml");
			
		} catch (Exception $e) {
		$this->getMessage()->addMessage('Currently Products Media not avilable',Model_Core_Message :: FAILURE);
			
		}
	}
    
	public function addAction(){

		$productId = (int)$this->getRequest()->getParam('product_id');
		$this->setProductId($productId);

        $this->getTemplate("product_media/add.phtml");

	}
	
	public function insertAction()
	{
		try {
            $this->getMessage()->getSession()->start();

			$mediaPost = $this->getRequest()->getPost();
	   	    $productId= (int)$this->getRequest()->getParam('product_id');

	   	    date_default_timezone_set("Asia/kolkata");
			$this->getMediaRow()->setData($mediaPost);
			$this->getMediaRow()->product_id = $productId;
			$this->getMediaRow()->created_at = date('Y-m-d h:i:sA');
			$media = $this->getMediaRow()->save();
			$mediaId = $media->media_id;

	   	    if (!$mediaId) {
			throw new Exception("Error Processing Request", 1);
			
		    }

		    $conditions['media_id'] = $mediaId;
	        $conditions['product_id'] =$productId;

			$tmp_name = $_FILES['image']['tmp_name'];
			$stringArray = explode('.', $_FILES['image']['name']);
			$extension = $stringArray[1];
			$fileName = $mediaId.'.'.$extension;
			$destination = 'View/product_media/upload/'.$fileName;

			$result = move_uploaded_file($tmp_name, $destination);
			
			$this->getMediaRow()->image = $photoname;
			$this->getMediaRow()->media_id = $mediaId;
			$mediaData = $this->getMediaRow()->save();

			if (!$mediaData) {
			throw new Exception("Error Processing Request", 1);
			
		    }

			$this->getMessage()->addMessage('Product Media added sucessfully','success');
			
			} catch (Exception $e) {
				$this->getMessage()->addMessage('data not added','failure');
			}
	   	    
        
		// print_r($result);
		// die();
		$this->redirect('product_media','grid',['product_id' =>$productId]);

	}

	public function updateAction(){
		try {
			$this->getMessage()->getSession()->start();

			$productId = $this->getRequest()->getParam('product_id');
			// $this->setProductId($productId);

			$result = $this->getRequest()->getPost();


			$baseId = $result['base'];
			$thumbnailId = $result['thumbnail'];
			$smallId = $result['small'];
			$galleryId = $result['gallery'];

			$resetId['base'] = 0;
			$resetId['small'] = 0;
			$resetId['thumbnail'] = 0;
			$resetId['gallery'] = 0;

			$condition['product_id'] = $productId;
			$result = $this->getMediaModel()->update($resetId, $condition);

			if (!$result) {
			throw new Exception("Error Processing Request", 1);
			
		    }

		    $base['base'] = 1;
			$base['media_id'] = $baseId;
			$this->getMediaRow()->setData($base);
			$result = $this->getMediaRow()->save();
			if ($result) {
				$this->getMessage()->addMessages('Media updated successfully', Model_Core_Message::SUCCESS);
			
		    }

			$thumbnail['thumbnail'] = 1;
			$thumbnail['media_id'] = $thumbnailId;
			$this->getMediaRow()->setData($thumbnail);
			$result = $this->getMediaRow()->save();
			if ($result) {
				$this->getMessage()->addMessages('Media updated successfully', Model_Core_Message::SUCCESS);
			
		    }

			$small['small'] = 1;
			$small['media_id'] = $smallId;
			$this->getMediaRow()->setData($small);
			$result = $this->getMediaRow()->save();
			if ($result) {
				$this->getMessage()->addMessages('Media updated successfully', Model_Core_Message::SUCCESS);
			
		    }

		    $gallery['gallery'] = 1;
			foreach($galleryId as $key => $value)
			{
				$gallery['media_id'] = $value;
				$this->getMediaRow()->setData($gallery);
				$result = $this->getMediaRow()->save();
			}
			if ($result) {
				$this->getMessage()->addMessages('Media updated successfully', Model_Core_Message::SUCCESS);
			}

			$this->redirect('product_media','grid', ['product_id' => $productId]);
			
		} catch (Exception $e) {
			$this->getMessage()->addMessages($e->getMessage(), Model_Core_Message::FAILURE);
		}
		

		$this->redirect($this->getMediaUrl()->getUrl('product_media','grid',['product_id' =>$productId]));


	}

	public function deleteAction(){
		// $id = $_GET['media_id'];
		try {
			$this->getMessage()->getSession()->start();
			$productId = $this->getRequest()->getParam('product_id');
			$mediaId = $this->getRequest()->getParam('media_id');
			if (!$mediaId) {
				throw new Exception("Id not found", 1);
			
			}
			$condition['product_id'] = $productId;
			$condition['media_id'] = $mediaId;

			$result = $this->getMediaModel()->delete($condition);
			$this->getMessage()->addMessages('Data deleted', Model_Core_Message::SUCCESS);
		
			
		} catch (Exception $e) {
			$this->getMessage()->addMessage('data not deleted','failure');
		}
		
		$this->redirect($this->getMediaUrl()->getUrl('product_media','grid',['product_id' =>$productId]));

	}
}

?>