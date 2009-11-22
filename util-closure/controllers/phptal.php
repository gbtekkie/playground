<?php defined( 'SYSPATH' ) OR die( 'No direct access allowed.' );
abstract class Phptal_Controller extends Controller {

	// Template view name
	public $layout = 'base.html';
	
	// Default to do auto-rendering
	public $auto_render = TRUE;
	
	/**
	 * Template loading and setup routine.
	 */
	public function __construct() {
		parent::__construct();
		
		// Load the template
		$this->layout = new PHPTAL(APPPATH . 'views/layouts/'.$this->layout);
		$this->layout->setTemplateRepository( APPPATH . 'views/' );
		$this->layout->sBasePath = APPPATH . 'views/';
		
		if ($this->auto_render == TRUE) {
			// Render the template immediately after the controller method
			Event::add('system.post_controller', array($this, '_render'));
		}
	} // END func __construct()
	
	/**
	 * Render the loaded template.
	 */
	public function _render() {
		if ($this->auto_render == TRUE) {
			// Render the template when the class is destroyed
			echo $this->layout->execute();
		}
	} // END func _render()

} // END class Phptal_Controller