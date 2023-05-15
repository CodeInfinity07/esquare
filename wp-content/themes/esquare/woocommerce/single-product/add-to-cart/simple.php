<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

echo wc_get_stock_html( $product ); // WPCS: XSS ok.

if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>
<div class="add-to-cart-wrapper d-block">
<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<?php
		do_action( 'woocommerce_before_add_to_cart_quantity' );

		woocommerce_quantity_input( array(
			'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
			'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
			'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
		) );

		do_action( 'woocommerce_after_add_to_cart_quantity' );
		?>

		<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="btn-brand mt-0"><i class="icon-f-47"></i> <?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>

	</div>
	
	<div class="buyon-whatsapp"><a href="https://wa.me/923260099111?text=Hello Esquare! I would like to buy <?php echo $product->get_title(); ?>" target="_blank"> <img src="https://esquare.store/wp-content/themes/esquare/app/images/wp.webp"><span>Enquire on Whatsapp</span></a></div>
	<div class="live-viewers">
		<i class="icon-f-73"></i><span><strong id="live-number"><?php echo(rand(10,100));?></strong> People watching this product now!</span>
	</div>


	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>


	<script>
		// setTimeout(() => {
		//  var liveNumber =	$("live-number").html();
		//  liverNumber++;
		// }, "1000");


		// var counter = 0;
		// var increment = 10;
		// var liveNumber = document.getElementById('live-number');
		// var number = liveNumber.html();
		// console.log(number);
		// var st = setInterval(function(){
		// 	div.innerHTML = ++number;
		// },1000);



		
// var increment = 10;
// var counter = div.innerHTML;
// var st = setInterval(function(){
// div.innerHTML = ++counter;
// },6000);

function randomNumber(){
  let div = document.getElementById('live-number');
  let x = Math.floor((Math.random() * 100) + 10);
  setTimeout(randomNumber, 120000);
  div.innerHTML = x;
  console.log(x);
}
randomNumber();
	</script>
<?php endif; ?>


	