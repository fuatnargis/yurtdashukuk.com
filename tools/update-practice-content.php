<?php
declare(strict_types=1);
$db = new PDO('sqlite:'.__DIR__.'/../storage/site.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$practices = [
    'Aile Hukuku' => [
        'excerpt' => 'Boşanma, velayet ve mal paylaşımı süreçlerinde, ailenizin geleceğini de düşünerek ilerleyen bir hukuki takip.',
        'body' => <<<'MD'
## Bu alanda neye dikkat ederiz
Boşanma davaları sadece bir hukuki süreç değil, aynı zamanda tarafların ve varsa çocukların hayatını doğrudan etkileyen bir dönemdir. Anlaşmalı boşanma mı, çekişmeli boşanma mı olacağına karar vermeden önce, tarafların gerçekten uzlaşabileceği noktaları görmek için mutlaka ayrıntılı bir ön görüşme yapıyoruz. Çoğu zaman dava açmadan önce doğru bir protokolle süreç aylar mahkeme kapısında beklemeden çözülebiliyor.

## Sık karşılaştığımız konular
- Anlaşmalı ve çekişmeli boşanma davaları
- Velayet, kişisel ilişki tesisi ve velayetin değiştirilmesi talepleri
- Nafaka (tedbir, iştirak, yoksulluk) hesaplamaları ve artırım/kaldırma davaları
- Mal rejimi tasfiyesi, katkı payı ve katılma alacağı talepleri
- Nişanın bozulması ve maddi-manevi tazminat talepleri
- Aile içi şiddet hallerinde 6284 sayılı Kanun kapsamında koruma tedbiri başvuruları

## İlk görüşmede neler konuşulur
Evlilik tarihi, varsa çocukların yaşı, mevcut mal varlığı durumu ve tarafların bu konudaki beklentilerini birlikte gözden geçiriyoruz. Elinizde varsa nikah cüzdanı, varsa önceki protokol taslakları ve mal varlığına ilişkin belgeleri (tapu, araç ruhsatı, banka hesap dökümleri gibi) görüşmeye getirmeniz süreci hızlandırır. Anlaşmalı boşanmaya uygun bir dosyada genellikle tek celsede sonuçlanabiliyoruz; çekişmeli dosyalarda ise süreci baştan sona bizzat takip ederim.
MD,
    ],
    'Ceza Hukuku' => [
        'excerpt' => 'Şüpheli, sanık veya mağdur sıfatıyla; soruşturmadan Yargıtay sürecine kadar hakların korunması.',
        'body' => <<<'MD'
## Bu alanda neye dikkat ederiz
Bir ceza dosyasında en kritik saatler genellikle soruşturmanın ilk saatleridir — ifade öncesi hazırlık, gözaltı süreci ve savcılık ifadesi. Bu aşamada yapılan bir hata, sonraki tüm süreci etkileyebiliyor. Bu yüzden ister şüpheli, ister mağdur sıfatıyla başvurmuş olun, dosyanıza mümkün olan en kısa sürede, soruşturma aşamasında dahil olmayı önemsiyorum.

## Sık karşılaştığımız konular
- Soruşturma aşamasında müdafi/vekil olarak ifade ve sorgu süreçlerine katılım
- Tutuklama, adli kontrol ve itiraz süreçleri
- Ağır ceza ve asliye ceza mahkemelerinde sanık veya mağdur/şikayetçi vekilliği
- Uzlaştırma sürecinin yürütülmesi
- İstinaf ve Yargıtay temyiz süreçleri
- Suça sürüklenen çocuklar için özel usul kapsamında savunma

## İlk görüşmede neler konuşulur
Dosya bir soruşturma numarasına (fezleke, C. savcılığı soruşturma no) bağlıysa bunu, mağdur iseniz elinizdeki şikayet dilekçesi veya tutanak örneklerini görüşmeye getirmeniz faydalı olur. Olayın kronolojik akışını birlikte çıkarıp, hangi aşamada olduğunuzu (soruşturma, kovuşturma, temyiz) netleştiriyoruz. Gözaltı veya tutuklama söz konusuysa görüşme talebinizi olabildiğince hızlı iletmenizi öneririm; bu tür dosyalarda zaman kaybı geri döndürülemez sonuçlar doğurabilir.
MD,
    ],
    'İş Hukuku' => [
        'excerpt' => 'İşe iade, kıdem-ihbar tazminatı ve fazla çalışma alacaklarında; işçi ve işveren tarafında hukuki destek.',
        'body' => <<<'MD'
## Bu alanda neye dikkat ederiz
İş hukuku dosyalarının büyük kısmı, işten çıkarılma anındaki tebligatın ve fesih bildiriminin doğru okunmasıyla başlar. Feshin haklı mı haksız mı olduğu, ihbar önellerinin doğru işletilip işletilmediği ve kıdem tazminatı hesaplamasının doğruluğu, davanın kaderini büyük ölçüde belirliyor. Hem işçi hem işveren tarafında çalıştığım için, sürecin her iki yönünü de biliyorum.

## Sık karşılaştığımız konular
- İşe iade davaları ve geçersiz fesih iddiaları
- Kıdem tazminatı, ihbar tazminatı ve fazla çalışma (mesai) alacakları
- Yıllık izin, hafta tatili ve resmi tatil ücreti alacakları
- Mobbing (psikolojik taciz) iddiaları ve tazminat talepleri
- İş sözleşmelerinin ve rekabet yasağı kayıtlarının hazırlanması/incelenmesi
- Arabuluculuk sürecinin yürütülmesi (iş davalarında dava şartı)

## İlk görüşmede neler konuşulur
İşe giriş tarihiniz, son brüt maaşınız, fesih bildirimi (varsa yazılı tebligat) ve elinizdeki bordro/SGK hizmet dökümü gibi belgeler alacak hesaplamasının ilk adımını oluşturuyor. İş davalarında dava açmadan önce zorunlu arabuluculuk süreci işletilmesi gerekiyor; bu süreci de baştan itibaren sizinle birlikte yürütüyorum, arabuluculukta varılan anlaşma genellikle dosyayı mahkemeye gitmeden sonuçlandırıyor.
MD,
    ],
    'Ticaret & Şirketler Hukuku' => [
        'excerpt' => 'Şirket kuruluşundan ortaklık uyuşmazlıklarına; ticari ilişkilerinizi hukuki riske karşı koruyacak bir çalışma.',
        'body' => <<<'MD'
## Bu alanda neye dikkat ederiz
Ticari ilişkilerde asıl mesele, uyuşmazlık çıktıktan sonra değil, sözleşme imzalanmadan önce başlıyor. Bir şirket kuruluşunda ortaklık yapısının, bir ticari sözleşmede ise cezai şart ve fesih maddelerinin doğru kurulması, ileride açılacak bir davayı büyük ölçüde önlüyor. Bu yüzden hem önleyici (kuruluş, sözleşme) hem de uyuşmazlık çözümü (dava, icra) aşamalarında birlikte çalışıyoruz.

## Sık karşılaştığımız konular
- Limited ve anonim şirket kuruluşu, esas sözleşme hazırlığı
- Ortaklar arası uyuşmazlıklar, ortaklıktan çıkarılma ve fesih davaları
- Ticari sözleşmelerin (bayilik, distribütörlük, hizmet, tedarik) hazırlanması ve incelenmesi
- Alacak takibi, senet/çek uyuşmazlıkları ve icra takipleri
- Haksız rekabet iddiaları
- Şirket birleşme, devir ve genel kurul süreçlerine hukuki destek

## İlk görüşmede neler konuşulur
Mevcut bir uyuşmazlık varsa ilgili sözleşmeyi, yazışmaları (e-posta, ihtarname) ve varsa fatura/ödeme kayıtlarını görüşmeye getirmeniz değerlendirmeyi hızlandırır. Yeni bir kuruluş veya sözleşme hazırlığı söz konusuysa, işin ticari mantığını (ortaklık payları, kâr paylaşımı, riskler) anlamak için önce sizi dinliyor, sonra bunu hukuki metne dönüştürüyoruz.
MD,
    ],
    'Gayrimenkul Hukuku' => [
        'excerpt' => 'Tapu iptali, kira uyuşmazlıkları ve kat mülkiyetinde; taşınmazınızla ilgili riskleri baştan görmek.',
        'body' => <<<'MD'
## Bu alanda neye dikkat ederiz
Bir taşınmaz alım satımında veya kira ilişkisinde sorunlar genellikle sözleşme imzalanmadan önce fark edilmeyen ayrıntılardan çıkıyor — tapu kaydındaki bir şerh, kat irtifakı eksikliği veya kira sözleşmesindeki eksik bir madde gibi. Alım satımdan önce tapu kaydını incelemek, sonradan açılacak bir tapu iptali davasından çok daha az maliyetli oluyor.

## Sık karşılaştığımız konular
- Tapu iptali ve tescil davaları (muris muvazaası, hile, ehliyetsizlik iddiaları)
- Kira uyuşmazlıkları: tahliye davaları, kira artış oranı itirazları, depozito iadesi
- Kat mülkiyeti ve yönetim planı kaynaklı komşuluk uyuşmazlıkları
- İzale-i şüyu (ortaklığın giderilmesi) davaları
- Önalım (şufa) hakkı davaları
- Taşınmaz satış vaadi sözleşmelerinin hazırlanması ve incelenmesi

## İlk görüşmede neler konuşulur
Taşınmazın tapu bilgileri (ada/parsel, il/ilçe) elinizde varsa görüşmeyi büyük ölçüde kolaylaştırır; gerekirse tapu kaydını sizinle birlikte sorgularız. Kira uyuşmazlıklarında mevcut kira sözleşmesi ve varsa ihtarname örnekleri önceliğimiz oluyor. Amacınız dava açmak değil de bir işlem öncesi risk taraması yaptırmaksa, bu görüşmeyi de aynı şekilde değerlendirebiliriz.
MD,
    ],
    'Miras Hukuku' => [
        'excerpt' => 'Mirasçılık belgesi, tenkis davaları ve miras paylaşımında; aile içi uyuşmazlığı büyütmeden çözüm.',
        'body' => <<<'MD'
## Bu alanda neye dikkat ederiz
Miras dosyaları, hukuki tarafı kadar aile içi hassasiyetleri de olan dosyalardır. Bir mirasçılık belgesi çıkarmak başka, mirasçılar arasındaki bir paylaşım anlaşmazlığını çözmek başka bir süreçtir. Mümkün olduğunca dava açmadan, mirasçılar arasında makul bir paylaşıma ulaşmayı önceliklendiriyorum; ancak bu mümkün olmadığında dava sürecini de aynı kararlılıkla takip ediyorum.

## Sık karşılaştığımız konular
- Mirasçılık belgesi (veraset ilamı) alınması
- Miras taksim sözleşmesi hazırlanması ve tereke paylaşımı
- Tenkis davaları (saklı pay ihlali iddiaları)
- Mirasın reddi ve mirastan mal kaçırma (muris muvazaası) davaları
- Vasiyetname hazırlanması ve vasiyetnamenin iptali davaları
- Mirasçılıktan çıkarma (ıskat) süreçleri

## İlk görüşmede neler konuşulur
Miras bırakanın vefat tarihi, mirasçıların kimler olduğu ve tereke içinde ne tür mal varlığı (taşınmaz, banka hesabı, araç) bulunduğunu birlikte netleştiriyoruz. Elinizde veraset ilamı, tapu kaydı veya varsa vasiyetname örneği varsa görüşmeye getirmeniz değerlendirmeyi hızlandırır. Aile içindeki mevcut anlaşmazlığın boyutunu da baştan konuşmak, izleyeceğimiz yolu (uzlaşma mı, dava mı) netleştirmemize yardımcı olur.
MD,
    ],
];

$stmt = $db->prepare('UPDATE entries SET excerpt = :excerpt, body = :body, updated_at = :updated_at WHERE type = :type AND title = :title');
$db->beginTransaction();
$affected = 0;
foreach ($practices as $title => $data) {
    $stmt->execute([
        ':excerpt' => $data['excerpt'],
        ':body' => $data['body'],
        ':updated_at' => date('Y-m-d H:i:s'),
        ':type' => 'practice',
        ':title' => $title,
    ]);
    $affected += $stmt->rowCount();
    echo "Updated: $title (rows: {$stmt->rowCount()})\n";
}
$db->commit();
echo "Total affected rows: $affected\n";
