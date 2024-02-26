<?php

$error_message = '';

if ( isset( $_POST['save_wc_update_price_percent_nonce'] ) && wp_verify_nonce( $_POST['save_wc_update_price_percent_nonce'], 'save_wc_update_price_percent' ) ) {
	$error_message = '';

	if ( isset( $_POST['amount'] ) ) {
		//$wc_update_price_percent_settings = get_option('wc_update_price_percent_settings');
		// print_r($wc_update_price_percent_settings);
		$wc_update_price_percent = sanitize_text_field( $_POST['amount'] );
		//update_option('wc_update_price_percent_settings', $wc_update_price_percent);
		//$wc_update_price_percent_settings = get_option('wc_update_price_percent_settings');

		if ( is_numeric( $wc_update_price_percent ) ) {

				$simple_products = wc_get_products(
					array(
						'type'        => 'simple',
						'limit'       => -1,
						'post_status' => 'publish',
					)
				);

				$variation_products = wc_get_products(
					array(
						'type'        => 'variation',
						'limit'       => -1,
						'post_status' => 'publish',
					)
				);


				$subscription = wc_get_products(
					array(
						'type'        => 'subscription',
						'limit'       => -1,
						'post_status' => 'publish',
					)
				);

				$variable_subscription = wc_get_products(
					array(
						'type'        => 'variable-subscription',
						'limit'       => -1,
						'post_status' => 'publish',
					)
				);


			$products_woocmmerce = array_merge( $simple_products, $variation_products );
			$products            = array_merge( $subscription, $products_woocmmerce );


			$len  = count( $products );
			$len1 = count( $variable_subscription );

			for ( $k = 0;$k < $len1;$k++ ) {

				$product_sub = $variable_subscription[ $k ];
				$prov_id     = $product_sub->get_ID();


				$variation_id         = '';
				$display_price        = '';
				$updated_price        = '';
				$signupfee            = '';
				$signupfee3           = '';
				$available_variations = '';



				$available_variations = $product_sub->get_available_variations();

				foreach ( $available_variations as $key => $val ) {
					$variation_id  = '';
					$variation_id  = $available_variations[ $key ]['variation_id'];
					$display_price = $available_variations[ $key ]['display_price'];
					$signupfee     = get_post_meta( $variation_id, '_subscription_sign_up_fee', true );

					$updated_price = number_format( ( $display_price / $wc_update_price_percent ) * 100, 3 );


					update_post_meta( $variation_id, '_price', $updated_price );
					update_post_meta( $variation_id, '_subscription_price', $updated_price );
					update_post_meta( $variation_id, '_regular_price', $updated_price );

					if ( ! empty( $signupfee ) ) {
						$signupfee_price = number_format( ( $signupfee / $wc_update_price_percent ) * 100, 3 );

						$subcription_vari_arr[] = $variation_id;
						update_post_meta( $variation_id, '_subscription_sign_up_fee', $signupfee_price );
					}
				}

				$signupfee3 = get_post_meta( $prov_id, '_subscription_sign_up_fee', true );
				if ( ! empty( $signupfee3 ) ) {
					$signupfee_price3 = number_format( ( $signupfee3 / $wc_update_price_percent ) * 100, 3 );
					update_post_meta( $prov_id, '_subscription_sign_up_fee', $signupfee_price3 );
				}
			}

			for ( $i = 0;$i < $len;$i++ ) {


				$product  = $products[ $i ];
				$prov_idd = $product->get_ID();

				if ( ! in_array( $prov_idd, $subcription_vari_arr ) ) {


					$signupfee1 = '';

					$signupfee1 = get_post_meta( $prov_idd, '_subscription_sign_up_fee', true );
					if ( ! empty( $signupfee1 ) ) {
						$signupfee_price1 = number_format( ( $signupfee1 / $wc_update_price_percent ) * 100, 3 );
						update_post_meta( $prov_idd, '_subscription_sign_up_fee', $signupfee_price1 );
					}

						$cur_price     = $product->get_price();
						$regular_price = $product->get_regular_price();
						$sale_price    = $product->get_sale_price();
						// echo "<br>cur_price > ".$cur_price;
						$updated_price2        = number_format( ( $cur_price / $wc_update_price_percent ) * 100, 3 );
						$updated_regular_price = number_format( ( $regular_price / $wc_update_price_percent ) * 100, 3 );
						$updated_sale_price    = number_format( ( $sale_price / $wc_update_price_percent ) * 100, 3 );

						//echo "<br>updated_price >>> ".$updated_price;
					if ( $updated_price2 > 0 ) {
						$product->set_price( "$updated_price2" );
					} else {
						$product->set_price( "$updated_regular_price" );
					}
					if ( $updated_regular_price > 0 ) {
						$product->set_regular_price( "$updated_regular_price" );
					} else {
						$product->set_regular_price( "$updated_price2" );
					}
					if ( $updated_sale_price > 0 ) {
						$product->set_sale_price( "$updated_sale_price" );
					} else {
						$product->set_sale_price( "$updated_regular_price" );
					}


					$product->save();


				}
			}

			$error_message = 'Prices are updated successfully.';
		} else {
			$error_message = 'Percentage should be integer value';
		}
	}
}
//$wc_update_price_percent_settings = get_option('wc_update_price_percent_settings');
?>
<div class="wrap tc_wrap">
	<?php if ( ! empty( $error_message ) ) { ?>
		<div class="error"><p><?php echo $error_message; ?></p></div>
	<?php } ?>

	<div id="poststuff">
		<form action="" method="post" enctype="multipart/form-data">
			<div class="postbox">
				<h3 class="hndle"><span><?php _e( 'WC Update Product Prices', 'tcawp' ); ?></span></h3>
				<div class="inside">
					<table class="form-table">
						<tbody>
							<tr>
								<th scope="row"><label for="qr_code_type"><?php _e( 'Percentage', 'tcawp' ); ?></label></th>
								<td>
									<input name="amount" min="100" max="199" required title="3 characters minimum"  type="number" id="amount" value="<?php echo isset( $_POST['amount'] ) ? $_POST['amount'] : '0'; ?>" class="regular-text" />%
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<?php
			wp_nonce_field( 'save_wc_update_price_percent', 'save_wc_update_price_percent_nonce' );
			?>
			<?php submit_button(); ?>
		</form>
	</div>
</div>
