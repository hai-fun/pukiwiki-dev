<?php
// PukiWiki - Yet another WikiWikiWeb clone.
// color.inc.php
// Copyright
//   2003-2022 PukiWiki Development Team
// License: GPL v2 or (at your option) any later version
//
// Text color plugin

// Allow CSS instead of <font> tag
// NOTE: <font> tag become invalid from XHTML 1.1
define('PLUGIN_COLOR_ALLOW_CSS', TRUE); // TRUE, FALSE

// ----
define('PLUGIN_COLOR_USAGE', '&color(foreground[,background]){text};');
define('PLUGIN_COLOR_REGEX', '/^(#[0-9a-f]{3}|#[0-9a-f]{6}|[a-z-]{1,20})$/i');

function plugin_color_inline()
{
	$args = func_get_args();
	$text = strip_autolink(array_pop($args)); // Already htmlsc(text)

	list($color, $bgcolor) = array_pad($args, 2, '');
	if ($color != '' && $bgcolor != '' && $text == '') {
		// Maybe the old style: '&color(foreground,text);'
		$text    = htmlsc($bgcolor);
		$bgcolor = '';
	}
	if (($color == '' && $bgcolor == '') || $text == '' || func_num_args() > 3)
		return PLUGIN_COLOR_USAGE;

	// Invalid color
	foreach(array($color, $bgcolor) as $col){
		if ($col != '' && ! preg_match(PLUGIN_COLOR_REGEX, $col))
			return '&color():Invalid color: ' . htmlsc($col) . ';';
	}

	if (PLUGIN_COLOR_ALLOW_CSS) {
		$delimiter = '';
		if ($color != '' && $bgcolor != '') $delimiter = '; ';
		if ($color   != '') $color   = 'color:' . $color;
		if ($bgcolor != '') $bgcolor = 'background-color:' . $bgcolor;
		return '<span style="' . $color . $delimiter . $bgcolor . '">' .
			$text . '</span>';
	} else {
		if ($bgcolor != '') return '&color(): bgcolor (with CSS) not allowed;';
		return '<font color="' . $color . '">' . $text . '</font>';
	}
}
