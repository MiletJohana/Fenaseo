<?php

function enqueue_styles_child_theme() {

	$parent_style = 'parent-style';
	$child_style  = 'child-style';

	wp_enqueue_style( $parent_style,
				get_template_directory_uri() . '/style.css' );

	wp_enqueue_style( $child_style,
				get_stylesheet_directory_uri() . '/style.css',
				array( $parent_style ),
				wp_get_theme()->get('Version')
				);
}
add_action( 'wp_enqueue_scripts', 'enqueue_styles_child_theme' );


/*Código*/
//Agregar JS Footer
function agregarJS() {

    wp_enqueue_script( 'script', 'https://fenaseo.masterquimica.com/wp-content/themes/child-theme/js/script.js', array(), '1.0.0', true );
} 
add_action('wp_enqueue_scripts', 'agregarJS');

//Redireccionar a la página Home al iniciar sesión
add_action('wp_login','go_home');
function go_home(){
wp_redirect( home_url() );
exit();
}

//Redireccionar al login al cerrar sesión
add_action('template_redirect', 'redirect_if_not_logged_in');
function redirect_if_not_logged_in() {
    if (is_page('mi-cuenta') && !is_user_logged_in()) {
        wp_redirect('login');
        exit;
    }
}


/*Obtener información usuario de la sesión*/

//display_name
//user_login
//user_email
//user_level
//user_firstname
//user_lastname
//ID
function syspa_mostrar_conectado( $atts ) {
 global $current_user, $user_login;
       wp_get_current_user();
 add_filter('widget_text', 'apply_shortcodes');
 if ($user_login)
  return  $current_user->display_name;
 else
  return  '<a href="https://fenaseo2.masterquimica.com/login/">Acceder</a>';
}
add_shortcode( 'user_conectado', 'syspa_mostrar_conectado' );

function remove_lostpassword_text ( $text ) {
     if ($text == 'Lost your password?'){$text = '';} 
        return $text; 
     }
add_filter( 'gettext', 'remove_lostpassword_text' ); 

add_action('woocommerce_add_to_cart', 'custom_after_add_to_quote_button',10,6);

function custom_after_add_to_quote_button() {
    /*global $woocommerce;

          $woocommerce->cart->empty_cart();*/
	 WC()->cart->add_to_cart(106, 2);
}

/* Remover precio de la parte superior y ponerlo encima de la cantidad de productos*/
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 20 );

add_action('woocommerce_acomulado_product', 'acomulado_x_product', 10);

function acomulado_x_product() {
    global $post, $product, $wpdb;

	//Consulta para traer la cantidades de los productos
	$id = $product->get_id();
	//the_ID();
	//echo $id;
	
	$volEscalaSiguiente = 0;
	$volEscalaActual = 0;
	$escalaActual = 0;
	$color = 'info';

	$sql = "SELECT id_product, SUM(cantidad) AS 'cantidad_total' FROM wpgb_orden_details_products WHERE id_product = '".$id."';";

	$cantidades = $wpdb->get_results($sql);

	//Cantidades totales de cada producto
	$cantidad = ($cantidades[0]->cantidad_total == '') ? 0 : $cantidades[0]->cantidad_total;
	
	$sqlEscalas = "SELECT escala, vol_min, color FROM wpgb_escala_producto WHERE id_producto = '".$id."';";

	$queryEscalas = $wpdb->get_results($sqlEscalas);
	
	if($cantidad > 0){
		for($i = 0; $i < count($queryEscalas); $i++){
			if($cantidad <= $queryEscalas[$i]->vol_min){
				$volEscalaSiguiente = $queryEscalas[$i]->vol_min;
				$color = ($i === 0) ? $color : $queryEscalas[$i-1]->color;
				$volEscalaActual = ($i === 0) ? 0 : $queryEscalas[$i-1]->vol_min;
				$escalaActual = ($i === 0) ? 0 : $queryEscalas[$i-1]->escala;
				break;
			} else if($cantidad >= $queryEscalas[$i]->vol_min){
				$volEscalaSiguiente = 1000;
				$color = $queryEscalas[$i]->color;
				$volEscalaActual = $queryEscalas[$i]->vol_min;
				$escalaActual = $queryEscalas[$i]->escala;
			}
		}
		$porcentaje = round((($cantidad - $volEscalaActual) / ($volEscalaSiguiente - $volEscalaActual)) *100, 1);
	} else {
		$porcentaje = 0;
	}
	
	echo '<picture class="container-torta"><div class="'.$color.'" role="progressbar" aria-valuenow="'.$porcentaje.'" aria-valuemin="0" aria-valuemax="100" style="--value: '.$porcentaje.';" data-porcentaje="'.$porcentaje.'%"></div><tool-tip role="tooltip"><span class="text-strong">Escala '.$escalaActual.'</span><br><span>Unidades Solicitadas: '.$cantidad.'</span></tool-tip></picture>' ; 
}

add_action('woocommerce_escala_product', 'escala_x_product', 10);

function escala_x_product() {
    global $post, $product, $wpdb;
	
	$input = '<div id="container-escalas"><input type="hidden" id="escala0" data-vol_min="0" data-escala="0" data-precio="0" data-color="0">';

	//Consulta para traer la cantidades de los productos
	$id = $product->get_id();
	//the_ID();
	//echo $id;

	$sql = "SELECT escala, vol_min, precio, color FROM wpgb_escala_producto WHERE id_producto = '".$id."';";

	$query_escalas = $wpdb->get_results($sql);
	
	foreach($query_escalas as $escala){
		$input .= '<input type="hidden" id="escala'.$escala->escala.'" data-vol_min="'.$escala->vol_min.'" data-escala="'.$escala->escala.'" data-precio="'.$escala->precio.'" data-color="'.$escala->color.'">';
	}
	
	$input .= '</div>';

	echo $input; 
}