<?php
/**
 * Components.
 * @author Dave Ligthart <info@daveligthart.com>
 * @version 0.1
 * @package ALP
 */

/**
 * Create HTML-code for a dropdown
 * containing a number of options.
 *
 * @param $name   string  The name of the select field.
 * @param $values hash    The values for the options by their names
 *                        eg. $values["value-1"] = "option label 1";
 * @param $selected  string The value of the selected option.
 * $attr Optional attributes (eg. onChange stuff)
 *
 * @return string The HTML code for a option construction.
 */
function alp_html_dropdown($name, $values, $selected = "", $attr = ""){
	foreach($values as $key => $value) {
        $options .= "\t<option ".(($key == $selected) ? "selected=\"selected\"" : "")." value=\"".$key."\">".$value."&nbsp;&nbsp;</option>\n";
    }
	return "<select name=\"".$name."\"  id=\"".$name."\" $attr>\n".$options."</select>\n";
}
?>