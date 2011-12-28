<?php
/*
Plugin Name: WP-GitHub
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 0.1
Author: Daniel Smith
Author URI: http://danielrs.com
License: GPL2

Copyright 2011  Daniel Smith  (email : daniel@danielrs.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
ini_set('display_errors', 1);
require_once 'lib/Github/Autoloader.php';
Github_Autoloader::register();

$github = new Github_Client();

class DRSGitHubProfileWidget extends WP_Widget
{
	public function __construct()
	{		
        parent::WP_Widget('drs_github', 'GitHub Profile Widget', array('A widget to display your GitHub Profile'));
	}
	
	public function widget($args, $instance)
	{
		$content = 'Github Content Here';
		
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		
		echo $before_widget;
		
		if($title)
		{
			echo $before_title . $title . $after_title;	
		}
		
		echo $content;
		
		echo $after_widget;
	}
	
	public function form($instance)
	{
		if($instance)
		{
			$title = esc_attr($instance['title']);
		}
		else
		{
			$title = __('New title', 'text_domain');
		}
		?>
				<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
				</p>
		<?php 
	}
	
	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
}

function initDRSGitHub()
{
	register_widget('DRSGitHubProfileWidget');
}

add_option('GitHub User Name');
add_option('GitHub Password');
add_action('widgets_init', 'initDRSGitHub');



?>