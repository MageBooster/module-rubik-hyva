# MageBooster Rubikpara ödeme modülü: kullanım kılavuzu

- **Paket:** `magebooster/module-rubik`
- **Lisans:** ücretli, seri numarası gerekir
- **Ödeme yöntemi kodu:** `magebooster_rubik`
- **Destek:** info@magebooster.com

## Ne işe yarar

Bu modül Rubikpara'yı kartla ödeme yöntemi olarak ekler. Her ödeme için tek kullanımlık, tutarı sabit, 3D Secure'lu bir ödeme linki oluşturulur.

### Ödeme akışı

1. Müşteri siparişi verir. Sipariş **Pending Payment** durumunda kaydedilir. Sipariş e-postası, ödeme onaylanana kadar gönderilmez.
2. Modül bir ödeme linki oluşturur (`/v1/Payments/createLink`). Müşteri ödemeyi Rubikpara'nın sayfasında yapar.
3. Müşteri `/mbrubik/payment/callback` adresine döner. Modül bu dönüş isteğindeki bilgilere güvenmez. Linkin durumunu Rubikpara'ya kendisi sorar (`/v1/Payments/link-status`).
4. Sonuca göre:
   - **Ödeme başarılıysa:** fatura kesilir, sipariş **Processing** durumuna geçer.
   - **Ödeme başarısızsa ya da müşteri vazgeçtiyse:** sipariş iptal edilir, sepet geri yüklenir.

### Diğer davranışlar

- **Arka plan kontrolü:** `magebooster_rubik_reconcile` cron görevi her 10 dakikada bir, ödeme sayfasından dönmeden tarayıcıyı kapatan müşterilerin siparişlerini kontrol eder.
- **Ödenmeyen siparişler:** Magento'nun **Pending Payment Order Lifetime** ayarına göre iptal edilir (**Stores > Configuration > Sales > Sales > Orders Cron Settings**).
- **İade:** Online credit memo, `/v1/Payments/return` çağrısını yapar. Tam ve kısmi iade desteklenir.
- **İmzalı dönüş adresleri:** Dönüş adreslerindeki sipariş numarası mağazanızın şifreleme anahtarıyla imzalanır.
- **Kart bilgileri:** Yalnızca Rubikpara'nın sayfasına girilir. Mağazanız bu bilgileri görmez.
- **Para birimi:** "Currencies charged directly" listesinde olmayan para birimindeki siparişler, **Stores > Currency Rates** sayfasındaki kayıtlı kurla TRY'ye çevrilir. TRY tutarı ve kur, ödeme adımında ve siparişte gösterilir.

## Gereksinimler

- PHP 8.0 veya üzeri
- Magento 2.4.4 – 2.4.9
- Rubikpara üye işyeri hesabı: üye işyeri numarası, public key ve Base64 biçiminde secret key
- **Luma sürümü:** `magebooster/module-rubik`
- **Hyvä sürümü:** `magebooster/module-rubik-hyva` (ayrıca indirilir)

### Hyvä durumu

Bu paketteki Knockout ödeme bileşeni, Luma tabanlı ödeme sayfaları içindir. Sunucu tarafı her temada aynı çalışır. README dosyasında Hyvä Checkout bileşeni "planlanan" olarak geçiyor. Hyvä mağazada kullanmadan önce Hyvä sürümünün hazır olduğunu teyit edin.

## Composer ile kurulum

```bash
composer require magebooster/module-rubik
```

## Zip dosyasıyla kurulum

Arşivi `app/code/MageBooster/Rubik` klasörüne açın, sonra aşağıdaki komutları çalıştırın.

## Modülü etkinleştirme

```bash
bin/magento module:enable MageBooster_Rubik
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:flush
```

Arka plan kontrolünün çalışması için Magento cron'unun çalışıyor olması gerekir.

## Yönetim paneli ayarları

Ayarlar **Stores > Configuration > Sales > Payment Methods > Rubikpara · MageBooster** altındadır.

| Alan | Varsayılan | Açıklama |
|---|---|---|
| Enabled | Hayır | Yöntemi ödeme adımında gösterir. |
| Title | Credit / debit card (Rubikpara) | Ödeme adımında ve siparişte görünen ad. |
| Text shown at checkout | (boş) | Yöntem adının altında görünen kısa açıklama. |
| Test mode | Evet | Test adresini ve test bilgilerini kullanır. Gerçek para çekilmez. |
| Merchant number | (boş) | Rubikpara üye işyeri numaranız. |
| Public key | (boş) | Rubikpara üye işyeri panelinden alınır. |
| Secret key | (boş) | Rubikpara panelindeki Base64 gizli anahtar. Şifreli saklanır. |
| Test API address | `https://testpfapi.rubikpara.com` | Rubikpara test API adresi. |
| Live API address | (boş) | Canlı API adresini Rubikpara'dan isteyin. |
| Currencies charged directly | TRY | Diğer para birimleri kayıtlı kurla TRY'ye çevrilir. |
| Payment from applicable countries | Tüm izinli ülkeler | Yöntemi belirli ülkelerle sınırlar. |
| Payment from specific countries | (boş) | Belirli ülkeler seçildiğinde kullanılır. |
| Sort order | 61 | Ödeme yöntemleri arasındaki sırası. |
| Debug log | Hayır | İstekleri ve yanıtları `var/log/magebooster_mbrubik.log` dosyasına yazar. Kart bilgisi ve gizli bilgiler yazılmaz. |

