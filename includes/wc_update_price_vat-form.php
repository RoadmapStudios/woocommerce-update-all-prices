<?php

$error_message = '';

if (isset($_POST['save_wc_update_price_percent_nonce']) && wp_verify_nonce($_POST['save_wc_update_price_percent_nonce'], 'save_wc_update_price_percent')) {
    $error_message = "";
    
    if(isset($_POST['wc_update_price_percent'])) {
$wc_update_price_percent_settings = get_option('wc_update_price_percent_settings');
// print_r($wc_update_price_percent_settings);
        $wc_update_price_percent = $_POST['wc_update_price_percent'];
        update_option('wc_update_price_percent_settings', $wc_update_price_percent);
        $wc_update_price_percent_settings = get_option('wc_update_price_percent_settings');

        if(is_numeric($wc_update_price_percent['amount'])) {
              $simple_products = wc_get_products( array(
                   'type' => 'simple',
                   'limit' => -1,
                ) );
                
            $variation_products = wc_get_products( array(
               'type' => 'variation',
               'limit' => -1,
            ) );
                
            $products = array_merge( $simple_products, $variation_products );
           
            $len = count($products);
            
            for($i=0;$i<$len;$i++) {
                $product = $products[$i];
                // echo "<pre>";
                // print_r($product);
                // echo "</pre>";
                $cur_price = $product->get_price();
                $regular_price = $product->get_regular_price();
                $sale_price = $product->get_sale_price();
                // echo "<br>cur_price > ".$cur_price;
                $updated_price = number_format(($cur_price / $wc_update_price_percent['amount']) * 100, 3);

                $updated_regular_price = number_format(($regular_price / $wc_update_price_percent['amount']) * 100, 3);
      
                $updated_sale_price = number_format(($sale_price / $wc_update_price_percent['amount']) * 100, 3);
      
                // echo "<br>updated_price >>> ".$updated_price;
                if($updated_price > 0) {
                    $product->set_price("$updated_price");    
                } else {
                    $product->set_price("$updated_regular_price");    
                }
                if($updated_regular_price > 0) {
                    $product->set_regular_price( "$updated_regular_price" );        
                } else {
                    $product->set_regular_price( "$updated_price" );        
                }
                if($updated_sale_price > 0) {
                    $product->set_sale_price( "$updated_sale_price" );        
                } else {
                    $product->set_sale_price( "$updated_regular_price" ); 
                }
                
                $product->save();
                // echo "<br>After Price > ".$product->get_price();
                // echo "<pre>";
                // print_r($product);
                // echo "</pre>";
            }
            $error_message = "Prices are updated successfully.";
        } else {
            $error_message = "Percentage should be integer value";
        }
    }
}
$wc_update_price_percent_settings = get_option('wc_update_price_percent_settings');
?>
<div class="wrap tc_wrap">
    <?php if (!empty($error_message)) { ?>
        <div class="error"><p><?php echo $error_message; ?></p></div>
    <?php } ?>

    <div id="poststuff">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="postbox">
                <h3 class="hndle"><span><?php _e('WC Update Product Prices', 'tcawp'); ?></span></h3>
                <div class="inside">
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row"><label for="qr_code_type"><?php _e('Percentage', 'tcawp') ?></label></th>
                                <td>
                                    <input name="wc_update_price_percent[amount]" type="text" id="amount" value="<?php echo isset($wc_update_price_percent_setting['amount']) ? $wc_update_price_percent_setting['amount'] : '0'; ?>" class="regular-text">%
                                </td>
                            </tr>
                            


                        </tbody>
                    </table>
                </div>
            </div>

           
            <?php wp_nonce_field('save_wc_update_price_percent', 'save_wc_update_price_percent_nonce');
            ?>
            <?php submit_button(); ?>
        </form>
    </div>
</div>