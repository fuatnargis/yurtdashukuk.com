# Projeye özel görseller

Üç görsel yerleşik **image_gen / imagegen** aracıyla üretildi. CLI/API yedeği kullanılmadı. Görseller gerçek bir büroyu veya belirli bir adliye binasını temsil etmez. PNG asılları `assets/images/` içinde korundu; web için PHP GD ile JPEG biçimine dönüştürüldü.

| Kullanım | Web dosyası | Asıl dosya |
|---|---|---|
| Ana sayfa karşılama alanı | `assets/images/justice-hero.jpg` | `assets/images/justice-hero.png` |
| Kurumsal bölüm / yayın görseli | `assets/images/architecture.jpg` | `assets/images/architecture.png` |
| Makale kapak görseli | `assets/images/library.jpg` | `assets/images/library.png` |

Logo işareti ve site simgesi kodla oluşturulmuş özgün SVG çizimleridir (`app/bootstrap.php`, `assets/images/favicon.svg`).

## Kullanılan tam istemler

### Ana görsel

> Use case: photorealistic-natural. Asset type: full-width hero background photograph for a sophisticated Turkish law firm's website. Create an exceptionally refined editorial architectural photograph, panoramic landscape 1792x1024 or similar. Subject: an antique bronze Lady Justice statue with blindfold and carefully formed scales, positioned on the far right third, viewed from waist up; imposing fluted classical courthouse stone columns softly out of focus behind it on right. Left half of image mostly empty, deeply shadowed navy-charcoal architectural surface, subtle atmospheric texture, suitable for overlaying large white headline. Warm muted champagne light comes from upper right and catches bronze sculpture and stone column edges. Near-monochromatic midnight navy, warm stone, desaturated brass palette. Rich photographic grain, very realistic museum-quality sculpture, sophisticated cinematic lighting, restrained premium editorial mood. No text, letters, logos, watermark, people, interface, borders, gavels, or books. Avoid bright orange or oversaturated gold. Single coherent photograph, not collage.

### Mimari detay

> Use case photorealistic-natural. Original editorial architectural photo for a refined Turkish law firm's website. Vertical leaning landscape 4:3 composition of imposing classical pale limestone courthouse columns and elegant steps, close-up architectural study with rich stone textures, oblique view and strong perspective looking slightly upward, warm natural side light, deep charcoal shadows, muted ivory and warm beige palette, sophisticated fine art architectural photography. No identifiable landmarks, no flags, no signage, no text, no logos, no people, no watermark. Realistic physical architecture. Premium quiet timeless atmosphere.

### Kitaplar

> Use case photorealistic-natural. Landscape 3:2 editorial still life for an elegant law website article. Neatly arranged antique leather bound law books in warm brown and dark navy on a dark oak desk, opened book in foreground, beautiful indirect warm window light from left, dark bookcase subtly out of focus in background, refined scholarly atmosphere, restrained warm muted colors, tactile leather and paper texture, cinematic but natural photographic detail. No legible book titles, no lettering, no text, no watermark, no logos, no people, no gavels. Focus is the books, not the background.
