<?php 
if (!defined('ABSPATH'))
    exit;

if (!class_exists('MAMWR_functionality')) {
    class MAMWR_functionality {

        /**
        * Constructor.
        *
        * @version 3.2.3
        */
        public $mamwr_enabled,$mamwr_min,$mamwr_max,$mamwr_qtymsg,$mamwr_singleqtymsg,$mamwr_catqtymsg,$mamwr_minprice,$mamwr_maxprice,$mamwr_roles,$mamwr_varqtymsg,$mamwr_groupmanager,$mamwr_pricemsg,$mamwr_pricecatmsg,$mamwr_costgroupmsg,$mamwr_groupqtymsg;

        function __construct() {
            //Enable Plugin
            if ( 'yes' === get_option( 'mamwr_enabled') ) {
                $this->mamwr_enabled = get_option('mamwr_enabled');
            }

            //Get value minimum qty
            if ( !empty(get_option( 'min_cart_quntity')) ) {
                $this->mamwr_min = get_option('min_cart_quntity');
            }

            //Get value maximum qty
            if ( !empty(get_option( 'max_cart_quntity')) ) {
                $this->mamwr_max = get_option('max_cart_quntity');
            }
            
            //Get Quantity Messages comman
            if ( !empty(get_option( 'mamwr_qtymsg')) ) {
                $this->mamwr_qtymsg = get_option('mamwr_qtymsg');
            }

            //Get Quantity Messages single product
            if ( !empty(get_option( 'mamwr_singleqtymsg')) ) {
                $this->mamwr_singleqtymsg = get_option('mamwr_singleqtymsg');
            }

            //Get Quantity Messages Category product
            if ( !empty(get_option( 'mamwr_catqtymsg')) ) {
                $this->mamwr_catqtymsg = get_option('mamwr_catqtymsg');
            }

            //Get Quantity Messages group product
            if ( !empty(get_option( 'mamwr_groupqtymsg')) ) {
                $this->mamwr_groupqtymsg = get_option('mamwr_groupqtymsg');
            }

            //Get value of min price
            if ( !empty(get_option( 'mamwr_minprice')) ) {
                $this->mamwr_minprice = get_option('mamwr_minprice');
            }

            //Get value of max price
            if ( !empty(get_option( 'mamwr_maxprice')) ) {
                $this->mamwr_maxprice = get_option('mamwr_maxprice');
            }

            //Get value of user roles for general option
            if ( !empty(get_option( 'mamwr_roles')) ) {
                $this->mamwr_roles = get_option('mamwr_roles');
            }

            //Get Quantity Messages variation product
            if ( !empty(get_option( 'mamwr_varqtymsg')) ) {
                $this->mamwr_varqtymsg = get_option('mamwr_varqtymsg');
            }

            //Get group manager data
            if ( !empty(get_option( 'mamwr_groupmanager')) ) {
              $this->mamwr_groupmanager = get_option('mamwr_groupmanager');
            }

            //Get price messages comman
            if ( !empty(get_option( 'mamwr_pricemsg')) ) {
              $this->mamwr_pricemsg = get_option('mamwr_pricemsg');
            }
            
            //Get price messages Category product
            if ( !empty(get_option( 'mamwr_pricemsg')) ) {
              $this->mamwr_pricecatmsg = get_option('mamwr_pricecatmsg');
            }

            //Get price messages group product
            if ( !empty(get_option( 'mamwr_costgroupmsg')) ) {
              $this->mamwr_costgroupmsg = get_option('mamwr_costgroupmsg');
            }
        }
          
        protected static $MAMWR_instance;

        function mamwr_check_if_selected() { 
            global $current_user;
            $user_roles = $current_user->roles[0];
            $count = WC()->cart->get_cart_contents_count();

            if(!empty($this->mamwr_min) && !empty($this->mamwr_max)){

                if($this->mamwr_min > $count || $this->mamwr_max < $count){

                    if ( is_checkout() || is_page( 'checkout' )) {

                        if ( is_user_logged_in() && !empty($this->mamwr_roles)) {

                            if (in_array($user_roles, $this->mamwr_roles)) {
                                wc_add_notice( __(sprintf($this->mamwr_qtymsg,$this->mamwr_min,$this->mamwr_max), MAMWR_DOMAIN), 'error');
                            }
                        }
                    }
                }
            }
        }

        function mamwr_product_validate_cart_item_add($passed, $product_id, $quantity, $variation_id = '', $variations= ''){
            $product = wc_get_product( $product_id );
            $product_id = $product_id;
            global $current_user;
            $user_roles = $current_user->roles[0];
            if( $product->get_type() == 'variable' ){

                $mamwr_min_qty = get_post_meta($variation_id, '_mamwr_min_qty', true);
                $mamwr_max_qty = get_post_meta($variation_id, '_mamwr_max_qty', true);

                global $woocommerce;

                $items = $woocommerce->cart->get_cart();
                $variation_sum=0;

                foreach($items as $item => $values) { 
                    if($values['variation_id'] == $variation_id){
                        $variation_sum = $values['quantity'] + $quantity;
                    }   
                } 

                if(!empty($mamwr_min_qty) && !empty($mamwr_max_qty)){
                    if($variation_sum == 0) {
                        if ($mamwr_max_qty >= $quantity && $mamwr_min_qty <= $quantity) {
                            $passed = true;
                        } else {
                            if ( is_user_logged_in() && !empty($this->mamwr_roles)) {
                                if (in_array($user_roles, $this->mamwr_roles)) {
                                    $passed = false;
                                    wc_add_notice( __(sprintf($this->mamwr_varqtymsg,$product->get_name(),$mamwr_min_qty,$mamwr_max_qty), MAMWR_DOMAIN), 'error');
                                }
                            } else{
                                $passed = false;
                                wc_add_notice( __(sprintf($this->mamwr_varqtymsg,$product->get_name(),$mamwr_min_qty,$mamwr_max_qty), MAMWR_DOMAIN), 'error');
                            }
                        } 
                    } else {

                        if ($mamwr_min_qty <= $variation_sum &&  $mamwr_max_qty >= $variation_sum){
                            $passed = true;
                        } else {
                            if ( is_user_logged_in() && !empty($this->mamwr_roles) ) {
                                if (in_array($user_roles, $this->mamwr_roles)) {
                                  $passed = false;
                                  wc_add_notice( __(sprintf($this->mamwr_varqtymsg,$product->get_name(),$mamwr_min_qty,$mamwr_max_qty), MAMWR_DOMAIN), 'error');
                                }
                            } else {
                                $passed = false;
                                wc_add_notice( __(sprintf($this->mamwr_varqtymsg,$product->get_name(),$mamwr_min_qty,$mamwr_max_qty), MAMWR_DOMAIN), 'error');
                            }   
                        }
                    }
                }
            } else {
                $mamwr_groupvalue = get_post_meta($product_id, '_mamwr_groupvalue', true);
                $mamwrmin = get_post_meta($product_id, '_custom_product_number_field_min', true);
                $mamwrmax = get_post_meta($product_id, '_custom_product_number_field_max', true);

                if(empty($mamwrmin) && empty($mamwrmin)){
                    if(!empty($mamwr_groupvalue)){
                        foreach ($this->mamwr_groupmanager as $mamwr_gm_key => $mamwr_gm_value) {
                            if($mamwr_gm_value['gm_id'] == $mamwr_groupvalue){
                                $min = $mamwr_gm_value['gm_min_quntity'];
                                $max = $mamwr_gm_value['gm_max_quntity'];
                                $singlecust_message = 'group';
                                $singlegmname = $mamwr_gm_value['gm_name'];
                            }
                        }
                    }
                } else {
                    $min = $mamwrmin;
                    $max = $mamwrmax;
                    $singlecust_message = 'normal';
                }

                $mam_p_roles = explode(",",get_post_meta($product_id, 'mam_p_roles', true));
                $qun = $quantity;
                global $woocommerce;
                $items = $woocommerce->cart->get_cart();
                $sum=0;
                foreach($items as $item => $values) { 
                    if($values['product_id'] == $product_id) {
                        $sum = $values['quantity'] + $qun;
                    }   
                } 

                if(!empty($min) && !empty($max)){
                    if($sum == 0) {
                        if ($max >= $qun && $min <= $qun) {
                            $passed = true;
                        } else{
                            if ( is_user_logged_in() && $mam_p_roles !== array(0 => '')) {
                                if (in_array($user_roles, $mam_p_roles)) {
                                    $passed = false;
                                    if($singlecust_message == 'group') {
                                        wc_add_notice( __(sprintf($this->mamwr_groupqtymsg,$singlegmname,$min,$max), MAMWR_DOMAIN), 'error');
                                    } else if($singlecust_message == 'normal'){
                                        wc_add_notice( __(sprintf($this->mamwr_singleqtymsg,$min,$max), MAMWR_DOMAIN), 'error');
                                    }
                                }
                            } else{
                                $passed = false;
                                if($singlecust_message == 'group') {
                                        wc_add_notice( __(sprintf($this->mamwr_groupqtymsg,$singlegmname,$min,$max), MAMWR_DOMAIN), 'error');
                                } else if($singlecust_message == 'normal') {
                                        wc_add_notice( __(sprintf($this->mamwr_singleqtymsg,$min,$max), MAMWR_DOMAIN), 'error');
                                }
                            }
                        }
                    } else {
                        if ($min <= $sum &&  $max >= $sum){
                            $passed = true;
                        } else {
                            if ( is_user_logged_in() && $mam_p_roles !== array(0 => '') ) {
                                if (in_array($user_roles, $mam_p_roles)){
                                    $passed = false;
                                    if($singlecust_message == 'group'){
                                        wc_add_notice( __(sprintf($this->mamwr_groupqtymsg,$singlegmname,$min,$max), MAMWR_DOMAIN), 'error');
                                    } else if($singlecust_message == 'normal'){
                                        wc_add_notice( __(sprintf($this->mamwr_singleqtymsg,$min,$max), MAMWR_DOMAIN), 'error');
                                    }
                                }
                            } else {
                                $passed = false;
                                if($singlecust_message == 'group'){
                                    wc_add_notice( __(sprintf($this->mamwr_groupqtymsg,$singlegmname,$min,$max), MAMWR_DOMAIN), 'error');
                                } else if($singlecust_message == 'normal'){
                                    wc_add_notice( __(sprintf($this->mamwr_singleqtymsg,$min,$max), MAMWR_DOMAIN), 'error');
                                }
                            }         
                        }
                    }
                }
            }
            return $passed;
        } 

        function mamwr_category_product_validate_cart_item_add($passed, $product_id, $quantity, $variation_id = '', $variations= ''){
            $mam_p_term_list = wp_get_post_terms($product_id,'product_cat',array('fields'=>'ids'));
            if (!empty($mam_p_term_list)) {
                foreach ($mam_p_term_list as $key => $value) {
                    $mam_p_min_qty = get_term_meta($value, 'mam_p_min_qty', true);
                    $mam_p_max_qty = get_term_meta($value, 'mam_p_max_qty', true);
                    $mam_p_roles = explode(",",get_term_meta($value, 'mam_p_roles', true));
                    $mamwr_term = get_term_by( 'id', $value, 'product_cat' );
                      
                    global $woocommerce;
                    $items = $woocommerce->cart->get_cart();
                    $sum=0;
                    foreach($items as $item => $values) { 
                        if($values['product_id'] == $product_id)
                        {
                          $sum = $values['quantity'] + $quantity;
                        }   
                    } 

                    if(!empty($mam_p_min_qty) && !empty($mam_p_max_qty)){
                        if($sum == 0) {
                            if ($mam_p_max_qty >= $quantity && $mam_p_min_qty <= $quantity) {
                                $passed = true;
                            } else{
                                if ( is_user_logged_in() && $mam_p_roles !== array(0 => '')) {
                                    global $current_user;
                                    $user_roles = $current_user->roles[0];
                                    if (in_array($user_roles, $mam_p_roles)) {
                                        $passed = false;
                                        wc_add_notice( __(sprintf($this->mamwr_catqtymsg,$mamwr_term->name,$mam_p_min_qty,$mam_p_max_qty), MAMWR_DOMAIN), 'error');
                                    }
                                } else{
                                    $passed = false;
                                    wc_add_notice( __(sprintf($this->mamwr_catqtymsg,$mamwr_term->name,$mam_p_min_qty,$mam_p_max_qty), MAMWR_DOMAIN), 'error');
                                }
                            }
                        } else {
                            if ($mam_p_min_qty<$sum &&  $mam_p_max_qty>$sum){
                                $passed = true;
                            } else {
                                if ( is_user_logged_in() && $mam_p_roles !== array(0 => '') ) {
                                    global $current_user;
                                    $user_roles = $current_user->roles[0];
                                    if (in_array($user_roles, $mam_p_roles)){
                                        $passed = false;
                                        wc_add_notice( __(sprintf($this->mamwr_catqtymsg,$mamwr_term->name,$mam_p_min_qty,$mam_p_max_qty), MAMWR_DOMAIN), 'error');
                                    }
                                } else {
                                    $passed = false;
                                    wc_add_notice( __(sprintf($this->mamwr_catqtymsg,$mamwr_term->name,$mam_p_min_qty,$mam_p_max_qty), MAMWR_DOMAIN), 'error');
                                }
                            }
                        }
                    }      
                }
            }   
            return $passed;
        } 

        function mamwr_product_validate_cart_item_update($passed, $cart_item_key, $product_id, $quantity){
            $product_ids = $product_id;
            $product_id = $product_ids['product_id'];
            $product = wc_get_product( $product_id );

            global $current_user;
            $user_roles = $current_user->roles[0];                    

            if( $product->get_type() == 'variable' ){

                $variation_id = $product_ids['variation_id'];
                $mamwr_min_qty = get_post_meta($variation_id, '_mamwr_min_qty', true);
                $mamwr_max_qty = get_post_meta($variation_id, '_mamwr_max_qty', true);

                if(!empty($mamwr_min_qty) && !empty($mamwr_max_qty)){
                    if ($mamwr_min_qty <= $quantity &&  $mamwr_max_qty >= $quantity){
                        $passed = true;
                    } else {
                        if ( is_user_logged_in() && !empty($this->mamwr_roles) ) {
                            if (in_array($user_roles, $this->mamwr_roles)){
                                  $passed = false;
                                  wc_add_notice( __(sprintf($this->mamwr_varqtymsg,$product->get_name(),$mamwr_min_qty,$mamwr_max_qty), MAMWR_DOMAIN), 'error');
                                 
                            }
                        } else {
                            $passed = false;
                            wc_add_notice( __(sprintf($this->mamwr_varqtymsg,$product->get_name(),$mamwr_min_qty,$mamwr_max_qty), MAMWR_DOMAIN), 'error');
                           
                        } 
                    }
                }
            } else {
                $mamwr_groupvalue = get_post_meta($product_id, '_mamwr_groupvalue', true);
                $mamwrmin = get_post_meta($product_id, '_custom_product_number_field_min', true);
                $mamwrmax = get_post_meta($product_id, '_custom_product_number_field_max', true);

                if(empty($mamwrmin) && empty($mamwrmin)){
                    if(!empty($mamwr_groupvalue)){
                        foreach ($this->mamwr_groupmanager as $mamwr_gm_key => $mamwr_gm_value) {
                            if($mamwr_gm_value['gm_id'] == $mamwr_groupvalue){
                                $min = $mamwr_gm_value['gm_min_quntity'];
                                $max = $mamwr_gm_value['gm_max_quntity'];
                            }
                        }
                    }
                } else {
                    $min = $mamwrmin;
                    $max = $mamwrmax;
                }   

                $mam_p_roles = explode(",",get_post_meta($product_id, 'mam_p_roles', true));
                if(!empty($min) && !empty($max)){
                    if ($min <= $quantity &&  $max >= $quantity){
                        $passed = true;
                    } else {
                        if ( is_user_logged_in() && $mam_p_roles !== array(0 => '') ) {
                            if (in_array($user_roles, $mam_p_roles)){
                                $passed = false;
                                wc_add_notice( __(sprintf($this->mamwr_singleqtymsg,$min,$max), MAMWR_DOMAIN), 'error');
                                
                            }
                        } else {
                            $passed = false;
                            wc_add_notice( __(sprintf($this->mamwr_singleqtymsg,$min,$max), MAMWR_DOMAIN), 'error');
                            
                        } 
                    }
                }
            }
            return $passed;
        } 

        function mamwr_category_validate_cart_item( $passed, $cart_item_key, $values, $quantity ) { 
            $mam_p_term_list = wp_get_post_terms($values['product_id'],'product_cat',array('fields'=>'ids'));
            if (!empty($mam_p_term_list)) {
                foreach ($mam_p_term_list as $key => $value) {
                    $mam_p_min_qty = get_term_meta($value, 'mam_p_min_qty', true);
                    $mam_p_max_qty = get_term_meta($value, 'mam_p_max_qty', true);
                    $mam_p_roles = explode(",",get_term_meta($value, 'mam_p_roles', true));
                    $mamwr_term = get_term_by( 'id', $value, 'product_cat' );
                    if(!empty($mam_p_min_qty) && !empty($mam_p_max_qty)) {

                        if ($mam_p_min_qty <= $quantity &&  $mam_p_max_qty >= $quantity){
                            $passed = true;
                        } else {

                            if ( is_user_logged_in() && $mam_p_roles !== array(0 => '') ) {
                                global $current_user;
                                $user_roles = $current_user->roles[0];
                                if (in_array($user_roles, $mam_p_roles)){
                                    $passed = false;
                                    wc_add_notice( __(sprintf($this->mamwr_catqtymsg,$mamwr_term->name,$mam_p_min_qty,$mam_p_max_qty), MAMWR_DOMAIN), 'error');
                                    
                                }
                            } else {
                                $passed = false;
                                wc_add_notice( __(sprintf($this->mamwr_catqtymsg,$mamwr_term->name,$mam_p_min_qty,$mam_p_max_qty), MAMWR_DOMAIN), 'error');
                                
                            } 
                        }
                    }
                }
            }
            return $passed;
        }

        function mamwr_min_max_quantities_proceed_to_checkout_conditions(){
            
            $checkout_url = wc_get_checkout_url();

            global $woocommerce;

            $qty = 0;

            $total_quantitys = $woocommerce->cart->cart_contents_count;

            $amt_total   = floatval( WC()->cart->cart_contents_total );

            $amt_total_fees = floatval( preg_replace( '#[^\d.]#', '', $woocommerce->cart->get_total() ) );

            
            
            
            $total_quantity = $total_quantitys;
            $total_amount = $amt_total;

            //echo $total_quantity;
            //echo "total amount".$total_amount;
            global $current_user;

            $user_roles = $current_user->roles[0];

            // total quantity validation
            if ( WC()->cart->get_cart_contents_count() != 0 ) {
                if(!empty($this->mamwr_min) && !empty($this->mamwr_max)) {
                    if($this->mamwr_min > $total_quantity || $this->mamwr_max < $total_quantity) {
                        if ( is_user_logged_in() && !empty($this->mamwr_roles)) {
                            if (in_array($user_roles, $this->mamwr_roles)) {
                                wc_add_notice( __(sprintf($this->mamwr_qtymsg,$this->mamwr_min,$this->mamwr_max), MAMWR_DOMAIN), 'error');
                               
                            }
                        } else{
                            wc_add_notice( __(sprintf($this->mamwr_qtymsg,$this->mamwr_min,$this->mamwr_max), MAMWR_DOMAIN), 'error');
                           
                        }
                    }
                }
            }

            // total cost validation
            if ( WC()->cart->get_cart_contents_count() != 0 ) {
                if(!empty($this->mamwr_minprice) && !empty($this->mamwr_maxprice)){
                    if($this->mamwr_minprice > $total_amount || $this->mamwr_maxprice < $total_amount){
                        if ( is_user_logged_in() && !empty($this->mamwr_roles)) {
                            if (in_array($user_roles, $this->mamwr_roles)) {
                                wc_add_notice( __(sprintf($this->mamwr_pricemsg,$this->mamwr_minprice,$this->mamwr_maxprice), MAMWR_DOMAIN), 'error');
                                
                            }
                        } else {
                            wc_add_notice( __(sprintf($this->mamwr_pricemsg,$this->mamwr_minprice,$this->mamwr_maxprice), MAMWR_DOMAIN), 'error');
                            
                        }  
                    }
                }
            }

            // total cost validation ( category wise )
            $items = WC()->cart->get_cart();
            $categorydatas = array();
            $groupdatas = array();
            $mamwr_groupmanager = get_option( 'mamwr_groupmanager' );
           
            foreach ( $items as $item ) {
                $mamwr_groupvalss = array();
                $product_id    = $item['product_id'];
                $qty           = $item['quantity'];
                $product_name  = $item['data']->get_title();

                $mamwr_term_list = wp_get_post_terms($product_id,'product_cat',array('fields'=>'ids'));
             
                foreach ($mamwr_term_list as $mamwr_key => $mamwr_value) {

                    $mamwr_minpricec = get_term_meta($mamwr_value, 'mamwr_minprice', true);
                    $mamwr_maxpricec = get_term_meta($mamwr_value, 'mamwr_maxprice', true);

                    if(!empty($mamwr_minpricec) && !empty($mamwr_maxpricec)) {
                        $categorydatas[$mamwr_value] = $categorydatas[$mamwr_value] + $item['line_total'];
                    } else {
                        $mamwr_groupvalue = get_post_meta($product_id, '_mamwr_groupvalue', true);
                        $gpval = get_post_meta($product_id, '_mamwr_groupvalue', true);
                        if(!empty($gpval)){
                            foreach ($mamwr_groupmanager as $gmkey => $gmvalue) {
                                if($gpval == $gmvalue['gm_id']){
                                    $mamwr_groupvalss[] = $gpval;
                                }
                            }
                        }

                        foreach ($mamwr_groupvalss as $mamwr_groupvalsskey => $mamwr_groupvalssvalue) {
                            $groupdatas[$mamwr_groupvalssvalue] = $groupdatas[$mamwr_groupvalssvalue] + $item['line_total'] ;
                        }
                    }
                }
            }

            if ( WC()->cart->get_cart_contents_count() != 0 ) {
                if(!empty($categorydatas)) {
                    foreach ($categorydatas as $cate_key => $totalvalue) {

                        $mamwr_minpricec = get_term_meta($cate_key, 'mamwr_minprice', true);

                        $mamwr_maxpricec = get_term_meta($cate_key, 'mamwr_maxprice', true);

                        $mamwr_termc = get_term_by( 'id', $cate_key, 'product_cat' );

                        if(!empty($mamwr_minpricec) && !empty($mamwr_maxpricec)) {

                            if($mamwr_minpricec > $totalvalue || $mamwr_maxpricec < $totalvalue) {
                 
                                if ( is_user_logged_in() && !empty($this->mamwr_roles)) {
                  
                                    if (in_array($user_roles, $this->mamwr_roles)) {

                                        wc_add_notice( __(sprintf($this->mamwr_pricecatmsg,$mamwr_termc->name,$mamwr_minpricec,$mamwr_maxpricec), MAMWR_DOMAIN), 'error');
                                        
                                    }
                                } else {
                                    wc_add_notice( __(sprintf($this->mamwr_pricecatmsg,$mamwr_termc->name,$mamwr_minpricec,$mamwr_maxpricec), MAMWR_DOMAIN), 'error');
                                    
                                }         
                            }
                        } 
                    }
                } 
            }

            if ( WC()->cart->get_cart_contents_count() != 0 ) {
                if(!empty($groupdatas)){

                    foreach ($groupdatas as $gds_key => $gds_value) {

                        foreach ($mamwr_groupmanager as $gmkey => $gmvalue) {

                            if($gds_key == $gmvalue['gm_id']){

                                $gds_mincost = $gmvalue['gm_min_cost'];
                                $gds_maxcost = $gmvalue['gm_max_cost'];
                                $gds_name = $gmvalue['gm_name'];

                                if(!empty($gds_mincost) && !empty($gds_maxcost)){

                                    if($gds_mincost > $gds_value || $gds_maxcost < $gds_value){
                             
                                        if ( is_user_logged_in() && !empty($this->mamwr_roles)) {
                              
                                            if (in_array($user_roles, $this->mamwr_roles)) {

                                                wc_add_notice( __(sprintf($this->mamwr_costgroupmsg,$gds_name,$gds_mincost,$gds_maxcost), MAMWR_DOMAIN), 'error');
                                                
                                            }
                                        } else {
                                            wc_add_notice( __(sprintf($this->mamwr_costgroupmsg,$gds_name,$gds_mincost,$gds_maxcost), MAMWR_DOMAIN), 'error');
                                            
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        
        function mamwr_min_max_notice() {
            global $product;
            $product_id = $product->get_id();
            $minqty = get_post_meta($product_id,'_custom_product_number_field_min',true);
            $maxqty = get_post_meta($product_id,'_custom_product_number_field_max',true);
            if(!empty($minqty) && !empty($maxqty) ) {
                ?>
                <div class="mam_noticeqty">
                  <p class="mam_qty_notice">Qty Must be Between <?php echo $minqty; ?> and <?php echo $maxqty; ?></p>
                </div>
                <style type="text/css">
                    p.mam_qty_notice {
                        padding: 8px;
                        background-color: #cce5ff;
                        border-color: #b8daff;
                        color: #000;
                        font-weight: 600;
                    }
                </style>
                <?php
            }
        }

        function init() {
            if($this->mamwr_enabled === 'yes'){
                //cart page qty validation
                add_action('woocommerce_checkout_process', array($this, 'mamwr_check_if_selected'));

                //single product page qty validation
                add_filter( 'woocommerce_add_to_cart_validation',array($this, 'mamwr_product_validate_cart_item_add'), 10, 4);

                //single product page qty validation ( category )
                add_filter( 'woocommerce_add_to_cart_validation',array($this, 'mamwr_category_product_validate_cart_item_add'), 10, 4);

                //cart page validation for single product
                add_filter( 'woocommerce_update_cart_validation', array($this, 'mamwr_product_validate_cart_item_update'), 10, 4 );

                //cart page validation for category wise product
                add_filter( 'woocommerce_update_cart_validation', array($this, 'mamwr_category_validate_cart_item'), 10, 4 );

                //all cost  validation
                add_action( 'woocommerce_check_cart_items', array($this, 'mamwr_min_max_quantities_proceed_to_checkout_conditions'));

                add_action( 'woocommerce_before_add_to_cart_form', array($this, 'mamwr_min_max_notice' ));
            }
        }

        public static function MAMWR_instance() {
            if (!isset(self::$MAMWR_instance)) {
                self::$MAMWR_instance = new self();
                self::$MAMWR_instance->init();
            }
            return self::$MAMWR_instance;
        }
    }
    MAMWR_functionality::MAMWR_instance();
}

 
