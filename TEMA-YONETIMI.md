# Tema ve içerik yönetimi

Yerel site: http://127.0.0.1:8088/ · Panel: http://127.0.0.1:8088/admin/

Ana sayfa, satın alınan `Juris HTML/HTML/homepage-5.html` şablonundan uyarlanan tam genişlikte görsel slayt, çalışma alanlarına hızlı bağlantılar, yönlendirme bandı, ortalanmış tanıtım, dört ilke, tam çerçeveli çalışma alanı kartları, görselli çalışma anlayışı ve iki sütunlu iletişim alanı içerir. Varsayılan Arial yazı tipi, kurumsal lacivert, açık nötr yüzeyler ve ölçülü altın vurgular kullanılır; metinler ve olgusal sayfa bilgileri panelden yönetilir. Şablondaki filigranlı örnek görseller, örnek avukat isimleri, ödüller ve sahte iletişim bilgileri kullanılmaz.

Karşılama metni sıralı olarak belirir; menü kaydırırken görünür kalır. Ana sayfa bölümleri ekrana girdiklerinde yumuşak geçişle açılır. Bağlantı ve kartlarda kısa, ölçülü tepki efektleri vardır. Cihazında hareket azaltma seçeneğini kullanan ziyaretçiler için bu animasyonlar devre dışıdır.

