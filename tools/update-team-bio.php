<?php
declare(strict_types=1);
$db = new PDO('sqlite:'.__DIR__.'/../storage/site.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$excerpt = 'Yurtdaş Hukuk\'un kurucusu; aile, ceza, iş, ticaret, gayrimenkul ve miras hukuku alanlarında dosyaları bizzat takip eder.';
$body = <<<'MD'
Avukatlığa başladığımdan bu yana, bir dosyanın arkasında her zaman bir insan ve onun hayatına dokunan bir mesele olduğunu düşünürüm. Bu yüzden Yurtdaş Hukuk'ta dosyalar stajyerden kıdemli avukata devredilmez; ilk görüşmeden mahkeme sürecinin sonuna kadar aynı kişi, yani ben ilgilenirim.

Çalışma alanlarım aile hukuku, ceza hukuku, iş hukuku, ticaret ve şirketler hukuku, gayrimenkul hukuku ile miras hukukunu kapsar. Her dosyayı almadan önce mutlaka detaylı bir ön görüşme yaparım; bazı durumlarda en doğru çözüm dava açmaktan değil, doğru kurulmuş bir uzlaşmadan geçer. Bunu da baştan, açık şekilde konuşurum.

Görüşme taleplerinize genellikle aynı gün içinde dönüş yaparım. Dosyanızı anlattığınızda size gerçekçi bir değerlendirme ve nasıl bir yol izleyebileceğimize dair net bir öneri sunarım.
MD;

$stmt = $db->prepare('UPDATE entries SET excerpt = :excerpt, body = :body, updated_at = :updated_at WHERE type = :type AND title = :title');
$stmt->execute([
    ':excerpt' => $excerpt,
    ':body' => $body,
    ':updated_at' => date('Y-m-d H:i:s'),
    ':type' => 'team',
    ':title' => 'Halil İbrahim Yurtdaş',
]);
echo "Rows affected: {$stmt->rowCount()}\n";
