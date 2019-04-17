<?php

	namespace Vendor\Javascript;

	class Parameters {
  
    private $__parameters;

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
		 * @return String
		 */
		public function setParameters($parameters = array()) 
    {
      if (!empty($parameters)) {
        // zapis javascriptu
        $this->__parameters = $parameters;
      }
		}

		/***
		 * Get parameters
		 *
		 * @param Void
		 * @return Array
		 */
		public function getParameters($name = false)
    {
      if ($name) {
        return $this->__parameters[$name];
      } else {
        return $this->__parameters;
      }
		}

	}

