<?php

	namespace Vendor\Form\Input;

	class Checkbox extends \Vendor\Form\Input {

		/*
		** Konstruktor triedy, nastavi premenne
		** a zavola funkciu fillContent() na naplnenie obsahu
		** @parameter String $name => meno pola text (bez diakritiky a medzery)
		** @parameter String $label => pomenovanie pola vlavo od textoveho pola (aj s diakritikou a medzerami)
		** @parameter String $value => preddefinovana hodnota (aj s diakritikou a medzerami)
		** @return void
		*/
		public function __construct( $name = false, $value = false, $label = false, $check = false ){

			$this->name  = $name;
			$this->value = $value;
			$this->label = $label;
			$this->check = $check;

			$this->fillContent();

		}

		/*
		** Plni obsahom premennu $this->content
		** @parameter void
		** @return void
		*/
		public function fillContent(){

			$this->content  = ( $this->getDisplay() === false ) ? "\n\t   <tr><td>" : "\n\t   <label for='id-" . strtolower( $this->name ) . "'>" ;
			$this->content .= ( ( $this->getDisplay() === false ) ? "</td><td>" : "</label><br/>" );
			$this->content .= "\n\t    <input type='checkbox' " . (($this->check === true) ? "checked" : "");
			$this->content .= " name='" . $this->name . "' id='id-" . strtolower( $this->name ) . "'";
			$this->content .= " value='" . $this->value  . "' >" . $this->label;
			$this->content .= ( ( $this->getDisplay() === false ) ? "</td></tr>" : "<br/>" );

		}

	}

