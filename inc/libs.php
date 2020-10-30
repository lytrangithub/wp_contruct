<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * asign data for insert, update
 * @param array $tbl_structure
 * @param array $data
 * @return string|boolean
 */
if (!function_exists('assign_data')) {
    function assign_data($tbl_structure, $data) {
        if(!$tbl_structure || !$data) return;
        $data=convert_object_to_array($data);
        foreach ($tbl_structure as $key => $val) {
            if (isset($data[$key])) {
                $tbl_structure[$key] = trim($data[$key]);
                if (is_blank($tbl_structure[$key])) {
                    $tbl_structure[$key] = $val;
                }
            } else { 
                unset($tbl_structure[$key]);
            }
        }
        return $tbl_structure;
    }
}
/**
 * convert object to array
 * @param array $data
 * @return multitype:|unknown
 */
if (!function_exists('convert_object_to_array')) {
    function convert_object_to_array($data) {
        if (is_object($data)) {
            $data = get_object_vars($data);
        }
        if (is_array($data)) {
            return $data;
        }
        else {
            return $data;
        }
    }
}
/**
 * Function for basic field validation (present and neither empty nor only white space)
 * @param unknown $var
 * @return boolean
 */
if (!function_exists('is_blank')) {
    function is_blank($var){
        return (!isset($var) || trim($var)==='');
    }
}
/**
 * return json result using ajax
 * @param string $status
 * @param unknown $mess
 * @param unknown $data
 * @param number $total_row
 * @return string
 */
if (!function_exists('return_json_result')) {
    function return_json_result($status = false, $mess = null, $data = null, $total_row = 0){
        $result["status"]=$status;
        $result["mess"]=$mess;
        $result["data"]=$data;
        $result["total_row"]=$total_row;
        return json_encode($result);
    }
}

/**
 * Get the folder name according to the path
 * @param string $base_dir
 * @param int $level
 * @return int[][]|string[][]|NULL[][]|unknown[][]
 */
if (!function_exists('expand_directories_matrix')) {
    function expand_directories_matrix($base_dir, $level = 0) {
        $directories = array();
        foreach(scandir($base_dir) as $file) {
            if($file == '.' || $file == '..') continue;
            $dir = $base_dir.DIRECTORY_SEPARATOR.$file;
            if(is_dir($dir)) {
                $directories[]= array(
                'level' => $level,
                'name' => $file,
                'path' => $dir,
                    'children' => expand_directories_matrix($dir, $level +1)
                );
            }
        }
        return $directories;
    }
}

/**
 * Get the directory list
 * @param array $list
 * @param array $parent
 * @param string $prefix
 */
if (!function_exists('show_directories_list')) {
    function show_directories_list($list, $parent = array(), &$output = array(), $sepector = "/")
    {
        foreach ($list as $directory){
            $parent_name = count($parent) ? $parent['name'] : '';
            $prefix = str_repeat($sepector, $directory['level']);
            $folder_name = $parent_name."$prefix{$directory['name']}";
            array_push($output, $folder_name);
            if(count($directory['children'])){
                // list the children directories
                show_directories_list($directory['children'], $directory, $output, $sepector);
            }
        }
        return $output;
    }
}

/**
 * Get timestamp to day
 *
 * @return string
 */
if (!function_exists('timestamp')) {
    function timestamp() {
        $date = date('Y-m-d H:i:s');
        return strtotime($date);
    }
}

/**
* Get the file extension
*
* @param string $fileName
* @return string
*/
if (!function_exists('get_file_extension')) {
    function get_file_extension($file_name) {
        return pathinfo($file_name, PATHINFO_EXTENSION);
    }
}

/**
* Replace apostrophe in string
*
* @param string $str
* @return string
*/
if (!function_exists('replace_apostrophe')) {
    function replace_apostrophe($str) {
        //$plain_text = preg_replace("/\\\\+'/", "'",$plain_text);
        $str=preg_replace('/(\\\\\\\\\\\\\')|(\\\\\')/',"'" ,$str);
        $str=preg_replace('/(\\\\\\\\\\\\\")|(\\\\\")/', '"' ,$str);
        return $str;
    }
}

