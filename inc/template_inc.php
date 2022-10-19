<?php

//=================== MAIN PAGE
function the_main_page_content()
{
  fs_1();           // Main offer
  fs_2();           // Arguments
  ip_products();    // Products
  s2();             // About company
  video();          // Video
  s3_advantages();  // Advantages list
  ip_contacts();    // Contact form
}

//=================== PAGE TITLE
function ip_title()
{
  if (is_front_page()) {
    return get_ip_field("s6_1");
  } else if (is_page('ip-catalog')) {
    return get_ip_field('catalog_2');
  } else {
    return get_bloginfo('name');
  }
}

//=================== SOCIAL LIST
function ip_social_list()
{
  $networks = array("telegram", "whatsapp", "viber", "vk", "fb", "inst");
  echo "<div class='social-list'>";

  foreach ($networks as $network) {
    $net = get_ip_field($network);
    $open = get_ip_field($network . "_open");

    if ($open == 0 || $net == '')
      continue;

    if (!str_contains($net, "http")) {
      switch ($network) {
        case 'whatsapp':
          $net = "https://wapp.click/" . clear_phone_number($net);
          break;

        case 'viber':
          $net = "https://viber.click/" . clear_phone_number($net);
          break;

        case 'telegram':
          $net = "https://t.me/+" . clear_phone_number($net);
          break;

        default:
          $net = "https://" . $net;
          break;
      }
    }

    echo sprintf("<a target='_blank' rel='noopener,noreferrer' href='%s'><i class='social-icon %s'></i></a>", $net, $network);
  }

  echo  "</div>";
}

//========================= PRODUCTS
function ip_products()
{
  $products = json_decode(wp_unslash(get_ip_field('products')));

  ## Отменяем, если продуктов нет или поле имеет недопустимое значение
  if (!is_array($products) || count($products) === 0) return;

  ## Валидируем поля
  $valid = true;
  $confirm_fields = ['name'];
  foreach ($products as $p) {
    foreach ($confirm_fields as $field_name) {
      if (strlen($p->$field_name) < 5) {
        $valid = false;
      }
    }
  }
  if (!$valid) return;

  ## Выводим блок продуктов
  echo '<section id="price" class="s4"><div class="container"><div class="row">';
  $i = 0;
  foreach ($products as $p) {
    $thumb = get_ip_field('product_' . $i . '_thumb') !== "" ? get_ip_field('product_' . $i . '_thumb') : false;
    ip_product($p, $i, $thumb);
    $i++;
  }
  echo '</div></div></section>';
}

//========================= PRODUCT
function ip_product($p, $i, $imgID)
{
  if ($i < 2) { ?>
    <div class="col-12 col-lg-6 mb-4">
      <div class="card">
        <div class="card__header text-center">
          <?php echo $p->name ?>
        </div>
        <div class="card__body">
          <div class="cb-title">
            <span class="cb-title__price"><?php echo $p->price ?></span>
            <span class="cb-title__caption"> <?php echo $p->condition ? "/ " . $p->condition : "" ?></span>
          </div>
          <?php ip_product_advanced_list($p->params) ?>
          <?php echo strlen($p->btnText) >= 5 ? sprintf("<div class='bbtn arrow-right my-3' data-scrollto='contacts'>%s</div>", $p->btnText) : ""; ?>
        </div>
      </div>
    </div>
  <?php } else { ?>
    <div class="col-12 col-lg-6 mb-4">
      <div class="product-card-alt">
        <?php echo $imgID ? ip_img($imgID) : "" ?>
        <div class="s3-content">
          <h2 class="s3-title"><?php echo $p->name ?></h2>
          <p><?php echo $p->caption ?></p>
        </div>
      </div>
      <?php echo strlen($p->btnText) >= 5 ? sprintf("<div class='bbtn arrow-right my-3' data-scrollto='contacts'>%s</div>", $p->btnText) : ""; ?>
    </div>
  <?php }
}

