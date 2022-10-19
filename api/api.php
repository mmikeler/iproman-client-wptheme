<?php

use function PHPSTORM_META\type;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Origin, Content-Type');

require_once '../../../../wp-load.php';

if (
  !function_exists('media_handle_sideload')
) {
  require_once ABSPATH . 'wp-admin/includes/image.php';
  require_once ABSPATH . 'wp-admin/includes/file.php';
  require_once ABSPATH . 'wp-admin/includes/media.php';
}

switch ($_POST['action']) {
  case 'upload_file':
    if (isset($_POST['meta_key'])) {
      $id = media_handle_sideload($_FILES['file'], NULL, $desc = null, $post_data = array());
      update_ip_row($_POST['meta_key'], $id);
      echo $id;
    }
    break;

  case 'remove_thumb':
    if ($_POST['meta_key']) {
      update_ip_row($_POST['meta_key'], "");
      echo $_POST['meta_key'] . " is empty now";
    }
    break;

  case 'get_thumb':
    if (isset($_POST['file_id'])) {
      echo json_encode(wp_get_attachment_url($_POST['file_id']));
    } else {
      echo 0;
    }
    break;

  case 'update_meta':
    $key = isset($_POST['meta_key']) ? $_POST['meta_key'] : false;
    $value = isset($_POST['meta_value']) ? $_POST['meta_value'] : false;

    isJson($value) && $value = json_decode($value, true);

    $key && $res = update_ip_row($key, $value);
    echo $res || new WP_Error('no_correct_data', 'Предоставлены не корректные данные для обработки');
    break;

  case 'init':
    get_ip_rows();
    break;

  case 'get_media_collection':
    $gallery = get_posts(array('post_type' => 'attachment', 'posts_per_page' => -1));
    $medialist = [];
    foreach ($gallery as $post) {
      $url = wp_get_attachment_image_url($post->ID);
      if ($url) {
        $medialist[] = [$url, $post->ID];
      }
    }
    echo json_encode($medialist);
    break;

  case 'IMPORT_REG_DATA':
    $variant = $_POST['options'];
    $time = time();
    if ($variant) :
      $r = get_ip_row('_start_synch_date');
      $key = get_ip_row('IPKEY');
      $result = 0;
      if (!$r && $key !== NULL) :
        if ($curl = curl_init()) :
          $url = 'https://biz.host/wp-json/iprest/v1/ipuser/' . $key;

          curl_setopt($curl, CURLOPT_URL, $url);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
          $result = json_decode(curl_exec($curl), true);

          if (isset($result['default_site_lang'])) {
            $default_lang = $result['default_site_lang'][0];
            foreach ($result as $k => $v) {
              $k === 'default_site_lang' ? update_ip_row('site_languages', $v[0]) : update_ip_row($k . '[' . $default_lang . ']', $v[0]);
            }
            update_ip_row('_start_synch_date', time());
          }

          curl_close($curl);
        endif;
      endif;
    endif;

    get_ip_rows();
    break;

  case "CALL_TO_SUPPORT":
    $subject = isset($_POST['theme']) ? $_POST['theme'] : 'UNKNOW theme';
    $message = '';

    foreach ($_POST as $key => $value) {
      $message .= "<b>$key:</b> <i>$value</i> <br />";
    }

    echo ip_send_mail(false, $subject, $message, '');
    break;

  case "RESET_PASSWORD";
    $pass = wp_generate_password();
    $user_id = $_POST['userID'];
    $email = $_POST['user_email'];
    $subject = $_POST['theme'];
    $message = sprintf('%s : %s', __('Your new password'), $pass);
    $send_email = ip_send_mail($email, $subject, $message, 'from_admin');

    if ($user_id > 0 && $send_email === 'ok') {
      wp_set_password($pass, $user_id);
    }

    echo $send_email;
    break;

  default:
    echo json_encode(array(
      'code' => '0'
    ));
    break;
};
