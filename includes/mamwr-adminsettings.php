<?php 

if (!defined('ABSPATH'))
  exit;

if (!class_exists('MAMWR_admin_settings')) {
    class MAMWR_admin_settings {

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


        function mamwr_register_my_custom_submenu_page() { 
            add_submenu_page( 'woocommerce', 'Cart Rules', 'Cart Rules', 'manage_options', 'woo-cartrules',array($this, 'mamwr_submenu_page_callback'));
        }


        function mamwr_submenu_page_callback() { 
            ?>
                <div class="mamwr-container">
                    <div class="wrap">
                        <h2><?php echo __( 'Cart Rules', MAMWR_DOMAIN );?></h2>
                        <?php if($_REQUEST['message'] == 'success'){ ?>
                            <div class="notice notice-success is-dismissible"> 
                                <p><strong>Record updated successfully.</strong></p>
                            </div>
                        <?php } ?>
                        <div class="mamwr-inner-block">
                            <form method="post" >
                                <?php wp_nonce_field( 'mamwr_nonce_action', 'mamwr_nonce_field' ); ?>
                                <ul class="tabs">
                                    <li class="tab-link current" data-tab="mamwr-tab-general"><?php echo __( 'General Settings', MAMWR_DOMAIN );?></li>
                                    <li class="tab-link" data-tab="mamwr-tab-cart"><?php echo __( 'Cart Settings', MAMWR_DOMAIN );?></li>
                                    <li class="tab-link" data-tab="mamwr-tab-group"><?php echo __( 'Groups Manager Settings', MAMWR_DOMAIN );?></li>
                                    <li class="tab-link" data-tab="mamwr-tab-messages"><?php echo __( 'Messages Settings', MAMWR_DOMAIN );?></li>
                                </ul>
                                <div id="mamwr-tab-general" class="tab-content current">
                                    <fieldset>
                                      <p>
                                          <label>
                                              <?php
                                                  $mamwr_enabled = empty(get_option( 'mamwr_enabled' )) ? 'no' : get_option( 'mamwr_enabled' );
                                              ?>
                                             <input type="checkbox" name="mamwr_enabled" value="yes" <?php if ($mamwr_enabled == "yes") {echo 'checked="checked"';} ?>><strong><?php echo __( 'Enable/Disable This Plugin', MAMWR_DOMAIN ); ?></strong>
                                          </label>
                                      </p>
                                      <div class="mamwr-top">
                                          <p class="mamwr-heading"><?php echo __( 'General options', MAMWR_DOMAIN );?></h2>
                                          <p class="mamwr-tips"><?php echo __( "Here is general options it's work for all settings.", MAMWR_DOMAIN );?></p>
                                      </div>
                                          <table class="form-table">
                                              <tbody>
                                                  <tr class="form-field">
                                                      <th scope="row">
                                                          <label><?php echo __( 'User Role', MAMWR_DOMAIN );?></label>
                                                      </th>
                                                      <td>
                                                        <select name="mamwr_roles[]" id="mamwr_roles" multiple>
                                                          <?php 
                                                            global $wp_roles;
                                                            $inbultroles = $wp_roles->get_names();
                                                            foreach ($inbultroles as $inbultkey => $inbultvalue) {
                                                              echo "<option value='".$inbultkey."' ".(!in_array($inbultkey, get_option( 'mamwr_roles'))).'selected="selected"'.">".$inbultvalue."</option>";
                                                                    
                                                            }
                                                          ?>
                                                        </select> 
                                                        <p class="mamwr-tips"><?php _e('If User Role is not Selected than apply for all user rolse',MAMWR_DOMAIN); ?></p>
                                                      </td>
                                                  </tr>
                                                  <tr class="form-field">
                                                      <th scope="row">
                                                          <label><?php echo __( 'Hide Checkout Button', MAMWR_DOMAIN );?></label>
                                                      </th>
                                                      <td>
                                                        <div class="mam_getpro">
                                                           <label>
                                                              
                                                             <input type="checkbox" name="mamwr_hidecheckoutbtn" value="yes"><?php echo __( 'Hide checkout button if minimum or maximum condition not passed.', MAMWR_DOMAIN ); ?>
                                                             <a href="https://www.xeeshop.com/product/min-and-max-quantity-rule-woocommerce/" target="_blank" class="mamget_proa">Get Pro</a>
                                                            </label>

                                                        </div>
                                                        
                                                      </td>

                                                  </tr>
                                              </tbody>
                                          </table>
                                    </fieldset>
                                </div>
                                <div id="mamwr-tab-cart" class="tab-content">
                                    <fieldset>
                                        <div class="mamwr-top">
                                          <p class="mamwr-heading"><?php echo __( 'Cart Page', MAMWR_DOMAIN );?></h2>
                                          <p class="mamwr-tips"><?php echo __( 'Here is all options for cart page.', MAMWR_DOMAIN );?></p>
                                        </div>
                                        <table class="form-table">
                                          <tbody>
                                            <tr>
                                              <th scope="row">
                                                  <label><?php echo __( 'Minimum Quantity', MAMWR_DOMAIN );?></label>
                                              </th>
                                              <td>
                                                <input type="number"  min="0" name="min_cart_quntity" value="<?php echo get_option( 'min_cart_quntity' ); ?>" id="min_cart_quntity" class="small-text ltr">
                                              </td>
                                            </tr>
                                            <tr>
                                              <th scope="row">
                                                <label><?php echo __( 'Maximum Quantity', MAMWR_DOMAIN );?></label>
                                              </th>
                                              <td>
                                                <input type="number"  min="0" name="max_cart_quntity" value="<?php echo get_option( 'max_cart_quntity' ); ?>" id="max_cart_quntity" class="small-text ltr">
                                              </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php echo __( 'Minimum Cost', MAMWR_DOMAIN );?></label>
                                                </th>
                                                <td>
                                                    <input type="number"  min="0" name="mamwr_minprice" value="<?php echo get_option( 'mamwr_minprice' ); ?>" id="mamwr_minprice" class="small-text ltr">
                                                </td>
                                            </tr>
                                            <tr>
                                              <th scope="row">
                                                  <label><?php echo __( 'Maximum Cost', MAMWR_DOMAIN );?></label>
                                              </th>
                                              <td>
                                                  <input type="number"  min="0" name="mamwr_maxprice" value="<?php echo get_option( 'mamwr_maxprice' ); ?>" id="mamwr_maxprice" class="small-text ltr">
                                                </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                    </fieldset>
                                </div>
                                <div id="mamwr-tab-group" class="tab-content">
                                    <fieldset>
                                        <div class="mamwr-top">
                                          <p class="mamwr-heading"><?php echo __( 'Groups Manager', MAMWR_DOMAIN );?></h2>
                                          <p class="mamwr-tips"><?php echo __( 'Here is all options for grouped products.', MAMWR_DOMAIN );?></p>
                                        </div>
                                        <?php 
                                          $mamwr_groupmanager = get_option( 'mamwr_groupmanager' );
                                        ?>
                                        <table class="mamwr_groping_pro">
                                          <thead>
                                            <tr>
                                              <th>Group Name ( Required ) </th>
                                              <th>Minimum Quantity</th>
                                              <th>Maximum Quantity</th>
                                              <th>Minimum Price</th>
                                              <th>Maximum Price</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <?php if(!empty($mamwr_groupmanager)){
                                                    foreach ($mamwr_groupmanager as $mamwr_groupmanager_key => $mamwr_groupmanager_value) { 
                                                      ?>
                                                      <tr class="form-field">
                                                        <td>
                                                          <input type="text" name="gm_name[]" value="<?php echo $mamwr_groupmanager_value['gm_name']; ?>" id="gm_name">
                                                        </td>
                                                        <td>
                                                          <input type="number"  min="0" name="gm_min_quntity[]" value="<?php echo $mamwr_groupmanager_value['gm_min_quntity']; ?>" id="gm_min_quntity" class="small-text ltr">
                                                        </td>
                                                        <td>
                                                          <input type="number"  min="0" name="gm_max_quntity[]" value="<?php echo $mamwr_groupmanager_value['gm_max_quntity']; ?>" id="gm_max_quntity" class="small-text ltr">
                                                        </td>
                                                        <td>
                                                          <input type="number"  min="0" name="gm_min_cost[]" value="<?php echo $mamwr_groupmanager_value['gm_min_cost']; ?>" id="gm_min_cost" class="small-text ltr">
                                                        </td>
                                                        <td>
                                                          <input type="number"  min="0" name="gm_max_cost[]" value="<?php echo $mamwr_groupmanager_value['gm_max_cost']; ?>" id="gm_max_cost" class="small-text ltr">
                                                        </td>
                                                        <td>
                                                          <a href="javascript:void(0);" class="gm_add_button">
                                                            <img src="<?php echo esc_url( MAMWR_PLUGIN_DIR.'/images/list-add.svg');?>" style="height: 15px;"/>
                                                          </a>
                                                        </td>
                                                        <?php if($mamwr_groupmanager_key != 0){?>
                                                        <td>
                                                          <a href="javascript:void(0);" class="gm_remove_button">
                                                            <img src="<?php echo esc_url( MAMWR_PLUGIN_DIR.'/images/list-remove.svg');?>" style="height: 15px;"/>
                                                          </a>
                                                        </td>
                                                       <?php } ?>
                                                      </tr>
                                            <?php } } else { ?>
                                              <tr class="form-field">
                                                <td>
                                                  <input type="text" name="gm_name[]" value="<?php echo $gm_name; ?>" id="gm_name">
                                                </td>
                                                <td>
                                                  <input type="number"  min="0" name="gm_min_quntity[]" value="<?php echo get_option( 'gm_min_quntity' ); ?>" id="gm_min_quntity" class="small-text ltr">
                                                </td>
                                                <td>
                                                  <input type="number"  min="0" name="gm_max_quntity[]" value="<?php echo get_option( 'gm_max_quntity' ); ?>" id="gm_max_quntity" class="small-text ltr">
                                                </td>
                                                <td>
                                                  <input type="number"  min="0" name="gm_min_cost[]" value="<?php echo get_option( 'gm_min_cost' ); ?>" id="gm_min_cost" class="small-text ltr">
                                                </td>
                                                <td>
                                                  <input type="number"  min="0" name="gm_max_cost[]" value="<?php echo get_option( 'gm_max_cost' ); ?>" id="gm_max_cost" class="small-text ltr">
                                                </td>
                                                <td>
                                                  <a href="javascript:void(0);" class="gm_add_button">
                                                    <img src="<?php echo esc_url( MAMWR_PLUGIN_DIR.'/images/list-add.svg');?>" style="height: 15px;"/>
                                                  </a>
                                                </td>
                                              </tr>
                                            <?php } ?>
                                            
                                          </tbody>
                                        </table>
                                    </fieldset>
                                </div>
                                <div id="mamwr-tab-messages" class="tab-content">
                                    <fieldset>
                                        <div class="mamwr-top">
                                          <p class="mamwr-heading"><?php echo __( 'Messages', MAMWR_DOMAIN );?></h2>
                                          <p class="mamwr-tips"><?php echo __( 'Here is custom Error message', MAMWR_DOMAIN );?></p>
                                          <p class="mamwr-tips"><?php echo __( 'Never change or remove %u and %s type of character in sentence', MAMWR_DOMAIN );?></p>
                                          
                                        </div>
                                        <table class="form-table">
                                          <tbody>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php echo __( 'General Quantity Messages', MAMWR_DOMAIN );?></label>
                                                </th>
                                                <td>
                                                  <?php
                                                     $mamwr_qtymsg = empty(get_option( 'mamwr_qtymsg' )) ? 'Total quantity must between in %u to %u' : get_option( 'mamwr_qtymsg' );
                                                  ?>
                                                    <input type="text" name="mamwr_qtymsg" value="<?php echo $mamwr_qtymsg; ?>" id="mamwr_qtymsg" class="mamwr_msg">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php echo __( 'Quantity Messages ( Single product ) ', MAMWR_DOMAIN );?></label>
                                                </th>
                                                <td>
                                                  <div class="mam_getpro">
                                                  <?php
                                                     $mamwr_singleqtymsg = empty(get_option( 'mamwr_singleqtymsg' )) ? 'Single product quantity must between in %u to %u' : get_option( 'mamwr_singleqtymsg' );
                                                  ?>
                                                    <input type="text" name="mamwr_singleqtymsg" value="<?php echo $mamwr_singleqtymsg; ?>" id="mamwr_singleqtymsg" class="mamwr_msg"><a href="https://www.xeeshop.com/product/min-and-max-quantity-rule-woocommerce/" target="_blank" class="mamget_proa">Get Pro</a>
                                                  </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php echo __( 'Quantity Messages ( Category Wise ) ', MAMWR_DOMAIN );?></label>
                                                </th>
                                                <td>
                                                  <div class="mam_getpro">
                                                  <?php
                                                     $mamwr_catqtymsg = empty(get_option( 'mamwr_catqtymsg' )) ? 'Category %s products quantity must between in %u to %u' : get_option( 'mamwr_catqtymsg' );
                                                  ?>
                                                    <input type="text" name="mamwr_catqtymsg" value="<?php echo $mamwr_catqtymsg; ?>" id="mamwr_catqtymsg" class="mamwr_msg"><a href="https://www.xeeshop.com/product/min-and-max-quantity-rule-woocommerce/" target="_blank" class="mamget_proa">Get Pro</a>
                                                  </div>
                                                </td>
                                            </tr>
                                             <tr>
                                                <th scope="row">
                                                    <label><?php echo __( 'Quantity Messages ( Variation product ) ', MAMWR_DOMAIN );?></label>
                                                </th>
                                                <td>
                                                  <div class="mam_getpro">
                                                  <?php
                                                     $mamwr_varqtymsg = empty(get_option( 'mamwr_varqtymsg' )) ? '%s variation product quantity must between in %u to %u' : get_option( 'mamwr_varqtymsg' );
                                                  ?>
                                                    <input type="text" name="mamwr_varqtymsg" value="<?php echo $mamwr_varqtymsg; ?>" id="mamwr_varqtymsg" class="mamwr_msg"><a href="https://www.xeeshop.com/product/min-and-max-quantity-rule-woocommerce/" target="_blank" class="mamget_proa">Get Pro</a>
                                                  </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php echo __( 'Quantity Messages (  Groups Wise ) ', MAMWR_DOMAIN );?></label>
                                                </th>
                                                <td>
                                                  <div class="mam_getpro">
                                                  <?php
                                                     $mamwr_groupqtymsg = empty(get_option( 'mamwr_groupqtymsg' )) ? '%s group product quantity must between in %u to %u' : get_option( 'mamwr_groupqtymsg' );
                                                  ?>

                                                    <input type="text" name="mamwr_groupqtymsg" value="<?php echo $mamwr_groupqtymsg; ?>" id="mamwr_groupqtymsg" class="mamwr_msg"><a href="https://www.xeeshop.com/product/min-and-max-quantity-rule-woocommerce/" target="_blank" class="mamget_proa">Get Pro</a>
                                                  </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php echo __( 'General Cost Messages', MAMWR_DOMAIN );?></label>
                                                </th>
                                                <td>
                                                  <?php
                                                     $mamwr_pricemsg = empty(get_option( 'mamwr_pricemsg' )) ? 'Total cost of products must between in %u to %u' : get_option( 'mamwr_pricemsg' );
                                                  ?>
                                                    <input type="text" name="mamwr_pricemsg" value="<?php echo $mamwr_pricemsg; ?>" id="mamwr_pricemsg" class="mamwr_msg">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php echo __( 'Cost Messages ( Category Wise ) ', MAMWR_DOMAIN );?></label>
                                                </th>
                                                <td>
                                                  <div class="mam_getpro">
                                                  <?php
                                                     $mamwr_pricecatmsg = empty(get_option( 'mamwr_pricecatmsg' )) ? 'Category %s products total cost must between in %u to %u' : get_option( 'mamwr_pricecatmsg' );
                                                  ?>
                                                    <input type="text" name="mamwr_pricecatmsg" value="<?php echo $mamwr_pricecatmsg; ?>" id="mamwr_pricecatmsg" class="mamwr_msg"><a href="https://www.xeeshop.com/product/min-and-max-quantity-rule-woocommerce/" target="_blank" class="mamget_proa">Get Pro</a>
                                                  </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php echo __( 'Cost Messages ( Groups Wise ) ', MAMWR_DOMAIN );?></label>
                                                </th>
                                                <td>
                                                  <div class="mam_getpro">
                                                  <?php
                                                     $mamwr_costgroupmsg = empty(get_option( 'mamwr_costgroupmsg' )) ? 'Group %s products total cost must between in %u to %u' : get_option( 'mamwr_costgroupmsg' );
                                                  ?>
                                                    <input type="text" name="mamwr_costgroupmsg" value="<?php echo $mamwr_costgroupmsg; ?>" id="mamwr_costgroupmsg" class="mamwr_msg"><a href="https://www.xeeshop.com/product/min-and-max-quantity-rule-woocommerce/" target="_blank" class="mamget_proa">Get Pro</a>
                                                  </div>
                                                </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                    </fieldset>
                                </div>
                                <input type="hidden" name="mamwr_action" value="mamwr_save_option_data"/>
                                <input type="submit" value="Save changes" name="submit" class="button-primary" id="mamwr-btn-space">
                            </form> 
                        </div>
                    </div>
                </div>
            <?php 
        }

        // Save Setting Option
        function mamwr_save_options(){
            if( current_user_can('administrator') ) { 
                if($_REQUEST['mamwr_action'] == 'mamwr_save_option_data'){
                    if(!isset( $_POST['mamwr_nonce_field'] ) || !wp_verify_nonce( $_POST['mamwr_nonce_field'], 'mamwr_nonce_action' ) ){
                        print 'Sorry, your nonce did not verify.';
                        exit;
                    }else{
                        $mamwr_enabled = (!empty(sanitize_text_field( $_REQUEST['mamwr_enabled'] )))? sanitize_text_field( $_REQUEST['mamwr_enabled'] ) : 'no';
                        update_option('mamwr_enabled',$mamwr_enabled, 'yes');

                        $min_cart_quntity = sanitize_text_field( $_REQUEST['min_cart_quntity']);
                        update_option('min_cart_quntity',$min_cart_quntity, 'yes');

                        $max_cart_quntity = sanitize_text_field( $_REQUEST['max_cart_quntity']);
                        update_option('max_cart_quntity',$max_cart_quntity, 'yes');

                        $mamwr_minprice = sanitize_text_field( $_REQUEST['mamwr_minprice']);
                        update_option('mamwr_minprice',$mamwr_minprice, 'yes');

                        $mamwr_maxprice = sanitize_text_field( $_REQUEST['mamwr_maxprice']);
                        update_option('mamwr_maxprice',$mamwr_maxprice, 'yes');

                        $mamwr_roles = $this->recursive_sanitize_text_field( $_REQUEST['mamwr_roles']);
                        update_option('mamwr_roles',$mamwr_roles, 'yes');

                        $mamwr_pricemsg = (!empty(sanitize_text_field( $_REQUEST['mamwr_pricemsg'] )))? sanitize_text_field( $_REQUEST['mamwr_pricemsg'] ) : 'Total cost of products must between in %u to %u';
                        update_option('mamwr_pricemsg',$mamwr_pricemsg, 'yes');

                        $mamwr_qtymsg = (!empty(sanitize_text_field( $_REQUEST['mamwr_qtymsg'] )))? sanitize_text_field( $_REQUEST['mamwr_qtymsg'] ) : 'Total quantity must between in %u to %u';
                        update_option('mamwr_qtymsg',$mamwr_qtymsg, 'yes');

                       

                        

                       

                        

                        $gm_name = $this->recursive_sanitize_text_field( $_REQUEST['gm_name']);
                        $gm_min_quntity = $this->recursive_sanitize_text_field( $_REQUEST['gm_min_quntity']);
                        $gm_max_quntity = $this->recursive_sanitize_text_field( $_REQUEST['gm_max_quntity']);
                        $gm_min_cost = $this->recursive_sanitize_text_field( $_REQUEST['gm_min_cost']);
                        $gm_max_cost = $this->recursive_sanitize_text_field( $_REQUEST['gm_max_cost']);

                        $mamwr_groupmanager = array();
                        if (!empty($gm_name)){

                            for($i=0;$i<count($gm_name);$i++){

                                if($gm_name[$i]!="" || $gm_min_quntity[$i]!="" || $gm_max_quntity[$i]!="" || $gm_min_cost[$i]!="" || $gm_max_cost[$i]!=""){

                                    $mamwr_groupmanager[$i] = array(
                                        'gm_id'=>$i+1,
                                        'gm_name' => $gm_name[$i],
                                        'gm_min_quntity' => $gm_min_quntity[$i],
                                        'gm_max_quntity' => $gm_max_quntity[$i],
                                        'gm_min_cost' => $gm_min_cost[$i],
                                        'gm_max_cost' => $gm_max_cost[$i],
                                    );
                                }
                            }
                            update_option('mamwr_groupmanager',$mamwr_groupmanager,'yes');
                        }

                       

                        

                        $mamwr_extrafees = (!empty(sanitize_text_field( $_REQUEST['mamwr_extrafees'] )))? sanitize_text_field( $_REQUEST['mamwr_extrafees'] ) : 'no';
                        update_option('mamwr_extrafees',$mamwr_extrafees, 'yes');

                        

                        wp_redirect( admin_url( 'admin.php?page=woo-cartrules&message=success') ); exit;
                    }
                }
            }
        }

        function admin_footer_script(){
            ?>
                <script type="text/javascript">

                    var wrapper = jQuery('.mamwr_groping_pro'); //Input field wrapper

                    var fieldHTML = '<tr class="form-field"><td><input type="text" name="gm_name[]" id="gm_name"></td><td><input type="number"  min="0" name="gm_min_quntity[]"  id="gm_min_quntity" class="small-text ltr"></td><td><input type="number"  min="0" name="gm_max_quntity[]" id="gm_max_quntity" class="small-text ltr"></td><td><input type="number"  min="0" name="gm_min_cost[]" id="gm_min_cost" class="small-text ltr"></td><td><input type="number"  min="0" name="gm_max_cost[]" id="gm_max_cost" class="small-text ltr"></td> <td><a href="javascript:void(0);" class="gm_add_button"><img src="<?php echo esc_url( MAMWR_PLUGIN_DIR.'/images/list-add.svg');?>" style="height: 15px;"/></a></td><td><a href="javascript:void(0);" class="gm_remove_button"><img src="<?php echo esc_url( MAMWR_PLUGIN_DIR.'/images/list-remove.svg');?>" style="height: 15px;"/></a></td></tr>';


                    jQuery(wrapper).on('click', '.gm_add_button', function(e){
                        e.preventDefault();
                        jQuery(wrapper).append(fieldHTML); //Add field html   
                        
                    });

                    jQuery(wrapper).on('click', '.gm_remove_button', function(e){
                        e.preventDefault();
                        jQuery(this).closest('tr').remove(); //Remove field html
                    });
                </script>
            <?php
        }

        function init() {

            /* Total QTY Field */
            add_action('admin_menu', array($this, 'mamwr_register_my_custom_submenu_page'));

            //Save all admin options
            add_action( 'admin_init',  array($this, 'mamwr_save_options'));

            //script 
            add_action('admin_footer', array($this, 'admin_footer_script'));
        }

        public static function MAMWR_instance() {
            if (!isset(self::$MAMWR_instance)) {
                self::$MAMWR_instance = new self();
                self::$MAMWR_instance->init();
            }
            return self::$MAMWR_instance;
        }
    }
    MAMWR_admin_settings::MAMWR_instance();
}