//============================ PRODUCT ADVANCED LIST
function ip_product_advanced_list($list)
{
  echo '<ul class="cb-list">';
  foreach ($list as $p) {
    if ($p !== "")
      echo "<li>$p</li>";
  }
  echo '</ul>';
}

//============================ CONTACTS TYPE LIST
function contacts_type_list()
{
  if (get_ip_field('s4_2') == 1) : echo '<option value="WhatsApp">WhatsApp</option>';
  endif;
  if (get_ip_field('s4_3') == 1) : echo '<option value="Telegram">Telegram</option>';
  endif;
  if (get_ip_field('s4_4') == 1) : echo '<option value="Viber">Viber</option>';
  endif;
  if (get_ip_field('s4_11') == 1) : echo '<option value="Email">Email</option>';
  endif;
  if (get_ip_field('s4_5') == 1) : echo '<option value="' . __('Phone', 'ip') . '">' . __("Phone", "ip") . '</option>';
  endif;
}

// =================================
//================================== TEMPLATE PARTS
//==================================
function confirm($fields)
{
  $valid = true;
  foreach ($fields as $f) {
    if (strlen(get_ip_field($f)) === 0) $valid = false;
  }
  return $valid;
}

//================================== HEADER NAV
function ip_header_nav()
{

  $products = json_decode(wp_unslash(get_ip_field('products')));

  echo '<div class="col-12"><div class="row nav">';

  /* 
  Здесь ссылка ведёт на блок продуктов ИЛИ при vip-статусе и активном каталоге - на каталог
  Статус определяется по запросу меты [_ip_user_level] текущего пользователя
  */
  if (is_user_vip() && get_catalog_status() && is_front_page()) {
    echo sprintf(
      '<div class="col-12 col-md-6 col-lg-3 my-2"><a class="bbtn small gray" href="/ip-catalog">%s</a></div>',
      get_nav_title("catalog_2", __("Catalog", 'ip'))
    );
  } else {
    if (is_array($products) && count($products) > 0 && is_front_page()) {
      echo sprintf(
        '<div class="col-12 col-md-6 col-lg-3 my-2"><a href="/#price" data-scrollto="price" class="bbtn small gray">%s</a></div>',
        get_nav_title("s23_title", __("Price", 'ip'))
      );
    } else {
      echo sprintf(
        '<div class="col-12 col-md-6 col-lg-3 my-2"><a href="/" class="bbtn small gray">%s</a></div>',
        __("Home", "ip")
      );
    }
  }

  if (confirm(['s3_1', 's3_2', 's3_3'])) {
    echo sprintf(
      '<div class="col-12 col-md-6 col-lg-3 my-2"><a data-scrollto="about-company" class="bbtn small gray" href="/#about-company">%s</a></div>',
      get_nav_title("s3_title", __("About company", 'ip'))
    );
  }

  if (confirm(['s2_1-1', 's2_1-2', 's2_1-3', 's2_2-1', 's2_2-2', 's2_2-3', 's2_3-1', 's2_3-2', 's2_3-3'])) {
    echo sprintf(
      '<div class="col-12 col-md-6 col-lg-3 my-2"><a href="/#advantage" data-scrollto="advantage" class="bbtn small gray">%s</a></div>',
      get_nav_title("s33_title", __("Our advantages", 'ip'))
    );
  }

  if (confirm(['s4_0', 's4_1', 's4_6', 's4_7', 's4_8'])) {
    echo sprintf(
      '<div class="col-12 col-md-6 col-lg-3 my-2"><a href="/#contacts" data-scrollto="contacts" class="bbtn small gray">%s</a></div>',
      get_nav_title("s4_title", __("Contacts", 'ip'))
    );
  }

  echo '</div></div>';
}

function get_nav_title($fieldName, $defaultTitle = "")
{
  $t = get_ip_field($fieldName);
  if ($t === "[get text]" || strlen($t) < 5) {
    return $defaultTitle;
  } else {
    return $t;
  }
}

