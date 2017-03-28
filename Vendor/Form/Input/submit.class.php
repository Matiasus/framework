<?php

	namespace Vendor\Form\Input;

	class Submit extends \Vendor\Form\Input {

		/*
		** Konstruktor triedy, nastavi premenne
		** a zavola funkciu fillContent() na naplnenie obsahu
		** @parameter String $name => meno pola text (bez diakritiky a medzery)
		** @parameter String $value => preddefinovana hodnota (aj s diakritikou a medzerami)
		** @return void
		*/
		public function __construct( $name = false,  $value = false )
		{
			$this->name  = $name;
			$this->value = $value;

			$this->fillContent();
		}

		/*
		** Plni obsahom premennu $this->content
		** @parameter void
		** @return void
		*/
		public function fillContent()
		{
			$this->content  = ( $this->getDisplay() === false ) ? "\n\t   <tr><td colspan='2' class='td-submit'>" : "";
			$this->content .= "\n\t    <input type='submit'";
			$this->content .= " name='" . strtolower( $this->name ) . "'";
			$this->content .= " value='" . $this->value . "'";
			$this->content .= " class='" . strtolower( $this->name ) . "' />";
			$this->content .= ( ( $this->getDisplay() === false ) ? "</td></tr>" : "" );
		}

	}