## Test

1. **Test mode** ve **Debug log** ayarlarını açın, test bilgilerinizi girin.
2. Sipariş verip Rubikpara sayfasında ödeyin. Siparişin **Processing** durumunda olduğunu ve faturasının kesildiğini kontrol edin.
3. Yeni bir sipariş verin ve ödeme sayfasındayken tarayıcıyı kapatın.
   - En geç 10 dakika içinde cron görevi siparişi güncellemeli.
   - Ödeme yapılmadıysa sipariş, Pending Payment Order Lifetime süresi dolunca iptal edilir.
4. Rubikpara sayfasında vazgeçin. Siparişin iptal edildiğini ve sepetin geri geldiğini kontrol edin.
5. Online credit memo ile önce kısmi, sonra tam iade yapın. Her `/v1/Payments/return` çağrısının kayda yazıldığını kontrol edin.
6. Bu adımlar sorunsuz geçtikten sonra Test mode'u kapatın.

## Bilinen sınırlar

- **Henüz denenmedi:** Bu sürüm Rubikpara servisinde test edilmedi. Geliştirme sırasında test bilgisi yoktu.
- **İmza doğrulanmadı:** İstek imzası Rubikpara belgelerine göre yazıldı, ama gerçek bir çağrıyla doğrulanmadı. Hesaplama şöyle:
  - Önce PublicKey+Nonce'un HMAC-SHA256 değeri alınır.
  - Sonra SecretKey+ConversationId+Nonce+securityData'nın HMAC-SHA256 değeri alınır.
  - Anahtar olarak Base64'ten çözülmüş secret kullanılır.
- **Yanıt alanları:** Link durumu ve iade yanıtının tamamı belgelenmemiş. Modül `linkPaymentStatus`, `linkStatus` ve `isSuccess` alanlarını kullanır.
- **Canlı adres:** Canlı API adresi herkese açık değil.

## Sorun giderme

- **Yöntem görünmüyor:** Şunları kontrol edin:
  - **Enabled** ayarı açık mı?
  - Canlı modda canlı API adresi girilmiş mi?
  - Ülke ve para birimi ayarları doğru mu?
- **Kayıtta imza ya da kimlik doğrulama hatası:** Üye işyeri numarasını, public key ve secret key değerlerini kontrol edin. Secret key paneldeki Base64 değer olmalı.
- **Sipariş Pending Payment'ta kalıyor:** Cron'un çalıştığını ve `/mbrubik/payment/callback` adresine dışarıdan erişilebildiğini kontrol edin.

## Kaldırma

1. **Enabled** ayarını kapatın.
2. Ödeme bekleyen sipariş kalmadığından emin olun.
3. Şu komutları çalıştırın:

```bash
bin/magento module:disable MageBooster_Rubik
composer remove magebooster/module-rubik
bin/magento setup:upgrade
bin/magento cache:flush
```

Rubikpara, sahibinin tescilli markasıdır. Bu modül Rubikpara tarafından yapılmamış ve onaylanmamıştır.

## Lisans ve kopyalama

Ticari lisans, bkz. `LICENSE.txt`. Telif hakkı (c) 2026 WebWorks 101 OÜ (MageBooster). Tüm hakları saklıdır.

- Bir seri numarası = bir canlı kurulum (bir mağaza alan adı). Aynı mağazanın test (staging) ve geliştirme kopyaları serbesttir.
- Her seri numarası ve her indirme, satın alan müşteriye bağlıdır. Luma ve Hyvä sürümleri ayrı ürünlerdir, seri numaraları da ayrıdır.
- Kodu kendi lisanslı mağazanız için değiştirebilirsiniz. Telif ve lisans notlarını silmeyin.
- Yasak olanlar: başka mağazalara kopyalamak, dağıtmak, yeniden satmak, alt lisans vermek, herkese açık depolarda, pazar yerlerinde veya forumlarda yayınlamak, lisans kontrolünü ya da bu notları kaldırmak, seri numarasını paylaşmak.
- İzinsiz kopyalama veya kullanımda seri numarası iade yapılmadan iptal edilir, güncelleme ve destek durur; WebWorks 101 OÜ tazminat isteyebilir ve yasal yola gidebilir. Estonya hukuku geçerlidir; yetkili mahkeme Harju İlçe Mahkemesi'dir (Harju Maakohus).

## Destek

info@magebooster.com
