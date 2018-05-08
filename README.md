## Options Panel Starter Kit for WordPress Theme

This project includes Redux Framework for Options Panel and TGMPA for bundled Plugins.

#### How to include in your theme
Add dew folder to your project and then initialize it in functions.php file of your theme.
```
get_template_part( 'dew/dew-init' );
dew_suite_init($theme_config);
```
`$theme_config` is an array that defines the control center of your theme.

An example functions.php file is included in the repo.

#### Configuration Array Definition

| Key | Type | Definition |
| --- | ---- | ---------- |
| textdomain | String | Textdomain for your theme |
| media_sizes | Array | Array of Media size associative array that takes parameters for add_image_size. [Refer](https://developer.wordpress.org/reference/functions/add_image_size/) |
| tgmpa | Array | Define tgmpa options and the recommended/bundled plugins plugins. [Refer](http://tgmpluginactivation.com/configuration/) |
| redux | Array | Define theme-options page and Redux Framework configurations. [Refer](https://docs.reduxframework.com/uncategorized/getting-started-with-arguments/) |
| admin_pages | Array | Array of Admin Pages and sub menu pages. [Refer](https://developer.wordpress.org/reference/functions/add_menu_page/)|
| toolbar | Array | Array of admin pages on the toolbar. [Refer](https://codex.wordpress.org/Class_Reference/WP_Admin_Bar/add_node) |
