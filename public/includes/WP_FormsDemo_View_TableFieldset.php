<?php

/**
 * Class WP_FormsDemo_View_TableFieldset
 *
 * Use a tbody instead of a fieldset for rendering a fieldset element
 */
class WP_FormsDemo_View_TableFieldset extends WP_Form_View_Fieldset {


	protected function fieldset( WP_Form_Element_Fieldset $element ) {
		$children = $this->render_children($element);
		$legend = $this->get_legend($element);
		$output = sprintf(
			'<tbody %s>%s%s</tbody>',
			WP_Form_View::prepare_attributes($element->get_all_attributes()),
			$legend,
			$children
		);
		return $output;
	}

	protected function get_legend( WP_Form_Element_Fieldset $element ) {
		$legend = '';
		$label = $element->get_label();
		if ( !empty($label) ) {
			$legend = sprintf('<tr><th colspan="2">%s</th></tr>', $label);
		}
		return $legend;
	}
}
