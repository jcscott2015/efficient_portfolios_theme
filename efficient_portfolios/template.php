<?php
// $Id$

/**
 * @file
 * Contains theme override functions and preprocess functions for the theme.
 *
 * ABOUT THE TEMPLATE.PHP FILE
 *
 *   The template.php file is one of the most useful files when creating or
 *   modifying Drupal themes. You can add new regions for block content, modify
 *   or override Drupal's theme functions, intercept or make additional
 *   variables available to your theme, and create custom PHP logic. For more
 *   information, please visit the Theme Developer's Guide on Drupal.org:
 *   http://drupal.org/theme-guide
 *
 * OVERRIDING THEME FUNCTIONS
 *
 *   The Drupal theme system uses special theme functions to generate HTML
 *   output automatically. Often we wish to customize this HTML output. To do
 *   this, we have to override the theme function. You have to first find the
 *   theme function that generates the output, and then "catch" it and modify it
 *   here. The easiest way to do it is to copy the original function in its
 *   entirety and paste it here, changing the prefix from theme_ to efficient_portfolios_.
 *   For example:
 *
 *     original: theme_breadcrumb()
 *     theme override: efficient_portfolios_breadcrumb()
 *
 *   where efficient_portfolios is the name of your sub-theme. For example, the
 *   zen_classic theme would define a zen_classic_breadcrumb() function.
 *
 *   If you would like to override any of the theme functions used in Zen core,
 *   you should first look at how Zen core implements those functions:
 *     theme_breadcrumbs()      in zen/template.php
 *     theme_menu_item_link()   in zen/template.php
 *     theme_menu_local_tasks() in zen/template.php
 *
 *   For more information, please visit the Theme Developer's Guide on
 *   Drupal.org: http://drupal.org/node/173880
 *
 * CREATE OR MODIFY VARIABLES FOR YOUR THEME
 *
 *   Each tpl.php template file has several variables which hold various pieces
 *   of content. You can modify those variables (or add new ones) before they
 *   are used in the template files by using preprocess functions.
 *
 *   This makes THEME_preprocess_HOOK() functions the most powerful functions
 *   available to themers.
 *
 *   It works by having one preprocess function for each template file or its
 *   derivatives (called template suggestions). For example:
 *     THEME_preprocess_page    alters the variables for page.tpl.php
 *     THEME_preprocess_node    alters the variables for node.tpl.php or
 *                              for node-forum.tpl.php
 *     THEME_preprocess_comment alters the variables for comment.tpl.php
 *     THEME_preprocess_block   alters the variables for block.tpl.php
 *
 *   For more information on preprocess functions and template suggestions,
 *   please visit the Theme Developer's Guide on Drupal.org:
 *   http://drupal.org/node/223440
 *   and http://drupal.org/node/190815#template-suggestions
 */


/*
 * Add any conditional stylesheets you will need for this sub-theme.
 *
 * To add stylesheets that ALWAYS need to be included, you should add them to
 * your .info file instead. Only use this section if you are including
 * stylesheets based on certain conditions.
 */
/* -- Delete this line if you want to use and modify this code
// Example: optionally add a fixed width CSS file.
if (theme_get_setting('efficient_portfolios_fixed')) {
  drupal_add_css(path_to_theme() . '/layout-fixed.css', 'theme', 'all');
}
// */


/**
 * Implementation of HOOK_theme().
 */
function efficient_portfolios_theme(&$existing, $type, $theme, $path) {
  $hooks = zen_theme($existing, $type, $theme, $path);
  // Add your theme hooks like this:
  /*
  $hooks['hook_name_here'] = array( // Details go here );
  */
  // @TODO: Needs detailed comments. Patches welcome!
  return $hooks;
}

/**
 * Override or insert variables into all templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered (name of the .tpl.php file.)
 */
