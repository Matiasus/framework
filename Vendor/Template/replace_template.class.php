<?php

	namespace Vendor\Template;

	/***
	** Trieda Replace template
	*/
	class Replace_template {

    /** @var */
		protected $layout;

    /** @var */
		protected $content;

    /** @var */
		protected $variables = array();
		
    /** @var */
    protected $controller;

    /** @var */
		protected $javascript;
		
    /** @var */
    protected $errorMessage;
		
    /** @var */
    protected $objectController;

		/***
		 * Konstruktor vytvorenia spojenia s registrom
		 *
		 * @param Void
		 * @return Void
		 */
		public function __construct()
		{
		}

		/***
		 * Konstruktor vytvorenia spojenia s registrom
		 *
		 * @param Void
		 * @return Void
		 */
		public function setJavascript($script)
		{
      $this->javascript = $script;
		}

		/***
		 * Volanie
		 *
		 * @param String - key
		 * @return String | Array
		 */
		public function __get($key)
		{
			if (is_array($this->variables) && 
          array_key_exists($key, $this->variables))
			{
				return $this->variables[$key];
			}
		}

		/***
		 * Nastavenie
		 *
		 * @param String, String - key, Value
		 * @return Void
		 */
		public function __set($key, $value)
		{
			$this->variables[$key] = $value;
		}

		/***
		 * Predpriprava na spracovanie sablony
		 *
		 * @param Void
		 * @return Void
		 */
		protected function prepare()
		{
			// AK nastala chyba, napr. niekde pri mysql zavola sa error.tpl.php
			if ($this->registry->errors->log !== FALSE)
			{
				/* Absolutna cesta k sablone error.tpl.php */
				$this->layout = ROOT_DIR . 'Application' . 
								(( !empty($this->registry->route->getModule()) ) ? DS . 
								'Module' : '') . DS . 'error.tpl.php';
				/* Vpise vsetky chyby za sebou odelovacom do titulu */
				$this->title = $this->registry->errors->getWholeAsString();

			} else {
				/* Absolutna cesta k sablone layout.tpl.php */
				$this->layout = ROOT_DIR . 'Application' . 
								(( !empty($this->registry->route->getModule()) ) ? DS . 
								'Module/' . ucfirst($this->registry->route->getModule()) : '') . DS . 
								'Views' . DS . 'layout.tpl.php';
			}

			$this->objectController = $this->registry->controller->getController();

			/*** 
			** Spracovanie sablony - volanie metod z replace template Rtemplate
			***/
			$this->process(array('Layout',
													 'Title',
											 		 'Content',
													 'Forms',
													 'Editor',
													 'Flash',
                           'Javascript',
													 'Errors'));		

		}

		/***
		 * Spracovanie sablony
		 *
		 * @param Array - pole metod, ktore sa maju volat
		 * @return Void
		 */
		protected function process($methods = array())
		{
			if (isset($this->registry->errors->log))
			{
				$this->Layout();
				$this->Errors();
			}
			else
			{
				if (array_key_exists("variables", $this->objectController) === true)
				{
					/***
					** Nacitanie premennych do premennej $variables
					** cim je mozne volat premnenne z prislusneho kontrolera
					***/
					$this->variables = $this->objectController->variables;
				}

				/* Overenie pola */
				if ( is_array($methods) === TRUE )
				{
					/* Prechazanie jednotlivych metod zadanych v poli */
					foreach ( $methods as $method )
					{
						if ( method_exists( $this, $method) )
						{
							/* Volanie metod jednotlivych metod z template class */
							$this->$method();
						} 
						else 
						{
							echo $this->registry->errors->methods = 'Method <b>' . $method . "</b> does not exits!<br/>";
						}
					}
					/***
					** Vykreslenie obsahu, cize tlacenie premennej $this->content
					*/
					echo $this->render();
				}

			}
		}

		/***
		 * Spracovanie sablony
		 *
		 * @param Void
		 * @return Void
		 */
		protected function render( )
		{
			echo $this->content;
		}

	}


