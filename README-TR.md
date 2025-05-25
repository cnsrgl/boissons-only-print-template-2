# Boissons Only Print Template - BizPrint İçin Kategori Bazlı Yazdırma

Bu eklenti, WooCommerce siparişlerinden sadece belirli bir kategorideki ürünleri yazdırmak için geliştirilmiştir. BizPrint eklentisi ile uyumlu çalışır.

## 🎯 Özellikler

- **Kategori Filtreleme**: İstediğiniz ürün kategorisini seçerek sadece o kategorideki ürünleri yazdırın
- **HTML ve Plain Text Desteği**: Hem normal hem de thermal yazıcılar için uygun formatlar
- **Özelleştirilebilir Başlık**: Fiş başlığını istediğiniz gibi değiştirin
- **Müşteri Bilgileri Kontrolü**: Müşteri bilgilerini göster/gizle
- **Debug Modu**: Sorunları kolayca tespit edin
- **Çoklu Dil Desteği**: WordPress çeviri sistemi ile uyumlu

## 📋 Kurulum

1. Eklenti klasörünü `/wp-content/plugins/` dizinine yükleyin
2. WordPress yönetim panelinden eklentiyi aktifleştirin
3. **BizPrint > Settings > Location** menüsüne gidin
4. Yazıcı konumunuzu seçin
5. Template olarak **"Beverages Only - İçecekler"** seçin
6. Ayarları kaydedin

## ⚙️ Ayarlar

### Kategori Slug'ı
- Hangi kategoriyi filtrelemek istediğinizi belirtin
- Varsayılan: `boissons`  
- Örnek sluglar: `beverages`, `drinks`, `icecekler`

### Debug Modu
- Sorun yaşadığınızda bu modu açın
- HTML yorumlarında detaylı bilgi görürsünüz
- Hangi ürünlerin hangi kategorilerde olduğunu gösterir

### Mevcut Kategoriler
- Ayarlar sayfasında tüm kategori slug'larını görebilirsiniz
- Doğru slug'ı buradan kopyalayın

## 🔧 Sorun Giderme

### Ürünler Görünmüyor mu?

1. **Debug Modunu Açın**
   - Template ayarlarından Debug Mode'u işaretleyin
   - Kaydedin ve tekrar yazdırmayı deneyin

2. **Kategori Slug'ını Kontrol Edin**
   - Kategori adı değil, slug kullanın
   - Örnek: "İçecekler" yerine "icecekler"
   - Ayarlar sayfasındaki listeden doğru slug'ı kopyalayın

3. **Test Script'ini Kullanın**
   - `test-categories.php` dosyasını WordPress kök dizinine yükleyin
   - Tarayıcıdan `siteniz.com/test-categories.php` adresine gidin
   - Tüm kategorileri ve ürünleri görebilirsiniz

4. **HTML Yorumlarını İnceleyin**
   - Tarayıcıda sağ tık > Kaynağı Görüntüle
   - `<!-- DEBUG:` ile başlayan yorumları arayın

5. **Ürün Kategorilerini Kontrol Edin**
   - WooCommerce > Ürünler menüsünden ürünleri kontrol edin
   - Ürünlerin gerçekten istediğiniz kategoride olduğundan emin olun

## 📝 Örnek Kullanım

### Sadece İçecekleri Yazdırma
```
Kategori Slug: icecekler
```

### Sadece Tatlıları Yazdırma
```
Kategori Slug: tatlilar
```

### Özel Başlık ile
```
Kategori Slug: kahve
Özel Başlık: Kahve Siparişi
```

## 🚀 Gelişmiş Özellikler

### WP Actions
Eklenti şu action'ları destekler:
- `Zprint\templates\boissons-plain\afterShippingDetails`
- `Zprint\templates\boissons-plain\beforeFooter`
- `Zprint\templates\boissons-plain\end`

### Özelleştirme
- `style.php` dosyasını düzenleyerek tasarımı değiştirebilirsiniz
- `html.php` ve `plain.php` dosyalarını düzenleyerek içeriği özelleştirebilirsiniz

## 📦 Paketleme

Eklentiyi ZIP olarak paketlemek için:
```bash
php package.php
```

## 🤝 Destek

Sorun yaşarsanız:
1. Debug modunu açın
2. Kategori slug'larını kontrol edin
3. BizPrint dokümantasyonunu inceleyin
4. WordPress hata günlüklerini kontrol edin

## 📄 Lisans

GPL v2 veya üstü

---

**Not**: Bu eklenti BizPrint (Print Manager for WooCommerce) eklentisi gerektirir.