//================================== CONTACTS
function ip_phone()
{
  $confirmedFields = ['s1_2'];
  $confirm = confirm($confirmedFields);
  if ($confirm && get_ip_field('s1_2') !== "+") :
  ?>
    <a href="tel:<?php echo get_ip_field('s1_2') ?>">
      <i class="bi bi-phone"></i>
      <span><?php echo get_ip_field('s1_2') ?></span>
    </a>
  <?php
  endif;
}
function ip_mail()
{
  $confirmedFields = ['s1_3'];
  $confirm = confirm($confirmedFields);
  if ($confirm) :
  ?>
    <a href="mailto:<?php echo get_ip_field('s1_3') ?>">
      <i class="bi bi-envelope"></i>
      <span><?php echo get_ip_field('s1_3') ?></span>
    </a>
  <?php
  endif;
}
function ip_address()
{
  $confirmedFields = ['s1_4'];
  $confirm = confirm($confirmedFields);
  if ($confirm) :
  ?>
    <div class="w100p" style="margin:2px 10px">
      <i class="bi bi-geo-alt"></i>
      <span class="address"><?php echo get_ip_field('s1_4') ?></span>
    </div>
  <?php
  endif;
}

//================================== FS_1
function fs_1()
{
  $confirmedFields = ['s2_1', 's2_2'];
  if (confirm($confirmedFields)) :
  ?>
    <section class="fs">
      <div class="container">
        <h1><?php the_ip_field('s2_1') ?></h1>
        <p><?php echo nl2br(get_ip_field('s2_2')) ?></p>
      </div>
    </section>
  <?php endif;
}

//================================== FS_2
function fs_2()
{
  $confirmedFields = ['s2_3', 's2_4', 's2_5', 's2_6', 's2_7', 's2_8', 's2_9', 's2_10', 's2_11', 's2_12', 's2_13', 's2_14'];
  $confirm = confirm($confirmedFields);
  if ($confirm) :
  ?>
    <section class="fs-2">
      <div class="container">
        <div class="row">
          <?php
          for ($i = 0; $i < count($confirmedFields) / 3; $i++) {
            $n = 3 * $i;
            $n_1 = 's2_' . ($n + 3);
            $n_2 = 's2_' . ($n + 4);
            $n_3 = 's2_' . ($n + 5);
          ?>
            <div class="col-12 col-md-6 col-lg-3">
              <div class="img-wrapper">
                <img src="<?php echo ip_img_url(get_ip_field($n_3), 'medium') ?>" />
              </div>
              <h3 class="title"><?php the_ip_field($n_1) ?></h3>
              <p><?php echo nl2br(get_ip_field($n_2)) ?></p>
            </div>
          <?php
          }
          ?>
        </div>
      </div>
    </section>
  <?php
  endif;
}

//=================================== S2 - About Company
function s2()
{
  $confirmedFields = ['s3_1', 's3_2', 's3_3'];
  if (confirm($confirmedFields)) :
  ?>
    <section class="s2" id="about-company">
      <div class="container">
        <div class="row">
          <div class="col-12 s2-header" style="background-image: url(<?php echo ip_img_url(get_ip_field('s3_3'), 'large') ?>);"></div>
          <div class="col-12 mb-4">
            <h1 class="decor-title"><?php the_ip_field('s3_1') ?></h1>
          </div>
          <div class="col-12">
            <p><?php echo nl2br(get_ip_field('s3_2')) ?></p>
          </div>
        </div>
      </div>
    </section>
  <?php
  endif;
}

//=================================== S3 - Video
function video()
{
  $confirmedFields = ['s3_4', 's3_5', 's3_6'];
  if (confirm($confirmedFields)) :
  ?>
    <section class="video">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-12 col-lg-5">
            <div class="title"><?php the_ip_field('s3_4') ?></div>
            <p><?php the_ip_field('s3_5') ?></p>
          </div>
          <div class="col-12 col-lg-7 frame">
            <?php ip_video(get_ip_field('s3_6')) ?>
          </div>
        </div>
      </div>
    </section>
  <?php
  endif;
}

