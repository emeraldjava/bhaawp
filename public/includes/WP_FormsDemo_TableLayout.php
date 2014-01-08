<?php

/**
 * Class WP_FormsDemo_TableLayout
 *
 * All the filters necessary to transform the default views of a form into
 * a table-based layout, with labels in one column and fields in the other
 */
class WP_FormsDemo_TableLayout {
	private $filters = array();
	private $actions = array();

	/**
	 * For convenience of hooking and unhooking, we list
	 * all of the filters and actions here
	 */
	public function __construct() {
		$this->filters = array(
			array('wp_form_view_classes', array( $this, 'filter_view_classes' ), 10, 2),
			array('wp_form_default_decorators', array( $this, 'filter_default_decorators' ), 10, 2),
		);
		$this->actions = array(
			array('wp_form_radio_group_member', array( $this, 'set_radio_group_member_view' ), 10, 1),
			array('wp_form_checkbox_group_member', array( $this, 'set_checkbox_group_member_view' ), 10, 1),
		);
	}

	/**
	 * Register all the filters and actions for this layout
	 * @return void
	 */
	public function add_hooks() {
		foreach ( $this->filters as $args ) {
			call_user_func_array('add_filter', $args);
		}
		foreach ( $this->actions as $args ) {
			call_user_func_array('add_action', $args);
		}
	}

	/**
	 * Unregister all the filters and actions for this layout
	 * @return void
	 */
	public function remove_hooks() {
		foreach ( $this->filters as $args ) {
			call_user_func_array('remove_filter', $args);
		}
		foreach ( $this->actions as $args ) {
			call_user_func_array('remove_action', $args);
		}
	}

	/**
	 * Filter the base view class for fieldsets and forms
	 *
	 * Note: this won't really work with nested fieldsets
	 *
	 * @param array $classes
	 * @param WP_Form_Component $element
	 *
	 * @return array
	 */
	public function filter_view_classes( array $classes, WP_Form_Component $element ) {
		if ( $element instanceof WP_Form_Element_Fieldset ) {
			array_unshift($classes, 'WP_FormsDemo_View_TableFieldset');
		} elseif ( $element instanceof WP_Form ) {
			array_unshift($classes, 'WP_FormsDemo_View_TableForm');
		}
		return $classes;
	}

	/**
	 * Change field decorators to wrap them in table rows and cells
	 *
	 * @param array $decorators
	 * @param WP_Form_Element $element
	 *
	 * @return array
	 */
	public function filter_default_decorators( array $decorators, WP_Form_Element $element ) {
		$return_decorators = array();
		$needs_table_cells = !isset($decorators['WP_Form_Decorator_Label']); // ordinarly the label decorator will handle this
		foreach ( $decorators as $decorator_class => $decorator_args ) {
			// Change the Label decorator to our custom TableLabel
			if ( $decorator_class == 'WP_Form_Decorator_Label' ) {
				$decorator_class = 'WP_FormsDemo_Decorator_TableLabel';
				if ( isset($decorator_args['position']) && $decorator_args['position'] == WP_Form_Decorator_Label::POSITION_SURROUND ) {
					// we still need to wrap table cells around it, but only after other decorators
					$needs_table_cells = TRUE;
				}
			}
			// Wrap in a table row instead of the default div
			if ( $decorator_class == 'WP_Form_Decorator_HtmlTag' ) {
				$decorator_args['tag'] = 'tr';
				if ( $needs_table_cells ) {
					// single checkboxes/radios will also need to be wrapped in a td
					$return_decorators['WP_FormsDemo_Decorator_TableCells'] = array();;
				}
			}
			$return_decorators[$decorator_class] = $decorator_args;
		}
		return $return_decorators;
	}

	/**
	 * If a checkbox/radio is a member of a group, it should have its view and
	 * decorators set explicitly to avoid the filters above
	 *
	 * @param WP_Form_Element $element
	 *
	 * @return void
	 */
	public function set_checkbox_group_member_view( WP_Form_Element $element ) {
		$element->set_view( new WP_Form_View_Input() );
		$decorators = array(
			'WP_Form_Decorator_Label' => array('position' => WP_Form_Decorator::POSITION_SURROUND),
			'WP_Form_Decorator_Description' => array(),
			'WP_Form_Decorator_HtmlTag' => array(),
		);
		foreach ( $decorators as $class => $args ) {
			$element->add_decorator($class, $args);
		}
	}

	/**
	 * @see set_checkbox_group_member_view()
	 * @param WP_Form_Element $element
	 *
	 * @return void
	 */
	public function set_radio_group_member_view( WP_Form_Element $element ) {
		$this->set_checkbox_group_member_view($element);
	}
}
