<?php
/**
 * Plugin Name: GitHub Widget
 * Description: A collection of useful GitHub Widgets
 * Author: Daniel Smith <daniel@danielrs.com>
 * Version: 0.1
 * Author URI: http://danielrs.com
 */
require_once('repo-widget.php');

add_action( 'widgets_init', create_function( '', 'register_widget("DRSGitHub_Widget");' ) );
?>