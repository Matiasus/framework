<?php

	namespace Vendor\Form\Input;

	class Textarea extends \Vendor\Form\Input {

		/*
		** Konstruktor triedy, nastavi premenne
		** a zavola funkciu fillContent() na naplnenie obsahu
		** @parameter String $name => meno pola text (bez diakritiky a medzery)
		** @parameter String $label => pomenovanie pola vlavo od textoveho pola (aj s diakritikou a medzerami)
		** @parameter Int $rows => pocet riadkov
		** @parameter Int $cols => pocet stlpcov
		** @parameter String $value => preddefinovana hodnota (aj s diakritikou a medzerami)
		** @return void
		*/
		public function __construct( $name = false, $label = false, $rows = false, $cols = false, $id = false, $value = false ){

			$this->id = $id;
			$this->rows  = $rows;
			$this->cols  = $cols;
			$this->name  = $name;
			$this->label = $label;
			$this->value = $value;

			$this->fillContent();

		}

		/*
		** Plni obsahom premennu $this->content
		** @parameter void
		** @return void
		*/
		public function fillContent(){

			$this->content  = ( $this->getDisplay() === false ) ? "\n\t   <tr><td class='textarea'>" : "\n\t   <label for='id-" . strtolower( $this->id ) . "'>" ;
			$this->content .= $this->label . (($this->required != '') ? '*' : '');
			$this->content .= ( ( $this->getDisplay() === false ) ? "</td><td>" : "</label><br/>" );
			$this->content .= "\n\t    <textarea";
			$this->content .= " name='" . $this->name . "'";
			$this->content .= " rows='". $this->rows . "'";
			$this->content .= " cols='". $this->cols . "'";
			$this->content .= " id='id-" . strtolower( $this->id ) . "'" . $this->required . "/>";
			$this->content .= "" . $this->value . "";
			$this->content .= "</textarea>";
			$this->content .= ( ( $this->getDisplay() === false ) ? "</td></tr>" : "<br/>" );

		}

	}