/**
* Check for comparison values that exist in the array
*
* @param array $arr ex:     [1,3,5,...]
* @param object $value      comparison value exists in an array
* @param boolean $is_index  Get index in the array
* @return boolean
*/
if (!function_exists('check_value_exist_in_array')) {
    function check_value_exist_in_array($arr, $value, $is_index = false) {
        if (!$arr || !$value) {
            return null;
        }
        if($is_position) {
            return array_search($value, $arr);
        } else if(in_array($value, $arr)) {
            return true;
        }
        return null;
    }
}

/**
* Get elements in arrays by key and value
*
* @param array $array
* @param string $index  key in array
* @param string $value  value in array
* @return boolean
*/
if (!function_exists('search_results_array')) {
    function search_results_array($array, $index, $value) {
        if (!$array || is_blank($index) || is_blank($value)) {
            return null;
        }
        foreach($array as $arrayInf) {
            if(isset($arrayInf[$index]) && $arrayInf[$index] == $value) {
                return $arrayInf;
            }
        }
        return null;
    }
}

/**
* Check email
*
* @param string $email  Email address
* @return boolean
*/
if (!function_exists('check_validate_email')) {
    function check_validate_email($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }
}

/**
 * Tạo post type
 * $data = array(
 *    'post_type' => tên post type,
 *    'post_type_slug' => slug post type,
 *    'taxonomy_cat' => danh mục chính cho post type
 *    'taxonomy_list' => danh sách taxonomy hiện thi trong post type(array)
 *    'post_type_name' => //Tên post type dạng số nhiều,
 *    'post_type_singular_name' => //Tên post type dạng số ít,
 *    )
 */
if ( ! function_exists( 'create_custom_post_type' ) ) {
    function create_custom_post_type($data = array(), $text_domain = 'ezloc')
    {
        if(!$data) return;
        if(empty($data['post_type'])) return;
        /*
         * Biến $label để chứa các text liên quan đến tên hiển thị của Post Type trong Admin
         */
        //__( $data['post_type_name'], $text_domain)
        $label = array(
            'name' => $data['post_type_name'], //Tên post type dạng số nhiều
            'singular_name' => __( $data['post_type_singular_name'], $text_domain ) //Tên post type dạng số ít
        );
        $rewrite = array(
            'slug'                  => (isset($data['taxonomy_cat'])) ? __($data['post_type_slug'].'/%'.$data['taxonomy_cat'].'%','slug', $text_domain) : $data['post_type_slug'], //Slug của trang chi tiet
            'with_front'            => false,
            'pages'                 => true,
            'feeds'                 => true,
        );
        $support_diff = (isset($data['support_diff'])) ? $data['support_diff'] : null;
        $support = array(
            'title',
            'editor',
            'excerpt',
            'author',
            'thumbnail',
            'comments',
            'trackbacks',
            'revisions',
            'custom-fields'
        );
        if($support_diff)
            $support = array_diff($support, $support_diff);
        $args = array(
            'labels' => $label, //Gọi các label trong biến $label ở trên
            'description' => __( (!empty($data['desc'])) ? $data['desc'] : '', $text_domain), //Mô tả của post type
            'supports' => $support, //Các tính năng được hỗ trợ trong post type
            'taxonomies' => (isset($data['taxonomy_list'])) ? $data['taxonomy_list'] : array() , //Các taxonomy được phép sử dụng để phân loại nội dung
            'hierarchical' => (empty($data['is_hierarchical'])) ? false : true, //Cho phép phân cấp, nếu là false thì post type này giống như Post, true thì giống như Page
            'public' => (!isset($data['is_public']) || $data['is_public']) ? true : false, //Kích hoạt post type: false sẽ ẩn Permalink khi tạo bài viết
            'show_ui' => true, //Hiển thị khung quản trị như Post/Page
            'show_in_menu' => true, //Hiển thị trên Admin Menu (tay trái)
            'show_in_nav_menus' => true, //Hiển thị trong Appearance -> Menus
            'show_in_admin_bar' => true, //Hiển thị trên thanh Admin bar màu đen.
            'menu_position' => 5, //Thứ tự vị trí hiển thị trong menu (tay trái)
            'menu_icon' => (empty($data['menu_icon'])) ? '' : $data['menu_icon'], //Đường dẫn tới icon sẽ hiển thị
            'can_export' => true, //Có thể export nội dung bằng Tools -> Export
            'has_archive' => true, //Cho phép lưu trữ (month, date, year)
            'exclude_from_search' => (empty($data['is_from_search'])) ? false : true, //Loại bỏ khỏi kết quả tìm kiếm
            'publicly_queryable' => true, //Hiển thị các tham số trong query, phải đặt true
            'capability_type' => 'post', //
            'rewrite' => $rewrite,
            'publicly_queryable'  => true,
            'query_var'           => true,
            'capabilities' => array(
                'create_posts' => (!isset($data['is_create_posts']) || $data['is_create_posts']) ? true : false, // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
            ),
            'map_meta_cap' => (empty($data['is_map_meta_cap'])) ? true : false, // Set to `false`, if users are not allowed to edit/delete existing posts
        );
        register_post_type($data['post_type'], $args);
    }
}

