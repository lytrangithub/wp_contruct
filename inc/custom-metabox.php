<?php
/*
 * @author: LuyenNguyen
 * Add metabox
 */
?>
<?php

/**
 * ----------------------------------------------------------------------------------------
 * Thêm metabox Plan
 * ----------------------------------------------------------------------------------------
 */

/**
 * Thêm metabox mã plan cho màn hình plan
 */
if (!function_exists('add_plan_cpt_metaboxes')) {
    add_action( 'add_meta_boxes_plan_cpt', 'add_plan_cpt_metaboxes' );
    function add_plan_cpt_metaboxes( ) {
        global $wp_meta_boxes;
        add_meta_box('metabox_plan_prices', __('Prices', TEXTDOMAIN), 'plan_prices_metaboxes_html', 'plan_cpt', 'normal', 'high');
        add_meta_box('metabox_plan_title_jp', __('Title jp', TEXTDOMAIN), 'plan_title_jp_metaboxes_html', 'plan_cpt', 'normal', 'high');
        add_meta_box('metabox_plan_code', __('Code', TEXTDOMAIN), 'plan_code_metaboxes_html', 'plan_cpt', 'normal', 'high');
    }
    /**
     * Nhập mã plan
     */
    function plan_prices_metaboxes_html()
    {
        global $post, $wpdb;
        //Lấy danh sách ngành
        $profession_list = ezloc_get_profession_list();
        $profession_code_first = null;
        if ($profession_list  && count($profession_list) > 0) {
            $profession_code_first = $profession_list[0] -> profession_code;
        }
        //Lấy mã plan theo post id
        $plan_id = get_post_meta($post->ID, '_plan_code' , true ) ;
        ?>
            <div class="prices">
                <!-- Price left -->
                <div class="left">
                    <?php if($profession_list && count($profession_list) > 0) {?>
                        <ul>
                            <?php $i=0; foreach($profession_list as $item) {?>
                                <li><span class="<?php echo ($i==0) ? ' active' : ''?>" onclick="ezlocPlan.getLangPriceByProfession(event, '<?php echo $item -> profession_code?>')"><?php echo $item -> post_title?></span></li>
                            <?php $i++;}?>
                        </ul>
                    <?php }?>
                </div>
                <!-- End price left -->
                <!-- Price right -->
                <div class="right">
                    <?php
                        $prices = ezloc_get_prices_by_plan_id_and_profession_id($plan_id, $profession_code_first);
                    ?>
                    <table>
                        <thead>
                            <tr>
                                <th align="left">From</th>
                                <th align="left">To</th>
                                <th align="right">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($prices && count($prices) > 0){ $i=0; foreach($prices as $item){?>
                                <tr row-index="<?php echo $i?>">
                                    <td align="left"><?php echo $item->from_lang_name?></td>
                                    <td align="left"><?php echo $item->to_lang_name?></td>
                                    <td align="right">
                                        <input class="align-right" onkeypress="Libs.fncInputNumberAllowComma(event);"  onkeyup = "ezlocPlan.priceChange(event, '<?php echo $i?>')"  type="text" maxlength="6" value="<?php echo $item->price?>" />
                                    </td>
                                </tr>
                            <?php $i++; }}?>
                        </tbody>
                    </table>
                    <?php
                        wp_enqueue_script('ezloc-admin-plan', EZLOC_SCRIPTS. 'admin/ezloc-plan.js', array());
                    ?>
                    <p style="text-align: right;">
                        <input type="button" class="button button-primary button-large" id="save-price" value="Save">
                    </p>
                    <p class="price-error error"></p>
                    <script type="text/javascript">
                        jQuery(document).ready(function(){
                             var priceJson = '<?php echo json_encode($prices)?>';
                            ezlocPlan.setPlanData('<?php echo $plan_id?>', '<?php echo $profession_code_first?>', priceJson);
                        });
                    </script>
                </div>
                <!-- End price price -->
            </div>
        <?php
    }
    /**
     * Nhập mã plan
     */
    function plan_code_metaboxes_html()
    {
        global $post;
        $planCode = get_post_meta($post->ID, '_plan_code' , true ) ;
        ?>
            <input type="text" name = "plan_code" maxlength="3" value="<?php echo ($planCode) ? $planCode : ''?>" <?php echo ($planCode) ? ' disabled' : ''?> />
        <?php
    }
    /**
     * Nhập tiêu đề jp
     */
    function plan_title_jp_metaboxes_html()
    {
        global $post;
        $planTitleJp = get_post_meta($post->ID, '_plan_title_jp' , true ) ;
        ?>
            <input style="width: 100%;" type="text" name = "plan_title_jp" maxlength="120" value="<?php echo ($planTitleJp) ? $planTitleJp : ''?>" />
        <?php
    }
    /**
     * Lưu lại giá trị meta box
     */
    function save_meta_boxes_plan_cpt()
    {
        global $post;
        if(!empty($_POST["plan_code"]))
        {
        	$planCode = get_post_meta($post->ID, '_plan_code' , true ) ;
        	if (empty($planCode)) {
	            update_post_meta($post->ID, "_plan_code", $_POST["plan_code"]);
	        }
        }
        if (!empty($_POST["plan_title_jp"])) {
            update_post_meta($post->ID, "_plan_title_jp", $_POST["plan_title_jp"]);
        }
    }
    add_action( 'save_post_plan_cpt', 'save_meta_boxes_plan_cpt' );
}
/**
 * Thêm cột mã plan hiển thị trong màn hình plan
 */
