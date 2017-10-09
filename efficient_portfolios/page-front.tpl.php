<?php
// $Id: page.tpl.php,v 1.14.2.10 2009/11/05 14:26:26 johnalbin Exp $

/**
 * @file page.tpl.php
 *
 * Theme implementation to display a single Drupal page.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *	  least, this will always default to /.
 * - $css: An array of CSS files for the current page.
 * - $directory: The directory the theme is located in, e.g. themes/garland or
 *	  themes/garland/minelli.
 * - $is_front: TRUE if the current page is the front page. Used to toggle the mission statement.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Page metadata:
 * - $language: (object) The language the site is being displayed in.
 *	  $language->language contains its textual representation.
 *	  $language->dir contains the language direction. It will either be 'ltr' or 'rtl'.
 * - $head_title: A modified version of the page title, for use in the TITLE tag.
 * - $head: Markup for the HEAD section (including meta tags, keyword tags, and
 *	  so on).
 * - $styles: Style tags necessary to import all CSS files for the page.
 * - $scripts: Script tags necessary to load the JavaScript files and settings
 *	  for the page.
 * - $body_classes: A set of CSS classes for the BODY tag. This contains flags
 *	  indicating the current layout (multiple columns, single column), the current
 *	  path, whether the user is logged in, and so on.
 * - $body_classes_array: An array of the body classes. This is easier to
 *	  manipulate then the string in $body_classes.
 * - $node: Full node object. Contains data that may not be safe. This is only
 *	  available if the current page is on the node's primary url.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *	  when linking to the front page. This includes the language domain or prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *	  in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *	  in theme settings.
 * - $mission: The text of the site mission, empty when display has been disabled
 *	  in theme settings.
 *
 * Navigation:
 * - $search_box: HTML to display the search box, empty if search has been disabled.
 * - $primary_links (array): An array containing primary navigation links for the
 *	  site, if they have been configured.
 * - $secondary_links (array): An array containing secondary navigation links for
 *	  the site, if they have been configured.
 *
 * Page content (in order of occurrance in the default page.tpl.php):
 * - $left: The HTML for the left sidebar.
 *
 * - $breadcrumb: The breadcrumb trail for the current page.
 * - $title: The page title, for use in the actual HTML content.
 * - $help: Dynamic help text, mostly for admin pages.
 * - $messages: HTML for status and error messages. Should be displayed prominently.
 * - $tabs: Tabs linking to any sub-pages beneath the current page (e.g., the view
 *	  and edit tabs when displaying a node).
 *
 * - $content: The main content of the current Drupal page.
 *
 * - $right: The HTML for the right sidebar.
 *
 * Footer/closing data:
 * - $feed_icons: A string of all feed icons for the current page.
 * - $footer_message: The footer message as defined in the admin settings.
 * - $footer : The footer region.
 * - $closure: Final closing markup from any modules that have altered the page.
 *	  This variable should always be output last, after all other dynamic content.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>">

<head>
	<title><?php print $head_title; ?></title>
	<?php print $head; ?>
	<?php print $styles; ?>
	<?php print $scripts; ?>
</head>
<body class="<?php print $body_classes; ?>">
<div id="page"><div id="page-inner">
	<div id="header"><div id="header-inner" class="clear-block">
		<?php
		$primary_menu = module_invoke('nice_menus', 'block', 'view', 1);
		echo $primary_menu['content'];
		?>
	</div></div> <!-- /#header-inner, /#header -->

	<div id="main" class="main-home"><div id="main-inner" class="clear-block<?php if ($search_box || $primary_links || $secondary_links || $navbar) { print ' with-navbar'; } ?>">
		<div id="emp-logo"></div>
		<?php if ($home_top): ?>
		<div id="home-top" class="region region-home_top clear-block">
			<?php print $home_top; ?>
		</div> <!-- /#home-top -->
		<?php endif; ?>

		<div id="content"><div id="content-inner">	
			<?php if ($mission): ?>
			<div id="mission"><?php print $mission; ?></div>
			<?php endif; ?>

			<?php if ($content_top): ?>
			<div id="content-top" class="region region-content_top">
				<?php print $content_top; ?>
			</div> <!-- /#content-top -->
			<?php endif; ?>

			<?php if ($content_bottom): ?>
			<div id="content-bottom" class="region region-content_bottom clear-block">
				<?php print $content_bottom; ?>
			</div> <!-- /#content-bottom -->
			<?php endif; ?>
		</div></div> <!-- /#content-inner, /#content -->

		<?php if ($right): ?>
		<div id="sidebar-right">
			<div id="sidebar-right-divider-top">
				<div id="sidebar-right-inner" class="region region-right">
					<?php print $right; ?>
				</div> <!-- /#sidebar-right-inner -->
			</div> <!-- /#sidebar-right-divider-top -->
		</div> <!-- /#sidebar-right -->
		<?php endif; ?>
	</div></div> <!-- /#main-inner, /#main -->

	<div id="footer"><div id="footer-inner" class="region region-footer clear-block">	
		<!-- Footer Sitemap -->
		<div id="footer-sitemap-row" class="clear-block">
		<div id="block-menus" class="clear-block">

		<div class="block-menu_block menu-col"><div class="block-inner">
		<?php
			$main_footer_sitemap = module_invoke('menu_block', 'block', 'view', 1);
			if(!empty($main_footer_sitemap['content'])):
		?>
		<h2 class="title">Main</h2>
		<div class="content"><?=$main_footer_sitemap['content']; ?></div>
		<?php endif; ?>
		</div></div>

		<div class="block-menu_block menu-col"><div class="block-inner">
		<?php
			$services_footer_sitemap = module_invoke('menu_block', 'block', 'view', 2);
			if(!empty($services_footer_sitemap['content'])):
		?>
		<h2 class="title"><?=$services_footer_sitemap['subject']; ?></h2>
		<div class="content"><?=$services_footer_sitemap['content']; ?></div>
		<?php endif; ?>
		<?php
			$contact_footer_sitemap = module_invoke('menu_block', 'block', 'view', 5);
			if(!empty($contact_footer_sitemap['content'])):
		?>
		<h2 class="title not-top"><?=$contact_footer_sitemap['subject']; ?></h2>
		<div class="content"><?=$contact_footer_sitemap['content']; ?></div>
		<?php endif; ?>
		</div></div>

		<div class="block-menu_block menu-col"><div class="block-inner">
		<?php
			$solutions_footer_sitemap = module_invoke('block', 'block', 'view', 17);
			if(!empty($solutions_footer_sitemap['content'])):
		?>
		<div class="content"><?=$solutions_footer_sitemap['content']; ?></div>
		<?php endif; ?>
		</div></div>

		<div class="block-menu_block menu-col"><div class="block-inner">
		<?php
			$tools_footer_sitemap = module_invoke('menu_block', 'block', 'view', 3);
			if(!empty($tools_footer_sitemap['content'])):
		?>
		<h2 class="title"><?=$tools_footer_sitemap['subject']; ?></h2>
		<div class="content"><?=$tools_footer_sitemap['content']; ?></div>
		<?php endif; ?>
		<?php
			$news_footer_sitemap = module_invoke('menu_block', 'block', 'view', 4);
			if(!empty($news_footer_sitemap['content'])):
		?>
		<h2 class="title not-top"><?=$news_footer_sitemap['subject']; ?></h2>
		<div class="content"><?=$news_footer_sitemap['content']; ?></div>
		<?php endif; ?>
		</div></div>

		</div><!--/#block-menus-->

		<?php
			$footer_contact = module_invoke('block', 'block', 'view', 16);
			echo $footer_contact['content'];
		?>
		</div><!--/#footer-sitemap-row-->
		<!-- End Footer Sitemap -->

		<?php if ($footer_message): ?>
		<div id="footer-message"><?php print $footer_message; ?></div>
		<?php endif; ?>
		<?php print $footer; ?>
	</div></div> <!-- /#footer-inner, /#footer -->

	<?php if ($closure_region): ?>
	<div id="closure-blocks" class="region region-closure"><?php print $closure_region; ?></div>
	<?php endif; ?>

</div></div> <!-- /#page-inner, /#page -->

<?php print $closure; ?>
</body>
</html>
