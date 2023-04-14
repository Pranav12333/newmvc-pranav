<?php

class Block_Core_Layout extends Block_Core_Template
{
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('core/layout/3columns.phtml');
		$this->prepareChildren();
	}

	public function prepareChildren()
	{
		$header = new Block_Html_Header();
		$message = new Block_Html_Message();
		$content = new Block_Html_Content();
		$left = new Block_Html_Left();
		$right = new Block_Html_Right();
		$footer = new Block_Html_Footer();
		
		$this->addChild('header',$header);
		$this->addChild('message',$message);
		$this->addChild('message',$content);
		$this->addChild('left',$left);
		$this->addChild('right',$right);
		$this->addChild('footer',$footer);
	}

	public function createBlock($blocknName)
	{
		$className = 'Block_Html_'.$blocknName;
		return new $className;
	}
}


?>