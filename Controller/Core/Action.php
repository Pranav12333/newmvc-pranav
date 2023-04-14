<?php

	class Controller_Core_Action
	{
		public $request = null;
		public $adapter = null;
		public $url = null;
		public $message = null;
		public $view = null;
		public $layout = null;

		public function setLayout(Block_Core_Layout $layout)
		{
			$this->layout = $layout;
			return $this;
		}

		public function getLayout()
		{
			if($this->layout)
			{
				$layout = new Block_Core_Layout();
				$this->setLayout($layout);
				return $layout;
			}
			return $this->layout;

		}

		public function setView(Model_Core_View $view)
		{
			$this->view = $view;
			return $this;
		}

		public function getView()
		{
			if($this->view)
			{
				return $this->view;
			}
			$view = new model_Core_View();
			$this->setView($view);
			return $view;
		}

		public function setUrlObj(Model_Core_Url $urlObj)
		{
			$this->urlObj = $urlObj;
			return $this;
		}

		public function getUrlObj()
		{
			if ($this->urlObj) {
				return $this->urlObj;
			}

			$urlObj = new Model_Core_Url();
			$this->setUrlObj($urlObj);
			return $urlObj;
		}

		public function setMessage(model_Core_Message $message)
		{
			$this->message = $message;
			return $this;
		}

		public function getMessage()
		{
			if ($this->message)
			{
				return $this->message;
			}
			$message = new model_Core_Message();
			$this->setRequest($message);
			return $message;
		}

		public function getUrl()
		{
			if ($this->url)
			{
				return $this->url;
			}
			$url=new Model_Core_Url();
			$this->setUrl($url);
			return $url;
		}

		public function setUrl(Model_Core_Url $url)
		{
			$this->url =$url;
			return $this;
		}

		public function getRequest()
		{
			if ($this->request)
			{
				return $this->request;
			}
			$request=new Model_Core_Request();
			$this->setRequest($request);
			return $request;
		}

		public function setRequest( $request)
		{
			$this->request = $request;
			return $this;
		}

		public function getAdapter()
		{
			if ($this->adapter)
			{
				return $this->adapter;
			}
			$adapter=new Model_Core_Adapter();
			$this->setAdapter($adapter);
			return $adapter;
		}

		public function setAdapter(Model_Core_Adapter $adapter)
		{
			$this->adapter =$adapter;
			return $this;
		}

		public function errorAction($action)
		{
			throw new Exception("method:{$action} does not exist", 1);
			
		}

		public function redirect($url)
		{
			if ($url == null)
			{
				header("location:http://localhost/project-anjali-vaghasiya-1/index.php?a=grid&c=product");
			}
				header("location:{$url}");
				exit();
		}
		
		public function gettemplate($templatePath)
		{
			require"View".DS.$templatePath;
		}

		
		public function render()
		{
			$this->getview()->render();
		}
	}
	?>