/**
 * Tạo một taxonomy(danh mục)
 * $data = array(
 *    'post_type' => 'ten post type',
 *    'taxonomy' => 'ten taxonomy',
 *    'post_type_slug' => 'slug post type',
 *    'taxonomy_name' => 'tên taxonomy số nhiều',
 *    'taxonomy_singular_name' => 'tên taxonomy số ít',
 *    'desc' => 'Mô tả cho taxonomy',
 *    'is_hierarchical' => 'boolean' => true: cho phép hiển thị taxonomy này khi tạo mới bài viết, ngược lại không hiển thị
 );
 */
if ( ! function_exists( 'create_custom_taxonomy' ) ) {
    function create_custom_taxonomy($data = array(), $text_domain = 'fl')
    {
        if(!$data) return;
        if(empty($data['post_type']) || empty($data['taxonomy'])) return;
        $labels = array(
            //'name'                       => _x( $data['taxonomy_name'], $text_domain ),
            'name'                       => __( (empty($data['taxonomy_name'])) ? "" : $data['taxonomy_name'], $text_domain ),
            'singular_name'              => __( (empty($data['taxonomy_singular_name'])) ? "" : $data['taxonomy_singular_name'], $text_domain ),
            'menu_name'                  => __( (empty($data['taxonomy_name'])) ? "" : $data['taxonomy_name'], $text_domain ),
            'all_items'                  => __( 'All Items', $text_domain ),
            'parent_item'                => __( 'Parent Item', $text_domain ),
            'parent_item_colon'          => __( 'Parent Item:', $text_domain ),
            'new_item_name'              => __( 'New Item Name', $text_domain ),
            'add_new_item'               => __( 'Add New Item', $text_domain ),
            'edit_item'                  => __( 'Edit Item', $text_domain ),
            'update_item'                => __( 'Update Item', $text_domain ),
            'view_item'                  => __( 'View Item', $text_domain ),
            'separate_items_with_commas' => __( 'Separate items with commas', $text_domain ),
            'add_or_remove_items'        => __( 'Add or remove items', $text_domain ),
            'choose_from_most_used'      => __( 'Choose from the most used', $text_domain ),
            'popular_items'              => __( 'Popular Items', $text_domain ),
            'search_items'               => __( 'Search Items', $text_domain ),
            'not_found'                  => __( 'Not Found', $text_domain ),
            'no_terms'                   => __( 'No items', $text_domain ),
            'items_list'                 => __( 'Items list', $text_domain ),
            'items_list_navigation'      => __( 'Items list navigation', $text_domain ),
        );
        $rewrite = array(
            'slug'                       => _x($data['post_type_slug'],'slug', $text_domain),
            'with_front'                 => true,
            'hierarchical'               => true,
        );
        
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => (!isset($data['is_hierarchical']) || $data['is_hierarchical']) ? true : false,//true: cho phép hiển thị chọn taxonomy khi tạo post type
            'public'                     => (!isset($data['is_public']) || $data['is_public']) ? true : false,
            'show_ui'                    => (!isset($data['show_ui'])) ? true : false,
            'show_admin_column'          => (!isset($data['show_admin_column'])) ? true : false,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'rewrite'                    => (empty($data['post_type_slug'])) ? true : $rewrite,
        );
        register_taxonomy($data['taxonomy'], array( $data['post_type'] ), $args );
    }
}

/*
 * Tạo đường dẫn cho custom post type
 * @param string $permalink
 * @param array $post
 * @param string $post_type
 * @param string $taxonomy
 * @param string $text_domain
 */
