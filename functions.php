<?php

### master / A@@UTZ4j&egm5$UFOC

include get_stylesheet_directory() . '/inc/theme_update_hook.php';
include get_stylesheet_directory() . '/inc/enqueue.php';
include get_stylesheet_directory() . '/inc/ajax.php';
include get_stylesheet_directory() . '/inc/options.php';
include get_stylesheet_directory() . '/inc/template_inc.php';
include get_stylesheet_directory() . '/inc/theme_functions.php';
include get_stylesheet_directory() . '/inc/init.php';
include get_stylesheet_directory() . '/languages/localize.php';

//============================== REDIRECT TO LANDING after login
add_filter('login_redirect', 'filter_function_name_7309', 10, 3);
function filter_function_name_7309($redirect_to, $requested_redirect_to, $user)
{
  if (isset($user->roles) && is_array($user->roles)) {
    if ($user->user_nicename == "ip_master") {
      return $redirect_to;
    } else {
      return "/wp-admin/options-general.php?page=iproman-theme";
    }
  } else {
    return $redirect_to;
  }
}

//========================================== IMAGE
function img($classNames, $url, $fullpath = false)
{
  $alt = get_bloginfo('name');
  $path = $fullpath ? $url : get_stylesheet_directory_uri() . "/img/" . $url;
  echo ("<img class='$classNames' src='$path'  alt='$alt' />");
}
function get_img($classNames, $url)
{
  $alt = get_bloginfo('name');
  $path = get_stylesheet_directory_uri() . "/img/" . $url;
  return ("<img class='$classNames' src='$path'  alt='$alt' />");
}
function get_img_url($path)
{
  $url = get_stylesheet_directory_uri() . "/img/" . $path;
  return $url;
}
function ip_img($imgID, $width = "", $classNames = "")
{
  if ($imgID) {
    $url = wp_get_attachment_image_url($imgID, $width);
    if ($url) {
      echo "<img class='$classNames' width='$width' src='$url' />";
    }
  } else {
    echo "<!-- изображение отсутствует -->";
  }
}
function ip_video($id)
{
  if ($id) {
    $url = wp_get_attachment_url($id);
    if ($url) {
      echo "<video controls width='100%' src='$url'></video>";
    }
  } else {
    echo "<!-- видео отсутствует -->";
  }
}

//========================================= LOGO
function bo_custom_logo($className)
{
  $logo = get_custom_logo();
  if ($logo) {
    echo $logo;
  } else {
    if (!is_front_page()) {
      echo '<a href="/">' . get_img($className, 'logo.png') . '</a>';
    } else {
      img($className, 'logo.png');
    }
  }
}

// =================== актуальный год в копирайте
function actual_year()
{
  $y = date('Y');
  echo $y > 2021 ? "2021 - $y" : "2021";
}

// 
function site_name()
{
  return str_replace(' ', '&nbsp;', get_bloginfo('name'));
}

/*====================================
Изменение логотипа на странице входа
======================================*/
## Изменяет логотип, его ссылку и title атрибут на странице входа
if (1) {
  // Изменяем картинку (логотип)
  // укажите правильную ссылку на картинку.
  add_action('login_head', 'wp_login_logo_img_url');
  function wp_login_logo_img_url()
  {
    echo '
		<style>
      @import url("https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap");
      body{ 
        background: #fff;
        font-family: "Open Sans", sans-serif;
        font-size: 18px;
      }
      #login .wp-core-ui .button, .wp-core-ui .button-secondary{
        color: #01DB5E;
      }
      .login form{
        background: #efefef
      }
			#login h1 a{
			  background-image: url( ' . get_template_directory_uri() . '/img/Logo.png ) !important;
			  background-size: contain;
        height: 112px;
        width: 112px;
			}
			#login{
			  background: url( ' . get_template_directory_uri() . '/img/webp/home-fs.webp) no-repeat center top / cover;
			  background-attachment: fixed;
        padding-bottom: 100px;
			}
			#login #nav a,
      #login #backtoblog a,
      #login .privacy-policy-page-link a{
        text-decoration: underline;
        text-align: center;
        width: 100%;
        display: block;
      }
      #login a:focus{
        box-shadow: 0 0 0 1px #01DB5E;
      }
      #login .button.wp-hide-pw:focus{
        background: 0 0;
        border-color: #01DB5E;
        box-shadow: 0 0 0 1px #01DB5E;
        outline: 2px solid transparent;
      }
      #login .button-primary, .wp-core-ui .button, #login .button-primary:hover, .wp-core-ui .button:hover{
        background-color: #01DB5E;
        width: fit-content;
        padding: 5px 20px;
        border-radius: 7px;
        border: 1px solid #01c757;
        font-weight: 700;
        color: #fff;
        cursor: pointer;
        transition: all 0.3s;
      }
      #login .button-primary:hover, .wp-core-ui .button:hover{
        background-color: #00a346;
      }
      .forgetmenot{
        display: none;
      }
      p.submit{
        display: flex;
      }
      #loginform{
        position: relative;
        padding-top: 80px;
        border-radius: 8px;
      }
      #loginform:after{
        content: "' . __("Log in", "ip") . '";
        position: absolute;
        top: 0px;
        padding: 15px;
        background: #333;
        left: 0;
        right: 0;
        color: #fff;
      }
      .login .button-primary{
        float: none;
        margin: auto;
      }
      #login input {
        padding: 10px .75rem;
        background-color: #fff;
        border-color: #ddd;
        font-size: 1rem;
      }
      #login input:focus{
        box-shadow: 0 0 0 1px #01DB5E;
      }
      #login input[type=checkbox] {
        padding: 0;
        min-width: 25px;
        min-height: 25px;
        max-width: 25px;
        max-height: 25px;
        outline: none;
        margin-right: 10px;
      }
		</style>';
  }

  // Изменяем ссылку с логотипа
  add_filter('login_headerurl', 'wp_login_logo_link_url');
  function wp_login_logo_link_url($url)
  {
    return home_url();
  }

  // Изменяем атрибут title у ссылки логотипа
  add_filter('login_headertext', 'wp_login_logo_title_attr');
  function wp_login_logo_title_attr($title)
  {
    $title = get_bloginfo('name');
    return $title;
  }
}

// Изменяем время жизни куки авторизации
add_filter('auth_cookie_expiration', 'filter_function_name_11', 10, 3);
function filter_function_name_11($length, $user_id, $remember)
{
  return 120 * 60;
}
