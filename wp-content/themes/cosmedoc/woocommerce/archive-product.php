<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

get_header('shop');

$post_id = is_shop() ? wc_get_page_id('shop') : get_the_ID();
$fields = get_fields(wc_get_page_id('shop'));
$options_fields = get_fields('options');
$title = is_shop() ? $fields['hero_title'] : single_cat_title('', false);
$category = get_queried_object();
if (!is_shop()):
	$t_id = $category->term_id;
	$thumb_id = get_term_meta($t_id, 'thumbnail_id', true);
	$term_img = wp_get_attachment_image_src($thumb_id, 'full');
endif;
$hero_image = is_shop() ? wp_get_attachment_image_src($fields['hero_image'], 'full')[0] : $options_fields['default_placeholder'];
?>
<section class="hero__section"
		 style="background-image: url(<?php echo $hero_image; ?>)">
<?php if (isset($fields['hero_title']) && $fields['hero_title']): ?>
	<h1 class="hero__section__title"><?php echo $title; ?></h1>
	</section>
<?php endif; ?>
<?php

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action('woocommerce_before_main_content');

?>
	<header class="woocommerce-products-header">

		<?php
		/**
		 * Hook: woocommerce_archive_description.
		 *
		 * @hooked woocommerce_taxonomy_archive_description - 10
		 * @hooked woocommerce_product_archive_description - 10
		 */
		do_action('woocommerce_archive_description');
		?>
	</header>
	<div class="grid-container">

		<?php
		global $wp_query;
		$ppp = 4;
		$product_args = array(
			'post_type' => 'product',
			'posts_per_page' => $ppp, // must be 36
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1,
			'meta_key' => 'total_sales',
			'orderby' => 'meta_value_num',
		);

		$product_query = new WP_Query($product_args);
		$count_posts = $product_query->post_count;
		set_query_var('product_pp', $ppp);
		set_query_var('product_count', $count_posts);
		if (woocommerce_product_loop()) {

			/**
			 * Hook: woocommerce_before_shop_loop.
			 *
			 * @hooked woocommerce_output_all_notices - 10
			 * @hooked woocommerce_result_count - 20
			 * @hooked woocommerce_catalog_ordering - 30
			 */
			remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
			remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

			do_action('woocommerce_before_shop_loop');
			woocommerce_product_loop_start();

			if ($product_query->have_posts()) {
				echo '<div class="product-list">';
				while ($product_query->have_posts()) {
					$product_query->the_post();

					/**
					 * Hook: woocommerce_shop_loop.
					 */
					do_action('woocommerce_shop_loop');

					wc_get_template_part('content', 'product');
				}
				echo '</div>';
			}


			if ($product_query->max_num_pages > 1):
				$paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
				?>
				<div class="load-container" data-page="<?= $paged; ?>">
					<div class="cosmedoc-btn" id="loadmore"><?= __('Загрузить Ещё', THEME_TD); ?></div>
				</div>
			<?php
			endif;
			woocommerce_product_loop_end();

			/**
			 * Hook: woocommerce_after_shop_loop.
			 *
			 * @hooked woocommerce_pagination - 10
			 */

//			do_action('woocommerce_after_shop_loop');
		} else {
			/**
			 * Hook: woocommerce_no_products_found.
			 *
			 * @hooked wc_no_products_found - 10
			 */
			do_action('woocommerce_no_products_found');
		}

		/**
		 * Hook: woocommerce_after_main_content.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action('woocommerce_after_main_content');

		/**
		 * Hook: woocommerce_sidebar.
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */

		?>

		<?php
		//do_action('woocommerce_sidebar');
		?>
	</div>
<?php
$big = 999999999; // need an unlikely integer
$pages = paginate_links(array(
	'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
	'format' => '?paged=%#%',
	'current' => max(1, get_query_var('paged')),
	'total' => $product_query->max_num_pages,
	'type' => 'array',
	'prev_text' => '<span class="prev-page"><</span>',
	'next_text' => '<span class="next-page">></span>',
	'end_size' => $big, //will show 2 numbers on either the start and the end list edges.
	'mid_size' => 3 //so that you won't have 1,2...,3,...,7,8

));
?>
	<div class="pagination-product<?= !is_array($pages) ? ' hide-it' : ''; ?>">
		<?php if (is_array($pages)): ?>
			<?php $paged = (get_query_var('paged') == 0) ? 1 : get_query_var('paged');
			echo '<ul class="pagination-product__list">';
			$i = 1;
			foreach ($pages as $page) {
				echo "<li data-num=" . $i . ">$page</li>";
				$i++;
			}
			echo '</ul>'; ?>
		<?php endif; ?>
	</div>
<?php get_footer('shop');
