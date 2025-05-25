<?php
use function Zprint\get_appearance_setting;
use Zprint\Order;

/* @var $order \WC_Order */
/* @var $location_data */
?>
<html>
<head>
	<style><?php include 'style.php'; ?></style>
</head>
<body>
<header>
	<?php 
	$header_text = !empty($templateOptions['custom_header']) ? $templateOptions['custom_header'] : __('Beverages Only - İçecekler Fişi', 'boissons-print-template');
	?>
	<h1><?= esc_html($header_text); ?></h1>
	<?php if (get_appearance_setting('logo')) { ?>
		<img src="<?= get_appearance_setting('logo'); ?>" class="logo" alt="Logo">
	<?php } ?>
	<?php if (get_appearance_setting('Check Header')) { ?>
		<h1><?= get_appearance_setting('Check Header'); ?></h1>
	<?php } ?>
	<?php if (get_appearance_setting('Company Name')) { ?>
		<h2><?= get_appearance_setting('Company Name'); ?></h2>
	<?php } ?>
	<?php if (get_appearance_setting('Company Info')) { ?>
		<h3><?= get_appearance_setting('Company Info'); ?></h3>
	<?php } ?>
</header>
<table class="info">
	<thead>
	<tr>
		<th><?php _e('Order Number', 'Print-Google-Cloud-Print-GCP-WooCommerce'); ?></th>
		<th><?php _e('Date', 'Print-Google-Cloud-Print-GCP-WooCommerce'); ?></th>
		<th><?php _e('Total', 'Print-Google-Cloud-Print-GCP-WooCommerce'); ?></th>
		<th><?php _e('Payment Method', 'Print-Google-Cloud-Print-GCP-WooCommerce'); ?></th>
	</tr>
	</thead>
	<tfoot>
	<tr>
		<td colspan="4"><?php _e('Time ordered', 'Print-Google-Cloud-Print-GCP-WooCommerce'); ?>
			- <?= date_i18n(\get_option('time_format', 'H:i'), strtotime($order->get_date_created())); ?></td>
	</tr>
	</tfoot>
	<tbody>
	<tr>
		<td><?= $order->get_id(); ?></td>
		<td><?= date_i18n(\get_option('date_format', 'm/d/Y'), strtotime($order->get_date_created())); ?></td>
		<td><?= wc_price($order->get_total(), array('currency' => $order->get_currency())); ?></td>
		<td><?= $order->get_payment_method_title(); ?></td>
	</tr>
	</tbody>
</table>


<?php if (get_appearance_setting('Order Details Header')) { ?>
	<h2 class="caption"><?= get_appearance_setting('Order Details Header'); ?></h2>
<?php } ?>
<table class="order">
	<thead>
	<tr>
		<th colspan="2"><?php _e('Product', 'Print-Google-Cloud-Print-GCP-WooCommerce'); ?></th>
		<th><?php _e('Total', 'Print-Google-Cloud-Print-GCP-WooCommerce'); ?></th>
	</tr>
	</thead>
	<?php
	// Kategori slug'ını al
	$category_slug = $templateOptions['category_slug'] ?? 'boissons';
	
	// Debug: Kategori bilgisi
	if (isset($templateOptions['debug_mode']) && $templateOptions['debug_mode']) {
		echo '<!-- DEBUG: Category Slug: ' . esc_html($category_slug) . ' -->';
	}
	
	// Sadece seçilen kategorideki ürünlerin toplamı için
	$category_subtotal = 0;
	$category_tax_total = 0;
	$category_total = 0;
	$filtered_items_count = 0;
	
	foreach ($order->get_items() as $item) {
		$product_id = $item->get_product_id();
		$product = wc_get_product($product_id);
		
		if ($product) {
			// Ürünün tüm kategorilerini al
			$product_categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'slugs'));
			
			// Debug: Ürün kategorileri
			if (isset($templateOptions['debug_mode']) && $templateOptions['debug_mode']) {
				echo '<!-- DEBUG: Product ' . $product->get_name() . ' categories: ' . implode(', ', $product_categories) . ' -->';
			}
			
			// Kategori kontrolü
			if (in_array($category_slug, $product_categories)) {
				$category_subtotal += $item->get_subtotal();
				$category_tax_total += $item->get_total_tax();
				$category_total += $item->get_total() + $item->get_total_tax();
				$filtered_items_count++;
			}
		}
	}
	
	// Eğer hiç ürün bulunmadıysa uyarı göster
	if ($filtered_items_count === 0 && isset($templateOptions['debug_mode']) && $templateOptions['debug_mode']) {
		echo '<!-- DEBUG: No products found in category: ' . esc_html($category_slug) . ' -->';
	}
	?>
