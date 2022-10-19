<?php

## Меняем фавик в админке на свой
function fav()
{
  echo '<link rel="Shortcut Icon" type="image/x-icon" href="' . get_theme_file_uri('/img/favicon8.png') . '" />';
}
add_action('admin_head', 'fav');
add_action('login_head', 'fav');

## Страница настроек
add_action('admin_menu', 'my_theme_menu');
function my_theme_menu()
{
  add_options_page('Настройки лэндинга', 'Лэндинг', 'manage_options', 'iproman-theme', 'my_theme_page_01');
}

function my_theme_page_01()
{
  $v = wp_get_theme()->get('Version');
  if (is_user_logged_in()) {
    echo '<div id="root"></div>';
  } else {
    wp_login_form();
  }

?>
  <script>
    window.o = {
      v: "<?php echo $v ?>",
      ajaxUrl: "<?php echo get_stylesheet_directory_uri() . '/api/api.php' ?>",
      homeUrl: "<?php echo site_url() ?>",
      reloginURL: "<?php echo is_plugin_active("hide-my-wp/index.php") ? "iplogin" : "wp-login" ?>",
      pages: {
        fullversion: 'fv',
        myproducts: 'mp',
        domen: 'dn',
        mails: 'ms',
        password: 'pd',
        support: 'st',
      },
      admin_settings: {
        min_title_value_length: 5, // минимальное кол-во символов для шортрида
        min_longtext_value_length: 15, // минимальное кол-во символов для лонгрида
      },
      user: <?php echo json_encode(wp_get_current_user()) ?>
    }

    const click = (e) => {
      const title = e.target.innerText
      const sections = document.querySelectorAll('.section__header')

      sections.forEach((s) => {
        if (true) {
          if (s.innerText === title) {
            const target = s.closest('.section')
            target.classList.remove('closed')
            root.scrollTo({
              top: target.offsetTop - 70,
              behavior: "smooth"
            });
          }
        }
      })
    }

    // удаляем toolbar для не админа
    if (o.user.data.user_login !== "ip_master") {
      document.getElementById("wpadminbar").remove()
      document.getElementById("adminmenumain").remove()
    }

    // HEARTBEAT релогин
    jQuery(document).on('heartbeat-tick', function(event, data, textStatus, jqXHR) {
      // event - объект события
      // data - приходящие данные
      // textStatus - статус выполнения запроса, к примеру success.
      // jqXHR - объект запроса
      if (!data["wp-auth-check"]) {
        window.location.href = "/iplogin"
      }
    })
  </script>
<?php
}
