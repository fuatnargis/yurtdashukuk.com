<?php
declare(strict_types=1);
$search=mb_substr(query('q'),0,100);
$needle=mb_strtolower(str_replace(['I','İ'],['ı','i'],$search));
$laws=array_values(array_filter(entries('law'),fn($law)=>!$search||str_contains(mb_strtolower(str_replace(['I','İ'],['ı','i'],$law['title'].' '.$law['category'].' '.$law['excerpt'])),$needle)));
page_heading('MEVZUAT',setting('laws_title'),setting('laws_text'));
?>
<section class="section laws-section"><div class="container narrow-container"><form class="law-search" action="/kanunlar" method="get"><label class="sr-only" for="law-search">Kanun adı veya numarası</label><div class="search-input"><input type="search" id="law-search" name="q" maxlength="100" value="<?=e($search)?>" placeholder="Kanun adı veya numarası ile arayın"><button class="button" type="submit"><?=icon('search')?> Ara</button></div></form><div class="law-grid">
<?php foreach($laws as $law):?><a class="law-card" href="<?=e(safe_url($law['link']))?>" target="_blank" rel="noopener noreferrer"><span class="law-icon"><?=icon('building')?></span><div><span class="law-number"><?=e($law['category'])?> SAYILI KANUN</span><h2><?=e($law['title'])?></h2><p><?=e($law['excerpt'])?></p><span class="law-source">Resmî kaynağı aç <span class="sr-only">(yeni sekme)</span></span></div><span class="law-arrow"><?=icon('arrow-up')?></span></a><?php endforeach;?>
</div><?php if(!$laws):?><div class="empty-state"><?=icon('search')?><h2>Bu aramayla eşleşen kanun bulunamadı.</h2><a class="text-link" href="/kanunlar">Tüm kanunları görüntüleyin <?=icon('arrow')?></a></div><?php endif;?><p class="law-note"><?=icon('file')?><?=e(setting('laws_note'))?></p></div></section>
<?php contact_band();?>