function fl_custom_post_type_link($permalink = null, $post = null, $post_type = null, $taxonomy = null, $text_domain = '')
{
    if(!$permalink || !$post || !$post_type || !$taxonomy) return;

    if ( $post_type !== $post->post_type ) {
        return $permalink;
    }
    // Abort early if the placeholder rewrite tag isn't in the generated URL.
    if ( false === strpos( $permalink, '%' ) ) {
        return $permalink;
    }

    $terms = get_the_terms( $post->ID, $taxonomy );

    if ( ! empty( $terms ) ) {
        if ( function_exists( 'wp_list_sort' ) ) {
            $terms = wp_list_sort( $terms, 'term_id', 'ASC' );
        } else {
            usort( $terms, '_usort_terms_by_ID' );
        }
        $category_object = apply_filters( 'fl_custom_post_type_link_cat', $terms[0], $terms, $post );

        $category_object = get_term( $category_object, $taxonomy );
        $slug     = $category_object->slug;
        if ( $category_object->parent ) {
            $ancestors = get_ancestors( $category_object->term_id,$taxonomy );
            foreach ( $ancestors as $ancestor ) {
                $ancestor_object = get_term( $ancestor, $taxonomy );
                $slug     = $ancestor_object->slug . '/' . $slug;
            }
        }
    } else {
        // If no terms are assigned to this post, use a string instead (can't leave the placeholder there)
        $slug = _x( 'khong-phan-loai', 'slug', $text_domain );
    }
    $permalink = str_replace( '%'.$taxonomy.'%', $slug, $permalink );
    return $permalink;
}

/*
 * Lấy danh sách bài viết theo taxonomy
 */
function list_posts_by_taxonomy( $post_type, $taxonomy, $get_terms_args = array(), $wp_query_args = array() ){
    $tax_terms = get_terms( $taxonomy, $get_terms_args );
    if( $tax_terms ){
        foreach( $tax_terms  as $tax_term ){
            $query_args = array(
                'post_type' => $post_type,
                "$taxonomy" => $tax_term->slug,
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'ignore_sticky_posts' => true
            );
            $query_args = wp_parse_args( $wp_query_args, $query_args );

            $my_query = new WP_Query( $query_args );
            if( $my_query->have_posts() ) { ?>
                <h2 id="<?php echo $tax_term->slug; ?>" class="tax_term-heading"><?php echo $tax_term->name; ?></h2>
                <ul>
                <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
                    <li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
                <?php endwhile; ?>
                </ul>
                <?php
            }
            wp_reset_query();
        }
    }
}
/**
 * Lấy id file upload
 * @param unknown $file: $_FILES['file']
 * @param number $post_id
 * @param string $set_as_featured
 */
