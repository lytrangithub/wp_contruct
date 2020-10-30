<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly 
}
/**
 * Lấy danh sách khóa học
 *
 * @param int $limit             	Giới hạn số bài viết hiển thị
 * @param string $catetory_id     	Mã danh mục khóa học, hiện tại chỉ có 2 mã danh mục là
 * 									CP: Dành cho cá nhân, CB: Dành cho Nhóm/Doanh nghiệp
 * @return array danh sách khóa học
 */

if (!function_exists('get_slide_list')) {
    function get_slide_list($limit = 3, $offset = -1) {
        global $wpdb;
        $tbl_posts = $wpdb->prefix . 'posts';
        $tbl_postmeta = $wpdb->prefix . 'postmeta';
        $qry_limit = "LIMIT ".$limit;
        if ($offset >= 0) {
        	$qry_limit = " LIMIT ".INFORMATION_LIMIT." OFFSET $offset";
        }
        $qry = "
            SELECT
                p.ID as post_id,
                p.post_title as post_title,
                DATE_FORMAT(p.post_date, '%d/%m/%Y') as updated_date
            FROM
                $tbl_posts p
            WHERE
                p.post_type = 'slideshow_cpt' 
                AND p.post_status = 'publish'
            ORDER BY
                p.menu_order,
                p.post_title
            $qry_limit
        ";
        //echo $qry;die();
        //$qry = $wpdb->prepare( $qry);
        $results = $wpdb->get_results($qry);
        if (!$results) {
            return;
        }
        foreach ($results as $item) {
            $item -> image_url = wp_get_attachment_url( get_post_thumbnail_id($item->post_id), 'thumbnail' );
            $item -> description = get_custom_field_by_post_id( "description", $item ->  post_id);
            $item -> main_content = get_custom_field_by_post_id( "main_content", $item ->  post_id);
            $item -> permalink = get_permalink($item->post_id);
        }
        return $results;
    }
}






?>