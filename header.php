<?php ?>

<!DOCTYPE html>
<html lang="<?php echo isset($_COOKIE["ip_lang"]) ? $_COOKIE["ip_lang"] : get_default_locale(true) ?>">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?php echo ip_title() ?></title>
  <meta name="description" content="<?php the_ip_field("s6_2") ?>">
  <meta name="keywords" content="<?php the_ip_field("s6_3") ?>">

  <?php ip_favicon() ?>

  <?php wp_head() ?>
</head>

<body>

  <header>
    <div class="container">
      <?php local_changer() ?>
      <div class="row">
        <div class="col-12 col-lg-5">
          <div class="d-flex flex-wrap">
            <?php
            ip_phone();
            ip_mail();
            ip_address();
            ?>
          </div>
        </div>
        <div class="col-12 col-lg-2 logo-wrapper">

          <?php
          /** 
           * Выводим логотип со ссылкой на главную.
           * Если логотип не загружен, то выводим название сайта, есть оно или нет.
           *
           * */
          ?>

          <a href="<?php echo site_url() ?>">
            <?php if (get_ip_field('s1_5')) { ?>
              <img class="mb-3" width="170" src="<?php ip_img_url(get_ip_field('s1_5'), 'medium') ?>" />
            <?php } elseif (get_ip_field('s1_1')) {
              echo '<div class="buisnes-name">' . get_ip_field('s1_1') . '</div>';
            } ?>
          </a>

        </div>
        <div class="col-12 col-lg-5">
          <?php

          ip_social_list();

          if (confirm(['s4_1', 's1_6'])) {
            echo '<div class="bbtn small" data-bs-toggle="modal" data-bs-target="#modalCallback" data-bs-title="' . get_ip_field("s1_6") . '">' . get_ip_field("s1_6") . '</div>';
          }

          ?>
        </div>
        <?php ip_header_nav() // template_inc 
        ?>
      </div>
    </div>
  </header>