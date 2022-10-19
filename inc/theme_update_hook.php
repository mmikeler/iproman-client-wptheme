<?php

require get_template_directory() . '/plugin-update-checker-master/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://biz.host/ip-update/?action=get_metadata&slug=iproman',
    get_template_directory(), //Full path to the main plugin file or functions.php.
    'iproman'
);
