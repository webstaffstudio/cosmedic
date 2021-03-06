<?php
global $product;
?>
<?php
$id = $product->get_id();
$title = get_the_title($id);
$brand_terms = get_the_terms($id, 'cos_brands');
$product = wc_get_product($id);
$product_thumb = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'single-post-thumbnail');
$link = get_the_permalink($id);
?>
<div class="cross-sale__list--product product">
	<a href="<?= $link; ?>" class="link product__thumbnail">
		<img src="<?= $product_thumb[0]; ?>" alt=""/>
	</a>
	<div class="product-descr">
		<?= (isset($brand_terms[0]->name)) ? '<div class="product__brand">' . $brand_terms[0]->name . '</div>' : ''; ?>
		<a class="product__title" href="<?= $link; ?>"><h3><?= $title; ?></h3></a>
		<div class="product__price">
			<?php if (!empty($product->get_sale_price())): ?>
				<span class="regular has-sale"><?= $product->get_regular_price(); ?><span
						class="currency"><?= show_currency_symbol(); ?></span></span>
				<span class="sale"><?= $product->get_sale_price(); ?><span
						class="currency"><?= show_currency_symbol(); ?></span></span>
			<?php else: ?>
				<span class="regular"><?= $product->get_regular_price() ?><span
						class="currency"><?= show_currency_symbol(); ?></span></span>
			<?php endif; ?>
		</div>
		<a class="cosmedoc-btn"
		   href="<?= $product->add_to_cart_url(); ?>"><?= __('Добавить в корзину', THEME_TD); ?></a>
	</div>
</div>
