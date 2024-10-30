<?php 

if (!defined('ABSPATH'))
exit;

if (!class_exists('MAMWR_pro_cat_other_settings')) {

    class MAMWR_pro_cat_other_settings {

        protected static $MAMWR_instance;

        // For multiple value
        function recursive_sanitize_text_field($array) {
            foreach ( $array as $key => &$value ) {
                if ( is_array( $value ) ) {
                    $value = $this->recursive_sanitize_text_field($value);
                }else{
                    $value = sanitize_text_field( $value );
                }
            }
            return $array;
        }


        /* ADD FIELD IN CATEGORY- UPDATE FIELD AND SAVE FIELD */
        function mam_p_taxonomy_add_new_meta_field() {
            $mam_roles = get_editable_roles();
            ?>

            <div class="form-field">
                <label for="mam_p_min_qty"><?php _e('Minimum Quantity', MAMWR_DOMAIN); ?></label>
                <input type="number" name="mam_p_min_qty" id="mam_p_min_qty">
            </div>
            <div class="form-field">
                <label for="mam_p_max_qty"><?php _e('Maximum Quantity', MAMWR_DOMAIN); ?></label>
                <input type="number" name="mam_p_max_qty" id="mam_p_max_qty">
            </div>
            <div class="form-field">
                <label for="mamwr_minprice"><?php _e('Minimum Cost', MAMWR_DOMAIN); ?></label>
                <input type="number" name="mamwr_minprice" id="mamwr_minprice">
            </div>
            <div class="form-field">
                <label for="mamwr_maxprice"><?php _e('Maximum Cost', MAMWR_DOMAIN); ?></label>
                <input type="number" name="mamwr_maxprice" id="mamwr_maxprice">
            </div>
            <div class="form-field">
                <label for="mam_p_max_qty"><?php _e('User Role', MAMWR_DOMAIN); ?></label>
                <select name="mam_p_roles[]" id="mam_p_roles" multiple>
                <?php 
              
                foreach ($mam_roles as $mam_roles_key => $mam_roles_value) {
                    echo "<option value='".$mam_roles_key."'>".$mam_roles_value['name']."</option>";
                }
                ?>
                 </select>   
                 <p><?php _e('If not Selected than apply for all user',MAMWR_DOMAIN); ?></p>
            </div>
            <?php
        }


        //Product Cat Edit page
        function mam_p_taxonomy_edit_meta_field($term) {

            $mam_roles = get_editable_roles();
            //getting term ID
            $term_id = $term->term_id;

            // retrieve the existing value(s) for this meta field.
            $mam_p_min_qty = get_term_meta($term_id, 'mam_p_min_qty', true);
            $mam_p_max_qty = get_term_meta($term_id, 'mam_p_max_qty', true);
            $mam_p_roles = explode(",",get_term_meta($term_id, 'mam_p_roles', true));
            $mamwr_minprice = get_term_meta($term_id, 'mamwr_minprice', true);
            $mamwr_maxprice = get_term_meta($term_id, 'mamwr_maxprice', true);
            ?>
            <tr class="form-field">
                <th scope="row" valign="top"><label for="mam_p_min_qty"><?php _e('Minimum Quantity', MAMWR_DOMAIN); ?></label></th>
                <td>
                    <input type="number" name="mam_p_min_qty" id="mam_p_min_qty" value="<?php echo esc_attr($mam_p_min_qty) ? esc_attr($mam_p_min_qty) : ''; ?>">
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row" valign="top"><label for="mam_p_max_qty"><?php _e('Maximum Quantity', MAMWR_DOMAIN); ?></label></th>
                <td>
                    <input type="number" name="mam_p_max_qty" id="mam_p_max_qty" value="<?php echo esc_attr($mam_p_max_qty) ? esc_attr($mam_p_max_qty) : ''; ?>">
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row" valign="top"><label for="mamwr_minprice"><?php _e('Minimum Cost', MAMWR_DOMAIN); ?></label></th>
                <td>
                    <input type="number" name="mamwr_minprice" id="mamwr_minprice" value="<?php echo esc_attr($mamwr_minprice) ? esc_attr($mamwr_minprice) : ''; ?>">
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row" valign="top"><label for="mamwr_maxprice"><?php _e('Maximum Cost', MAMWR_DOMAIN); ?></label></th>
                <td>
                    <input type="number" name="mamwr_maxprice" id="mamwr_maxprice" value="<?php echo esc_attr($mamwr_maxprice) ? esc_attr($mamwr_maxprice) : ''; ?>">
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row" valign="top"><label for="mam_p_user_role"><?php _e('User Role', MAMWR_DOMAIN); ?></label></th>
                <td>
                <select name="mam_p_roles[]" id="mam_p_roles" multiple>
                <?php 
              
                foreach ($mam_roles as $mam_roles_key => $mam_roles_value) {
                    echo "<option value='".$mam_roles_key."' ".((in_array($mam_roles_key, $mam_p_roles))?'selected':'').">".$mam_roles_value['name']."</option>";
                }
                ?>
                 </select>   
                 <p><?php _e('If not Selected than apply for all user',MAMWR_DOMAIN); ?></p>
                </td>
            </tr>
            <?php
        }

        // Save extra taxonomy fields callback function.
        function mam_p_save_taxonomy_custom_meta($term_id) {

            $mam_p_min_qty = sanitize_text_field($_REQUEST['mam_p_min_qty']);
            if (isset($mam_p_min_qty))
            update_term_meta($term_id, 'mam_p_min_qty', $mam_p_min_qty);

            $mamwr_minprice = sanitize_text_field($_REQUEST['mamwr_minprice']);
            if (isset($mamwr_minprice))
            update_term_meta($term_id, 'mamwr_minprice', $mamwr_minprice);

            $mamwr_maxprice = sanitize_text_field($_REQUEST['mamwr_maxprice']);
            if (isset($mamwr_maxprice))
            update_term_meta($term_id, 'mamwr_maxprice', $mamwr_maxprice);

            $mam_p_max_qty = sanitize_text_field($_REQUEST['mam_p_max_qty']);
            if (isset($mam_p_max_qty))
            update_term_meta($term_id, 'mam_p_max_qty', $mam_p_max_qty);

            $mam_p_roles = $this->recursive_sanitize_text_field($_REQUEST['mam_p_roles']);
            if (isset($mam_p_roles))
            update_term_meta($term_id, 'mam_p_roles', implode(",",$mam_p_roles));
        }


        function woocommerce_product_custom_fields() {
            global $woocommerce, $post;
            $mamwr_groupmanager = get_option( 'mamwr_groupmanager' );
            echo '<div class="product_custom_field">';
                if(!empty($mamwr_groupmanager)){
                    echo '<p class="form-field"><label for="_mamwr_groupvalue">'. __('Groups Manager', MAMWR_DOMAIN).'</label>';
                    echo '<select name="_mamwr_groupvalue" id="_mamwr_groupvalue">';
                        $mamwr_groupvalue = get_post_meta($post->ID, '_mamwr_groupvalue', true); ?>
                        <option value="" <?php if(empty($mamwr_groupvalue)){ echo "selected"; }?>></option>
                        <?php
                        foreach ($mamwr_groupmanager as $mamwr_groupmanager_key => $mamwr_groupmanager_value) { 
                            if(!empty($mamwr_groupmanager_value['gm_id'])){
                                ?>
                                    <option value="<?php echo $mamwr_groupmanager_value['gm_id']; ?>" <?php if($mamwr_groupmanager_value['gm_id'] == $mamwr_groupvalue){ echo "selected"; }?>><?php echo $mamwr_groupmanager_value['gm_name']; ?></option>
                                <?php
                            }
                        }
                    echo '</select></p>';
                    echo '<p class="form-field">If minimum and maximum quantity not set then group codition work only</p>';
                }

                //echo "hello";
                // Custom Product Text Field
                woocommerce_wp_text_input(
                    array(
                        'id' => '_custom_product_number_field_min',
                        //'placeholder' => 'Custom Product Number Field',
                        'label' => __('Minimum Quantity', MAMWR_DOMAIN),
                        'type' => 'number',
                        'custom_attributes' => array(
                            'step' => 'any',
                            'min' => '0'
                        )
                    )
                );


                //Custom Product Number Field
                woocommerce_wp_text_input(
                    array(
                        'id' => '_custom_product_number_field_max',
                        //'placeholder' => 'Custom Product Number Field',
                        'label' => __('Maximum Quantity', MAMWR_DOMAIN),
                        'type' => 'number',
                        'custom_attributes' => array(
                            'step' => 'any',
                            'min' => '0'
                        )
                    )
                );
                
                echo '<div class="mam_getpro"><p class="form-field"><label for="mam_p_max_qty">'. __('Exclude this product', MAMWR_DOMAIN).'</label>';
                ?>
                <input type="checkbox" name="mam_p_exclude">
                <?php
                echo '   Exclude this product in cart rules <a href="https://www.xeeshop.com/product/min-and-max-quantity-rule-woocommerce/" target="_blank" class="mamget_proa">Get Pro</a></p></div>';



                echo '<p class="form-field"><label for="mam_p_max_qty">'. __('User Role', MAMWR_DOMAIN).'</label>';
                echo '<select name="mam_p_roles[]" id="mam_p_roles" multiple>';
                $mam_p_roles1 = explode(",",get_post_meta($post->ID, 'mam_p_roles', true));
                //print_r($mam_p_roles1);
                global $wp_roles;
                $roles = $wp_roles->get_names();
                foreach ($roles as $key => $value) {
                    echo "<option value='".$key."' ".(!in_array($key, $mam_p_roles1)).'selected="selected"'.">".$value."</option>";
                }
                echo '</select></p>';  
                echo '<p class="form-field">If Not Select Any Role It Will Be Apply all User Role</p>';
            echo '</div>';
        }


        function woocommerce_product_custom_fields_save($post_id) {
            $woocommerce_custom_product_number_field_min = sanitize_text_field($_POST['_custom_product_number_field_min']);
            if (isset($woocommerce_custom_product_number_field_min))
                update_post_meta($post_id, '_custom_product_number_field_min', esc_attr($woocommerce_custom_product_number_field_min));

            $woocommerce_custom_product_number_field_max = sanitize_text_field($_POST['_custom_product_number_field_max']);
            if (isset($woocommerce_custom_product_number_field_max))
                update_post_meta($post_id, '_custom_product_number_field_max', esc_attr($woocommerce_custom_product_number_field_max));

            $_mamwr_groupvalue = sanitize_text_field($_POST['_mamwr_groupvalue']);
            if (isset($_mamwr_groupvalue))
                update_post_meta($post_id, '_mamwr_groupvalue', esc_attr($_mamwr_groupvalue));

            $mam_p_roles = $this->recursive_sanitize_text_field($_REQUEST['mam_p_roles']);
            update_post_meta($post_id, 'mam_p_roles', implode(",",$mam_p_roles));

            
        }

     
        function mamwr_variation_settings_fields( $loop, $variation_data, $variation ) {
            // Minimum Quantity
            woocommerce_wp_text_input( 
                array( 
                    'id'          => '_mamwr_min_qty[' . $variation->ID . ']', 
                    'type'        => 'number',
                    'label'       => __( 'Minimum Quantity', 'woocommerce' ), 
                    'desc_tip'    => 'true',
                    'description' => __( 'Enter the minimum quantity here.', 'woocommerce' ),
                    'value'       => get_post_meta( $variation->ID, '_mamwr_min_qty', true ),
                    'custom_attributes' => array(
                        'step'   => 'any',
                        'min' => '0'
                    ) 
                )
            );

            // Maximum Quantity
            woocommerce_wp_text_input( 
                array( 
                    'id'          => '_mamwr_max_qty[' . $variation->ID . ']', 
                    'type'        => 'number',
                    'label'       => __( 'Maximum Quantity', 'woocommerce' ), 
                    'desc_tip'    => 'true',
                    'description' => __( 'Enter the maximum quantity here.', 'woocommerce' ),
                    'value'       => get_post_meta( $variation->ID, '_mamwr_max_qty', true ),
                    'custom_attributes' => array(
                        'step'   => 'any',
                        'min' => '0'
                    ) 
                )
            );
        }
   
        /**
        * Save new fields for variations
        *
        */
        function mamwr_save_variation_settings_fields( $post_id ) {

            // Minimum Quantity
            $_mamwr_min_qty = $_POST['_mamwr_min_qty'][ $post_id ];
            if( isset( $_mamwr_min_qty ) ) {
                update_post_meta( $post_id, '_mamwr_min_qty', esc_attr( $_mamwr_min_qty ) );
            }

            // Maximum Quantity
            $_mamwr_max_qty = $_POST['_mamwr_max_qty'][ $post_id ];
            if(isset( $_mamwr_max_qty ) ) {
                update_post_meta( $post_id, '_mamwr_max_qty', esc_attr( $_mamwr_max_qty ) );
            }
        }


        function init() {

            /* ADD FIELD IN PRODUCT- UPDATE FIELD AND SAVE FIELD */
            add_action('woocommerce_product_options_general_product_data', array($this, 'woocommerce_product_custom_fields'));

            // Save Fields
            add_action('woocommerce_process_product_meta', array($this, 'woocommerce_product_custom_fields_save'));

            add_action('edited_product_cat', array($this, 'mam_p_save_taxonomy_custom_meta'), 10, 1);

            add_action('create_product_cat', array($this, 'mam_p_save_taxonomy_custom_meta'), 10, 1);

            add_action('product_cat_add_form_fields', array($this, 'mam_p_taxonomy_add_new_meta_field'), 10, 1);

            add_action('product_cat_edit_form_fields', array($this, 'mam_p_taxonomy_edit_meta_field'), 10, 1);

            add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'mamwr_variation_settings_fields'), 10, 3 );
         
            add_action( 'woocommerce_save_product_variation', array( $this, 'mamwr_save_variation_settings_fields'), 10, 2 );
        }


        public static function MAMWR_instance() {
            if (!isset(self::$MAMWR_instance)) {
                self::$MAMWR_instance = new self();
                self::$MAMWR_instance->init();
            }
            return self::$MAMWR_instance;
        }
    }
    MAMWR_pro_cat_other_settings::MAMWR_instance();
}







