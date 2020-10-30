<?php
/**
 * ----------------------------------------------------------------------------------------
 * Xác định các hằng số và các tập tin chức năng cần thiết
 * ----------------------------------------------------------------------------------------
 */
//Đường dẫn đến thư mục template
define('DEF_THEMEROOT', get_stylesheet_directory_uri());
//Đường dẫn đến thư mục hình ảnh
define('DEF_IMAGES', DEF_THEMEROOT . '/images/');
//Đường dẫn đến thư mục js
define('DEF_SCRIPTS', DEF_THEMEROOT . '/js/');
//Đường dẫn đến thư mục css
define('DEF_STYLE', DEF_THEMEROOT . '/css/');
//Đường dẫn đến thư mục vendor
define('DEF_VENDOR', DEF_THEMEROOT . '/vendor/');
//Đường dẫn vào thư mục template-parts
define('TEMPLATES_PARTS', 'template-parts/pages/content');
//Định nghĩa text domain để làm đa ngôn ngữ
define('TEXTDOMAIN', 'mytheme');
//Đường dẫn lưu file báo giá tạm
define('UPLOAD_TERM_DIR', ABSPATH.'wp-content/upload_term/');