//=================================== S3 - Advantages
function s3_advantages()
{
  $confirmedFields = ['s2_1-1', 's2_1-2', 's2_1-3', 's2_2-1', 's2_2-2', 's2_2-3', 's2_3-1', 's2_3-2', 's2_3-3'];
  if (confirm($confirmedFields)) :
  ?>
    <section id="advantage" class="s6">
      <div class="container">
        <div class="row">
          <?php
          for ($i = 1; $i <= 3; $i++) {
          ?>
            <div class="col-12 col-md-6 col-lg-4">
              <div class="wrapper">
                <div class="img-wrapper" style="background-image: url(<?php echo ip_img_url(get_ip_field("s2_$i-3"), 'medium') ?>)"></div>
                <div class="title"><?php echo get_ip_field("s2_$i-1") ?></div>
                <p><?php the_ip_field("s2_$i-2") ?></p>
              </div>
            </div>
          <?php
          }
          ?>
        </div>
      </div>
    </section>
  <?php
  endif;
}

//=================================== CONTACT FORM
function ip_contacts()
{
  $confirmedFields = ['s4_0', 's4_1', 's4_6', 's4_7', 's4_8'];
  if (confirm($confirmedFields)) :
  ?>
    <section id="contacts" class="s5">
      <div class="container">
        <h2 class="mb-5 text-center"><?php the_ip_field('s4_0') ?></h2>
        <div class="row">
          <div class="col-12 col-lg-5">
            <img src="<?php echo ip_img_url(get_ip_field('s4_8')) ?>" />
          </div>
          <div class="col-12 col-lg-7">
            <form class="form connect-form" action="">
              <div class="row">
                <div class="col-12 my-2">
                  <label>
                    <span><?php _e("Your name", "ip") ?>:</span>
                    <input required class="form-control" type="text" name="username" placeholder="<?php _e("Enter your name", "ip") ?>">
                  </label>
                </div>
                <div class="col-12 my-2">
                  <label>
                    <span><?php _e("Your phone", "ip") ?>:</span>
                    <input required class="form-control" type="tel" name="phone" placeholder="<?php _e("Enter your phone", "ip") ?>">
                  </label>
                </div>
                <div class="col-12 my-2">
                  <label>
                    <span><?php _e("Your email", "ip") ?>:</span>
                    <input required class="form-control" type="email" name="email" placeholder="<?php _e("Enter your email", "ip") ?>">
                  </label>
                </div>

                <?php
                if (get_ip_field('s4_9') == 1) { ?>
                  <div class="col-12 my-2">
                    <label>
                      <span><?php _e("Your message", "ip") ?>:</span>
                      <input class="form-control" type="text" name="message" placeholder="<?php _e("Enter your message", "ip") ?>">
                    </label>
                  </div>
                <? }
                ?>
                <div class="row">
                  <div class="col-12 my-2">
                    <div class="form-subjects">
                      <?php
                      foreach (json_decode(wp_unslash(get_ip_field('s4_6'))) as $s) {
                        echo "<label class='checkbox-label'><input class='form-check-input' type='checkbox' value='$s'>$s</input></label>";
                      }
                      ?>
                    </div>
                  </div>
                  <div class="col-12 col-lg-6 my-2">
                    <label>
                      <span><?php _e("How to contact you", "ip") ?>:</span>
                      <select size class="form-control" name="connect">
                        <?php contacts_type_list() ?>
                      </select>
                    </label>
                  </div>
                  <?php
                  if (get_ip_field('s4_10') == 1) { ?>
                    <div class="col-12 col-lg-6 my-2">
                      <label>
                        <span><?php _e("Promocode", "ip") ?>:</span>
                        <input class="form-control" type="text" name="promocode" placeholder="<?php _e("Enter promocode (if you have)", "ip") ?>">
                      </label>
                    </div>
                  <? }
                  ?>
                </div>

                <div class="col-12">
                  <button class="bbtn submit mt-3 w100p"><?php the_ip_field('s4_7') ?></button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
<?php
  endif;
}
