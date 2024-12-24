<?php



/**



 * HTML Template Email



 *



 * @package YITH\RequestAQuote



 * @since   1.0.0



 * @version 1.5.3



 * @author  YITH <plugins@yithemes.com>



 *



 * @var $email_heading array



 * @var $raq_data array



 * @var $email



 */







?>







<?php

	date_default_timezone_set("America/Bogota");

	$fecha = date("Y-m-d H:i:s");

 	global $wpdb;

	/* Inserción ID orden de compra */
	$sql_id_orden = 'INSERT INTO wpgb_orden_compra(id_clie, mensaje, fecha) 
					 VALUES ('.$raq_data['user_id'].', ';

	if($raq_data['user_message'] != ''){
		$sql_id_orden .= '"'.$raq_data['user_message'].'", ';
	} else {
		$sql_id_orden .= '" ", ';
	}

	$sql_id_orden .= '"'.$fecha.'");';

	$wpdb -> query($sql_id_orden);
?>


<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>


<p>



<?php



	/* translators: %s: user name */



	printf( esc_html__( 'Se ha recibido una nueva orden de compra de: %s.', 'yith-woocommerce-request-a-quote' ), esc_html( $raq_data['user_name'] ) );



?>



	</p>







<?php do_action( 'yith_ywraq_email_before_raq_table', $raq_data ); ?>







<h2><?php esc_html_e( 'Lista de Productos', 'yith-woocommerce-request-a-quote' ); ?></h2>







<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee; margin-bottom:30px" border="1" bordercolor="#eee">



	<thead>



	<tr>



		<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php esc_html_e( 'Producto', 'yith-woocommerce-request-a-quote' ); ?></th>
		<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php esc_html_e( 'Cantidad', 'yith-woocommerce-request-a-quote' ); ?></th>
		<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php esc_html_e( 'Escala', 'yith-woocommerce-request-a-quote' ); ?></th>
		<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php esc_html_e( 'Precio por Unidad', 'yith-woocommerce-request-a-quote' ); ?></th>


	</tr>



	</thead>



	<tbody>



	<?php



	if ( ! empty( $raq_data['raq_content'] ) ) :



		foreach ( $raq_data['raq_content'] as $item ) :



			$_product = isset( $item['variation_id'] ) ? wc_get_product( $item['variation_id'] ) : wc_get_product( $item['product_id'] );

			/* Consulta para traer el id de la orden de compra */
			$sql_obtener_id_orden = $wpdb -> prepare("SELECT id_orden FROM wpgb_orden_compra ORDER BY id_orden DESC LIMIT 1;");
			$query_obtener_id_orden = $wpdb -> get_results($sql_obtener_id_orden);

			/* Consulta para traer la información de las escalas y precios por productos */
			$sql = $wpdb -> prepare("SELECT escala, precio FROM wpgb_escala_producto WHERE id_producto = ".$item['product_id']." AND vol_min <= ".$item['quantity']." ORDER BY vol_min DESC LIMIT 1;");
			$query = $wpdb -> get_results($sql);

			$sql_order_details = 'INSERT INTO wpgb_orden_details_products(id_orden, id_product, cantidad, escala, precio) 
					VALUES ('.$query_obtener_id_orden[0]->id_orden.', 
							'.$item["product_id"].', 
							'.$item["quantity"].', ';

			if(count($query) > 0 && isset($query[0]->escala)){ 
				$sql_order_details .= $query[0]->escala.', ' ;
			} else {
				$sql_order_details .= '0, ' ;
			} 

			if(count($query) > 0 && isset($query[0]->precio)){ 
				$sql_order_details .= $query[0]->precio.'); ' ;
			} else {
				$sql_order_details .= $_product->get_price().'); ' ;
			} 

			$wpdb -> query($sql_order_details);

			if ( ! $_product ) {



				continue;



			}


			$urlPage = 'https://fenaseo.masterquimica.com/producto/';





			/*$product_admin_link = '';



			$posttype_object    = get_post_type_object( get_post( $_product->get_id() )->post_type );



			if ( ( $posttype_object ) && ( $posttype_object->_edit_link ) ) {



				$product_admin_link = admin_url( sprintf( $posttype_object->_edit_link . '&action=edit', $_product->get_id() ) );



			}*/



			?>



			<tr>



				<td scope="col" style="text-align:left;"><a href="<?php echo esc_url( $urlPage.$_product->get_id() ); ?>"><?php echo wp_kses_post( $_product->get_title() ); ?></a>



					<?php if ( isset( $item['variations'] ) ) : ?>



						<small><?php echo wp_kses_post( yith_ywraq_get_product_meta( $item ) ); ?></small>



					<?php endif ?>



				</td>



				<td scope="col" style="text-align:left;"><?php echo esc_html( $item['quantity'] ); ?></td>

				<td scope="col" style="text-align:left;">	
					<?php 
						if(count($query) > 0 && isset($query[0]->escala)){ 
							echo $query[0]->escala;
						} else {
							echo '0';
						} 
					?>
				</td>
				<td scope="col" style="text-align:left;">	
					<?php 
						if(count($query) > 0 && isset($query[0]->precio)){ 
							echo "$ " . number_format($query[0]->precio, 0, ',', '.');
						} else {
							echo wp_kses_post( WC()->cart->get_product_price( $_product ));
						} 
				?>
				</td>

			</tr>



			<?php



		endforeach;



	endif;



	?>



	</tbody>



</table>















<?php do_action( 'yith_ywraq_email_after_raq_table', $raq_data ); ?>



<?php if ( ! empty( $raq_data['user_message'] ) ) : ?>



<h2><?php esc_html_e( 'Customer message', 'yith-woocommerce-request-a-quote' ); ?></h2>



	<p><?php echo wp_kses_post( $raq_data['user_message'] ); ?></p>



<?php endif ?>



<h2><?php esc_html_e( 'Customer details', 'yith-woocommerce-request-a-quote' ); ?></h2>







<p><strong><?php esc_html_e( 'Nombre:', 'yith-woocommerce-request-a-quote' ); ?></strong> <?php echo esc_html( $raq_data['user_name'] ); ?></p>



<p><strong><?php esc_html_e( 'Correo electrónico:', 'yith-woocommerce-request-a-quote' ); ?></strong> <a href="mailto:<?php echo esc_attr( $raq_data['user_email'] ); ?>"><?php echo esc_html( $raq_data['user_email'] ); ?></a></p>







<?php do_action( 'woocommerce_email_footer' ); ?>



