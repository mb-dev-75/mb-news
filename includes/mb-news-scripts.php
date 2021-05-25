<?php

  function mb_news_add_scripts() {
    // CSS
    wp_enqueue_style('slick', plugins_url(). '/mb-news/includes/assets/js/slick/slick.css', NULL, false);
    wp_enqueue_style('slick-theme', plugins_url(). '/mb-news/includes/assets/js/slick/slick-theme.css', array('slick'), NULL, false);
    wp_enqueue_style('mb-news', plugins_url(). '/mb-news/includes/assets/css/mb-news.css', NULL, false);
    // JS
    wp_enqueue_script('slick', plugins_url(). '/mb-news/includes/assets/js/slick/slick.min.js', array('jquery'), '1.8.1', true);
  }

  add_action('wp_enqueue_scripts', 'mb_news_add_scripts');
