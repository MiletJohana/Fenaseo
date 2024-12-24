<?php
/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 *
 * @var bool   $readonly If the input should be set to readonly mode.
 * @var string $type     The input type attribute.
 */

global $product;

$product_id = $product->id;

defined( 'ABSPATH' ) || exit;

/* translators: %s: Quantity. */
$label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'woocommerce' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'woocommerce' );

?>
    <!-- <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">-->
<div class="quantity"> 
	<?php
	/**
	 * Hook to output something before the quantity input field.
	 *
	 * @since 7.2.0
	 */
	do_action( 'woocommerce_before_quantity_input_field' );
	?>
	<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_attr( $label ); ?></label>

	<?php 
			$options = '';
			
			if ( empty($max_value) ) $max_value = 2000;

			for ( $count = $min_value; $count <= $max_value; $count = $count + $step ) {
				$selected = ($count == $input_value ) ? 'selected' : '';
				$options .= '<option value="' . $count . '" ' . $selected . '>' . $count . '</option>';
			 }

			 $select = '<select name="' . $input_name . '" id="" class="qty" onchange="precioProducto(this.value, '.$product_id.');">' . $options . ' </select>';
			 echo $select;
		?>
	<?php
	/**
	 * Hook to output something after quantity input field
	 *
	 * @since 3.6.0
	 */
	do_action( 'woocommerce_after_quantity_input_field' );
	?>
</div>

<?php if($_SERVER["SCRIPT_URL"] != "/orden-de-compra/"){ ?>
	<div class="container-bar">
		<div class="container-progress">
			<div class="progress" id="progress"></div>
			<div class="progress-step" style="--color: #4b3f40">
				<i class="fas fa-check active"></i>
				<p class="text">Escala 0</p>
			</div>
			<div class="progress-step" style="--color: #ff001f">
				<i class="fas fa-check"></i>
				<p class="text">Escala 1</p>
			</div>
			<div class="progress-step" style="--color: #ff9100">
				<i class="fas fa-check"></i>
				<p class="text">Escala 2</p>
			</div>
			<div class="progress-step" style="--color: #00ff3b">
				<i class="fas fa-check"></i>
				<p class="text">Escala 3</p>
			</div>
		</div>
	</div>

<?php } ?>

