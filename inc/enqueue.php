<?php

// Скрипты и стили для админки
function ip_admin_style()
{
  $v = wp_get_theme()->get('Version');

  ## Bootstrap
  global $pagenow;
  if ($pagenow === "options-general.php") {
    wp_enqueue_style('ip-admin-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
    ## React App 
    wp_enqueue_style('ip-admin-app-styles', get_stylesheet_directory_uri() . '/static/css/main.css', "", $v, false);
    wp_enqueue_script('ip-admin-app-script', get_stylesheet_directory_uri() . '/static/js/main.js', "", $v, true);
    wp_enqueue_script('ip-admin-app-ext-script', get_stylesheet_directory_uri() . '/static/js/chunk.js', "", $v, true);
  }
  wp_enqueue_style('ip-admin-bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.2/font/bootstrap-icons.css');

  ## Customs
  wp_enqueue_style('ip-admin-styles', get_stylesheet_directory_uri() . '/css/ip-admin-style.css', "", $v, false);
  wp_enqueue_script('ip-admin-script', get_stylesheet_directory_uri() . '/js/ip-admin-script.js', "", $v, false);
}
add_action('admin_enqueue_scripts', 'ip_admin_style');

// Скрипты и стили для фронта
function ip_front_style()
{
  $v = wp_get_theme()->get('Version');
  //wp_enqueue_style('ip-animate', get_stylesheet_directory_uri() . '/css/animate.css', "", $v);
  wp_enqueue_style('ip-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css');
  wp_enqueue_style('ip-bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.2/font/bootstrap-icons.css');
  wp_enqueue_style('ip-util', get_stylesheet_directory_uri() . '/css/util.css', "", $v);
  wp_enqueue_style('ip-main', get_stylesheet_directory_uri() . '/css/style.css', "", $v);
  wp_enqueue_style('ip-responsive', get_stylesheet_directory_uri() . '/css/response.css', "", $v);

  wp_enqueue_script('ip-jquery', 'https://code.jquery.com/jquery-3.3.1.min.js', array('jquery'), true);
  wp_enqueue_script('ip-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', array('jquery'), '', true);
  wp_enqueue_script('ip-main', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), $v, true);

  if (is_page("ip-catalog")) {
    wp_enqueue_style('ip-catalog', get_stylesheet_directory_uri() . '/css/catalog.css', "", $v);
    wp_enqueue_script('ip-catalog-custom', get_stylesheet_directory_uri() . '/js/catalog-custom.js', array('jquery'), $v, true);
  }
}
add_action('wp_enqueue_scripts', 'ip_front_style');
