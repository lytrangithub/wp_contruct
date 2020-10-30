<?php
/**
 * functions.php
 * Auth: LuyenNguyen
 * Định nghĩa các chức năng của template
 */
require get_template_directory() . '/inc/constants.php';
require get_template_directory() . '/inc/libs.php';
require get_template_directory() . '/inc/query-data.php';
//require get_template_directory() . '/inc/frontend/html-to-word.php';
//require get_template_directory() . '/inc/custom-field-taxonomy.php';
//Get current locale
//echo get_locale();
/**
 * ----------------------------------------------------------------------------------------
 * Thiết lập đường dẫn site cho js
 * ----------------------------------------------------------------------------------------
 */
if (!function_exists('fl_header_extra')) {
    function fl_header_extra()
    {
        echo '<script>
            var site_url = "' . site_url() . '";
            var ajaxUrl = "' . site_url() . '/wp-admin/admin-ajax.php?action=";
        </script>';
    }
    add_action('wp_head', 'fl_header_extra');
}

/**
 * ----------------------------------------------------------------------------------------
 * Set up theme default and register various supported features.
 * ----------------------------------------------------------------------------------------
 */
if (!function_exists('fl_setup')) {
    function fl_setup() {
        /**
         * Tạo đa ngôn ngữ cho theme
         */
        //load_theme_textdomain(TEXTDOMAIN, get_stylesheet_directory() . "/languages");
        /**
         * THêm hỗ trợ tự động feed links.
         */
        add_theme_support('automatic-feed-links');
        /**
         * Thêm hỗ trợ cho post thumbnails.
         */
        add_theme_support('post-thumbnails');
        /*
        *Thêm hỗ trợ cho trang homepage single post image sizes
        */
        //add_image_size('vwsaigon-post-image', 2000, 0, true);
        /*
        * Thêm chức năng post format
        */
        add_theme_support('post-formats',
            array(
                'image',
                'video',
                'gallery',
                'quote',
                'link'
            )
        );
        //đăng ký menu
        register_nav_menus ( array (
            'main-menu' => __ ( 'Main Menu', TEXTDOMAIN ),
            'footer-menu' => __ ( 'Footer Menu', TEXTDOMAIN ),
            'plan-other-menu' => __ ( 'Plan other menu', TEXTDOMAIN )
        ));
    }
    add_action ( 'after_setup_theme', 'fl_setup' );
}

/**
 * ----------------------------------------------------------------------------------------
 * Thêm class active đến menu
 * ----------------------------------------------------------------------------------------
 */
if (!function_exists('special_nav_class')) {
    function special_nav_class($classes, $item)
    {
        global $post;
        if (in_array('current-post-ancestor', $classes) || in_array('current-page-ancestor', $classes) || in_array('current-menu-item', $classes)) {
            $classes[] = 'active ';
        }
        return $classes;
    }
    add_filter('nav_menu_css_class', 'special_nav_class', 10, 2);
}

/**
 * ----------------------------------------------------------------------------------------
 * Tạo sidebar cho theme
 * ----------------------------------------------------------------------------------------
 */
