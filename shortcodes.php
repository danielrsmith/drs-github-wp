<?php
/*
 * Github Shortcodes
 */

/**
 * 
 * Adds the github-repo shortcode to display pertinant repository information on a post or page.
 * @param array $atts
 * @return string
 */
function github_repo($atts)
{
	extract(shortcode_atts(
			array(	'username'		=> '',
					'repository'	=> '',
			), $atts));
	$content .= '<div class="github-repository">';
	$content .= '<h3>' . $repository . '</h3>';
	
	$github = new Github_Client();
	
	$repo_info = $github->getRepoApi()->show($username, $repository);
	
	if(!empty($repo_info))
	{
		//display repo info
		$content .= '<p><span class="github-field">Name: </span><span>' . $repo_info['name'] . '</span></p>';
		$content .= '<p><span class="github-field">Owner: </span><span><a href="http://github.com/' . $repo_info['owner'] . '">' . $repo_info['owner'] . '</a></span></p>';
		$content .= '<p><span class="github-field">URL: </span><span><a href="' . $repo_info['url'] . '">' . $repo_info['url'] . '</a></span></p>';
		$content .= '<p><span class="github-field">Description: </span><span>' . $repo_info['description'] . '</span></p>';
	}
	else
	{
		$content .= '<p>This repository does not exist.</p>';
	}
	
	$content .= '</div>';
	
	return $content;
}

//Adds shortcode to Wordpress
add_shortcode('github-repo', 'github_repo');