/* -- Delete this line if you want to use this function
function efficient_portfolios_preprocess(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the page templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */
function efficient_portfolios_preprocess_page(&$vars, $hook) {
	global $theme_info;
	 
	// Check if there are stylesheets to be placed at the
	// top of the stack.
	if (isset($theme_info->info['top-stylesheets'])) {
	  $top_css = array();
	  // Format the stylesheets to work with
	  // drupal_get_css().
	  foreach ($theme_info->info['top-stylesheets'] as $media => $styles) {
		foreach ($styles as $style){
		  $top_css[$media][_efficient_portfolios_path() . '/' . $style] = TRUE;
		}
		// Add the stylesheets to the top of the proper
		// media type.
		array_unshift($vars['css'][$media], $top_css[$media]);
	  }
	  // Replace $styles with the new string.
	  $vars['styles'] = drupal_get_css($vars['css']);
	}

	// Target Blank for links to external urls.
	$js = <<<JS
$(function() {
	$('a[href^="http://"]').click(function(e){
		e.preventDefault();
		window.open($(this).attr("href"));
	});
});
JS;
	drupal_add_js($js, "inline", "header");
}
//

/**
 * Override or insert variables into the node templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */
function efficient_portfolios_preprocess_node(&$vars, $hook) {
  // In body, find last paragraph (<p>) tag, without attributes, and give it a "last" class.
  $pos = strripos($vars['body'], '<p>');
  if($pos !== false){
	  $new_body = substr_replace($vars['body'], ' class="last"', $pos+2, 0);
	  $vars['content'] = $new_body;
  }
  // In teaser, find last paragraph (<p>) tag, without attributes, and give it a "last" class.
  $pos = strripos($vars['teaser'], '<p>');
  if($pos !== false){
	  $new_teaser = substr_replace($vars['teaser'], ' class="last"', $pos+2, 0);
	  $vars['content'] = $new_teaser;
  }
  
  if($vars['type'] === "commentary"){
  	unset($vars['terms']);
  	
  	// Format link to iframe.
	if($vars['field_sliderocket'][0]['value']) {
	  $vars['content'] = '<iframe src="' . $vars['field_sliderocket'][0]['value'] . '" width="616" height="494" scrolling=no frameBorder="0"></iframe>';
	}
  }
  
  // Prepend location and date to first paragraph of Press Room body.
  if( ($vars['type'] === "pressroom") && ($vars['body']) ){
  	  $date = "";
  	  $location = ($vars['field_pr_location'][0]['value']) ? $vars['field_pr_location'][0]['value'] . ", " : "";
  	  if($vars['field_pub_date'][0]['value']) {
		$timestamp = strtotime($vars['field_pub_date'][0]['value']);
		$date = format_date($timestamp, 'custom', 'F j, Y') . " &mdash; ";
	  }

  	  $newBody = preg_replace('/<p>/', "<p>" . $location . $date, $vars['body'], 1);

  	  $vars['content'] = $newBody;
  }
}
//

/**
 * Preprocess theme function to print a single record from a row, with fields
 */
function efficient_portfolios_preprocess_views_view_fields(&$vars) {
  $view = $vars['view'];

  // Loop through the fields for this view.
  $inline = FALSE;
  $vars['fields'] = array(); // ensure it's at least an empty array.
  foreach ($view->field as $id => $field) {
    // render this even if set to exclude so it can be used elsewhere.
    $field_output = $view->style_plugin->get_field($view->row_index, $id);
    $empty = $field_output !== 0 && empty($field_output);
    if (empty($field->options['exclude']) && (!$empty || empty($field->options['hide_empty']))) {
      $object = new stdClass();

	  // Find last paragraph (<p>) tag, without attributes, and give it a "last" class.
	  $pos = strripos($field_output, '<p>');
	  if($pos !== false){
		  $new_field_output = substr_replace($field_output, ' class="last"', $pos+2, 0);
		  $field_output = $new_field_output;
	  }
      $object->content = $field_output;
      if (isset($view->field[$id]->field_alias) && isset($vars['row']->{$view->field[$id]->field_alias})) {
        $object->raw = $vars['row']->{$view->field[$id]->field_alias};
      }
      else {
        $object->raw = NULL; // make sure it exists to reduce NOTICE
      }
      $object->inline = !empty($vars['options']['inline'][$id]);
      $object->inline_html = $object->inline ? 'span' : 'div';
      if (!empty($vars['options']['separator']) && $inline && $object->inline && $object->content) {
        $object->separator = filter_xss_admin($vars['options']['separator']);
      }

      $inline = $object->inline;

      $object->handler = &$view->field[$id];
      $object->element_type = $object->handler->element_type();

      $object->class = views_css_safe($id);
      $object->label = check_plain($view->field[$id]->label());
      $vars['fields'][$id] = $object;
    }
  }
}

