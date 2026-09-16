<?php
declare(strict_types=1);
const CONTENT_TYPES=[
    'article'=>['label'=>'Makaleler','single'=>'Makale','icon'=>'book','hint'=>'Hukuki yayınlarınızı hazırlayın, taslak olarak saklayın veya yayınlayın.'],
    'practice'=>['label'=>'Çalışma Alanları','single'=>'Çalışma alanı','icon'=>'scale','hint'=>'Çalışma alanlarını, açıklamalarını ve görüntülenme sıralarını düzenleyin.'],
    'law'=>['label'=>'Kanunlar','single'=>'Kanun bağlantısı','icon'=>'building','hint'=>'Kanun adını, numarasını, açıklamasını ve resmî kaynak bağlantısını düzenleyin.'],
    'page'=>['label'=>'Sayfalar','single'=>'Sayfa','icon'=>'file','hint'=>'Kurumsal bilgiler, aydınlatma metinleri ve ek sayfaları yönetin.'],
    'team'=>['label'=>'Ekip','single'=>'Ekip üyesi','icon'=>'users','hint'=>'Avukatların özgeçmişlerini, görevlerini ve fotoğraflarını ekleyin.'],
    'faq'=>['label'=>'Sık Sorulan Sorular','single'=>'Soru','icon'=>'mail','hint'=>'Ana sayfadaki soru ve yanıtları düzenleyin.'],
    'menu'=>['label'=>'Menüler','single'=>'Menü bağlantısı','icon'=>'menu','hint'=>'Ana menü ve alt menü bağlantılarını sıralayın. Küçük sıra numarası önce görünür.'],
];
function settings_fields(): array {return [
    'identity'=>['title'=>'Büro Kimliği','description'=>'Büronuzun adı, iletişim bilgileri ve sosyal bağlantıları.','fields'=>[
        'brand'=>['Büro adı','text'],'brand_subtitle'=>['Alt başlık','text'],'logo'=>['Logo','image'],'favicon'=>['Site simgesi','image'],
        'city'=>['Şehir','text'],'address'=>['Açık adres','textarea'],'phone'=>['Telefon','text'],'email'=>['E-posta','email'],'whatsapp'=>['WhatsApp numarası (ülke koduyla)','text'],'office_hours'=>['Çalışma saatleri','text'],'linkedin'=>['LinkedIn bağlantısı','url'],'instagram'=>['Instagram bağlantısı','url'],
    ]],
    'home'=>['title'=>'Ana Sayfa','description'=>'Karşılama alanını ve kurumsal tanıtım bölümünü düzenleyin.','fields'=>[
        'hero_eyebrow'=>['Üst etiket','text'],'hero_title'=>['Ana başlık','textarea'],'hero_text'=>['Karşılama metni','textarea'],'hero_image'=>['Ana görsel','image'],'hero_button'=>['Birinci buton metni','text'],'hero_button_url'=>['Birinci buton bağlantısı','link'],'hero_secondary'=>['İkinci buton metni','text'],'hero_secondary_url'=>['İkinci buton bağlantısı','link'],'hero_note'=>['Görsel altındaki ilke satırı','text'],
        'intro_eyebrow'=>['Tanıtım üst etiketi','text'],'intro_title'=>['Tanıtım başlığı','textarea'],'intro_text'=>['Tanıtım metni','textarea'],'intro_image'=>['Tanıtım görseli','image'],'intro_label'=>['Görsel kartı başlığı','text'],'intro_caption'=>['Görsel kartı açıklaması','text'],
        'approach_eyebrow'=>['Çalışma anlayışı etiketi','text'],'approach_title'=>['Çalışma anlayışı başlığı','textarea'],'approach_text'=>['Çalışma anlayışı metni','textarea'],'approach_image'=>['Çalışma anlayışı görseli','image'],
        'office_eyebrow'=>['Büro kartı etiketi','text'],'office_title'=>['Büro kartı başlığı','textarea'],'office_text'=>['Büro kartı açıklaması','textarea'],
    ]],
    'sections'=>['title'=>'Bölümler & Görünürlük','description'=>'Bölüm başlıklarını ve ana sayfada hangi bölümlerin görüneceğini seçin.','fields'=>[
        'practice_eyebrow'=>['Çalışma alanları etiketi','text'],'practice_title'=>['Çalışma alanları başlığı','textarea'],'practice_text'=>['Çalışma alanları açıklaması','textarea'],
        'articles_eyebrow'=>['Yayınlar etiketi','text'],'articles_title'=>['Yayınlar başlığı','text'],'articles_text'=>['Yayınlar açıklaması','textarea'],
        'contact_eyebrow'=>['İletişim bandı etiketi','text'],'contact_title'=>['İletişim bandı başlığı','textarea'],'contact_text'=>['İletişim bandı açıklaması','textarea'],'contact_button'=>['İletişim butonu metni','text'],
        'show_intro'=>['Kurumsal tanıtımı göster','checkbox'],'show_approach'=>['Çalışma anlayışını göster','checkbox'],'show_office'=>['Büro ve iletişim kartını göster','checkbox'],'show_practices'=>['Çalışma alanlarını göster','checkbox'],'show_articles'=>['Son yayınları göster','checkbox'],'show_faq'=>['Sık sorulan soruları göster','checkbox'],'show_contact'=>['İletişim bandını göster','checkbox'],'show_mobile_contact'=>['Mobil iletişim çubuğunu göster','checkbox'],'contact_enabled'=>['İletişim formunu etkinleştir','checkbox'],'articles_per_page'=>['Arşivde sayfa başına makale','number'],
    ]],
    'appearance'=>['title'=>'Görünüm & Alt Bilgi','description'=>'Kurumsal renkleri ve alt bilgi metinlerini düzenleyin.','fields'=>[
        'primary_color'=>['Ana renk','color'],'accent_color'=>['Klasik vurgu rengi','color'],'action_color'=>['Buton ve bağlantı rengi','color'],'footer_text'=>['Alt bilgi tanıtım metni','textarea'],'legal_notice'=>['Genel bilgilendirme notu','textarea'],
    ]],
    'directory'=>['title'=>'Arşiv & Ekip Metinleri','description'=>'Kategori arşivi, kanunlar ve ekip sayfalarındaki tanıtım metinleri.','fields'=>[
        'category_directory_title'=>['Kategori dizini başlığı','text'],'category_directory_text'=>['Kategori dizini açıklaması','textarea'],
        'laws_title'=>['Kanunlar başlığı','text'],'laws_text'=>['Kanunlar açıklaması','textarea'],'laws_note'=>['Kanunlar kaynak notu','textarea'],
        'team_empty_title'=>['Ekip tanıtım kartı başlığı','text'],'team_empty_text'=>['Ekip tanıtım kartı metni','textarea'],
    ]],
    'texts'=>['title'=>'Bölüm & Buton Metinleri','description'=>'Sitedeki ortak başlıkları, açıklamaları ve buton metinlerini düzenleyin.','fields'=>array_combine(array_map(fn($key)=>'text_'.$key,array_keys(SITE_TEXTS)),array_map(fn($row)=>[$row[0],'textarea'],array_values(SITE_TEXTS)))],
    'seo'=>['title'=>'Arama Motorları','description'=>'Site adresi, paylaşım bilgileri ve arama motoru görünürlüğü. Sayfa bazlı başlık ve açıklamalar için “Meta & SEO” bölümünü kullanın.','fields'=>[
        'site_url'=>['Yayın adresi (https://alanadiniz.com)','url'],'seo_title'=>['Ana sayfa başlığı','text'],'seo_description'=>['Ana sayfa açıklaması','textarea'],'indexing'=>['Arama motorlarının siteyi dizine eklemesine izin ver','checkbox'],
        'seo_og_image'=>['Varsayılan paylaşım görseli (1200×630)','image'],'seo_twitter'=>['X / Twitter kullanıcı adı (@buro)','text'],'seo_google_verification'=>['Google Search Console doğrulama kodu','text'],
        'seo_legal_name'=>['Resmî unvan (schema.org legalName)','text'],'seo_founding_year'=>['Kuruluş yılı','text'],'seo_geo_region'=>['Bölge kodu (ör. 34 = İstanbul)','text'],'seo_geo_lat'=>['Büro enlem (latitude)','text'],'seo_geo_lng'=>['Büro boylam (longitude)','text'],
    ]],
];}
function valid_setting(string $key,string $value,string $type): bool {
    if(mb_strlen($value)>15000)return false;
    if(in_array($key,['brand','hero_title','seo_title'],true)&&$value==='')return false;
    if($type==='color')return (bool)preg_match('/^#[a-f0-9]{6}$/i',$value);
    if($type==='checkbox')return in_array($value,['0','1'],true);
    if($key==='articles_per_page')return ctype_digit($value)&&(int)$value>=3&&(int)$value<=24;
    if($value==='')return true;
    if(in_array($key,['seo_geo_lat','seo_geo_lng'],true))return is_numeric($value)&&abs((float)$value)<=($key==='seo_geo_lat'?90:180);
    if($key==='seo_founding_year')return (bool)preg_match('/^(18|19|20)\d{2}$/',$value);
    if($key==='seo_twitter')return (bool)preg_match('/^@?[A-Za-z0-9_]{1,15}$/',$value);
    if($key==='seo_google_verification')return (bool)preg_match('/^[A-Za-z0-9_-]{8,120}$/',$value);
    if($key==='seo_geo_region')return (bool)preg_match('/^[A-Za-z0-9]{1,3}$/',$value);
    if($type==='email')return (bool)filter_var($value,FILTER_VALIDATE_EMAIL);
    if($type==='url')return filter_var($value,FILTER_VALIDATE_URL)&&in_array(parse_url($value,PHP_URL_SCHEME),['http','https'],true)&&!parse_url($value,PHP_URL_USER)&&!parse_url($value,PHP_URL_PASS)&&!parse_url($value,PHP_URL_FRAGMENT)&&($key!=='site_url'||!parse_url($value,PHP_URL_QUERY));
    if($type==='link')return safe_url($value)===$value;
    if($type==='image')return safe_image($value)===$value && is_file(ROOT.$value);
    return true;
}
