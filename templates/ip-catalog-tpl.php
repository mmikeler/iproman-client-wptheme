<?php

// Template name: ip-catalog-tpl

if (!get_catalog_status()) {
  wp_redirect(site_url());
  exit;
}
get_header();

$products = json_decode(wp_unslash(get_ip_field("catalog_items")));
/*
  product = {
    title: string,
    description: string,
    price: string,
    price_caption: string,
    txt_btn: string,
    gallery: array(img_id, img_id)
  }
*/

?>

<div id="catalog" class="container">
  <div class="row mb-5">
    <div class="col-12">
      <h1 class="fs-1 text-center fw-bold mb-3"><?php the_ip_field("catalog_0") ?></h1>
      <p class="text-center w-75 m-auto"><?php the_ip_field("catalog_1") ?></p>
    </div>
  </div>
  <div id="catalog">
    <div class="row">
      <div class="col-12">

        <?php if ($products && count($products) > 0) {
          foreach ($products as $key => $p) {
        ?>
            <div class="product mb-5">
              <!-- контент -->
              <div class="product__caption">
                <div class="row">
                  <div class="col-12 col-md-7 col-lg-9 pr-3 pr-lg-4">

                    <? if ($p->gallery && count($p->gallery) > 0) : ?>
                      <!-- галерея -->
                      <div class="product__gallery float-none float-lg-start mb-md-4 mb-lg-0">
                        <div class="main-img" original="<?php echo ip_img_url($p->gallery[0], "full") ?>" style="background-image: url(<?php echo ip_img_url($p->gallery[0], "medium") ?>)"></div>
                        <div class="preview-list">
                          <?php
                          foreach ($p->gallery as $key => $img) {
                            if ($key > 0) :
                          ?>
                              <div class="pg-img" original="<? echo ip_img_url($img, "full") ?>" style="background-image: url(<? echo ip_img_url($img, "small") ?>);"></div>
                          <?php endif;
                          } ?>
                        </div>
                      </div>
                      <!-- //галерея -->
                    <?php endif; ?>

                    <div class="pc-title mb-3"><?php echo $p->title ?></div>
                    <div class="pc-content"><?php echo $p->description ?></div>

                    <?php if (strlen($p->description) > 400) : ?>
                      <div class="pc-btn mt-3 rm-btn">
                        <div class="text-close"><?php _e("Read more", "ip") ?></div>
                        <div class="text-open"><?php _e("Read less", "ip") ?></div>
                      </div>
                    <?php endif; ?>

                  </div>
                  <div class="col-12 col-md-5 col-lg-3 my-3 bl">
                    <div class="pc-coster d-flex flex-column align-items-center justify-content-center h-100">
                      <div class="pc-cost"><?php echo $p->price ?></div>
                      <div class="pc-cost-text fw-bold"><?php echo $p->price_caption ?></div>

                      <?php
                      ## Кнопка заказа показывается только когда указан имейл приёма заявок - s4_1
                      if (get_ip_field("s4_1") !== "") :
                        $premessage = sprintf('%s %s. %s', __("Hi, I'm interested in", 'ip'), $p->title, __("I have some questions. Please contact me for details", 'ip'));
                      ?>
                        <div class="pc-btn mt-5 w-100" data-bs-toggle="modal" data-bs-target="#modalCallback" data-bs-premessage="<?php echo $premessage ?>" data-bs-title="<?php echo __("New order", "ip") . " " . $p->title ?>:" data-bs-pname="<?php echo $p->title ?>">
                          <?php $t = strlen($p->txt_btn) > 0 ? $p->txt_btn : __("Order", "ip");
                          echo $t; ?>
                        </div>
                      <?php endif; ?>

                    </div>
                  </div>
                </div>
              </div>
              <!-- //контент -->
            </div>

        <?php }
        } else {
          echo "<div class='catalog-empty'>" . __("Catalog is empty", "ip") . "</div>";
        } ?>

      </div>
    </div>
  </div>
</div>

<!-- слайдшоу -->
<div id="ip-carousel" class="carousel slide" data-bs-ride="carousel">
  <button type="button" class="btn-close" aria-label="Close"></button>
  <div class="carousel-inner"></div>
  <button class="carousel-control-prev" type="button" data-bs-target="#ip-carousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden"><?php _e("Prev", "ip") ?></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#ip-carousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden"><?php _e("Next", "ip") ?></span>
  </button>
</div>

<?php get_footer() ?>