/**
 * Override or insert variables into the comment templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("comment" in this case.)
 */
/* -- Delete this line if you want to use this function
function efficient_portfolios_preprocess_comment(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the block templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
/* -- Delete this line if you want to use this function
function efficient_portfolios_preprocess_block(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Theme a form button.
 *
 * @ingroup themeable
 */
function efficient_portfolios_button($element) {
  $form_class = 'form-' . $element['#button_type'];
  $default_button_class = 'orange';
  
  // Make sure not to overwrite classes.
  if (isset($element['#attributes']['class'])) {
	if ((isset($element['#attributes']['override'])) && ($element['#attributes']['override'] > 0)) {
		$element['#attributes']['class'] = $form_class . ' ' . $element['#attributes']['class'];
		unset($element['#attributes']['override']);
	} else if (strpos($element['#attributes']['class'], 'node-add-to-cart') !== false) {
		$element['#attributes']['class'] = 'orange ' . $form_class . ' ' . $element['#attributes']['class'];
	} else if (strpos($element['#attributes']['class'], 'list-add-to-cart') !== false) {
		$element['#attributes']['class'] = 'orange ' . $form_class . ' ' . $element['#attributes']['class'];
	} else {
		$element['#attributes']['class'] = $default_button_class . ' ' . $form_class . ' ' . $element['#attributes']['class'];
	}
  } else {
    $element['#attributes']['class'] = $default_button_class . ' ' . $form_class;
  }

  return '<button' . 
  	drupal_attributes($element['#attributes']) . 
  	(empty($element['#button_type']) ? '' : ' type="'. $element['#button_type'] .'" ') .
  	(empty($element['#id']) ? '' : 'id="'. $element['#id'] . '" ') .
  	(empty($element['#name']) ? '' : 'name="'. $element['#name'] . '" ') .
  	(empty($element['#value']) ? '' : 'value="'. check_plain($element['#value']) . '"') .
  	'><span><em>'. 
  	(empty($element['#value']) ? 'Submit' : check_plain($element['#value'])) .
  	'</em></span></button>' .
  	"\n";
}

/**
* Quick fix for the validation error: 'ID "edit-submit" already defined' or edit-name
* There is a solution in d6 core: <a href="http://drupal.org/node/111719
" title="http://drupal.org/node/111719
" rel="nofollow">http://drupal.org/node/111719
</a> */
function efficient_portfolios_submit($element) {
	// webform with ajax submit needs the "edit-submit", so we'll not change it.
	if(strpos($element['#attributes']['class'], "ajax-trigger") === false){
		static $count_double_id = 0;
		$tmp = str_replace('edit-submit', 'edit-submit-'. $count_double_id++, theme('button', $element));
		return str_replace('edit-name', 'edit-name-'. $count_double_id++, $tmp);
	} else {
		return theme('button', $element);
	}
}

/**
 * Display the simple view of rows one after another
 */
function efficient_portfolios_preprocess_views_view_unformatted(&$vars) {
  $view     = $vars['view'];
  $rows     = $vars['rows'];

  $vars['classes'] = array();
  // Set up striping values.
  foreach ($rows as $id => $row) {
    $row_classes = array();
    $row_classes[] = 'views-row';
    $row_classes[] = 'views-row-' . ($id + 1);
    $row_classes[] = 'views-row-' . ($id % 2 ? 'even' : 'odd');
    $row_classes[] = 'clear-block';
    if ($id == 0) {
      $row_classes[] = 'views-row-first';
    }
    if ($id == count($rows) -1) {
      $row_classes[] = 'views-row-last';
    }
    // Flatten the classes to a string for each row for the template file.
    $vars['classes'][$id] = implode(' ', $row_classes);
  }
}

/**
 * Returns the path to the Efficient Portfolios theme.
 *
 * drupal_get_filename() is broken; see #341140. When that is fixed in Drupal 6,
 * replace _efficient_portfolios_path() with drupal_get_path('theme', 'zen').
 */
function _efficient_portfolios_path() {
  static $path = FALSE;
  if (!$path) {
    $matches = drupal_system_listing('efficient_portfolios\.info$', 'themes', 'name', 0);
    if (!empty($matches['efficient_portfolios']->filename)) {
      $path = dirname($matches['efficient_portfolios']->filename);
    }
  }
  return $path;
}
