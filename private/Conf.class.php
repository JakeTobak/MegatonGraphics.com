<?php
class Conf {
	private static $pagewhitelist = array(
			'home'	=> array(
					'file'	=> 'home.php'
					),
			'contact'  => array(
                                        'file'  => 'contact.php'
                                        ),
                        'signs'  => array(
                                        'file'  => 'signs.php'
                                        ),
                        'glassware'  => array(
                                        'file'  => 'glassware.php'
                                        ),
                        'decals'  => array(
                                        'file'  => 'decals.php'
                                        ),
                        'drinkware'  => array(
                                        'file'  => 'drinkware.php'
                                        )
		);

	private static $mysql = array('server' => 'localhost', 'user' => 'megatongraphics', 'password' => 'jake5253', 'database' => 'megatongraphics');

	public function __construct() {

	}

	public static function init() {

	}

	public static function getPage($pagename = NULL) {
		if($pagename == NULL && isset($_GET['page']))
			$pagename = $_GET['page'];

		if(array_key_exists($pagename, self::$pagewhitelist))
			return self::$pagewhitelist[$pagename];
		else
			return self::$pagewhitelist['home'];
	}

	public static function getDB() {
		return new mysqli(self::$mysql['server'], self::$mysql['user'], self::$mysql['password'], self::$mysql['database']);
	}
}
?>
