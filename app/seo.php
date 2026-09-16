<?php
declare(strict_types=1);
// SEO / GEO helpers: static route meta, structured data (JSON-LD), feeds.
const SEO_ROUTES=[
    'home'=>['path'=>'/','label'=>'Ana Sayfa','hint'=>'Başlık ve açıklama “Arama Motorları” ayarlarındaki ana sayfa alanlarından gelir.'],
    'makaleler'=>['path'=>'/makaleler','label'=>'Makaleler','hint'=>'Hukuki yayın arşivi ve kategori dizini.'],
    'calisma-alanlari'=>['path'=>'/calisma-alanlari','label'=>'Çalışma Alanları','hint'=>'Tüm çalışma alanlarının listelendiği sayfa.'],
    'kanunlar'=>['path'=>'/kanunlar','label'=>'Kanunlar','hint'=>'Resmî kaynaklara yönlendiren kanun dizini.'],
    'iletisim'=>['path'=>'/iletisim','label'=>'İletişim','hint'=>'İletişim bilgileri ve görüşme talebi formu.'],
];
const SEO_ROUTE_DEFAULT_TITLES=['makaleler'=>'Makaleler','calisma-alanlari'=>'Çalışma Alanları','kanunlar'=>'Kanunlar','iletisim'=>'İletişim'];
function seo_route_meta(string $route): array {
    if($route==='home')return ['title'=>setting('seo_title'),'description'=>setting('seo_description'),'noindex'=>false];
    $default=SEO_ROUTE_DEFAULT_TITLES[$route]??'';
    if($route==='kanunlar')$default=setting('laws_title')?:$default;
    $title=setting('meta_'.$route.'_title')?:$default;
    $text=match($route){'makaleler'=>setting('articles_text'),'calisma-alanlari'=>setting('practice_text'),'kanunlar'=>setting('laws_text'),'iletisim'=>setting('contact_text'),default=>''};
    return ['title'=>$title,'description'=>setting('meta_'.$route.'_description')?:($text?:setting('seo_description')),'noindex'=>setting('meta_'.$route.'_noindex')==='1'];
}
function site_base(): string {return rtrim(setting('site_url'),'/');}
function absolute_url(string $path): string {return site_base().$path;}
function jsonld_organization(): array {
    $base=site_base();$brand=setting('brand');
    $org=['@type'=>['LegalService','Organization'],'@id'=>$base.'/#organization','name'=>$brand,'url'=>$base.'/','description'=>setting('seo_description'),'areaServed'=>'TR','priceRange'=>'$$'];
    if(setting('seo_legal_name'))$org['legalName']=setting('seo_legal_name');
    if(setting('seo_founding_year'))$org['foundingDate']=setting('seo_founding_year');
    $logo=setting('logo')?safe_image(setting('logo')):'';if($logo)$org['logo']=['@type'=>'ImageObject','url'=>$base.$logo];
    $org['image']=$base.safe_image(setting('seo_og_image')?:setting('hero_image'));
    if(setting('phone'))$org['telephone']=setting('phone');
    if(setting('email'))$org['email']=setting('email');
    if(setting('address'))$org['address']=['@type'=>'PostalAddress','streetAddress'=>preg_replace('/\s+/',' ',setting('address')),'addressLocality'=>setting('city'),'addressRegion'=>setting('seo_geo_region')?:setting('city'),'addressCountry'=>'TR'];
    if(setting('seo_geo_lat')&&setting('seo_geo_lng'))$org['geo']=['@type'=>'GeoCoordinates','latitude'=>(float)setting('seo_geo_lat'),'longitude'=>(float)setting('seo_geo_lng')];
    if(setting('office_hours'))$org['openingHours']=setting('office_hours');
    $same=array_values(array_filter([setting('linkedin'),setting('instagram')]));if($same)$org['sameAs']=$same;
    $org['contactPoint']=['@type'=>'ContactPoint','contactType'=>'customer service','telephone'=>setting('phone'),'email'=>setting('email'),'availableLanguage'=>['Turkish']];
    return $org;
}
function jsonld_website(): array {
    $base=site_base();
    return ['@type'=>'WebSite','@id'=>$base.'/#website','url'=>$base.'/','name'=>setting('brand'),'inLanguage'=>'tr-TR','publisher'=>['@id'=>$base.'/#organization'],'potentialAction'=>['@type'=>'SearchAction','target'=>['@type'=>'EntryPoint','urlTemplate'=>$base.'/makaleler?q={search_term_string}'],'query-input'=>'required name=search_term_string']];
}
function jsonld_breadcrumbs(array $items): array {
    $list=[];$pos=1;$list[]=['@type'=>'ListItem','position'=>$pos++,'name'=>'Ana Sayfa','item'=>site_base().'/'];
    foreach($items as $name=>$url)$list[]=['@type'=>'ListItem','position'=>$pos++,'name'=>$name,'item'=>site_base().$url];
    return ['@type'=>'BreadcrumbList','itemListElement'=>$list];
}
function jsonld_article(array $a,string $canonical): array {
    $base=site_base();
    return ['@type'=>'Article','@id'=>$base.$canonical.'#article','headline'=>mb_substr($a['title'],0,110),'description'=>$a['meta_description']?:$a['excerpt'],'articleSection'=>$a['category']?:'Hukuk','inLanguage'=>'tr-TR','image'=>[$base.safe_image($a['image'])],'datePublished'=>date(DATE_ATOM,strtotime($a['published_at'])),'dateModified'=>date(DATE_ATOM,strtotime(substr($a['updated_at'],0,19))?:strtotime($a['published_at'])),'author'=>['@type'=>$a['author']===setting('brand')?'Organization':'Person','name'=>$a['author']?:setting('brand'),'url'=>$base.'/ekibimiz'],'publisher'=>['@id'=>$base.'/#organization'],'mainEntityOfPage'=>['@type'=>'WebPage','@id'=>$base.$canonical],'wordCount'=>count(preg_split('/\s+/u',trim($a['body']))),'speakable'=>['@type'=>'SpeakableSpecification','cssSelector'=>['.page-heading h1','.page-heading p']],'isAccessibleForFree'=>true];
}
function jsonld_faq(array $faqs): array {
    return ['@type'=>'FAQPage','mainEntity'=>array_map(fn($f)=>['@type'=>'Question','name'=>$f['title'],'acceptedAnswer'=>['@type'=>'Answer','text'=>strip_tags(text_markup($f['body']))]],array_values($faqs))];
}
function jsonld_person(array $p,string $canonical): array {
    $base=site_base();
    return ['@type'=>'Person','@id'=>$base.$canonical.'#person','name'=>$p['title'],'jobTitle'=>$p['category']?:'Avukat','description'=>$p['meta_description']?:$p['excerpt'],'image'=>$p['image']?$base.safe_image($p['image']):null,'worksFor'=>['@id'=>$base.'/#organization'],'url'=>$base.$canonical];
}
function jsonld_service(array $p,string $canonical): array {
    $base=site_base();
    return ['@type'=>'Service','@id'=>$base.$canonical.'#service','name'=>$p['title'],'serviceType'=>$p['title'],'description'=>$p['meta_description']?:$p['excerpt'],'provider'=>['@id'=>$base.'/#organization'],'areaServed'=>'TR','url'=>$base.$canonical];
}
function jsonld_webpage(string $title,string $description,string $canonical,string $type='WebPage'): array {
    $base=site_base();
    return ['@type'=>$type,'@id'=>$base.$canonical,'url'=>$base.$canonical,'name'=>$title,'description'=>$description,'inLanguage'=>'tr-TR','isPartOf'=>['@id'=>$base.'/#website'],'about'=>['@id'=>$base.'/#organization']];
}
function jsonld_graph(array $nodes): string {
    $clean=array_map(fn($n)=>array_filter($n,fn($v)=>$v!==null&&$v!==''&&$v!==[]),$nodes);
    return json_encode(['@context'=>'https://schema.org','@graph'=>array_values($clean)],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_HEX_TAG|JSON_HEX_AMP);
}
function seo_plain(string $text,int $limit=160): string {
    $t=trim(preg_replace('/\s+/u',' ',strip_tags(text_markup($text))));
    return mb_strlen($t)>$limit?rtrim(mb_substr($t,0,$limit-1)).'…':$t;
}
