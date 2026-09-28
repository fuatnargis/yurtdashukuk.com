# Yurtdaş Hukuk — 28 Eylül 2026 kontrol notları

Yerel önizleme: http://127.0.0.1:8088/  
Yönetim paneli: http://127.0.0.1:8088/admin/  
Avukat profili: http://127.0.0.1:8088/avukatlar/halil-ibrahim-yurtdas

## Yapılan değişiklikler

- Profil sayfası; portre/baş harf alanı, mesleki bilgiler, biyografi, yayınlar ve iletişim bağlantılarıyla yeniden düzenlendi. Profil kartının tamamı tıklanabilir; bağlantı belirgin bir buton görünümündedir.
- Baro, baro ve TBB sicil numaraları, mesleğe başlama tarihi, üniversite, yabancı dil ve KEP adresi profil düzenleyicisinden yönetilir. Boş bilgiler gösterilmez. Başka bir kişiye ait olduğu doğrulanmamış görsel profil fotoğrafı yapılmadı.
- Profil, görüşme süreci ve konum dahil bütün ana sayfa bölümleri panelden sıralanabilir ve gizlenebilir. Ortak profil, form ve arşiv metinleri mevcut metin ayarlarına bağlandı.
- Panelde profil, tema, bölüm sırası ve KVKK için hızlı bağlantılar; ayar sayfalarında arama eklendi. Menülerin hatalı “İçerik eksik” uyarısı giderildi.
- Tema ayarındaki sıfır köşe yarıçapı korunur; ana görsel yüksekliği uygulanır. İç içe paragraf HTML’i düzeltildi. Slayta duraklatma kontrolü eklendi.
- Sayfa adresi değişince eski adresten yeni adrese 301 yönlendirmesi oluşur. Arşivlenmiş/taslak içerik bu yönlendirmelerle açılmaz. Menüdeki ilgili adres güncellenir.
- Profil adı meta düzenleyicisinde değiştiğinde makale yazar bağlantıları da güncellenir. Aynı büro adının sayfa başlığında iki kez görünmesi düzeltildi.
- Ayarlar her HTTP isteğinde bir kez okunur. Yedekler mesleki bilgileri ve kalıcı yönlendirmeleri de içerir; eski bölüm sırasına sahip yedekler desteklenir.
- Ana sayfada Google Haritalar gömülü haritası yeniden görünür. Harita ekrana yaklaşıldığında yüklenir; yol tarifi bağlantısı Google Haritalar’ı yeni sekmede açar. Harita alanı ve konumu yönetim panelinden değiştirilebilir.
- Mobil alt çubukta telefon ve WhatsApp ayrı, büyük dokunma alanlarına taşındı. WhatsApp simgesi projenin önceki Font Awesome marka setinden alınan SVG'dir; kaynak ve lisans bilgisi SVG içinde bulunur.

## Panelde tamamlanacak gerçek bilgiler

1. **Avukat Profilleri:** doğrulanmış mesleki bilgiler ve kullanılmasına izin verilen gerçek profil fotoğrafı.
2. **Site Ayarları → Büro Kimliği:** e-posta ve iletişim bilgilerinin doğruluğu. Yerel kayıtta e-posta boş.
3. **Sayfalar → Kişisel Verilerin Korunması:** gerçek veri sorumlusu, veri kategorileri, amaçlar, hukuki sebepler, toplama yöntemi, alıcılar, barındırma/aktarım süreçleri ve başvuru kanalları. Metin hâlen taslak; gerçek süreçler bilinmeden tamamlanmış sayılmaz.
4. **Site Ayarları → Form ve KVKK:** tamamlanan metnin mevcut sürümünü doğrulama. Doğrulanmadan form gösterilmez ve sunucu başvuru kabul etmez. KVKK gövdesi değişince inceleme yenilenir. Telefon ve iletişim bağlantıları çalışmaya devam eder. Form talepleri panelde saklanır; SMTP/e-posta bildirimi kurulu değildir.
5. Yayın öncesi içeriklerin mesleki incelemesi, gerçek sunucuda HTTPS ve veri saklama/aktarım koşullarının kontrolü. Yerel veritabanında arama motoru dizinlemesi kapalı tutuldu; canlı dağıtım yapılmadı.

## Hukuki yaklaşım

TBB Reklam Yasağı Yönetmeliği m. 7(d)-(e), web sitesi bilgilerini ve meslektaşlarla rekabete/iş elde etmeye yönelik sıralama uygulamalarını sınırlar. Çalışma alanları uzmanlık iddiası olmadan sunulmalıdır. Üst sıralara çıkma veya yapay zekâ sonuçlarında önerilme garantisi verilmez. Teknik erişilebilirlik, tutarlı kimlik ve görünür içerikle uyumlu yapılandırılmış veri korunmuştur; bu, sitenin bütün içerik ve işletme süreçlerine ilişkin hukuki uygunluk belgesi değildir.

- [TBB — güncel Reklam Yasağı Yönetmeliği](https://d.barobirlik.org.tr/mevzuat/avukata_ozel/yonetmelikler/2011/reklam_yas_yon.pdf)
- [KVKK — aydınlatma yükümlülüğü açıklaması](https://www.kvkk.gov.tr/Icerik/6765/AYDINLATMA-YUKUMLULUGUNUN-YERINE-GETIRILMESI-HAKKINDA-KAMUOYU-DUYURUSU)
- [KVKK — çerez ve aydınlatma uygulamaları, 2024/1361](https://www.kvkk.gov.tr/Icerik/8884/2024-1361)

## Doğrulama sınırı

`node tools/deploy-check.mjs` PHP sözdizimini ve ayrı geçici veritabanındaki HTTP/entegrasyon senaryolarını kontrol eder. Son sonuçlar `tests/artifacts/integration-results.json` dosyasındadır. Yönetici girişi, CSRF, içerik yayınlama, tema ayarları, profil bilgileri, bölüm sırası, form, KVKK sürüm denetimi, dosya yükleme, yedekler ve eski adresler kapsanır. Gerçek siteye test içerikleri veya test başvuruları eklenmez.

Yerel ana sayfa, kurumsal sayfa, iletişim ve profil adreslerinin HTTP 200 yanıtı doğrulandı. Bu oturumda Chrome ve uygulama içi tarayıcı bağlantısı bulunmadığından ekran görüntüsüyle masaüstü/mobil görsel kontrol ve gerçek tarayıcı etkileşim testi tamamlanamadı. Üretim sunucusu/PostgreSQL dağıtımı bu yerel testin kapsamında değildir.
