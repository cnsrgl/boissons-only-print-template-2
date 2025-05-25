<?php
/**
 * Kategori Test Scripti
 * 
 * Bu dosyayı WordPress kök dizinine yükleyin ve tarayıcıdan çalıştırın
 * Örnek: https://siteniz.com/test-categories.php
 */

// Güvenlik kontrolleri
if (!defined('ABSPATH')) {
    // WordPress yüklenmemiş
    if (file_exists('./wp-load.php')) {
        require_once('./wp-load.php');
    } else if (file_exists('../wp-load.php')) {
        require_once('../wp-load.php');
    } else {
        die('WordPress bulunamadı. Bu dosyayı WordPress kök dizinine yükleyin.');
    }
}

// Admin yetkisi kontrol et
if (!current_user_can('manage_options')) {
    wp_die('Bu sayfayı görüntüleme yetkiniz yok.');
}

// WooCommerce kontrolü
if (!class_exists('WooCommerce')) {
    wp_die('WooCommerce eklentisi aktif değil!');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>WooCommerce Kategori Test - Boissons Print Template</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #0073aa;
            padding-bottom: 10px;
        }
        h2 {
            color: #666;
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background: #0073aa;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .slug {
            font-family: monospace;
            background: #f0f0f0;
            padding: 2px 6px;
            border-radius: 3px;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        .info {
            background: #e7f3ff;
            border: 1px solid #0073aa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>WooCommerce Kategori Test</h1>
        <p>Bu sayfa WooCommerce kategorilerinizi ve ürünlerinizi test etmenize yardımcı olur.</p>
        
        <div class="info">
            <strong>Not:</strong> Boissons Print Template'de kullanmak için aşağıdaki <span class="slug">slug</span> değerlerini kullanın.
        </div>

        <h2>📁 Mevcut Kategoriler</h2>
        <?php
        $categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'orderby' => 'name',
            'order' => 'ASC'
        ));

        if (!empty($categories) && !is_wp_error($categories)) {
            ?>
            <table>
                <thead>
                    <tr>
                        <th>Kategori Adı</th>
                        <th>Slug (Kullanılacak Değer)</th>
                        <th>Ürün Sayısı</th>
                        <th>Üst Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category) : 
                        $parent_name = '';
                        if ($category->parent != 0) {
                            $parent = get_term($category->parent, 'product_cat');
                            if (!is_wp_error($parent)) {
                                $parent_name = $parent->name;
                            }
                        }
                    ?>
                        <tr>
                            <td><?php echo esc_html($category->name); ?></td>
                            <td><span class="slug"><?php echo esc_html($category->slug); ?></span></td>
                            <td><?php echo $category->count; ?></td>
                            <td><?php echo $parent_name ? esc_html($parent_name) : '-'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php
        } else {
            echo '<p class="error">Kategori bulunamadı!</p>';
        }
        ?>

        <h2>🛍️ Son 10 Sipariş ve Ürün Kategorileri</h2>
        <?php
        $orders = wc_get_orders(array(
            'limit' => 10,
            'orderby' => 'date',
            'order' => 'DESC'
        ));

        if (!empty($orders)) {
            ?>
            <table>
                <thead>
                    <tr>
                        <th>Sipariş #</th>
                        <th>Tarih</th>
                        <th>Ürün</th>
                        <th>Kategoriler (Slug)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order) : ?>
                        <?php foreach ($order->get_items() as $item) : 
                            $product_id = $item->get_product_id();
                            $product = wc_get_product($product_id);
                            if (!$product) continue;
                            
                            $product_categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'all'));
                            $category_info = array();
                            foreach ($product_categories as $cat) {
                                $category_info[] = '<span class="slug">' . $cat->slug . '</span> (' . $cat->name . ')';
                            }
                        ?>
                            <tr>
                                <td>#<?php echo $order->get_id(); ?></td>
                                <td><?php echo $order->get_date_created()->format('d.m.Y H:i'); ?></td>
                                <td><?php echo esc_html($product->get_name()); ?></td>
                                <td><?php echo !empty($category_info) ? implode(', ', $category_info) : '<span class="error">Kategori yok</span>'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php
        } else {
            echo '<p>Sipariş bulunamadı.</p>';
        }
        ?>

        <h2>🧪 Kategori Testi</h2>
        <p>Bir kategori slug'ı girin ve hangi ürünlerin o kategoride olduğunu görün:</p>
        
        <form method="get" action="">
            <label>
                Kategori Slug: 
                <input type="text" name="test_category" value="<?php echo isset($_GET['test_category']) ? esc_attr($_GET['test_category']) : ''; ?>" style="margin: 0 10px;">
            </label>
            <button type="submit">Test Et</button>
        </form>

        <?php
        if (isset($_GET['test_category']) && !empty($_GET['test_category'])) {
            $test_slug = sanitize_text_field($_GET['test_category']);
            echo '<h3>Test Sonucu: <span class="slug">' . esc_html($test_slug) . '</span></h3>';
            
            // Kategori var mı kontrol et
            $term = get_term_by('slug', $test_slug, 'product_cat');
            if ($term) {
                echo '<p class="success">✅ Kategori bulundu: ' . esc_html($term->name) . '</p>';
                
                // Bu kategorideki ürünleri listele
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => $test_slug
                        )
                    )
                );
                $products = new WP_Query($args);
                
                if ($products->have_posts()) {
                    echo '<p>Bu kategorideki ürünler:</p>';
                    echo '<ul>';
                    while ($products->have_posts()) {
                        $products->the_post();
                        echo '<li>' . get_the_title() . '</li>';
                    }
                    echo '</ul>';
                    wp_reset_postdata();
                } else {
                    echo '<p>Bu kategoride ürün bulunamadı.</p>';
                }
            } else {
                echo '<p class="error">❌ Bu slug ile kategori bulunamadı!</p>';
            }
        }
        ?>

        <h2>📋 Kullanım Talimatları</h2>
        <ol>
            <li>Yukarıdaki tabloda listelenen <span class="slug">slug</span> değerlerinden birini kopyalayın</li>
            <li>BizPrint ayarlarına gidin (BizPrint > Settings > Location)</li>
            <li>Template Settings bölümünde "Category Slug" alanına yapıştırın</li>
            <li>Kaydedin ve yazdırmayı test edin</li>
        </ol>

        <div class="info">
            <strong>İpucu:</strong> Eğer ürünler görünmüyorsa:
            <ul>
                <li>Debug Mode'u açın</li>
                <li>Kategori slug'ını kontrol edin (büyük/küçük harf duyarlıdır)</li>
                <li>Ürünlerin gerçekten o kategoride olduğundan emin olun</li>
            </ul>
        </div>
    </div>
</body>
</html>
