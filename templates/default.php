<!DOCTYPE html>
<html lang="<?php echo isset($_COOKIE["ip_lang"]) ? $_COOKIE["ip_lang"] : get_default_locale(true) ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php ip_favicon() ?>
    <title><?php _e('iProMan.Business site') ?></title>

    <style>
        body {
            padding: 0;
            margin: 0;
        }

        .container {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            display: flex;
            background: url('<?php echo get_stylesheet_directory_uri() ?>/img/Logo.png') no-repeat center / auto;
        }

        .footer {
            text-align: center;
            margin-top: auto;
            width: 100%;
            margin-bottom: 30px;
        }

        .footer a {
            color: #01DB5E;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="footer">
            <a href="https://biz.host"><?php _e('iProMan.Business site') ?></a>
        </div>
    </div>
</body>

</html>