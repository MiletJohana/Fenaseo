<?php
/**
 * Table view to Request A Quote
 *
 * @package YITH\RequestAQuote
 * @since   1.0.0
 * @version 1.5.3
 * @author  YITH <plugins@yithemes.com>
 *
 * @var $raq_content array
 */


$product_column_colspan = apply_filters( 'ywraq_item_thumbnail', ! wp_is_mobile() ) ? 2 : 1;
$hide_price             = 'yes' === get_option( 'ywraq_hide_price', 'no' );

if ( isset( $_REQUEST['sent'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ($_REQUEST['sent'] == 1){
		return header('Location: https://fenaseo.masterquimica.com/orden-de-compra-enviada/');
	} else {
		return header('Location: https://fenaseo.masterquimica.com/orden-de-compra-error/');
	}
	
}

if ( count( $raq_content ) === 0 ) :
	header('Location: https://fenaseo.masterquimica.com/orden-de-compra-vacia/');	
	?>

<?php else : ?>
	<form id="yith-ywraq-form" name="yith-ywraq-form"
		action="<?php echo esc_url( YITH_Request_Quote()->get_raq_page_url( 'update' ) ); ?>" method="post">
		<table class="shop_table shop_table_responsive" id="yith-ywrq-table-list" cellspacing="0">
			<thead>
			<tr>
				<th class="product-remove">&nbsp;</th>
				<th class="product-name" colspan="2"><?php esc_html_e( 'Product', 'yith-woocommerce-request-a-quote' ); ?></th>
				<th class="product-quantity"><?php esc_html_e( 'Quantity', 'yith-woocommerce-request-a-quote' ); ?></th>
				<th class="product-escala"><?php esc_html_e( 'Escala', 'yith-woocommerce-request-a-quote' ); ?></th>
				<?php if ( ! $hide_price ) : ?>
					<th class="product-precio"><?php esc_html_e( 'Precio por unidad', 'yith-woocommerce-request-a-quote' ); ?></th>
				<?php endif; ?>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach ( $raq_content as $key => $raq ) :
				$product_id = ( isset( $raq['variation_id'] ) && '' !== $raq['variation_id'] ) ? $raq['variation_id'] : $raq['product_id'];
				$_product   = wc_get_product( $product_id );
				if ( ! isset( $_product ) || ! is_object( $_product ) ) {
					continue;
				}
				?>
				<tr class="cart_item">

					<td class="product-remove">
						<?php
						echo apply_filters( 'yith_ywraq_item_remove_link', sprintf( '<a href="#"  data-remove-item="%s" data-wp_nonce="%s"  data-product_id="%d" class="yith-ywraq-item-remove remove" title="%s">&times;</a>', esc_attr( $key ), esc_attr( wp_create_nonce( 'remove-request-quote-' . $product_id ) ), esc_attr( $product_id ), esc_attr__( 'Remove this item', 'yith-woocommerce-request-a-quote' ) ), $key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</td>

					<td class="product-thumbnail">
						<?php
						$thumbnail = $_product->get_image();

						if ( ! $_product->is_visible() ) {
							echo $thumbnail; //phpcs:ignore
						} else {
							printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail ); //phpcs:ignore
						}
						?>
					</td>

					<td class="product-name" data-title="Product">
						<?php
						$product_title = $_product->get_title();
						if ( $_product->get_sku() !== '' && get_option( 'ywraq_show_sku' ) === 'yes' ) {
							$product_title .= ' ' . apply_filters( 'ywraq_sku_label', __( ' SKU:', 'yith-woocommerce-request-a-quote' ) ) . $_product->get_sku();
						}
						?>
						<a href="<?php echo esc_url( $_product->get_permalink() ); ?>"><?php echo wp_kses_post( $product_title ); ?></a>
						<?php
						// Meta data.

						$item_data = array();

						// Variation data.

						if ( ! empty( $raq['variation_id'] ) && is_array( $raq['variations'] ) ) {

							foreach ( $raq['variations'] as $name => $value ) {
								$label = '';

								if ( '' === $value ) {
									continue;
								}

								$attr_taxonomy = wc_attribute_taxonomy_name( str_replace( 'attribute_pa_', '', urldecode( $name ) ) );

								// If this is a term slug, get the term's nice name.
								if ( taxonomy_exists( $attr_taxonomy ) ) {
									$attr_term = get_term_by( 'slug', $value, $attr_taxonomy );
									if ( ! is_wp_error( $attr_term ) && $attr_term && $attr_term->name ) {
										$value = $attr_term->name;
									}
									$label = wc_attribute_label( $attr_taxonomy );
								} elseif ( strpos( $name, 'attribute_' ) !== false ) {
									$custom_att = str_replace( 'attribute_', '', $name );

									if ( '' !== $custom_att ) {
										$label = wc_attribute_label( $custom_att );
									} else {
										$label = $name;
									}
								}

								$item_data[] = array(
									'key'   => $label,
									'value' => $value,
								);
							}
						}

						$item_data = apply_filters( 'ywraq_request_quote_view_item_data', $item_data, $raq, $_product );


						// Output flat or in list format.
						if ( count( $item_data ) > 0 ) {
							foreach ( $item_data as $data ) {
								echo esc_html( $data['key'] ) . ': ' . wp_kses_post( $data['value'] ) . "\n";
							}
						}


						?>
					</td>


					<td class="product-quantity" data-title="Quantity">
						<?php
						/*$product_quantity = woocommerce_quantity_input(
							array(
								'input_name'  => "raq1[{$key}][qty]",
								'product_ids'  => $_product->id,
								'input_value' => apply_filters( 'ywraq_quantity_input_value', $raq['quantity'] ),
								'max_value'   => apply_filters( 'ywraq_quantity_max_value', $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(), $_product ),
								'min_value'   => apply_filters( 'ywraq_quantity_min_value', 0, $_product ),
								'step'        => apply_filters( 'ywraq_quantity_step_value', 1, $_product ),
							),
							$_product,
							false
						);*/

						echo $raq['quantity']; //phpcs:ignore
						?>
					</td>
					<?php
					
						global $wpdb;
				
						$sql = $wpdb -> prepare("SELECT escala, precio FROM wpgb_escala_producto WHERE id_producto = ".$_product->id." AND vol_min <= ".$raq['quantity']." ORDER BY vol_min DESC LIMIT 1;");
					
						$query = $wpdb -> get_results($sql);
					
					?>

					<td class="product-escala" data-title="Escala">
						<?php 
							if(count($query) > 0 && isset($query[0]->escala)){ 
								echo $query[0]->escala;
							} else {
								echo '0';
							} 
						?>
					</td>
					<td class="product-precio">
						<?php 
							if(count($query) > 0 && isset($query[0]->precio)){ 
								echo "$ " . number_format($query[0]->precio, 0, ',', '.');
							} else {
								echo wp_kses_post( WC()->cart->get_product_price( $_product ));
							} 
						?>
					</td>
				</tr>

			<?php endforeach ?>

			<?php
			if ( get_option( 'ywraq_show_update_list' ) === 'yes' ) :
					$colspan = $hide_price ? 5 : 6;
				?>
				<tr>
					<td colspan="<?php echo esc_attr( $colspan ); ?>" class="pagelayer-alert pagelayer-alert-warning pagelayer-alert-dismissible">
						<p>*Si desea realizar un cambio en la cantidad del producto debe <b>Eliminar el producto y volverlo agregar.</b>*</p>
					</td>
				</tr>
			
			<?php endif ?>


			</tbody>
		</table>
	</form>
<?php endif ?>

