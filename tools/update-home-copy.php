<?php
declare(strict_types=1);
$db = new PDO('sqlite:'.__DIR__.'/../storage/site.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// General homepage copy — more natural, first-person, human tone; avoids repeated generic phrasing.
$settings = [
    'hero_eyebrow' => 'YURTDAŞ HUKUK BÜROSU',
    'hero_title' => "Sorununuzu dinleriz,\nyol haritasını birlikte çıkarırız.",
    'hero_text' => 'Bir hukuki meseleyle karşılaştığınızda genelde ilk soru "şimdi ne olacak?" oluyor. Dosyanızı ilk günden itibaren bizzat ben takip eder, süreci size adım adım anlatırım.',
    'hero_button' => 'Ofisimizi tanıyın',
    'hero_secondary' => 'Çalışma alanlarımız',
    'hero_note' => 'AV. HALİL İBRAHİM YURTDAŞ İLE DOĞRUDAN GÖRÜŞME',

    'intro_eyebrow' => 'NASIL ÇALIŞIYORUZ',
    'intro_title' => "Dosyanız kaç kişiden geçmiyor;\ntek bir avukattan geçiyor.",
    'intro_text' => "Büyük bürolarda dosyanız stajyerden kıdemli avukata, oradan ortağa kadar birkaç elden geçebilir. Burada öyle değil: görüştüğünüz kişi, dosyanızı mahkemeye taşıyan kişinin ta kendisi.\nBu da hem daha hızlı geri dönüş, hem de sürecin her aşamasında aynı kişiyle konuşma rahatlığı demek.",

    'approach_eyebrow' => 'ÇALIŞMA TARZIM',
    'approach_title' => "Önce dosyayı anlarım,\nsonra strateji kurarım.",
    'approach_text' => 'Bir dosyayı almadan önce mutlaka detaylı bir ön görüşme yaparım: elde olan belgeler, zaman çizelgesi ve sizin hedefiniz nedir. Bazı dosyalarda en iyi sonuç mahkemeden değil, doğru bir uzlaşmadan geçer; bunu da baştan açık şekilde konuşurum.',

    'office_eyebrow' => 'RANDEVU',
    'office_title' => "Durumunuzu anlatın,\nsize ne kadar sürede dönebileceğimizi söyleyeyim.",
    'office_text' => 'Telefon veya iletişim formundan yazın; genellikle aynı gün içinde geri dönüş yaparım. İlk görüşmede dosyanızı dinler, gerçekçi bir değerlendirme sunarım.',

    'practice_title' => 'Hangi konuda yardımcı olabilirim?',
    'practice_text' => 'Aşağıdaki altı alanda düzenli olarak dosya alıyorum. Alanınızı görmüyorsanız da yazabilirsiniz; ilk görüşmede size doğru yönü gösterebilirim.',

    'articles_text' => 'Sık sorulan sorulara ve pratik bilgilere dair yazdığım kısa notlar. Hukuki tavsiye niteliğinde değildir; somut durumunuz için görüşme talep etmenizi öneririm.',

    'contact_title' => "Durumunuzu anlatın,\nne yapabileceğimize birlikte bakalım.",
    'contact_text' => 'Görüşme talebinizi iletin; size dönüş yapıp uygun bir randevu saati ayarlayalım.',

    'home_profile_title' => 'Dosyanızı ben, bizzat ben takip ederim.',
    'home_profile_text' => "Yurtdaş Hukuk'u kurduğumdan beri her dosyayı kendim yürütüyorum. Görüşmeleriniz de, mahkeme takibiniz de doğrudan benimle olur; aracı ya da devir yoktur.",

    'seo_title' => 'Yurtdaş Hukuk | Av. Halil İbrahim Yurtdaş',
    'seo_description' => 'Aile, ceza, iş, ticaret, gayrimenkul ve miras hukuku alanlarında Av. Halil İbrahim Yurtdaş ile bizzat görüşün. Dosyanız baştan sona tek bir avukat tarafından takip edilir.',

    'footer_text' => 'Dosyanızı baştan sona bizzat takip ettiğim, doğrudan iletişime açık bir çalışma anlayışı.',
];

$stmt = $db->prepare('INSERT INTO settings (key, value) VALUES (:key, :value) ON CONFLICT(key) DO UPDATE SET value = :value2');
$db->beginTransaction();
foreach ($settings as $key => $value) {
    $stmt->execute([':key' => $key, ':value' => $value, ':value2' => $value]);
    echo "Set: $key\n";
}
$db->commit();
echo "Done.\n";