if (!function_exists('fl_widget_init')) {
    function fl_widget_init()
    {
        if (function_exists('register_sidebar')) {
            //sidebar copy right
            register_sidebar( array(
                'name' => 'Copy right',
                'id'            => 'sidebar-copyright',
                'before_widget' => '',
                'after_widget'  => '',
                'before_title'  => '<!-- ',
                'after_title'   => ' -->',
            ));
            //sidebar Header right
            register_sidebar( array(
                'name' => 'Header right',
                'id'            => 'sidebar-headeright',
                'before_widget' => '',
                'after_widget'  => '',
                'before_title'  => '<!-- ',
                'after_title'   => ' -->',
            ));
            // tạo widget footer-left cho footer
            register_sidebar( array(
                'id'            => 'left',
                'name'          => __( 'Footer Left' ),
                'description'   => __( 'A short description of the sidebar.' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ));
            // tạo widget footer-middle cho footer
            register_sidebar( array(
                'name' => 'Footer Main Middle',
                'id'            => 'footer-middle',
                'before_widget' => '',
                'after_widget'  => '',
                'before_title'  => '<!-- ',
                'after_title'   => ' -->',
            ));
            // tạo widget footer-right cho footer
            register_sidebar( array(
                'name' => 'Footer Main Right',
                'id'            => 'footer-right',
                'before_widget' => '',
                'after_widget'  => '',
                'before_title'  => '<!-- ',
                'after_title'   => ' -->',
            ));
        }
    }
    add_action('widgets_init', 'fl_widget_init');
}

/*
* Hàm thực thi sau khi WordPress đã load xong trang
*/
if (!function_exists('fl_init')) {
    function fl_init() {
        //Bỏ đi những chức năng không sử dụng đến cho custom post type
        $support_diff = array(
            'excerpt',
            'comments',
            'trackbacks',
            'revisions',
            'custom-fields'
        );
        // //Tạo post type coach card
        // $coachcard_post_type_args = array(
        //     'post_type' => 'coachcard_cpt',
        //     'post_type_slug' => '',
        //     'support_diff' => $support_diff,
        //     'post_type_name' => 'Coach card',
        //     'post_type_singular_name' => 'Coach card',
        //     'menu_icon' => 'dashicons-clipboard',
        //     'is_public' => false
        // );
        // create_custom_post_type($coachcard_post_type_args);
        //Hidden menu bar trang frontend sau khi đăng nhập vào trang admin
        if (!is_admin()) {
            show_admin_bar(false);
        }
        //Call session
        // if(!session_id()) {
        //     session_start();
        // }
        //Xóa post type
        //unregister_post_type( 'ezloc_config_post_type_args' );
        //unregister_taxonomy('lang_translation_tax');

        // Tạo custom post type cho slide
        $slideshow_post_type_args = array(
            'post_type' => 'slideshow_cpt',
            'post_type_slug' => '',
            'support_diff' => $support_diff,
            'post_type_name' => 'Slide show',
            'post_type_singular_name' => 'Slide show',
            'menu_icon' => 'dashicons-clipboard',
            'is_public' => false
        );
        create_custom_post_type($slideshow_post_type_args);
    }
    add_action('init', 'fl_init', 0);
}

/**
 * ----------------------------------------------------------------------------------------
 * Lấy thông tin page theo page template
 * ----------------------------------------------------------------------------------------
 */
if (!function_exists('get_page_by_page_template')) {
    function get_page_by_page_template($page_template = null) {
        if (!$page_template) return;
        $args = array(
            'meta_key' => '_wp_page_template',
            'meta_value' => $page_template
        );
        $page = get_pages($args);
        if (empty($page[0])) {
            return;
        }
        $page = $page[0];
        $url = get_page_link($page->ID);
        $page -> url = $url;
        return $page;
    }
}

/** 
 * ----------------------------------------------------------------------------------------
 * Thêm js và css đến thẻ head
 * ----------------------------------------------------------------------------------------
 */
if (!function_exists('fl_scripts')) {
    function fl_scripts() {
        //load css
        wp_enqueue_style('def-css-bootstrap.min', DEF_VENDOR. 'bootstrap/bootstrap.min.css');
        wp_enqueue_style('def-css-font-awesome.min', DEF_STYLE. 'font-awesome.min.css');
        wp_enqueue_style('def-css-select2.min', DEF_VENDOR. 'select2/css/select2.min.css');
        wp_enqueue_style('def-css-style', DEF_STYLE. 'style.css');
        wp_enqueue_style('def-css-my-style', DEF_STYLE. 'my-style.css');
        wp_enqueue_style('def-css-fl-dialog', DEF_STYLE. 'fl-dialog.css');
        //load javascript
        wp_enqueue_script('def-js-jquery-3.3.1', DEF_VENDOR. 'jquery/jquery-3.3.1.min.js', array());
        wp_enqueue_script('def-js-select2.full.min', DEF_VENDOR. 'select2/js/select2.full.min.js', array());
        wp_enqueue_script('def-js-bootstrap.min', DEF_VENDOR. 'bootstrap/bootstrap.min.js', array());
        wp_enqueue_script('def-js-bootbox.min', DEF_VENDOR. 'bootbox/bootbox.min.js', array());
        wp_enqueue_script('def-js-libs', DEF_SCRIPTS. 'libs.js', array());
        wp_enqueue_script('def-js-fl-http', DEF_SCRIPTS. 'fl-http.js', array());
        // wp_enqueue_script('def-js-common', DEF_SCRIPTS. 'common.js', array());
    }
    add_action ( 'wp_enqueue_scripts', 'fl_scripts' );
}