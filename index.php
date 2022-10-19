<?php

$confirmedFields = ['s1_1', 's1_2', 's1_3', 's1_4'];

if (!confirm($confirmedFields)) {
    get_template_part('templates/default');
} else {
    get_header();

    the_main_page_content();

    get_footer();
}