function upload_file_action( $file, $post_id = 0 , $set_as_featured = false ) {
    if(!$file) return;
    $upload = wp_upload_bits( $file['name'], null, file_get_contents( $file['tmp_name'] ) );
    $wp_filetype = wp_check_filetype( basename( $upload['file'] ), null );
    $wp_upload_dir = wp_upload_dir();
    $attachment = array(
        'guid' => $wp_upload_dir['baseurl'] .'/'. _wp_relative_upload_path( $upload['file'] ),
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => preg_replace('/\.[^.]+$/', '', basename( $upload['file'] )),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment( $attachment, $upload['file'], $post_id );
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
    wp_update_attachment_metadata( $attach_id, $attach_data );
    if( $set_as_featured == true ) {
        update_post_meta( $post_id, '_thumbnail_id', $attach_id );
    }
    return $attach_id;
}

/**
 * Xóa bulk action theo post type
 * @param array|string $post_type: danh sách các mảng post type
 * @param string void
 */
if (!function_exists('remove_bulk_actions')) {
    function remove_bulk_actions($post_types = array()) {
        if (!$post_types) {
            return;
        }
        if (is_string($post_types)) {
            add_filter('bulk_actions-edit-'.$post_types, '__return_empty_array');
        } else {
            for ($i=0; $i < count($post_types); $i++) {
                add_filter('bulk_actions-edit-'.$post_types[$i], '__return_empty_array');
            }
        }
    }
}

/**
 * Get custom field by post id
 * @param string $field_type   mã custom filed được tạo từ custom field
 * @param int $post_id   mã post id
 * @param object đối tượng custom field
 */
if (!function_exists('get_custom_field_by_post_id')) {
    function get_custom_field_by_post_id($field_type = null, $post_id = null) {
        if (is_blank($field_type) || is_blank($post_id)) {
            return;
        }
        return get_field( $field_type, $post_id);
    }
}

/**
 * Decode file base64 string
 * @param array $file_data   Mảng chứa thông tin file bao gồm tên file, đuôi file, file base64 string
 * @param int $post_id   mã post id
 * @param object đối tượng custom field
 */
if (!function_exists('decode_file_base64_string')) {
    function decode_file_base64_string($file_data = array(), $random_name = false) {
        if (!$file_data) {
            return;
        }
        $file_name = (!empty($file_data['file_name'])) ? $file_data['file_name'] : '';
        $file_ext = (!empty($file_data['file_ext'])) ? $file_data['file_ext'] : '';
        $file_base64_string = (!empty($file_data['file_base64_string'])) ? $file_data['file_base64_string'] : '';
        if (!$file_name || !$file_ext || !$file_base64_string) {
            return;
        }
        list($extension, $file) = explode(';', $file_base64_string);
        list(, $file) = explode(',', $file);
        $file = str_replace(' ', '+', $file);
        $file_base64_decode = base64_decode($file);
        $new_file_name = date('YmdHis');
        if ($random_name) {
            $new_file_name = strtolower(fl_random_string(20));
        }
        $file_name = sprintf('%s.%s', $new_file_name, $file_ext);
        return array(
            'file_data' => $file_base64_decode,
            'file_name' => $file_name,
            'file_ext' => $file_ext
        );
    }
}

/**
* Random string
*
* @param int $length
* @param int $type
* @return string
*/
if (!function_exists('fl_random_string')) {
    function fl_random_string($length = 10, $type = 0) {
        $chars = "";
        if ($type == 0) {
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        } elseif ($type == 1) {
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        } elseif ($type == 2) {
            $chars = "0123456789";
        } elseif ($type == 3) {
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!\"\#$%&'()*+,-./:;<=>?@[\]^_`{|}~";
        }
        if (!$chars) {
            return '';
        }
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $char = $chars[rand(0, strlen($chars) - 1)];
            $randomString .= $char;
        }
        return $randomString;
    }
} 

/**
* Remove accented Vietnamese characters
*
* @param string $str
* @return string
*/
if (!function_exists('strip_unicode')) {
    function strip_unicode($str) {
        $unicode = array("à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă",
            "ằ","ắ","ặ","ẳ","ẵ","è","é","ẹ","ẻ","ẽ","ê","ề",
            "ế","ệ","ể","ễ",
            "ì","í","ị","ỉ","ĩ",
            "ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ",
            "ờ","ớ","ợ","ở","ỡ",
            "ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
            "ỳ","ý","ỵ","ỷ","ỹ",
            "đ",
            "À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă",
            "Ằ","Ắ","Ặ","Ẳ","Ẵ",
            "È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
            "Ì","Í","Ị","Ỉ","Ĩ",
            "Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ","Ờ","Ớ","Ợ","Ở","Ỡ",
            "Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
            "Ỳ","Ý","Ỵ","Ỷ","Ỹ",
            "Đ");
        $chr_replace=array("a","a","a","a","a","a","a","a","a","a","a",
            "a","a","a","a","a","a",
            "e","e","e","e","e","e","e","e","e","e","e",
            "i","i","i","i","i",
            "o","o","o","o","o","o","o","o","o","o","o","o",
            "o","o","o","o","o",
            "u","u","u","u","u","u","u","u","u","u","u",
            "y","y","y","y","y",
            "d",
            "A","A","A","A","A","A","A","A","A","A","A","A",
            "A","A","A","A","A",
            "E","E","E","E","E","E","E","E","E","E","E",
            "I","I","I","I","I",
            "O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O",
            "U","U","U","U","U","U","U","U","U","U","U",
            "Y","Y","Y","Y","Y",
            "D");
        return str_replace($unicode,$chr_replace,$str);
    }
}
/**
 * Check multibyte character
 *
 * @param srting $s
 * @return boolean
 */
if ( !function_exists('is_multibyte') ) {
    function is_multibyte($s)
    {
        return mb_strlen($s, 'utf-8') < strlen($s);
    }
}
/**
 * Count the total number of words in the string
 *
 * @param srting $string
 * @param int $split_length  The number of characters you want to split
 * @param bolean $is_mb_strlen  true: Count all characters in the string, false: Count all words in the string
 * @return boolean
 */
