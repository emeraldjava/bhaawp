<?php

/**
 * Class WP_FormsDemo_View_TableForm
 *
 * Wrap all children of the form in a table
 */
class WP_FormsDemo_View_TableForm extends WP_Form_View_Form {
	protected function render_children( WP_Form $form ) {
		return '<table>'.parent::render_children($form).'</table>';
	}
}
