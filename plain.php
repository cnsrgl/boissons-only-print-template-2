<?php
use function Zprint\get_appearance_setting;
use Zprint\Order;
use Zprint\Document;

/* @var $order \WC_Order */
/* @var $location_data */
/* @var $templateOptions */

// Kategori slug'ını al
$category_slug = $templateOptions['category_slug'] ?? 'boissons';
$header_text = !empty($templateOptions['custom_header']) ? $templateOptions['custom_header'] : __('Beverages Only - İçecekler Fişi', 'boissons-print-template');

// Header
echo Document::centerLine($header_text);
echo Document::emptyLine();

if (get_appearance_setting('Company Name')) {
    echo Document::centerLine(get_appearance_setting('Company Name'));
}

if (get_appearance_setting('Company Info')) {
    echo Document::centerLine(get_appearance_setting('Company Info'));
}

echo Document::emptyLine();
echo Document::line('================================');
echo Document::emptyLine();

// Order Info
echo Document::symbolsAlign(__('Order Number', 'Print-Google-Cloud-Print-GCP-WooCommerce') . ':', '#' . $order->get_id());
echo Document::symbolsAlign(__('Date', 'Print-Google-Cloud-Print-GCP-WooCommerce') . ':', date_i18n(\get_option('date_format', 'm/d/Y'), strtotime($order->get_date_created())));
echo Document::symbolsAlign(__('Time', 'Print-Google-Cloud-Print-GCP-WooCommerce') . ':', date_i18n(\get_option('time_format', 'H:i'), strtotime($order->get_date_created())));
echo Document::symbolsAlign(__('Payment Method', 'Print-Google-Cloud-Print-GCP-WooCommerce') . ':', $order->get_payment_method_title());

echo Document::emptyLine();
echo Document::line('================================');
echo Document::emptyLine();

// Order Details Header
if (get_appearance_setting('Order Details Header')) {
    echo Document::centerLine(get_appearance_setting('Order Details Header'));
    echo Document::emptyLine();
}

// Kategori toplamları
$category_subtotal = 0;
$category_tax_total = 0;
$category_total = 0;
$has_items = false;

// Ürünleri listele
foreach ($order->get_items() as $item) {
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
    
    $has_items = true;
    
    // Ürün adı ve miktarı
    echo Document::symbolsAlign($item['name'] . ' x' . $item['qty'], wc_price($item->get_data()['total'], array('currency' => $order->get_currency())));
    
    // Meta bilgileri
    $meta = $item['item_meta'];
    $meta = array_filter($meta, function ($key) {
        return !in_array($key, Order::getHiddenKeys());
    }, ARRAY_FILTER_USE_KEY);
    
    foreach ($meta as $key => $value) {
        echo Document::line('  ' . $key . ': ' . $value);
    }
    
    // Toplamları güncelle
    $category_subtotal += $item->get_subtotal();
    $category_tax_total += $item->get_total_tax();
    $category_total += $item->get_total() + $item->get_total_tax();
}

if (!$has_items) {
    echo Document::centerLine(__('No beverages in this order', 'boissons-print-template'));
    echo Document::emptyLine();
}

echo Document::line('================================');
echo Document::emptyLine();

// Toplamlar
echo Document::symbolsAlign(__('Subtotal', 'Print-Google-Cloud-Print-GCP-WooCommerce') . ':', wc_price($category_subtotal, array('currency' => $order->get_currency())));

if ($location_data['shipping']['cost'] && $order->get_shipping_total() > 0) {
    echo Document::symbolsAlign(__('Shipping', 'Print-Google-Cloud-Print-GCP-WooCommerce') . ':', wc_price($order->get_shipping_total(), array('currency' => $order->get_currency())));
}

echo Document::symbolsAlign(__('Tax', 'Print-Google-Cloud-Print-GCP-WooCommerce') . ':', wc_price($category_tax_total, array('currency' => $order->get_currency())));

echo Document::line('--------------------------------');
echo Document::symbolsAlign(__('Total', 'Print-Google-Cloud-Print-GCP-WooCommerce') . ':', wc_price($category_total, array('currency' => $order->get_currency())));

// POS ekstra alanlar
if ($order->get_meta('pos-tip')) {
    echo Document::symbolsAlign(__('Tip', 'Print-Google-Cloud-Print-GCP-WooCommerce') . ':', wc_price($order->get_meta('pos-tip'), array('currency' => $order->get_currency())));
}

if ($order->get_meta('pos-cash-tendered')) {
    echo Document::symbolsAlign(__('Amount Collected', 'Print-Google-Cloud-Print-GCP-WooCommerce') . ':', wc_price($order->get_meta('pos-cash-tendered'), array('currency' => $order->get_currency())));
}

echo Document::emptyLine();
echo Document::line('================================');
echo Document::emptyLine();

// Müşteri Bilgileri
if (($templateOptions['show_customer_info'] ?? true) && $location_data['shipping']['customer_details']) {
    $show_customer_section = false;
    
    if (!empty($order->get_billing_first_name()) || !empty($order->get_billing_last_name())) {
        $show_customer_section = true;
        echo Document::centerLine(__('Customer Details', 'Print-Google-Cloud-Print-GCP-WooCommerce'));
        echo Document::emptyLine();
        echo Document::line(__('Name', 'Print-Google-Cloud-Print-GCP-WooCommerce') . ': ' . $order->get_billing_first_name() . ' ' . $order->get_billing_last_name());
    }
    
    if (!empty($order->get_billing_phone())) {
        if (!$show_customer_section) {
            echo Document::centerLine(__('Customer Details', 'Print-Google-Cloud-Print-GCP-WooCommerce'));
            echo Document::emptyLine();
            $show_customer_section = true;
        }
        echo Document::line(__('Phone', 'Print-Google-Cloud-Print-GCP-WooCommerce') . ': ' . $order->get_billing_phone());
    }
    
    if (!empty($order->get_billing_email())) {
        if (!$show_customer_section) {
            echo Document::centerLine(__('Customer Details', 'Print-Google-Cloud-Print-GCP-WooCommerce'));
            echo Document::emptyLine();
            $show_customer_section = true;
        }
        echo Document::line(__('Email', 'Print-Google-Cloud-Print-GCP-WooCommerce') . ': ' . $order->get_billing_email());
    }
    
    if ($show_customer_section) {
        echo Document::emptyLine();
    }
}

// Order Notes
if (!empty($order->get_customer_note())) {
    echo Document::centerLine(__('Order Notes', 'Print-Google-Cloud-Print-GCP-WooCommerce'));
    echo Document::emptyLine();
    echo Document::line($order->get_customer_note());
    echo Document::emptyLine();
}

// Shipping Method
if ($location_data['shipping']['method'] && $shipping_method = $order->get_shipping_method()) {
    echo Document::line(__('Shipping Method', 'Print-Google-Cloud-Print-GCP-WooCommerce') . ': ' . $shipping_method);
    echo Document::emptyLine();
}

// Footer
echo Document::line('================================');
echo Document::emptyLine();

if (get_appearance_setting('Footer Information #1')) {
    echo Document::centerLine(get_appearance_setting('Footer Information #1'));
}

if (get_appearance_setting('Footer Information #2')) {
    echo Document::centerLine(get_appearance_setting('Footer Information #2'));
}

echo Document::emptyLine();
echo Document::emptyLine();

// WP Actions for extending
do_action('Zprint\\templates\\boissons-plain\\afterShippingDetails', $order->get_id());
do_action('Zprint\\templates\\boissons-plain\\beforeFooter', $order->get_id());
do_action('Zprint\\templates\\boissons-plain\\end', $order->get_id());
