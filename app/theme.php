<?php
declare(strict_types=1);

// One schema for defaults, safe CSS output and the administration controls.
const THEME_COLORS = [
    'primary_color'=>['Ana kurumsal renk','navy','#102b46'],
    'accent_color'=>['Vurgu ve simge rengi','gold','#c9ad7a'],
    'action_color'=>['Buton ve bağlantı rengi','action','#20527a'],
    'button_text_color'=>['Buton yazısı','button-text','#ffffff'],
    'background_color'=>['Sayfa arka planı','background','#f4f6f8'],
    'surface_color'=>['Kart ve form zemini','surface','#ffffff'],
    'text_color'=>['Ana metin','ink','#192b3b'],
    'muted_color'=>['İkincil metin','muted','#596b7a'],
    'border_color'=>['Çizgi ve kenarlık','line','#dbe3ea'],
    'header_color'=>['Üst menü zemini','header-bg','#ffffff'],
    'header_text_color'=>['Üst menü yazısı','header-text','#102b46'],
    'footer_color'=>['Alt bilgi zemini','footer-bg','#102b46'],
    'footer_text_color'=>['Alt bilgi yazısı','footer-text','#eef2f5'],
    'hero_overlay_color'=>['Ana görsel üzerindeki gölge','hero-overlay','#102b46'],
    'hero_text_color'=>['Ana görsel üzerindeki yazı','hero-text','#ffffff'],
    'dark_section_color'=>['Koyu bölüm zemini','dark-bg','#102b46'],
    'dark_section_text_color'=>['Koyu bölüm yazısı','dark-text','#ffffff'],
];
const HOME_SECTIONS = ['home_profile'=>'Avukat profili','home_consultation'=>'Görüşme süreci','principles'=>'İlkeler','intro'=>'Tanıtım','approach'=>'Çalışma anlayışı','office'=>'Büro bilgileri','practices'=>'Çalışma alanları','articles'=>'Makaleler','faq'=>'Sık sorulan sorular','contact'=>'İletişim bandı','home_map'=>'Büro konumu'];
const THEME_OPTIONS = [
    'heading_font'=>['arial'=>'Arial','sans'=>'Manrope','serif'=>'Cormorant Garamond'],
    'header_layout'=>['centered'=>'Ortalanmış logo ve iki katlı menü','inline'=>'Tek satır logo ve menü'],
    'home_articles_layout'=>['editorial'=>'Öne çıkan yazı ve son yazılar','grid'=>'Eşit boyutlu kartlar'],
];
const THEME_NUMBERS = ['hero_overlay_opacity'=>[0,90,0],'hero_height'=>[400,950,600],'card_radius'=>[0,32,8],'home_practice_count'=>[1,24,6],'home_article_count'=>[1,12,6],'home_press_count'=>[1,12,3]];
function theme_css(): string {
    $css='';foreach(THEME_COLORS as $key=>[$label,$variable,$default]){
        $value=setting($key,$default);if(!preg_match('/^#[a-f0-9]{6}$/i',$value))$value=$default;
        $css.='--'.$variable.':'.$value.';';
    }
    foreach(['hero_overlay_opacity'=>'hero-opacity','hero_height'=>'hero-height','card_radius'=>'radius'] as $key=>$variable){
        [$min,$max,$default]=THEME_NUMBERS[$key];$v=max($min,min($max,(int)setting($key,(string)$default)));
        $css.='--'.$variable.':'.($key==='hero_overlay_opacity'?$v/100:$v.'px').';';
    }
    $font=match(setting('heading_font','serif')){
        'sans'=>'Manrope,Arial,sans-serif',
        'serif'=>'"Cormorant Garamond",Georgia,serif',
        default=>'Arial,Helvetica,sans-serif',
    };
    $body=setting('heading_font','serif')==='arial'?'Arial,Helvetica,sans-serif':'Manrope,Arial,sans-serif';
    $css.='--sans:'.$body.';--serif:"Cormorant Garamond",Georgia,serif;--heading:'.$font.';';
    return ':root{'.$css.'--navy-deep:color-mix(in srgb,var(--navy) 85%,#000);--paper:var(--background);--ink-soft:var(--muted);--line-soft:var(--line)}';
}
function home_sections(): array {
    $order=array_filter(array_map('trim',explode(',',setting('home_section_order',implode(',',array_keys(HOME_SECTIONS))))));
    return array_values(array_unique(array_merge(array_intersect($order,array_keys(HOME_SECTIONS)),array_keys(HOME_SECTIONS))));
}
function migrate_classic_design(PDO $db): void {
    $q=$db->prepare('SELECT value FROM settings WHERE key=?');$q->execute(['private_classic_design_version']);
    $version=(int)$q->fetchColumn();if($version>=3)return;
    $db->beginTransaction();
    try {
        $order='home_profile,intro,office,practices,home_consultation,principles,articles,faq,approach,home_map,contact';
        $replace=$db->prepare('UPDATE settings SET value=? WHERE key=? AND value=?');
        foreach([
            'principles,intro,approach,office,practices,articles,faq,contact',
            implode(',',array_keys(HOME_SECTIONS)),
            'intro,home_profile,practices,home_consultation,principles,articles,faq,approach,home_map,contact,office',
            'intro,home_profile,office,practices,home_consultation,principles,articles,faq,approach,home_map,contact',
        ] as $previous)$replace->execute([$order,'home_section_order',$previous]);
        if($version<1){
            $replace->execute(['serif','heading_font','arial']);
            $replace->execute(['serif','heading_font','sans']);
            $replace->execute(['0','show_topbar','1']);
        }
        if($version<3){
            foreach([
                'hero_eyebrow'=>[['YURTDAŞ HUKUK BÜROSU','YURTDAŞ HUKUK & DANIŞMANLIK'],'YURTDAŞ HUKUK'],
                'hero_title'=>[["Sorununuzu dinleriz,\nyol haritasını birlikte çıkarırız.","Hukukun rehberliğinde,\ngüvenle ileriye."],"Avukatlık & Hukuk\nHizmetleri"],
                'hero_text'=>[['Bir hukuki meseleyle karşılaştığınızda genelde ilk soru "şimdi ne olacak?" oluyor. Dosyanızı ilk günden itibaren bizzat ben takip eder, süreci size adım adım anlatırım.','Her hukuki mesele, kendine özgü bir yaklaşımı hak eder. Haklarınızı anlamak, sürecinizi planlamak ve geleceğinizi güvenle şekillendirmek için yanınızdayız.'],'Yurtdaş Hukuk, Hatay merkezli olarak bireylere ve kurumlara avukatlık ve hukuki danışmanlık hizmeti sunar.'],
                'hero_button'=>[['Ofisimizi tanıyın','Büromuzu tanıyın'],'Bilgi Edinin'],
                'hero_button_url'=>[['/kurumsal'],'/iletisim'],
                'home_profile_eyebrow'=>[['KURUCU AVUKAT','AVUKATIMIZ'],'YURTDAŞ HUKUK'],
                'home_profile_title'=>[['Dosyanızı ben, bizzat ben takip ederim.','Hukuki sürecinizde doğrudan iletişim.'],'Yurtdaş Hukuk'],
                'intro_title'=>[["Dosyanız kaç kişiden geçmiyor;\ntek bir avukattan geçiyor.","Her adımda açık iletişim.\nHer dosyada aynı özen."],'Hakkımızda'],
                'intro_text'=>[["Büyük bürolarda dosyanız stajyerden kıdemli avukata, oradan ortağa kadar birkaç elden geçebilir. Burada öyle değil: görüştüğünüz kişi, dosyanızı mahkemeye taşıyan kişinin ta kendisi.\nBu da hem daha hızlı geri dönüş, hem de sürecin her aşamasında aynı kişiyle konuşma rahatlığı demek.","Hukuki süreçlerin yalnızca evrak ve usullerden ibaret olmadığını biliyoruz. Her dosyanın arkasında bir insan, bir emek ve bir gelecek var.\nBireysel ve kurumsal ihtiyaçları dikkatle dinliyor; hukuki seçenekleri anlaşılır bir dille değerlendirerek süreci birlikte planlıyoruz."],'Yurtdaş Hukuk, bireylerin ve kurumların hukuki ihtiyaçlarını dikkatle dinleyerek her meseleyi kendi koşulları içinde değerlendirir. Av. Halil İbrahim Yurtdaş ile doğrudan iletişim kurabilir, sürecin aşamalarına ilişkin açık bilgi alabilirsiniz.'],
                'intro_image'=>[['/assets/images/architecture.jpg'],'/assets/images/about-overhead.webp'],
                'intro_image_alt'=>[['Hukuk bürosu tanıtımı'],'Büro bekleme alanında dosya inceleyen kişi'],
                'office_eyebrow'=>[['RANDEVU','BÜROMUZ'],'HATAY · ANTAKYA'],
                'office_title'=>[["Durumunuzu anlatın,\nsize ne kadar sürede dönebileceğimizi söyleyeyim.","Hukuki sürecinizi\nbirlikte değerlendirelim."],"Antakya'daki hukuk büromuz"],
                'office_text'=>[['Telefon veya iletişim formundan yazın; genellikle aynı gün içinde geri dönüş yaparım. İlk görüşmede dosyanızı dinler, gerçekçi bir değerlendirme sunarım.','Bireysel ve kurumsal hukuki ihtiyaçlarınız için görüşme talebinizi iletebilir, çalışma alanlarımız ve görüşme sürecimiz hakkında bilgi alabilirsiniz.'],'Yurtdaş Hukuk, Hatay Antakya’daki bürosunda bireysel ve kurumsal hukuki ihtiyaçlar için avukatlık ve danışmanlık hizmeti sunar.'],
            ] as $key=>[$oldValues,$newValue])foreach($oldValues as $oldValue)$replace->execute([$newValue,$key,$oldValue]);
        }
        $db->prepare('INSERT INTO settings(key,value) VALUES(?,?) ON CONFLICT(key) DO UPDATE SET value=excluded.value')->execute(['private_classic_design_version','3']);
        $db->commit();
    }catch(Throwable $e){$db->rollBack();throw $e;}
}
function migrate_cinar_theme(PDO $db): void {
    $q=$db->prepare('SELECT value FROM settings WHERE key=?');$q->execute(['private_cinar_theme_version']);$version=(int)$q->fetchColumn();if($version>=2)return;
    $db->beginTransaction();
    try {
        $defaults=['heading_font'=>'serif','header_layout'=>'inline','home_articles_layout'=>'editorial','home_section_order'=>implode(',',array_keys(HOME_SECTIONS)),'show_principles'=>'1','show_press'=>'1','show_topbar'=>'0','show_header_contact'=>'1','show_practice_dropdown'=>'1','show_floating_contact'=>'1','hero_image_alt'=>'Adalet ve hukuk temalı ana görsel','intro_image_alt'=>'Hukuk bürosu tanıtımı','approach_image_alt'=>'Hukuk kütüphanesi','contact_band_image'=>'/assets/images/architecture.jpg','header_contact_url'=>'/iletisim','intro_link_url'=>'/kurumsal','contact_button_url'=>'/iletisim','seo_service_area'=>'Türkiye','seo_postal_code'=>'','geo_summary'=>'','geo_note'=>'Bu sitedeki yayınlar genel bilgilendirme amaçlıdır. Somut olay için hukuki değerlendirme gerekir.'];
        foreach(THEME_COLORS as $key=>$row)$defaults[$key]=$row[2];
        foreach(THEME_NUMBERS as $key=>$row)$defaults[$key]=(string)$row[2];
        $insert=$db->prepare('INSERT INTO settings(key,value) VALUES(?,?) ON CONFLICT(key) DO NOTHING');foreach($defaults as $k=>$v)$insert->execute([$k,$v]);
        // Only replace the previous bundled palette. Preserve custom brand colors.
        $update=$db->prepare('UPDATE settings SET value=? WHERE key=? AND value=?');
        if($version<1)foreach(['primary_color'=>['#142b36','#14110f'],'accent_color'=>['#b59463','#b48348'],'action_color'=>['#234e78','#856821']] as $k=>[$old,$new])$update->execute([$new,$k,$old]);
        $menu=$db->prepare("INSERT INTO entries(type,title,slug,link,sort_order,status,published_at,updated_at) SELECT 'menu',?,?,?,?, 'published',?,? WHERE NOT EXISTS(SELECT 1 FROM entries WHERE type='menu' AND link=?) ON CONFLICT(type,slug) DO NOTHING");
        foreach([['Sıkça Sorulan Sorular','sikca-sorulan-sorular','/sikca-sorulan-sorular',8]] as [$title,$slug,$url,$order])$menu->execute([$title,$slug,$url,$order,date('Y-m-d H:i:s'),date('Y-m-d H:i:s'),$url]);
        $db->prepare('INSERT INTO settings(key,value) VALUES(?,?) ON CONFLICT(key) DO UPDATE SET value=excluded.value')->execute(['private_cinar_theme_version','2']);$db->commit();
    }catch(Throwable $e){$db->rollBack();throw $e;}
}
function migrate_editorial_theme(PDO $db): void {
    $q=$db->prepare('SELECT value FROM settings WHERE key=?');$q->execute(['private_editorial_theme_version']);
    if((int)$q->fetchColumn()>=1)return;
    $db->beginTransaction();
    try {
        // Change bundled defaults only. An administrator's different palette stays intact.
        $replace=$db->prepare('UPDATE settings SET value=? WHERE key=? AND value=?');
        foreach([
            'primary_color'=>['#14110f','#19342f'],'accent_color'=>['#b48348','#c5a87a'],
            'action_color'=>['#856821','#735639'],'background_color'=>['#f7f5f2','#f5f3ee'],
            'text_color'=>['#14110f','#22332e'],'muted_color'=>['#6b665f','#596b64'],
            'border_color'=>['#e8e4df','#d9ded6'],'header_text_color'=>['#14110f','#19342f'],
            'footer_color'=>['#14110f','#19342f'],'footer_text_color'=>['#eee9e2','#eeeae1'],
            'hero_overlay_color'=>['#14110f','#19342f'],'dark_section_color'=>['#14110f','#19342f'],
            'heading_font'=>['sans','serif'],'header_layout'=>['centered','inline'],
        ] as $key=>[$old,$new])$replace->execute([$new,$key,$old]);
        $db->prepare('INSERT INTO settings(key,value) VALUES(?,?) ON CONFLICT(key) DO UPDATE SET value=excluded.value')->execute(['private_editorial_theme_version','1']);
        $db->commit();
    }catch(Throwable $e){$db->rollBack();throw $e;}
}
function migrate_juris_home(PDO $db): void {
    $q=$db->prepare('SELECT value FROM settings WHERE key=?');$q->execute(['private_juris_home_version']);
    if((int)$q->fetchColumn()>=1)return;
    $db->beginTransaction();
    try {
        $db->prepare('UPDATE settings SET value=? WHERE key=? AND value=?')->execute([
            'office,intro,principles,practices,articles,press,faq,approach,contact',
            'home_section_order',implode(',',array_keys(HOME_SECTIONS)),
        ]);
        $db->prepare('UPDATE settings SET value=? WHERE key=? AND value=?')->execute(['1','show_topbar','0']);
        $db->prepare('INSERT INTO settings(key,value) VALUES(?,?) ON CONFLICT(key) DO UPDATE SET value=excluded.value')->execute(['private_juris_home_version','1']);
        $db->commit();
    }catch(Throwable $e){$db->rollBack();throw $e;}
}
function migrate_navy_palette(PDO $db): void {
    $q=$db->prepare('SELECT value FROM settings WHERE key=?');$q->execute(['private_navy_palette_version']);
    if((int)$q->fetchColumn()>=1)return;
    $db->beginTransaction();
    try {
        // Replace the bundled green palette. Leave any administrator customizations intact.
        $replace=$db->prepare('UPDATE settings SET value=? WHERE key=? AND value=?');
        foreach([
            'primary_color'=>['#19342f','#102b46'],'accent_color'=>['#c5a87a','#c9ad7a'],
            'action_color'=>['#735639','#20527a'],'background_color'=>['#f5f3ee','#f4f6f8'],
            'text_color'=>['#22332e','#192b3b'],'muted_color'=>['#596b64','#596b7a'],
            'border_color'=>['#d9ded6','#dbe3ea'],'header_text_color'=>['#19342f','#102b46'],
            'footer_color'=>['#19342f','#102b46'],'footer_text_color'=>['#eeeae1','#eef2f5'],
            'hero_overlay_color'=>['#19342f','#102b46'],'dark_section_color'=>['#19342f','#102b46'],
            'hero_overlay_opacity'=>['55','68'],
        ] as $key=>[$old,$new])$replace->execute([$new,$key,$old]);
        $db->prepare('INSERT INTO settings(key,value) VALUES(?,?) ON CONFLICT(key) DO UPDATE SET value=excluded.value')->execute(['private_navy_palette_version','1']);
        $db->commit();
    }catch(Throwable $e){$db->rollBack();throw $e;}
}
function migrate_arial_font(PDO $db): void {
    $q=$db->prepare('SELECT value FROM settings WHERE key=?');$q->execute(['private_arial_font_version']);
    if((int)$q->fetchColumn()>=1)return;
    $db->beginTransaction();
    try {
        $db->prepare("UPDATE settings SET value='arial' WHERE key='heading_font' AND value IN ('sans','serif')")->execute();
        $db->prepare('INSERT INTO settings(key,value) VALUES(?,?) ON CONFLICT(key) DO UPDATE SET value=excluded.value')->execute(['private_arial_font_version','1']);
        $db->commit();
    }catch(Throwable $e){$db->rollBack();throw $e;}
}
function migrate_remove_press(PDO $db): void {
    $q=$db->prepare('SELECT value FROM settings WHERE key=?');$q->execute(['private_remove_press_version']);
    if((int)$q->fetchColumn()>=1)return;
    $db->beginTransaction();
    try {
        // "Basında Biz" removed from the site: archive its entries and unlink every reference.
        $db->exec("UPDATE entries SET status='archived' WHERE type='press'");
        $db->exec("DELETE FROM entries WHERE type='menu' AND (link='/basinda-biz' OR slug='basinda-biz')");
        $db->exec("DELETE FROM settings WHERE key IN ('home_press_count','show_press','meta_basinda-biz_title','meta_basinda-biz_description','meta_basinda-biz_noindex')");
        $db->exec("UPDATE settings SET value=REPLACE(value,char(10)||'Basında Biz|/basinda-biz','') WHERE key='footer_extra_links'");
        $db->exec("UPDATE settings SET value=REPLACE(value,'Basında Biz|/basinda-biz'||char(10),'') WHERE key='footer_extra_links'");
        $db->prepare("UPDATE settings SET value=? WHERE key='home_section_order' AND value LIKE '%press%'")->execute([implode(',',array_keys(HOME_SECTIONS))]);
        $db->prepare('INSERT INTO settings(key,value) VALUES(?,?) ON CONFLICT(key) DO UPDATE SET value=excluded.value')->execute(['private_remove_press_version','1']);
        $db->commit();
    }catch(Throwable $e){$db->rollBack();throw $e;}
}
function migrate_natural_hero(PDO $db): void {
    $q=$db->prepare('SELECT value FROM settings WHERE key=?');$q->execute(['private_natural_hero_version']);
    if((int)$q->fetchColumn()>=1)return;
    $db->beginTransaction();
    try {
        // Remove the bundled blue tint once; later admin changes remain editable.
        $db->prepare("UPDATE settings SET value='0' WHERE key='hero_overlay_opacity' AND value IN ('55','68')")->execute();
        $db->prepare('INSERT INTO settings(key,value) VALUES(?,?) ON CONFLICT(key) DO UPDATE SET value=excluded.value')->execute(['private_natural_hero_version','1']);
        $db->commit();
    }catch(Throwable $e){$db->rollBack();throw $e;}
}
