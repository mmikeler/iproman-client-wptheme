<?php
global $o;
?>

<footer>
  <div class="bottom-bar">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-4 mt-2">
          <div class="d-flex flex-column">
            <?php

            if (get_ip_field('s1_5')) : ?>
              <img class="mb-3" width="170" src="<?php ip_img_url(get_ip_field('s1_5'), 'medium') ?>" />
            <?php endif;

            ip_phone($o);
            ip_mail($o);
            ?>
          </div>
        </div>
        <div class="col-12 col-md-8">
          <?php
          ip_header_nav($o);
          ip_social_list($o)
          ?>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="copyright"><?php echo site_name() . " " . get_ip_field('s5_1') ?></div>
  </div>

  <!-- форма обращения -->
  <div class="modal fade" id="modalCallback" tabindex="-1" aria-labelledby="modalCallback" aria-hidden="false">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><?php _e("New message", "ip") ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <form id="modalCallbackForm" class="form">
            <div class="mb-3">
              <label for="recipient-name" class="col-form-label"><?php _e("Your name", "ip") ?>:</label>
              <input required type="text" name="username" class="form-control" id="recipient-name">
            </div>
            <div class="mb-3">
              <label for="recipient-phone" class="col-form-label"><?php _e("Your phone", "ip") ?>:</label>
              <input required type="text" name="phone" class="form-control" id="recipient-phone">
            </div>
            <div class="mb-3">
              <label for="recipient-email" class="col-form-label"><?php _e("Your email", "ip") ?>:</label>
              <input required type="email" name="email" class="form-control" id="recipient-email">
            </div>
            <div class="mb-3">
              <label for="message-text" class="col-form-label"><?php _e("Your message", "ip") ?>:</label>
              <textarea required name="message" rows="4" class="form-control" id="message-text" placeholder="<?php _e("Enter your message", "ip") ?>"></textarea>
            </div>
            <input type="hidden" name="trigger" id="trigger">
            <input class="d-none" type="submit">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php _e("Close", "ip") ?></button>
          <button data-target="#modalCallbackForm" type="button" class="btn btn-success ajax-btn"><?php _e("Send", "ip") ?></button>
        </div>
      </div>
    </div>
  </div>

  <?php wp_footer() ?>
</footer>
</body>

</html>