<?php
/**
 * Dew Theme Suite
 *
 */

if( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Dew_Admin_Page extends Dew_Theme_Base {

		/**
     * The slug name for the parent menu.
     * @var string
     */
    public $parent = null;

	/**
     * The capability required for this menu to be displayed to the user.
     * @var string
     */
    public $capability = 'manage_options';

	/**
     * The icon for this menu.
     * @var string
     */
	public $icon = 'dashicons-art';
	/**
     * The position in the menu order this menu should appear.
     * @var string
     */
    public $position;

	/**
	 * [__construct description]
	 * @method __construct
	 */
	public function __construct( $page, $key = null) {

		foreach ($page as $key => $value) {
			$this->$key = $value;
		}


		$this->add_action( 'admin_menu', 'register_page', $this->position );

		if( !isset( $_GET['page'] ) || empty( $_GET['page'] ) || ! $this->id === $_GET['page'] ) {
			return;
		}

		$this->add_action( 'admin_init', 'save' );
	}

	/**
	 * Display Dashboard View
	 */
	public function display() {
		if(isset( $this->view ) and  $this->view )
			include_once( $this->view );
		else echo 'no view';
	}

	/**
	 * Register Menu Page
	 */
	public function register_page() {

		if( ! $this->parent ) {
			add_menu_page(
				$this->page_title,
				$this->menu_title,
				$this->capability,
				$this->id,
				array( $this, 'display' ),
				$this->icon,
				$this->position
			);
		}
		else {
			add_submenu_page(
				$this->parent,
				$this->page_title,
				$this->menu_title,
				$this->capability,
				$this->id,
				array( $this, 'display' )
			);
		}
	}

	/**
	 * **empty**
	 */
	public function save() {

	}

}
