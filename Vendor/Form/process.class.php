<?php

	namespace Vendor\Form;

	class Process extends \Vendor\Form\Form {

		/**
		** @Var parameter
		*/
		private $parameter;

		/**
		** Objekt registru
		*/
		private $registry;

		/**
		** Konstruktor triedy
		*/
		public function __construct( \Vendor\Registry\Registry $registry )
		{
			$this->registry = $registry;
		}

		/**
		** Vlozenie dat do databazy
		**
		** @parameter void
		** @return void
		*/
		private function vloz(){

			$data = array();

			if (isset($_POST))
			{
				foreach ($_POST as $key => $value)
				{
					if (strcmp($key, $_SESSION['submit']) != 0)
					{
						$data[$key] = $value;
					}
				}

				/**
				** Doplnenie autora do pola dat
				*/
				$data['Author'] = $this->registry->user->getLoggedUser();
				$data['Author'] = $data['Author']->Nick;

				/**
				** Spracovanie prostrednictvom triedy
				** database, ktora obsahuje metodu vloz
				*/
				$dtbase = new \Vendor\Database( $this->registry );
				$result = $dtbase->insert($data, "Contents");

				if ($result === TRUE)
				{
					$this->successfulSend();
				}
			}

		}

		/**
		** Vlozenie dat do databazy
		** @parameter void
		** @return void
		*/
		private function vymaz(){

			/**
			** Spracovanie prostrednictvom triedy
			** database, ktora obsahuje metodu vymaz
			*/
			$dtbase = new \Vendor\Database( $this->registry );
			$result = $dtbase->delete( $this->registry->route->parameter );

			if ($result === TRUE)
			{
				$this->successfulSend();
			}

		}

		/**
		** Update dat do databazy
		** @parameter void
		** @return void
		*/
		private function edituj(){

			/**
			** Premenna, ktora ma obsahovat data
			** ukladane do tabulky
			*/
			$data = array();

			if (isset($_POST))
			{
				foreach ($_POST as $key => $value)
				{
					if (strcmp($key, $_SESSION['submit']) != 0)
					{
						$data[$key] =  $value;
					}
				}

				/**
				** Doplnenie casu do pola dat
				*/
				$data['Published'] = date("Y-m-d H:i:s"); 

				/**
				** Spracovanie prostrednictvom triedy
				** database, ktora obsahuje metodu edituj
				*/
				$dtbase = new \Vendor\Database( $this->registry );
				$result = $dtbase->update( $data, $this->registry->route->parameter );

				if ($result === TRUE)
				{
					$this->successfulSend();
				}

			}

		}

		/**
		** Vypisanie o uspesnosti
		** @parameter void
		** @return void
		*/
		private function successfulSend(){

			$this->registry->route->redirect( $this->registry->user->Privileges . "/home/default/" );

		}

	}

