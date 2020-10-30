<?php
/**
 * ----------------------------------------------------------------------------------------
 * Thêm field mã ngôn ngữ cho category danh mục ngôn ngữ
 * ----------------------------------------------------------------------------------------
 */
/**
 * Thêm mới field mã ngôn ngữ
 */
if (!function_exists('add_lang_translation_tax_form_field')) {
    function add_lang_translation_tax_form_field()
    {
        ?>
        <div class="form-field">
            <label>Language code</label>
            <input name="_lang_code" value="" />
        </div>
        <?php
    }
    add_action( 'lang_translation_tax_add_form_fields', 'add_lang_translation_tax_form_field', 10, 2 );
}
/**
 * cập nhật field mã ngôn ngữ
 */
if (!function_exists('edit_lang_translation_tax_form_field')) {
    function edit_lang_translation_tax_form_field($term)
    {
        if(!$term) return;
        $lang_code = get_term_meta($term -> term_id, '_lang_code', true);
        ?>
        <tr class="form-field">
        		<th scope="row" valign="top"><label><?php _e( 'Language code:', 'ezloc' ); ?></label></th>
        		<td>
        			<input name="_lang_code" value="<?php echo $lang_code;?>" />
        		</td>
        	</tr>
        <?php
    }
    add_action( 'lang_translation_tax_edit_form_fields', 'edit_lang_translation_tax_form_field', 10, 2 );
}
/*
 * Lưu lại mã ngôn ngữ
 * */
if (!function_exists('save_lang_translation_tax_custom_meta')) {
    function save_lang_translation_tax_custom_meta( $term_id ) {
        if(!$term_id || $term_id <= 0) return;
        if (!empty( $_POST['_lang_code'] )) {
            update_term_meta( $term_id, '_lang_code', $_POST['_lang_code'] );
        }
    }
    add_action( 'create_lang_translation_tax', 'save_lang_translation_tax_custom_meta', 10, 2 );
    add_action( 'edited_lang_translation_tax', 'save_lang_translation_tax_custom_meta', 10, 2 );  
}
/**
 * ----------------------------------------------------------------------------------------
 * Kết thúc thêm field mã ngôn ngữ cho category danh mục ngôn ngữ
 * ----------------------------------------------------------------------------------------
 */