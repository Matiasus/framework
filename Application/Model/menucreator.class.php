<?php

	namespace Application\Model;

	class Menucreator {

    const DELIMETER = "-";

		/** @var Objekt \Vendor\Menu\Menu */
		private $menu;

		/** Objekt uzivatela */
		private $user;

		/** @var Objekt routovania */
		private $route;

		/** @var Objekt obsahu */
		private $content;

		/** @var Objekt databazy */
		private $database;

		/** Objekt registru */
		private $registry;

		/** @var Array uloziste premennych */
		public $variables = array();

		/**
		 * Konstruktor 
		 *
		 * @param Objekt \Vendor\Registry\Registry
		 * @return void
		 */
		public function __construct() 
		{
			// Register
			$this->registry = $registry;
      // prihlaseny uzvatel
      $this->user = $this->registry->user->getLoggedUser();
			// pripajanie na route
			$this->route = $this->registry->route; 
			// Spracovanie databazy
      $this->database = $this->registry->database;
      // ak neexistuje objekt databazy
      if (empty($this->database)) {
			  $this->database = new \Vendor\Database\Database($this->registry->mysql, $this->registry->session);
      }
			// Objekt uzivatela
			$this->content = new \Application\Model\Content($this->registry);	
			// Tituly menu
			$this->variables = array('Items' => array(
																 // Polozky na pracu s kontom
																 'Konto' => array(
																 	 'Profil' => "/".$this->user['Privileges']."/home/default",
																	 'Odhlás' => "/".$this->user['Privileges']."/home/default/?do=odhlas"
																	),
																 // Polozky - projekty
																 'Projekty' => array(
																 	 'Google API' => "/".$this->user['Privileges']."/googleapi/default",
                                   'Google API play'=>"/".$this->user['Privileges']."/googleapi/play"
																	),
																 // Polozky na pracu s clankami
																 'Poznámky' => array(
																	 'Všetky' => "/".$this->user['Privileges']."/articles/default"
																  )
															  )
			 );
		}

		/***
		 * Vytvorenie bocneho menu
		 *
		 * @param Array - polozky, ktore sa maju pripojit
		 * @return void
		 */
		public function addSupplement($add = array())
		{
    	$this->variables = array_merge_recursive($this->variables, $add);
		}

		/***
		 * Vytvorenie bocneho menu
		 *
		 * @param Array - tituly
		 * @param Array - linky k titulom
		 * @param Boolean - uvadzat linky vsade, nie len v podurovnach
		 * @return String - html kod menu
		 */
		public function create($data, $with = false)
		{
			// Objekt menu
      if (empty($this->menu)) {
        $this->menu = new \Vendor\Menu\Menu($this->route, $this->database);
      }
			// Vytvorenie menu
			$html = $this->menu->build($data, $with);

			return $html;
		}

		/***
		 * Vytvorenie navigacie
		 *
		 * @param String - privilegia [autorizacia]
     * @param String - delimeter
		 * @return String - html kod menu
		 */
		public function queryToMenu($array, $privileges, $delimeter = false)
		{
      // Objekt menu
      if (empty($this->menu)) {
        $this->menu = new \Vendor\Menu\Menu($this->route, $this->database);
      }
      // s nastavenym delimetrom
      if (!empty($delimeter)) {
        return $this->menu->preprocessing()->buildArray($array, $privileges, $delimeter);
      }
      // bez delimetru
      return $this->menu->preprocessing()->buildArray($array, $privileges);
		}

		/***
		 * Vytvorenie menu
		 *
		 * @param Void
		 * @return String - html kod menu
		 */
		public function build($privileges)
		{
      $condition = '';
      // overenie roly
      if (strcmp($privileges,
                 \Application\Config\Settings::$Detail->Roles->Admin) !== 0) {
        // podmienka zobrazenia poloziek v menu ak sa nejedna o admina
        $condition = "WHERE ".\Application\Config\Settings::$Detail->Mysql->Table_Articles.".Type = 'released' ";
      }
      // najdenie unikatnych hodnot kategorii v databaze
			$query = $this->database
                    ->query("SELECT ".
                            \Application\Config\Settings::$Detail->Mysql->Table_Articles.".Category, CONCAT(".
                            \Application\Config\Settings::$Detail->Mysql->Table_Articles.".Title, '".self::DELIMETER."', ".
                            \Application\Config\Settings::$Detail->Mysql->Table_Articles.".Id) FROM ".
                            \Application\Config\Settings::$Detail->Mysql->Table_Articles." INNER JOIN ". 
                            \Application\Config\Settings::$Detail->Mysql->Table_Users." ON ".
                            \Application\Config\Settings::$Detail->Mysql->Table_Articles.".Usersid = Users.Id ".
                            $condition.
                            "ORDER BY ".\Application\Config\Settings::$Detail->Mysql->Table_Articles.".Category, ".
                            \Application\Config\Settings::$Detail->Mysql->Table_Articles.".Title ASC;");

      // pripojenie clankov do menu
      $content = $this->queryToMenu($query, $privileges);

      if ($content !== False) {
			  // Doplnenie poloziek do menu
			  $this->addSupplement(array("Items"=>array("Poznámky"=>$content)));
      }
      // html kod menu
			return $this->create($this->variables['Items']);
    }
	}


