<?php
/**
 * Plain text table cell.
 * 
 * Renders a totally unformatted text string from the column value.
 * @package dotproject
 * @subpackage system
 * @version not.even.alpha
 *
 */
class DP_View_Cell extends DP_View {
	private $value_key;
	
	public function __construct($value_key, $attribs = array()) {
		// TODO - proper generation of parent id
		parent::__construct('dp-cell-'.$value_key);
		$this->value_key = $value_key;
		$this->setHTMLAttribs($attribs);
	}
	
	public function render($rowhash) {
		$output = $rowhash[$this->value_key];
		return $output;
	}	
}
?>