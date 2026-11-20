# MageBooster_RubikHyva – Rubikpara in MageBooster Checkout (Hyvä and Luma)

Free, MIT. Adds the Rubikpara method panel to the **MageBooster Checkout** (Alpine.js one-page checkout), which runs on
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

## License

MIT, see `LICENSE`.

---

# MageBooster_RubikHyva – MageBooster Checkout'ta Rubikpara (Hyvä ve Luma)

Ücretsiz, MIT. **MageBooster Checkout**'a (Hyvä ve Luma temalarında çalışan Alpine.js tek sayfa ödeme) Rubikpara yöntem
panelini ekler: açıklama, test modu notu, TL tutarı ve kuru, Rubikpara sayfasına yönlendirme notu.
`magebooster/module-rubik` gerekir (ödeme, dönüş, iade orada). Luma checkout'ta ve Luma checkout'a düşen Hyvä
temasında bu pakete gerek yoktur; Hyvä Checkout (Magewire) henüz desteklenmiyor.