Tasarım düzeni için [Moroğlu Arseven](https://www.morogluarseven.com/tr/) ve [Clifford Chance](https://www.cliffordchance.com/home.html) ana sayfalarındaki net uzmanlık alanı ve yayın bağlantıları incelendi. Metin ve zemin karşıtlığında [WCAG ölçütü](https://www.w3.org/WAI/WCAG21/Understanding/contrast-minimum) esas alındı. Görünür içerik ile yapılandırılmış verinin uyumu [Google Search Central](https://developers.google.com/search/docs/appearance/ai-features) önerilerine göre korundu.

Ana sayfa menüsü açık zemin üzerinde lacivert yazıyla görünür. Slider fotoğraflarına varsayılan olarak renk katmanı veya doygunluk filtresi uygulanmaz; yazılar fotoğraf üzerinde gölgeyle okunur. Panelde **Site Ayarları → Tema & Renkler → Ana görsel renk katmanı** değeri `0` doğal fotoğrafı gösterir. İstenirse buradan renk katmanı yeniden ayarlanabilir.

Ana sayfadaki avukat kartı `/avukatlar/halil-ibrahim-yurtdas` profilini açar; profil sayfası aynı yazar adına bağlı yayımlanmış makaleleri listeler. Makale kartları ve makale ayrıntısındaki yazar kartı da bu profile bağlanır. Fotoğrafı **Medya Kütüphanesi**'ne yükleyip **Avukat Profilleri → Halil İbrahim Yurtdaş → Görsel** alanından seçebilirsiniz. Adres **Büro Kimliği**'nden yönetilir; farklı bir konum yazılmadıkça yol tarifi bağlantısı adresi kullanır; harita otomatik yüklenmez. Arama ve WhatsApp bağlantıları aynı paneldeki telefon alanlarından oluşur.

Güncel değişiklikler ve tamamlanacak bilgiler: [Teslim notları](TESLIM-NOTLARI.md). İletişim formu, KVKK metni tamamlanıp **Form ve KVKK** ayarından doğrulandıktan sonra başvuru alır.

## Panelde nereden değiştirilir?

| Değişiklik | Panel bölümü |
|---|---|
| 17 ayrı renk, hazır palet, site yazı tipi (Arial, Manrope veya Cormorant Garamond), menü düzeni, köşe yuvarlaklığı, ana görsel yüksekliği ve gölge yoğunluğu | Site Ayarları → Tema & Renkler |
| Bölümleri yukarı/aşağı taşıma, gösterilecek kart sayıları, makale düzeni, görsel alternatif metinleri, buton bağlantıları | Site Ayarları → Sayfa Düzeni |
| Ana sayfa başlıkları, metinleri, üç slaytın görselleri (ana, tanıtım ve çalışma anlayışı görseli) ve ilk iki buton | Site Ayarları → Ana Sayfa |
| Tanıtım, çalışma alanları, makaleler, SSS ve iletişim bölümlerini açma/kapatma | Site Ayarları → Bölümler & Görünürlük |
| İlkeler bandı, görüşme süreci, avukat kartı, konum, üst iletişim şeridi, açılır menü ve sabit iletişim butonunu açma/kapatma | Site Ayarları → Sayfa Düzeni |
| Logo, favicon, adres, haritada aranacak konum, telefon, e-posta, WhatsApp ve sosyal hesaplar | Site Ayarları → Büro Kimliği |
| Avukatın adı, unvanı, fotoğrafı, biyografisi, baro/sicil/eğitim bilgileri ve profil sayfası | Avukat Profilleri |
| Makalenin yazarı ve profil bağlantısı | Makaleler → Yazar (profil adını seçin) |
| Bölüm ve buton metinleri, SSS ve medya sayfası başlıkları, alt menü metinleri | Site Ayarları → Bölüm & Buton Metinleri |
| Menü bağlantıları ve sıraları | Menüler |
| Makale, çalışma alanı, kurumsal ve diğer sayfa içerikleri | İlgili içerik bölümü |
| Basın haberi/röportaj, görsel, yayın kuruluşu ve kaynak bağlantısı | Basında Biz |
| Sorular, yanıtlar, sıralama ve yayın durumu | Sık Sorulan Sorular |
| Sayfa başlığı, meta açıklaması ve sabit sayfa dizin durumu | Meta & SEO |
| Alan adı, dizinleme, paylaşım görseli, Search Console ve konum | Site Ayarları → Arama Motorları |
| Olgusal büro özeti, hizmet bölgesi, posta kodu ve makine tarafından okunabilir bilgilendirme notu | Site Ayarları → GEO & Yerel Bilgiler |
| Üst ve alt bilgi ödeme bağlantısı | Site Ayarları → Ödeme Butonu |

Renk önizlemesi ve hazır paletler, **Ayarları kaydet** seçilene kadar siteyi değiştirmez. HEX renk kodu da yazabilirsiniz. Varsayılan palet ana renk `#102B46`, buton rengi `#20527A`, vurgu rengi `#C9AD7A` ve açık zemin `#F4F6F8` kullanır. Kontrast kontrolü, sayfa/kart metni, ikincil metin, buton, menü, alt bilgi ve koyu bölüm için 4,5:1 oranını kontrol eder. Görseller üzerindeki yazı kontrastı ayrıca görsel olarak değerlendirilmelidir.

Basın bölümü kaldırılmıştır; eski `/basinda-biz` adresi ana sayfaya yönlendirilir. İçerikler taslak, ileri tarihli yayın ve arşiv durumlarını destekler. SSS sayfası `/sikca-sorulan-sorular` adresindedir; ana sayfadaki SSS kapatılsa bile kendi sayfasında erişilebilir kalır. Yeni sayfaların menü bağlantıları Menüler bölümünden düzenlenebilir.

## SEO ve GEO

Sunucuda üretilen HTML, canonical adresler, sosyal paylaşım metaları, LegalService/Organization, Article, Service, BreadcrumbList ve görünür sorularla eşleşen FAQPage verileri korunur. Profil ve SSS adresleri site haritasına dahildir; taslak ve ileri tarihli yayınlar dahil edilmez. Arama/filtre sayfaları `noindex,follow` kullanır. RSS ve `llms.txt` dinamik üretilir. Büro özeti, hizmet bölgesi ve posta kodu görünür içerikle birlikte paylaşılır.

Bu teknik altyapı arama veya yapay zekâ sonuçlarında görünme garantisi değildir. `llms.txt` ek bir içerik rehberidir; Google için özel bir GEO dosyası zorunluluğu yoktur. [Google'ın AI arama yönergeleri](https://developers.google.com/search/docs/appearance/ai-features), erişilebilir içerik ve görünür metinle tutarlı yapılandırılmış veriyi esas alır.

Yeni kurulumda dizinleme kapalıdır. Gerçek büro bilgileri ve yayın alan adı tamamlanınca panelden açılabilir. İletişim formu talepleri panelde saklar; mevcut uygulamada SMTP/e-posta gönderimi bağlı değildir.

## Doğrulama ve dosyalar

`node tests/integration.mjs`: ayrı geçici veritabanında 234 kontrol geçti. Varsayılan lacivert palet ve Arial, panelden yazı tipi ve avukat profili değişimi, yazar bağlantıları, adres/harita/telefon/WhatsApp ayarları, renk ve düzen kaydı, bölüm sırası/görünürlüğü, slayt görselleri, yayın akışı, ana sayfa iletişim formu, site haritası, yedek içe/dışa aktarma, CSRF ve oturum kontrolleri kapsam dahilindedir. PHP ve JavaScript sözdizimi kontrolleri de geçti.

Bu oturumda tarayıcı bağlantısı kullanılamadı; masaüstü/telefon ekran görüntüsü ve gerçek tarayıcı etkileşim doğrulaması tamamlanmadı. CSS masaüstü, tablet ve telefon kırılımlarını içerir. Yerel HTTP önizlemesinin açıldığı doğrulandı. Canlı sunucuya dağıtım yapılmadı.

- `app/theme.php`: ayar şeması, renk değişkenleri ve veri kaybetmeyen sürüm geçişi.
- `assets/cinar.css`: önceki temanın temel bileşenleri.
- `assets/mizan.css`: sitedeki ortak editoryal görünüm.
- `assets/juris-home.css`: Juris düzeninden uyarlanan ana sayfa görünümü.
- `app/views/home-juris.php`: panel sırasıyla oluşturulan yeni ana sayfa.
- `app/cinar-components.php`: açılır menü, çalışma alanı, medya ve SSS bileşenleri.
- `app/theme-admin.php`, `assets/theme-admin.js`: renk önizlemesi ve bölüm sıralama arayüzü.
- `storage/before-cinar-theme-*.sqlite`: yerel veritabanının alınan tutarlı yedeği. Genel erişime kapalı dizindedir.

Önceki sürümün özel içerikleri, yönetici hesapları ve ödeme ayarları korunur. Önceki varsayılan yeşil palet lacivert palete geçirilir; önceden özelleştirilmiş kurumsal renkler değiştirilmez. Bundan sonraki renk değişiklikleri yeniden açılışta sıfırlanmaz.
