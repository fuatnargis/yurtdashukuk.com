<?php declare(strict_types=1);
$laws=entries('law');
?>
<?php page_heading('KANUN FİHRİSTİ','Kanunlar','Sık başvurulan temel kanunlara ve resmî madde metinlerine tek noktadan hızlıca ulaşın.');?>
<section class="section law-index-section">
    <div class="container">
        <div class="law-index">
            <div class="law-index-controls">
                <label class="law-index-search-label" for="law-search">Kanun ara</label>
                <div class="law-index-search-control">
                    <?=icon('search','law-index-search-icon')?>
                    <input id="law-search" class="law-index-search" type="search" inputmode="search" autocomplete="off" placeholder="Örnek: Medeni Kanun, Borçlar, İş Kanunu" aria-describedby="law-index-status">
                    <button class="law-index-search-clear" type="button" aria-label="Arama metnini temizle" hidden>&times;</button>
                </div>
                <p id="law-index-status" class="law-index-status" aria-live="polite" aria-atomic="true"><span><strong><?=count($laws)?></strong> kanun gösteriliyor</span></p>
            </div>
            <div class="law-index-grid" role="list">
                <?php foreach($laws as $law):?>
                <article class="law-card" role="listitem" data-search="<?=e(mb_strtolower($law['title'].' '.$law['category'].' '.$law['excerpt']))?>">
                    <a class="law-card-link" href="<?=e(safe_url($law['link']))?>" target="_blank" rel="noopener noreferrer">
                        <span class="law-card-index"><?=icon('book')?><span>Kanun No <?=e($law['category'])?></span></span>
                        <span class="law-card-body">
                            <h2 class="law-card-title"><?=e($law['title'])?></h2>
                            <span class="law-card-description"><?=e($law['excerpt'])?></span>
                        </span>
                        <span class="law-card-arrow" aria-hidden="true"><?=icon('arrow-up')?></span>
                    </a>
                </article>
                <?php endforeach;?>
            </div>
            <div class="law-index-empty" hidden>
                <p class="law-index-empty-title">Aramanızla eşleşen kanun bulunamadı</p>
                <p class="law-index-empty-text">Farklı bir anahtar kelime deneyebilir veya aramayı temizleyebilirsiniz.</p>
                <button class="law-index-empty-button" type="button">Tüm kanunları göster</button>
            </div>
        </div>
    </div>
</section>
<?php contact_band();?>
