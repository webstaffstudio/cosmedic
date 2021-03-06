<?php
add_filter('woocommerce_add_to_cart_fragments', 'header_add_to_cart_fragment', 30, 1);

function header_add_to_cart_fragment()
{
	ob_start();
	?>

	<div class="header-mini-cart">
		<?php woocommerce_mini_cart(); ?>
	</div>

	<?php
	$count = WC()->cart->get_cart_contents_count() === 0 ? 'no-count' : '';
	$fragments['div.header-mini-cart'] = ob_get_clean();

	?>
	<?php
	$fragments['span.header-cart-count'] = '<span class="header-cart-count count ' . $count . '">' . WC()->cart->get_cart_contents_count() . '</span>';
	return $fragments;

}

//Ajax quantity product in cart
add_action('wp_ajax_qty_cart', 'ajax_qty_cart');
add_action('wp_ajax_nopriv_qty_cart', 'ajax_qty_cart');
function ajax_qty_cart()
{
	// Set item key as the hash found in input.qty's name

	$cart_item_key = $_POST['hash'];
	// Get the array of values owned by the product we're updating
	$wss_product_values = WC()->cart->get_cart_item($cart_item_key);
	// Get the quantity of the item in the cart
	$wss_product_quantity = apply_filters('woocommerce_stock_amount_cart_item', apply_filters('woocommerce_stock_amount', preg_replace("/[^0-9\.]/", '', filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT))), $cart_item_key);
	// Update cart validation
	$passed_validation = apply_filters('woocommerce_update_cart_validation', true, $cart_item_key, $wss_product_values, $wss_product_quantity);
	// Update the quantity of the item in the cart
	if ($passed_validation) {
		WC()->cart->set_quantity($cart_item_key, $wss_product_quantity, true);
	}

	wc_cart_totals_order_total_html();

	wp_die();
}

//Ajax count cart product
add_action('wp_ajax_cart_items', 'cart_items');
add_action('wp_ajax_nopriv_cart_items', 'cart_items');
function cart_items()
{
	echo WC()->cart->get_cart_contents_count();
	wp_die();
}