<tfoot>
	<tr>
		<td colspan="2"><?php _e('Subtotal', 'Print-Google-Cloud-Print-GCP-WooCommerce'); ?></td>
		<td><?= wc_price($category_subtotal, array('currency' => $order->get_currency())); ?></td>
	</tr>
	<?php if ($location_data['shipping']['cost']) { ?>
		<tr>
			<td colspan="2"><?php _e('Shipping', 'Print-Google-Cloud-Print-GCP-WooCommerce'); ?></td>
			<td><?= wc_price($order->get_shipping_total(), array('currency' => $order->get_currency())); ?></td>
		</tr>
	<?php } ?>
	<tr>
		<td colspan="2"><?php _e('Tax', 'Print-Google-Cloud-Print-GCP-WooCommerce'); ?></td>
		<td><?= wc_price($category_tax_total, array('currency' => $order->get_currency())); ?></td>
	</tr>
	<tr>
		<td colspan="2"><?php _e('Payment Method', 'Print-Google-Cloud-Print-GCP-WooCommerce'); ?></td>
		<td><?= $order->get_payment_method_title(); ?></td>
	</tr>
	<tr>
		<td colspan="2"><?php _e('Total', 'Print-Google-Cloud-Print-GCP-WooCommerce'); ?></td>
		<td><?= wc_price($category_total, array('currency' => $order->get_currency())); ?></td>
	</tr>
	<?php if ($order->get_meta('pos-tip')): ?>
		<tr>
			<td colspan="2"><?php _e('Add Tip Amount', 'Print-Google-Cloud-Print-GCP-WooCommerce'); ?></td>
			<td><?= wc_price($order->get_meta('pos-tip'), array('currency' => $order->get_currency())); ?></td>
		</tr>
	<?php endif; ?>
	<?php if ($order->get_meta('pos-cash-tendered')): ?>
		<tr>
			<td colspan="2"><?php _e('Amount Collected', 'Print-Google-Cloud-Print-GCP-WooCommerce'); ?></td>
			<td><?= wc_price($order->get_meta('pos-cash-tendered'), array('currency' => $order->get_currency())); ?></td>
		</tr>
	<?php endif; ?>
	</tfoot>
	<?php foreach ($order->get_items() as $item) {
		/* @var $item \WC_Order_item */
		// Product ID'yi alalım
		$product_id = $item->get_product_id();
		$product = wc_get_product($product_id);
		
		// Eğer ürün yoksa devam et
		if (!$product) {
			continue;
		}
		
		// Ürünün kategorilerini kontrol et
		$product_categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'slugs'));
		if (!in_array($category_slug, $product_categories)) {
			continue;
		}
		
		$meta = $item['item_meta'];
		$meta = array_filter($meta, function ($key) {
			return !in_array($key, Order::getHiddenKeys());
		}, ARRAY_FILTER_USE_KEY);
		?>
		<tbody>
		<tr>
			<td colspan="2"><?= $item['name']; ?> &times; <?= $item['qty']; ?></td>
			<td
				rowspan="<?= count($meta) + 1; ?>"><?= wc_price($item->get_data()['total'], array('currency' => $order->get_currency())); ?></td>
		</tr>
		<?php $meta = array_map(function ($meta, $key) {
			$result = '<tr>';
			$result .= '<td>' . $key . '</td>';
			$result .= '<td>' . $meta . '</td>';
			$result .= '</tr>';
			return $result;
		}, $meta, array_keys($meta));
		echo implode(PHP_EOL, $meta);
		?>
		</tbody>
	<?php } ?>
	<?php
	// Eğer hiç ürün listelenemediyse bilgi ver
	if ($filtered_items_count === 0) {
		?>
		<tbody>
		<tr>
			<td colspan="3" style="text-align: center; padding: 20px;">
				<em><?php echo sprintf(__('No products found in category "%s"', 'boissons-print-template'), esc_html($category_slug)); ?></em>
				<?php if (isset($templateOptions['debug_mode']) && $templateOptions['debug_mode']) { ?>
					<br><small><?php _e('Debug mode is ON. Check HTML comments for more information.', 'boissons-print-template'); ?></small>
				<?php } ?>
			</td>
		</tr>
		</tbody>
		<?php
	}
	?>
