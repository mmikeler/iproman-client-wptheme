<?php

// Здесь описываются вспомогательные функции темы

## Проверка на соответствие JSON
function isJson($str)
{
  return (json_decode(
    $str,
    true
  ) == NULL) ? false : true;
}

## Получение настроек из новой таблицы
function get_iproman_options()
{
  global $wpdb;
  $t = $wpdb->get_blog_prefix() . 'iproman';
  $value = $wpdb->get_results("SELECT meta_key , meta_value FROM `$t`", 'ARRAY_A');
  $out = [];
  foreach ($value as $obj) {
    $key = $obj['meta_key'];
    $value = wp_unslash($obj['meta_value']);
    if ($key === 'products') {
      $value = $value;
    }
    $out[$key] = $value;
  }
  return $out;
}

## Обновление значения строки в IP таблице
function update_ip_row($key, $value)
{
  global $wpdb;
  // Обновляем

  $result = $wpdb->replace(
    $wpdb->get_blog_prefix() . 'iproman',
    array('meta_key' => $key, 'meta_value' => $value),
    array('%s', '%s')
  );

  return $result;
}

## Получение значения строки из IP таблицы
function get_ip_row($key)
{
  global $wpdb;
  $t = $wpdb->get_blog_prefix() . 'iproman';
  $value = $wpdb->get_var("SELECT meta_value FROM `$t` WHERE meta_key = '$key'");

  return $value;
}

## Получение всех значений из IP таблицы
function get_ip_rows()
{
  global $wpdb;
  $t = $wpdb->get_blog_prefix() . 'iproman';
  $value = $wpdb->get_results("SELECT meta_key , meta_value FROM `$t`", 'ARRAY_A');
  $out = [];
  foreach ($value as $obj) {
    $key = $obj['meta_key'];
    $value = $obj['meta_value'];
    $value = wp_unslash($value);
    $out[$key] = $value;
  }
  $gallery = get_posts(array('post_type' => 'attachment', 'posts_per_page' => -1));
  $medialist = [];
  foreach ($gallery as $post) {
    $url = wp_get_attachment_image_url($post->ID);
    if ($url) {
      $medialist[] = [$url, $post->ID];
    }
  }
  $out['media'] = $medialist;
  echo json_encode($out);
}

## Получение поля с обработкой локализации
function get_ip_field($fieldName)
{
  global $o;
  $ggl = get_guest_locale();
  $f = $fieldName . "[$ggl]";
  return isset($o[$f]) ? $o[$f] : "";
}

## Обновление значения поля в IP таблице, с учётом локализации
function update_ip_field($key, $value)
{
  global $wpdb;
  $locale = get_ip_row('admin_locale');
  // Обновляем

  $result = $wpdb->replace(
    $wpdb->get_blog_prefix() . 'iproman',
    array('meta_key' => $key . '[' . $locale . ']', 'meta_value' => $value),
    array('%s', '%s')
  );

  return $result;
}

## Вывод поля с обработкой ошибок
function the_ip_field($fieldName)
{
  echo get_ip_field($fieldName);
}

## Очистка номера телефона от дефисов и скобок
function clear_phone_number($number)
{
  return str_replace(array('(', ')', ' ', '-', '+'), '', $number);
}

## Проверка связанных полей на заполнение
## Принимает: массив имён полей
## Возвращает: true - если чекбоксы нажаты и текстовые поля имеют хотя бы один символ, иначе - false 
function is_field_valid($fieldNames = [])
{
  if (!is_array($fieldNames)) return;

  foreach ($fieldNames as $field) {
    if (get_ip_field($field) === "") {
      return false;
      break;
    }
  }
  return true;
}

## Получаем url изображения по ID
function get_ip_img_url($id, $size = 'full')
{
  $url = wp_get_attachment_image_url($id, $size);
  if ($url) {
    return $url;
  } else {
    return get_theme_file_uri('img/default_img.png');
  }
}

## Выводит url изображения по ID
function ip_img_url($id, $size = false)
{
  echo get_ip_img_url($id, $size);
}

## Проверка на vip статус клиента
function is_user_vip()
{
  $meta = get_ip_row("_ip_user_level");
  if ($meta && $meta === "vip") {
    return true;
  } else {
    return false;
  }
}

## Проверка на активность каталога
function get_catalog_status()
{
  if (get_ip_field("catalog_on") != 1) {
    return false;
  }
  return true;
}

## Проверка на суперадмина
function is_ipadmin()
{
  $is_ipadmin = wp_get_current_user()->user_nicename === "ip_master" ? true : false;
  return $is_ipadmin;
}

// подключаем MO файл перевода и указываем ему ID — ip
load_theme_textdomain('ip', get_template_directory() . '/languages');


## Переключатель языка
function local_changer()
{
  echo '<div class="local-changer">';
  // Массив с локалями
  $locals = array("EN" => "en_US", "LV" => "lv", "RU" => "ru_RU", 'DE' => 'de_DE', 'FR' => 'fr_FR');
  // Основной язык сайта
  $default_lang = explode(',', get_ip_row('site_languages'))[0];
  // Значение переключателя по-умолчанию
  $default = isset($_COOKIE["ip_lang"]) ? $_COOKIE["ip_lang"] : $default_lang;
  // Выводим переключатели локалей, при условии, что нужные поля заполнены
  foreach ($locals as $k => $v) {
    if (get_ip_row("lang_on[$v]") == 1 || $v == $default_lang) {
      // Определяем, активен ли данный язык
      $c = $default === $v ? 1 : 0;
      // Выводим сам переключатель
      echo "<div class='local-changer__item' current='$c' data-local='$v'>$k</div>";
    }
  }
  echo "</div>";
}

## Выводим фавик загруженный юзером или по-умолчанию, если его нет
function ip_favicon()
{
  if (get_ip_field('s6_4')) {
    $url = get_ip_img_url(get_ip_field('s6_4'), "small");
  } else {
    $url = get_theme_file_uri('/img/favicon8.png');
  }

  echo "<link type='image/x-icon' href='$url' rel='shortcut icon'>";
}

## Отправка письма
function ip_send_mail($email, $subject, $message, $from)
{
  switch ($from) {
    case 'from_admin':
      add_filter('wp_mail_from_name', function () {
        return 'IPRoman';
      });
      add_filter('wp_mail_from', function ($email_address) {
        return 'support@biz.host';
      });
      break;

    default:
      add_filter('wp_mail_from_name', function () {
        return 'Client - ' . get_ip_row('IPKEY');
      });
      break;
  }

  add_filter('wp_mail_content_type', function ($content_type) {
    return "text/html";
  });

  return wp_mail($email ?: 'support@biz.host', $subject, $message) ? 'ok' : 'error';
}

## Запись в лог-файл
function iplog($message)
{
  error_log($message, 3, get_stylesheet_directory() . "/logs/errors.log");
}
