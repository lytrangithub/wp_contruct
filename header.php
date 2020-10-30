<?php 
/**
 * header.php
 *
 * The header for the theme.
 */
?>
<!DOCTYPE html>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title><?php wp_title( '', true, 'right' );?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <link rel="shortcut icon" href="<?php echo DEF_IMAGES; ?>/favicon.ico" />
    <script type="text/javascript">
        if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) {
            document.write('<script src="<?php echo DEF_VENDOR?>blazor/blazor.polyfill.min.js"><\/script>');
        }
    </script>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
   <!-- Header -->
    <div id="header">
        
        <div class="container">
            <?php 
                wp_nav_menu( array( 
                    'theme_location' => 'main-menu',
                    'fallback_cb' => false,
                    'container' => 'ul',
                    'container_id' => '',
                    'link_before' => '',
                    'link_after' => '',
                    'menu_class' => 'nav-menu'
                ));
            ?>
            <div class="search">
                <input type="text" class="input-search">
                <button class="btn btn-search">search</button>
            </div>
        </div>
    </div>
    <!-- //Header -->
    <div id="content">