</table>

<?php if (($templateOptions['show_customer_info'] ?? true) && $location_data['shipping']['customer_details'] && (!empty($order->get_billing_first_name()) || !empty($order->get_billing_last_name()) || !empty($order->get_billing_phone()))): ?>
	<h2 class="caption"><?php _e('Customer Details', 'Print-Google-Cloud-Print-GCP-WooCommerce'); ?></h2>
<?php endif; ?>
<table class="customer">
	<?php if (($templateOptions['show_customer_info'] ?? true) && $location_data['shipping']['customer_details'] && (!empty($order->get_billing_first_name()) || !empty($order->get_billing_last_name()) || !empty($order->get_billing_phone()))): ?>
		<tbody class="base">
		<?php if (!empty($order->get_billing_first_name()) || !empty($order->get_billing_last_name())) { ?>
			<tr>
				<td><?php _e('Name', 'Print-Google-Cloud-Print-GCP-WooCommerce'); ?></td>
				<td><?= $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(); ?></td>
			</tr>
		<?php } ?>
		<?php if (!empty($order->get_billing_phone())) { ?>
			<tr>
				<td><?php _e('Telephone', 'Print-Google-Cloud-Print-GCP-WooCommerce'); ?></td>
				<td><?= $order->get_billing_phone(); ?></td>
			</tr>
		<?php } ?>
		<?php if (!empty($order->get_billing_email())) { ?>
			<tr>
				<td colspan="2"><?php _e('Email', 'Print-Google-Cloud-Print-GCP-WooCommerce'); ?></td>
			</tr>
			<tr>
				<td colspan="2"><?= $order->get_billing_email(); ?></td>
			</tr>
		<?php } ?>
		</tbody>
	<?php
	endif; ?>
	<?php if (!empty($order->get_customer_note())) { ?>
		<tbody class="notes">
		<tr>
			<td width="50%" colspan="2">
				<?php _e('Order Notes', 'Print-Google-Cloud-Print-GCP-WooCommerce'); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?= $order->get_customer_note(); ?>
			</td>
		</tr>
		</tbody>
	<?php } ?>
	<?php if ($location_data['shipping']['method'] && $shipping_method = $order->get_shipping_method()) { ?>
		<tbody class="base">
		<tr>
			<td><?php _e('Shipping method', 'Print-Google-Cloud-Print-GCP-WooCommerce'); ?></td>
			<td>
				<?= $shipping_method; ?>
			</td>
		</tr>
		</tbody>
	<?php } ?>
</table>
<footer>
	<?php if (get_appearance_setting('Footer Information #1')) { ?>
		<h4><?= get_appearance_setting('Footer Information #1'); ?></h4>
	<?php } ?>

	<?php if (get_appearance_setting('Footer Information #2')) { ?>
		<h5><?= get_appearance_setting('Footer Information #2'); ?></h5>
	<?php } ?>
</footer>
</body>
</html>
