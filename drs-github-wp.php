<?php
/**
 * Plugin Name: GitHub Widget
 * Plugin URI: http://github.com/danielrsmith/drs-github-wp
 * Description: A collection of useful GitHub Widgets
 * Author: Daniel Smith <daniel@danielrs.com>
 * Author URI: http://danielrs.com
 * Version: 0.2
 * License: GPL2
 *
 * Copyright 2012  Daniel Smith  (email : daniel@danielrs.com)
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as 
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
require_once('lib/Github/Autoloader.php');
Github_Autoloader::register();

require_once('shortcodes.php');
require_once('repo-widget.php');

add_action( 'widgets_init', create_function( '', 'register_widget("DRSGitHub_Widget");' ) );
?>