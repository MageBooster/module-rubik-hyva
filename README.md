# MageBooster_RubikHyva – Rubikpara in MageBooster Checkout (Hyvä and Luma)

Commercial licence (see `LICENSE.txt`). Adds the Rubikpara method panel to the **MageBooster Checkout** (Alpine.js one-page checkout), which runs on
Hyvä and Luma themes. Needs `magebooster/module-rubik` (payment, callbacks, refunds stay there).

| Storefront | What renders the method |
|---|---|
| Luma theme, Magento checkout | Knockout renderer of MageBooster_Rubik (this package not needed) |
| Hyvä theme with the Luma checkout fallback | the same Knockout renderer |
| Hyvä or Luma theme + MageBooster Checkout | this package (panel) |
| Hyvä Checkout (Magewire) | not supported yet |

## Install

```
composer require magebooster/module-rubik-hyva
bin/magento module:enable MageBooster_RubikHyva
bin/magento setup:upgrade
```

The blocks go into MageBooster Checkout's `mb.checkout.payment.panels`
containers; without MageBooster Checkout nothing is rendered. They are shown only while the method is active.

## Why this package is small

Rubikpara only offers a hosted payment link: the card is always entered on the Rubikpara page, so there is no card form
to render and no script. The redirect after "Place order" needs no code here either: `MageBooster_Rubik` sets
`payment/magebooster_rubik/order_place_redirect_url` to `mbrubik/redirect/start`, and MageBooster Checkout's
`mbcheckout/place/next` sends every method that has this value there. The package only adds the method panel
(description, test mode note, TRY amount and rate, "you will be taken to Rubikpara" note).

## Licence & copying

Commercial licence, see `LICENSE.txt`. Copyright (c) 2026 WebWorks 101 OÜ (MageBooster). All rights reserved.

- One serial number = one production installation (one store domain). Staging and development copies of that same store are allowed.
- Each serial number and each download is tied to the customer who bought it. Luma and Hyvä editions are separate products with separate serial numbers.
- You may modify the code for your own licensed store. Keep the copyright and licence notices.
- Not allowed: copying to other stores, redistributing, reselling, sublicensing, publishing in public repositories, marketplaces or forums, removing licence checks or these notices, sharing serial numbers.
- In case of unauthorised copying or use, the serial number is revoked without refund, updates and support stop, and WebWorks 101 OÜ may claim damages and take legal action. Estonian law applies; the court is Harju County Court.

---

# MageBooster_RubikHyva – MageBooster Checkout'ta Rubikpara (Hyvä ve Luma)

Ticari lisans (bkz. `LICENSE.txt`). **MageBooster Checkout**'a (Hyvä ve Luma temalarında çalışan Alpine.js tek sayfa ödeme) Rubikpara yöntem
panelini ekler: açıklama, test modu notu, TL tutarı ve kuru, Rubikpara sayfasına yönlendirme notu.
`magebooster/module-rubik` gerekir (ödeme, dönüş, iade orada). Luma checkout'ta ve Luma checkout'a düşen Hyvä
temasında bu pakete gerek yoktur; Hyvä Checkout (Magewire) henüz desteklenmiyor.

## Lisans ve kopyalama

Ticari lisans, bkz. `LICENSE.txt`. Telif hakkı (c) 2026 WebWorks 101 OÜ (MageBooster). Tüm hakları saklıdır.

- Bir seri numarası = bir canlı kurulum (bir mağaza alan adı). Aynı mağazanın test (staging) ve geliştirme kopyaları serbesttir.
- Her seri numarası ve her indirme, satın alan müşteriye bağlıdır. Luma ve Hyvä sürümleri ayrı ürünlerdir, seri numaraları da ayrıdır.
- Kodu kendi lisanslı mağazanız için değiştirebilirsiniz. Telif ve lisans notlarını silmeyin.
- Yasak olanlar: başka mağazalara kopyalamak, dağıtmak, yeniden satmak, alt lisans vermek, herkese açık depolarda, pazar yerlerinde veya forumlarda yayınlamak, lisans kontrolünü ya da bu notları kaldırmak, seri numarasını paylaşmak.
- İzinsiz kopyalama veya kullanımda seri numarası iade yapılmadan iptal edilir, güncelleme ve destek durur; WebWorks 101 OÜ tazminat isteyebilir ve yasal yola gidebilir. Estonya hukuku geçerlidir; yetkili mahkeme Harju İlçe Mahkemesi'dir (Harju Maakohus).
