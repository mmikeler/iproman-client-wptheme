<?php
/*
 Здесь размещаем неприкреплённые вызовы ajax
*/

//====================================== DELETE ON PRODUCTION
function add_cors_http_header()
{
  //header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST, GET");
  header("Access-Control-Allow-Headers: append,delete,entries,foreach,get,has,keys,set,values,Authorization");
}
add_action('init', 'add_cors_http_header');

//======================================== ADD AJAX TO FRONT
add_action('wp_enqueue_scripts', 'myajax_data', 99);
function myajax_data()
{
  $url = admin_url('admin-ajax.php');
  $nonce = wp_create_nonce('wp-pageviews-nonce');
  $l = is_user_logged_in();
  $s = is_single();
  wp_add_inline_script(
    'ip-main',
    "const myajax = {
      'url': '$url',
      'nonce': '$nonce',
      'is_user_logged_in': '$l',
      'is_single': '$s'
    }",
    'before'
  );
}

//===================================== AJAX ACTION
add_action('wp_ajax_' . 'ip_send_email', 'get_email_callback');
add_action('wp_ajax_nopriv_' . 'ip_send_email', 'get_email_callback');
function get_email_callback()
{
  add_filter('wp_mail_from_name', function () {
    return 'From: IPRoman';
  });
  add_filter('wp_mail_from', function () {
    return '<info@' . $_SERVER['SERVER_NAME'] . '>';
  });
  add_filter('wp_mail_content_type', function ($content_type) {
    return "text/html";
  });

  $r = wp_mail(
    get_ip_field('s4_1'),
    __('New message', 'ip'),
    set_email_message(), //message
    "", // headers 
    "" // attachments
  );

  if ($r) {
    echo json_encode([1, $r]);
  } else {
    echo json_encode([0, $r]);
  };

  wp_die();
}

## Переключение языка
add_action('wp_ajax_' . 'change_local', 'ip_change_local');
add_action('wp_ajax_nopriv_' . 'change_local', 'ip_change_local');
function ip_change_local()
{
  global $wpdb;
  $res = $wpdb->update($wpdb->prefix . "options", array("option_value" => $_POST['local']), array("option_name" => "WPLANG"));
  echo $res;
  //var_dump($wpdb->last_error);
  wp_die();
}

function set_email_message()
{
  $p = array(
    "trigger"   => __("Subject", "ip"),
    "username"  => __("Name", "ip"),
    "phone"     => __("Phone", "ip"),
    "email"     => __("Email", "ip"),
    "connect"   => __("Communication type", "ip"),
    "message"   => __("Message", "ip"),
    "promocode" => __("Promocode", "ip"),
  );
  $m = "";
  foreach ($p as $key => $value) {
    $f = isset($_POST[$key]) ? $_POST[$key] : false;
    if ($f && $key === "email") {
      $f = sanitize_email($f);
    }
    $f ? $m .= $value . ": " . $f . "\r\n" : "";
  }

  return $m;
}
