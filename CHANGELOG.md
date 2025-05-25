# Changelog - Boissons Only Print Template

## Version 1.0.0 - Enhanced Edition

### 🚀 Yeni Özellikler + Debug Düzeltmeleri

#### 1. **Plain Text Format Desteği**
- Artık hem HTML hem de Plain text formatlarında yazdırma yapabilirsiniz
- Thermal yazıcılar için optimize edilmiş plain text çıktısı
- `Document` sınıfının tüm yardımcı metodlarını kullanır (centerLine, symbolsAlign, vb.)

#### 2. **Dinamik Kategori Filtreleme**
- Sadece "boissons" kategorisiyle sınırlı değilsiniz
- BizPrint ayarlarından istediğiniz kategori slug'ını girebilirsiniz
- Örnek: "beverages", "drinks", "icecekler" vb.

#### 3. **Template Options Desteği**
- **Kategori Slug**: Hangi ürün kategorisini filtreleyeceğinizi seçin
- **Müşteri Bilgileri**: Müşteri bilgilerini göster/gizle
- **Özel Başlık**: Varsayılan başlığı özelleştirin

#### 4. **Çoklu Dil Desteği**
- WordPress text domain sistemi entegrasyonu
- Tüm metinler çeviri için hazır
- POT dosyası ile kolay çeviri imkanı

#### 5. **Debug Modu ve Kategori Hata Ayıklama**
- Debug mode ile sorunları kolayca tespit edin
- Mevcut kategori slug'larını ayarlar sayfasında görün
- HTML yorumlarında detaylı debug bilgisi
- `has_term()` yerine `wp_get_post_terms()` kullanımı ile daha güvenilir kategori kontrolü
- Ürün bulunamadığında kullanıcı dostu mesaj

### 📝 Güncellenmiş Dosyalar

1. **template.php**
   - `Zprint\Template\Options` interface eklendi
   - `renderOptions()` ve `processOptions()` metodları eklendi
   - Çoklu dil desteği için `__()` fonksiyonları

2. **html.php**
   - Dinamik kategori filtreleme
   - Template options kullanımı
   - Özelleştirilebilir başlık metni

3. **plain.php** (YENİ)
   - Thermal yazıcılar için optimize edilmiş format
   - Document sınıfının yardımcı metodlarını kullanır
   - Temiz ve okunabilir çıktı

4. **index.php**
   - Text domain yükleme fonksiyonu eklendi
   - `plugins_loaded` hook'u ile dil dosyaları yüklenir

5. **languages/** (YENİ)
   - boissons-print-template.pot dosyası
   - Çeviri şablonu

### 🛠️ Kullanım

1. Eklentiyi WordPress'e yükleyin ve etkinleştirin
2. BizPrint > Settings > Location'a gidin
3. Template olarak "Beverages Only - İçecekler" seçin
4. Yeni ayarlar bölümünde:
   - Kategori slug'ını değiştirin (varsayılan: boissons)
   - Müşteri bilgilerini göster/gizle
   - Özel başlık metni girin

### 🔧 Geliştirici Notları

- WP Actions ve Filters kullanılabilir
- `do_action()` hooks eklendi plain.php dosyasına
- Tüm metinler çevrilebilir
- WordPress kodlama standartlarına uygun

### 📦 Paketleme

```bash
php package.php
```

Bu komut tüm dosyaları içeren bir ZIP arşivi oluşturur.
