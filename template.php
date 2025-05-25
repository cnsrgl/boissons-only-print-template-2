<?php

class BoissonsOnlyTemplate implements Zprint\Template\Index, Zprint\Template\Options {

	public function getName()
	{
		return __('Beverages Only - İçecekler', 'boissons-print-template');
	}

	public function getSlug()
	{
		return 'boissons-only';
	}

	public function getPath($format)
	{
		if ($format === 'plain') {
			return __DIR__.'/plain.php';
		}
		return __DIR__.'/html.php';
	}

	public function getFormats()
	{
		return ['html' => true, 'plain' => true];
	}

	public function renderOptions($options) {
		?>
		<h3><?php _e('Beverages Template Settings', 'boissons-print-template'); ?></h3>
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="category_slug"><?php _e('Category Slug', 'boissons-print-template'); ?></label>
				</th>
				<td>
					<input type="text" id="category_slug" name="category_slug" value="<?= esc_attr($options['category_slug'] ?? 'boissons') ?>" />
					<p class="description"><?php _e('Enter the product category slug to filter (default: boissons)', 'boissons-print-template'); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="show_customer_info"><?php _e('Show Customer Info', 'boissons-print-template'); ?></label>
				</th>
				<td>
					<input type="checkbox" id="show_customer_info" name="show_customer_info" value="1" <?= checked($options['show_customer_info'] ?? true, true) ?> />
					<p class="description"><?php _e('Display customer details on receipt', 'boissons-print-template'); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="custom_header"><?php _e('Custom Header Text', 'boissons-print-template'); ?></label>
				</th>
				<td>
					<input type="text" id="custom_header" name="custom_header" value="<?= esc_attr($options['custom_header'] ?? '') ?>" style="width: 100%;" />
					<p class="description"><?php _e('Override default header text (leave empty for default)', 'boissons-print-template'); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="debug_mode"><?php _e('Debug Mode', 'boissons-print-template'); ?></label>
				</th>
				<td>
					<input type="checkbox" id="debug_mode" name="debug_mode" value="1" <?= checked($options['debug_mode'] ?? false, true) ?> />
					<p class="description"><?php _e('Show debug information in HTML comments', 'boissons-print-template'); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e('Available Categories', 'boissons-print-template'); ?>
				</th>
				<td>
					<?php
					$categories = get_terms(array(
						'taxonomy' => 'product_cat',
						'hide_empty' => false,
					));
					if (!empty($categories) && !is_wp_error($categories)) {
						echo '<div style="background: #f1f1f1; padding: 10px; border-radius: 5px; max-height: 200px; overflow-y: auto;">';
						echo '<strong>' . __('Category slugs you can use:', 'boissons-print-template') . '</strong><br>';
						foreach ($categories as $category) {
							echo '<code>' . esc_html($category->slug) . '</code> - ' . esc_html($category->name) . ' (' . $category->count . ' ' . __('products', 'boissons-print-template') . ')<br>';
						}
						echo '</div>';
					} else {
						echo '<p>' . __('No categories found', 'boissons-print-template') . '</p>';
					}
					?>
				</td>
			</tr>
		</table>
		<?php
	}

	public function processOptions($options) {
		$options['category_slug'] = sanitize_text_field($_POST['category_slug'] ?? 'boissons');
		$options['show_customer_info'] = isset($_POST['show_customer_info']) ? 1 : 0;
		$options['custom_header'] = sanitize_text_field($_POST['custom_header'] ?? '');
		$options['debug_mode'] = isset($_POST['debug_mode']) ? 1 : 0;
		return $options;
	}
}