if ( !function_exists('fl_get_word_count') ) {
    function fl_get_word_count($string = '', $is_mb_strlen = true, $split_length = 1) {
        // mb_internal_encoding('UTF-8'); 
        // mb_regex_encoding('UTF-8');
        $string = trim($string);
        if (empty($string)) {
            return 0;
        }
        //Remove accented Vietnamese characters
        $string = strip_unicode($string);
        if ($is_mb_strlen) {
            $string = preg_replace('/\s+/', '',$string);
            return mb_strlen($string);
        }
        $split_length = ($split_length <= 0) ? 1 : $split_length;
        $mb_strlen = mb_strlen($string, 'utf-8');
        $array = array();
        $str_latin = $string;
        $total_chr = 0;
        for($i = 0; $i < $mb_strlen; $i += $split_length) {
            $chr = mb_substr($string, $i, $split_length);
            if ($chr) {
                $chr = trim($chr);
                if (is_multibyte($chr)) {
                    $str_latin = str_replace($chr, ' ', $str_latin);
                    $array[] = $chr;
                    $total_chr ++; 
                }
            }
        }
        // print_r(count($array));
        // echo '<br>';
        // echo $str_latin;
        // echo '<br>';
        if (!is_blank($str_latin)) {
            $str_latin = trim($str_latin);
            //Split characters by spaces and /
            $str_latin = preg_split('/[\s]+/', $str_latin);
            $total_chr = $total_chr + count($str_latin);
        }
        return $total_chr;
    }
}

/**
 * Get the words in the html
 *
 * @param srting $html_file_path  Path to save html file
 * @return string
 */
if ( !function_exists('fl_get_text_in_html') ) {
    function fl_get_text_in_html($html_file_path) {
        if ( !file_exists( $html_file_path ) ) {
           return;
        }
        $contents = file_get_contents($html_file_path, true);
        // Get rid of style, script etc
        $search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
                   '@<head>.*?</head>@siU',            // Lose the head section
                   '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
                   '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA
        );
        $contents = preg_replace($search, '', $contents); 
        $contents = strip_tags($contents);
        $contents = trim($contents);
        if (empty($contents)) {
            return '';
        }
        return $contents;
    }
}

/**
 * Download file
 *
 * @param srting $file_path   Path to read the file (ex: /Source/uploads/test.png)
 * @param srting $file_name   File name
 * @return string
 */
if ( !function_exists('fl_download_file') ) {
    function fl_download_file($file_path = null, $file_name = null, $is_delete = false) {
        if (!$file_path || !$file_name) {
            return;
        }
        if ( !file_exists( $file_path )) {
            return;
        }
        $file_ext = get_file_extension($file_name);
        $file_ext = strtolower($file_ext);
        $ctype = '';
        if ($file_ext == 'pdf') {
            $ctype="application/pdf";
        } else if ($file_ext == 'exe') {
            $ctype="application/octet-stream";
        } else if ($file_ext == 'exe') {
            $ctype="application/octet-stream";
        } else if ($file_ext == 'zip') {
            $ctype="application/zip";
        } else if ($file_ext == 'doc' || $file_ext == 'docx') {
            $ctype="application/msword";
        } else if ($file_ext == 'csv' || $file_ext == 'xls' || $file_ext == 'xlsx') {
            $ctype="application/vnd.ms-excel";
        } else if ($file_ext == 'ppt') {
            $ctype="application/vnd.ms-powerpoint";
        } else if ($file_ext == 'gif') {
            $ctype="image/gif";
        } else if ($file_ext == 'png') {
            $ctype="image/png";
        } else if ($file_ext == 'jpeg' || $file_ext == 'jpg') {
            $ctype="image/jpg";
        } else if ($file_ext == 'tif' || $file_ext == 'tiff') {
            $ctype="image/tiff";
        } else if ($file_ext == 'psd') {
            $ctype="image/psd";
        } else if ($file_ext == 'bmp') {
            $ctype="image/bmp";
        } else if ($file_ext == 'ico') {
            $ctype="image/vnd.microsoft.icon";
        } else {
            $ctype="application/force-download";
        }
        header("Pragma: public"); // required
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false); // required for certain browsers
        header("Content-Type: $ctype");
        header("Content-Disposition: attachment; filename=\"".$file_name."\";" );
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".filesize($file_path));
        ob_clean();
        flush();
        readfile( $file_path );
        if ($is_delete && file_exists( $file_path)) {
            unlink($file_path);
        }
        exit();
    }
}
?>