add_filter( 'manage_plan_cpt_posts_columns', 'set_custom_edit_plan_cpt_columns' );
function set_custom_edit_plan_cpt_columns($columns) {
	//Remove checkbox
    unset( $columns['cb'] );
  	unset( $columns['date'] );
  	unset( $columns['author'] );
    $columns['plan_code'] = __( 'Code');
    $columns['author'] = __( 'Author');
    $columns['date'] = __( 'Date');
    return $columns;
}
// Add the data to the custom columns for the plan post type:
add_action( 'manage_plan_cpt_posts_custom_column' , 'custom_plan_cpt_column', 10, 2 );
function custom_plan_cpt_column( $column, $post_id ) {
    switch ( $column ) {
        case 'plan_code' :
            echo get_post_meta($post_id, '_plan_code' , true ) ;
        break;
    }
}

/*
* Lấy giá theo mã plan và mã ngành
*/
add_action('wp_ajax_get_prices_by_plan_id_and_profession_id', 'fn_get_prices_by_plan_id_and_profession_id');
if ( ! function_exists( 'fn_get_prices_by_plan_id_and_profession_id' ) ){
    function fn_get_prices_by_plan_id_and_profession_id(){
        $data = $_POST;
        $plan_id = $data['plan_id'];
        $profession_code = $data['profession_code'];
        $prices = ezloc_get_prices_by_plan_id_and_profession_id($plan_id, $profession_code);
        echo return_json_result(($prices) ? true : false, '', $prices);
        exit();
    }
}

/*
* Lưu giá theo mã plan và mã ngành
*/
add_action('wp_ajax_admin_save_prices', 'fn_admin_save_prices');
if ( ! function_exists( 'fn_admin_save_prices' ) ){
    function fn_admin_save_prices(){
        global $wpdb;
        $data = $_POST;
        $plan_id = $data['plan_id'];
        $profession_code = $data['profession_code'];
        $json_data = stripslashes($data['json_data']);
        $price_data = json_decode($json_data, true);
        if (empty($price_data) || !$plan_id || !$profession_code) {
            echo return_json_result(false);
            exit();
        }
        $tbl_price = $wpdb->prefix . 'prices';
        $current_user = wp_get_current_user();
        //Tiến hành xóa dữ liệu trước khi lưu
        $wpdb->delete($tbl_price, array('plan_id' => $plan_id, 'profession_code' => $profession_code));
        //Lưu data
        foreach ($price_data as $item) {
            $price = ($item['price']) ? $item['price'] : null;
            if ($price) {
                $data_insert['plan_id'] = $plan_id;
                $data_insert['profession_code'] = $profession_code;
                $data_insert['from_lang_id'] = $item['from_lang_id'];
                $data_insert['to_lang_id'] = $item['to_lang_id'];
                $data_insert['price'] = ($item['price']) ? $item['price'] : null;
                $data_insert['created_by'] = $current_user->user_login;
                $data_insert['created_date'] = date('Y-m-d H:i:s');
                $wpdb->insert($tbl_price, $data_insert);
            }
        }
        echo return_json_result(true);
        exit();
    }
}
/**
 * ----------------------------------------------------------------------------------------
 * End thêm metabox Plan
 * ----------------------------------------------------------------------------------------
 */

/**
 * ----------------------------------------------------------------------------------------
 * Thêm metabox ngành
 * ----------------------------------------------------------------------------------------
 */

/**
 * Thêm metabox mã ngành cho màn ngành
 */
if (!function_exists('add_profession_cpt_metaboxes')) {
    add_action( 'add_meta_boxes_profession_cpt', 'add_profession_cpt_metaboxes' );
    function add_profession_cpt_metaboxes( ) {
        global $wp_meta_boxes;
        add_meta_box('metabox_profession_code', __('Code', TEXTDOMAIN), 'profession_code_metaboxes_html', 'profession_cpt', 'normal', 'high');
    }

    /**
     * Nhập mã ngành
     */
    function profession_code_metaboxes_html()
    {
        global $post;
        $professionCode = get_post_meta($post->ID, '_profession_code' , true ) ;
        ?>
            <input type="text" name = "profession_code" maxlength="5" value="<?php echo ($professionCode) ? $professionCode : ''?>" />
        <?php
    }
    /**
     * Lưu lại giá trị meta box
     */
    function save_meta_boxes_profession_cpt()
    {
        if(!empty($_POST["profession_code"]))
        {
            global $post;
            update_post_meta($post->ID, "_profession_code", $_POST["profession_code"]);
        }
    }
    add_action( 'save_post_profession_cpt', 'save_meta_boxes_profession_cpt' );
}
/**
 * Thêm cột mã ngành hiển thị trong màn hình ngành
 */
add_filter( 'manage_profession_cpt_posts_columns', 'set_custom_edit_profession_cpt_columns' );
function set_custom_edit_profession_cpt_columns($columns) {
    //Remove checkbox
    unset( $columns['cb'] );
    unset( $columns['date'] );
    unset( $columns['author'] );
    $columns['profession_code'] = __( 'Code');
    $columns['author'] = __( 'Author');
    $columns['date'] = __( 'Date');
    return $columns;
}
// Add the data to the custom columns for the plan post type:
add_action( 'manage_profession_cpt_posts_custom_column' , 'custom_profession_cpt_column', 10, 2 );
function custom_profession_cpt_column( $column, $post_id ) {
    switch ( $column ) {
        case 'profession_code' :
            echo get_post_meta($post_id, '_profession_code' , true ) ;
        break;
    }
}
/**
 * ----------------------------------------------------------------------------------------
 * End thêm metabox Plan
 * ----------------------------------------------------------------------------------------
 */

?>