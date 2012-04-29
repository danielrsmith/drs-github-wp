<?php
require_once('lib/Github/Autoloader.php');
Github_Autoloader::register();
defined('ABSPATH') or die("Cannot access pages directly.");
defined("DS") or define("DS", DIRECTORY_SEPARATOR);

/**
 * 
 * @author Daniel Smith <daniel@danielrs.com>
 * 
 */
class DRSGitHub_Widget extends WP_Widget
{
	protected $widget = array(
		'name' => 'GitHub Repository Widget',
		'description' => 'GitHub repository widget',
		'do_wrapper' => true, 
		'view' => false,
		
		'fields' => array(
			array(
				'name' => 'Title',
				'desc' => '',
				'id' => 'title',
				'type' => 'text',
				'std' => 'My GitHub Repositories'
			),
			array(
				'name' => 'GitHub Username',
				'desc' => '',
				'id' => 'username',
				'type' => 'text',
				'std' => 'GitHub Username'
			),
			array(
				'name' => 'Items to Display',
				'desc' => 'Maximum number of repositories to show',
				'id' => 'repo_count',
				'type' => 'text',
				'std' => '3'
			),
			array(
				'name' => 'Open repositories in a new window?',
				'desc' => '',
				'id' => 'new_window',
				'type' => 'checkbox'
			),
						
		)
	);
	
	/**
	 * @param array $widget
	 * @param array $params
	 * @param array $sidebar
	 */
	function html($widget, $params, $sidebar)
	{
		echo '<h3 class="widget-title">' . $params['title'] . '</h3>';
		
		$github = new Github_Client();
		$user_repos = $github->getRepoApi()->getUserRepos($params['username']);
		$num_of_repos = count($user_repos);
		
		if(!empty($user_repos))
		{
			
			if($num_of_repos < $params['repo_count'])
			{
				$params['repo_count'] = $num_of_repos;
			}
			
			
			$rand_repos = array_rand($user_repos, $params['repo_count']);
	
			if(!is_array($rand_repos))
			{
				$rand_repos =  array($rand_repos);
			}
			
			
			echo '<ul>';
			
			foreach($rand_repos as $repo_key)
			{
				$repo = $user_repos[$repo_key];
				$new_window = '';
				
				if($params['new_window'])
				{
					$new_window = 'target="_blank"';
				}
				echo sprintf("<li><a href=\"%s\" title=\"%s\" %s>%s</a></li>", $repo['url'], $repo['description'] , $new_window ,$repo['name']);
			}
			
			echo '</ul>';
		}
		else 
		{
			echo 'This user does not have any public repositories.';
		}
	}

	function DRSGitHub_Widget()
	{
		$classname = sanitize_title(get_class($this));

		parent::WP_Widget( 
			$id = $classname, 
			$name = (isset($this->widget['name'])?$this->widget['name']:$classname), 
			$options = array( 'description'=>$this->widget['description'] )
		);
	}
	
	/**
	 * @param array $sidebar
	 * @param array $params
	 */
	function widget($sidebar, $params)
	{
		//initializing variables
		$this->widget['number'] = $this->number;
		$title = apply_filters( 'DRSGitHub_Widget_title', $params['title'] );
		$do_wrapper = (!isset($this->widget['do_wrapper']) || $this->widget['do_wrapper']);
		
		if ( $do_wrapper ) 
		{
			echo $sidebar['before_widget'];
		}
		
		//loading a file that is isolated from other variables
		if (file_exists($this->widget['view']))
		{
			$this->getViewFile($widget, $params, $sidebar);
		}
			
		if ($this->widget['view'])
		{
			echo $this->widget['view'];
		}	
		else 
		{
			$this->html($this->widget, $params, $sidebar);
		}
			
		if ($do_wrapper)
		{ 
			echo $sidebar['after_widget'];
		}
	}
	

	function getViewFile($widget, $params, $sidebar) 
	{
		require $this->widget['view'];
	}


	function form($instance)
	{
		//reasons to fail
		if (empty($this->widget['fields'])) return false;
		
		$defaults = array(
			'id' => '',
			'name' => '',
			'desc' => '',
			'type' => '',
			'options' => '',
			'std' => '',
		);
		
		do_action('DRSGitHub_Widget_before');
		foreach ($this->widget['fields'] as $field)
		{
			//making sure we don't throw strict errors
			$field = wp_parse_args($field, $defaults);

			$meta = false;
			if (isset($field['id']) && array_key_exists($field['id'], $instance))
				@$meta = attribute_escape($instance[$field['id']]);

			if ($field['type'] != 'custom' && $field['type'] != 'metabox') 
			{
				echo '<p><label for="',$this->get_field_id($field['id']),'">';
			}
			if (isset($field['name']) && $field['name']) echo $field['name'],':';

			switch ($field['type'])
			{
				case 'text':
					echo '<input type="text" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '" value="', ($meta ? $meta : @$field['std']), '" class="vibe_text" />', 
					'<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'textarea':
					echo '<textarea class="vibe_textarea" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '" cols="60" rows="4" style="width:97%">', $meta ? $meta : @$field['std'], '</textarea>', 
					'<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'select':
					echo '<select class="vibe_select" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '">';

					foreach ($field['options'] as $value => $option)
					{
 					   $selected_option = ( $value ) ? $value : $option;
					    echo '<option', ($value ? ' value="' . $value . '"' : ''), ($meta == $selected_option ? ' selected="selected"' : ''), '>', $option, '</option>';
					}

					echo '</select>', 
					'<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'radio':
					foreach ($field['options'] as $option)
					{
						echo '<input class="vibe_radio" type="radio" name="', $this->get_field_name($field['id']), '" value="', $option['value'], '"', ($meta == $option['value'] ? ' checked="checked"' : ''), ' />', 
						$option['name'];
					}
					echo '<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'checkbox':
					echo '<input type="hidden" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '" /> ', 
						 '<input class="vibe_checkbox" type="checkbox" name="', $this->get_field_name($field['id']), '" id="', $this->get_field_id($field['id']), '"', $meta ? ' checked="checked"' : '', ' /> ', 
					'<br/><span class="description">', @$field['desc'], '</span>';
					break;
				case 'custom':
					echo $field['std'];
					break;
			}

			if ($field['type'] != 'custom' && $field['type'] != 'metabox') 
			{
				echo '</label></p>';
			}
		}
		do_action('DRSGitHub_Widget_after');
		return true;
	}

	function update($new_instance, $old_instance)
	{
		// processes widget options to be saved
		$instance = wp_parse_args($new_instance, $old_instance);
		return $instance;
	}

}