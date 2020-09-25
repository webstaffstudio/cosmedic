<?php

/**
 * Change a currency symbol
 */
add_filter('woocommerce_currency_symbol', 'change_existing_currency_symbol', 10, 2);

function change_existing_currency_symbol( $currency_symbol, $currency ) {
	switch( $currency ) {
		case 'RUB': $currency_symbol = __('руб.', THEME_TD); break;
	}
	return $currency_symbol;
}

//remove tabs at single product page
add_filter( 'woocommerce_product_tabs', 'cosmedoc_remove_description_tab', 11 );

function cosmedoc_remove_description_tab( $tabs ) {

	unset( $tabs['description'] );
	unset( $tabs['reviews'] );
	return $tabs;

}

/**
 * Remove the breadcrumbs
 */
add_action( 'woocommerce_before_main_content', 'woo_remove_wc_breadcrumbs' );
function woo_remove_wc_breadcrumbs() {
	if (is_product()) {
		remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);
	}
}
//custom size image single product gallery
add_filter( 'woocommerce_gallery_thumbnail_size', 'custom_woocommerce_gallery_thumbnail_size' );

function custom_woocommerce_gallery_thumbnail_size() {
	return 'large';
}