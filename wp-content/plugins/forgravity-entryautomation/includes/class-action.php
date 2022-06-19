<?php
/**
 * Base Action Class for Entry Automation
 *
 * @package ForGravity\Entry_Automation
 */

namespace ForGravity\Entry_Automation;

use ForGravity\Entry_Automation\Task;
use GFAPI;

/**
 * Class Action
 *
 * @package ForGravity\Entry_Automation
 */
class Action {

	/**
	 * Contains an instance of this class, if available.
	 *
	 * @since  1.2
	 * @access protected
	 * @var    Action $_instance If available, contains an instance of this class.
	 */
	protected static $_instance = null;

	/**
	 * Available Entry Automation actions.
	 *
	 * @since  1.2
	 * @access private
	 * @var    array $_registered_actions Available Entry Automation actions.
	 */
	private static $_registered_actions = array();

	/**
	 * Defines the action name.
	 *
	 * @since  1.2
	 * @access protected
	 * @var    string $name Action name.
	 */
	protected $name;

	/**
	 * The task object for this action.
	 *
	 * @since 3.0
	 *
	 * @var Task $task The task object.
	 */
	public $task = null;

	/**
	 * The form object for this action.
	 *
	 * @since 3.0
	 *
	 * @var array $form The form object.
	 */
	public $form = null;

	/**
	 * The Entries instance.
	 *
	 * @since 5.0
	 *
	 * @var Entries
	 */
	public $entries;

	/**
	 * Get instance of this class.
	 *
	 * @since  1.2
	 * @access public
	 * @static
	 *
	 * @return Action
	 */
	public static function get_instance() {

		if ( null === static::$_instance ) {
			static::$_instance = new static();

			static::$_instance->entries = Entries::get_instance();
		}

		return static::$_instance;

	}

	/**
	 * Initialize action.
	 *
	 * @since  1.4
	 * @access public
	 */
	public function __construct() {
	}

	/**
	 * Action name.
	 *
	 * @since  1.2
	 * @access public
	 *
	 * @return string
	 */
	public function get_name() {

		return $this->name;

	}




	// # ACTION REGISTRATION -------------------------------------------------------------------------------------------

	/**
	 * Registers an action so that it gets initialized appropriately.
	 *
	 * @since  1.2
	 * @access public
	 * @static
	 *
	 * @param string $class The class name.
	 */
	public static function register( $class = '' ) {

		if ( class_exists( $class ) ) {
			self::$_registered_actions[] = $class;
		}

	}

	/**
	 * Gets all registered actions.
	 *
	 * @since  1.2
	 * @access public
	 * @static
	 *
	 * @uses   Action::$_registered_actions
	 *
	 * @return Action[]
	 */
	public static function get_registered_actions() {

		// Initialize actions array.
		$actions = array();

		// Loop through registered actions.
		foreach ( self::$_registered_actions as $action ) {

			// Get action.
			$action = call_user_func( array( $action, 'get_instance' ) );

			// Add to array.
			$actions[ $action->get_name() ] = $action;

		}

		return $actions;

	}

	/**
	 * Get action by name.
	 *
	 * @since  1.2
	 * @since  3.0  Passing the task object when getting an action.
	 *
	 * @param string $name Action name.
	 * @param Task   $task The task object.
	 *
	 * @return static|bool
	 */
	public static function get_action_by_name( $name = '', $task = null ) {

		// If name is blank, return.
		if ( rgblank( $name ) ) {
			return false;
		}

		// Get registered actions.
		$actions = self::get_registered_actions();

		// If action is registered, return.
		if ( isset( $actions[ $name ] ) ) {

			// Set up the task and form property.
			if ( $task instanceof Task ) {
				$actions[ $name ]->task = $task;
				$actions[ $name ]->form = GFAPI::get_form( $task->form_id );
			}

			return $actions[ $name ];
		}

		return false;

	}




	// # ACTION SETTINGS -----------------------------------------------------------------------------------------------

	/**
	 * Retrieves settings fields for configuring this Entry Automation action.
	 *
	 * @since  1.2.4
	 * @access public
	 *
	 * @return array
	 */
	public function get_settings_fields() {

		/**
		 * Modify the Entry Automation action settings fields.
		 *
		 * @since 1.2.4
		 *
		 * @param array $settings Settings fields.
		 */
		return apply_filters( 'fg_entryautomation_' . $this->name . '_settings_fields', $this->settings_fields() );

	}

	/**
	 * Settings fields for configuring this Entry Automation action.
	 *
	 * @since  1.2
	 * @access public
	 *
	 * @return array
	 */
	public function settings_fields() {

		return array();

	}

	/**
	 * Icon class for Entry Automation settings button.
	 *
	 * @since  1.2
	 * @access public
	 *
	 * @return string
	 */
	public function get_icon() {

		$icon_path = sprintf( '%s/images/%s/action-icon.svg', fg_entryautomation()->get_base_path(), $this->name );

		return file_exists( $icon_path ) ? file_get_contents( $icon_path ) : 'fa-cogs';

	}

	/**
	 * Action label, used in Entry Automation settings.
	 *
	 * @since  1.2
	 * @access public
	 *
	 * @return string
	 */
	public function get_label() {

		return '';

	}

	/**
	 * Action short label, used in Entry Automation Tasks table.
	 *
	 * @since  1.2
	 * @access public
	 *
	 * @return string
	 */
	public function get_short_label() {

		return '';

	}




	// # ACTION SETTINGS -----------------------------------------------------------------------------------------------

	/**
	 * Add links to feed list actions.
	 *
	 * @since  1.2
	 * @access public
	 *
	 * @param array  $links  Action links to be filtered.
	 * @param array  $task   Entry Automation Task meta.
	 * @param string $column The column ID.
	 *
	 * @return array
	 */
	public function feed_list_actions( $links, $task, $column ) {

		return $links;

	}




	// # RUNNING ACTION ------------------------------------------------------------------------------------------------

	/**
	 * Process task.
	 *
	 * @since  1.2
	 * @since  3.0 Deprecated the $task and $form parameters.
	 *
	 * @return bool
	 */
	public function run() {

		return true;

	}




	// # ACTION DELETION -----------------------------------------------------------------------------------------------

	/**
	 * Delete task.
	 *
	 * @since  1.2.5
	 * @access public
	 *
	 * @param int $task_id Task ID.
	 */
	public function delete_task( $task_id ) {
	}

}
