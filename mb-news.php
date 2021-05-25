<?php
/**
 * Plugin Name:       MB News
 * Plugin URI:        https://github.com/mb-dev-75/mb-news
 * Description:       A configurable Wordpress carousel for your last articles
 * Version:           1.0.0
 * Author:            MB
 * Author URI:        https://github.com/mb-dev-75/
 * License:           MIT
 * Text Domain:       mb-news
 * Domain Path:       /languages
 */

// Security
if(!defined('ABSPATH' )) {
  exit;
}

// Loading scripts (CSS et JS)
require_once(plugin_dir_path(__FILE__).'/includes/mb-news-scripts.php');

// Loading class
require_once(plugin_dir_path(__FILE__).'/includes/mb-news-class.php');

// Widget register
function register_mb_news() {
  register_widget('mb_News_Widget');
}

add_action('widgets_init', 'register_mb_news');
