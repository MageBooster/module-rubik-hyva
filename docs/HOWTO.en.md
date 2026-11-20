# MageBooster Rubikpara payment: how-to

**Package:** `magebooster/module-rubik`
**Licence:** paid (serial number required)
**Payment method code:** `magebooster_rubik`
**Support:** info@magebooster.com

## What it does

This module adds Rubikpara as a card payment method. Each payment uses a single-use, fixed-amount 3D Secure payment link.

### Payment flow

1. The customer places the order. The order is saved as **Pending Payment**, and the order email waits until the payment is confirmed.
2. The module creates a payment link (`/v1/Payments/createLink`). The customer pays on Rubikpara's page.
3. The customer returns to `/mbrubik/payment/callback`. The module trusts nothing in that return request. Instead, it asks Rubikpara for the link status (`/v1/Payments/link-status`).
4. If the payment succeeded, an invoice is created and the order moves to **Processing**.
5. If it failed or was cancelled, the order is cancelled and the cart is restored.

### Background checks and cancellations

- The cron job `magebooster_rubik_reconcile` runs every 10 minutes. It checks orders whose customer closed the browser before coming back.
- Unpaid orders are cancelled by Magento's own **Pending Payment Order Lifetime** setting. You can find it under **Stores > Configuration > Sales > Sales > Orders Cron Settings**.

### Refunds, security and currency

- **Refunds:** an online credit memo calls `/v1/Payments/return`. Full and partial refunds are supported.
- **Return URLs:** they carry the order number signed with your store's crypt key.
- **Card details:** they are entered on Rubikpara's page only. Your store never sees them.
- **Currency:** orders in a currency outside "Currencies charged directly" are converted to TRY with the rate saved in **Stores > Currency Rates**. The TRY amount and rate are shown at checkout and on the order.

## Requirements

- PHP 8.0 or later
- Magento 2.4.4 to 2.4.9
- A Rubikpara merchant account: merchant number, public key and Base64 secret key
- Luma edition: `magebooster/module-rubik`
- Hyvä edition: `magebooster/module-rubik-hyva` (a separate download)

**Hyvä status:** this package contains a Knockout payment renderer for Luma-based checkouts. The server side works the same on any theme. The module's README lists the Hyvä Checkout component as planned. Confirm the Hyvä edition is available before using it on a Hyvä store.

## Install with Composer

```bash
composer require magebooster/module-rubik
```

## Install from a zip file

Unzip the archive into `app/code/MageBooster/Rubik`, then run the commands below.

## Enable the module

```bash
bin/magento module:enable MageBooster_Rubik
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:flush
```

Magento cron must be running, because the reconcile job depends on it.

## Admin configuration

Go to **Stores > Configuration > Sales > Payment Methods > Rubikpara · MageBooster**.

| Field | Default | What it means |
|---|---|---|
| Enabled | No | Shows the method at checkout. |
| Title | Credit / debit card (Rubikpara) | Name at checkout and on the order. |
| Text shown at checkout | (empty) | Short text under the method name. |
| Test mode | Yes | Uses the test API address and test credentials. No real money is taken. |
| Merchant number | (empty) | Your Rubikpara merchant number. |
| Public key | (empty) | From the Rubikpara merchant panel. |
| Secret key | (empty) | The Base64 secret key from the Rubikpara merchant panel. Stored encrypted. |
| Test API address | `https://testpfapi.rubikpara.com` | Rubikpara test API. |
| Live API address | (empty) | Ask Rubikpara for the live API address. |
| Currencies charged directly | TRY | Other currencies are converted to TRY with the saved store rate. |
| Payment from applicable countries | All allowed countries | Limits the method by country. |
| Payment from specific countries | (empty) | Used when specific countries are chosen. |
| Sort order | 61 | Position among the payment methods. |
| Debug log | No | Writes requests and answers to `var/log/magebooster_mbrubik.log`. Card data and secrets are never written. |

## Testing

1. Turn on **Test mode** and **Debug log**, then enter your test credentials.
2. Place an order and pay on the Rubikpara page. Check that the order is **Processing** and has an invoice.
3. Start another order, then close the browser on the payment page. Within 10 minutes the cron job should update the order. If the order stays unpaid, it will be cancelled by Pending Payment Order Lifetime.
4. Cancel on the Rubikpara page. Check that the order is cancelled and the cart is restored.
5. Make a partial refund, then a full refund, with online credit memos. Check the log for each `/v1/Payments/return` call.
6. Turn off Test mode only after these steps pass.

## Known limits

- **No real calls yet:** the module has not yet run against Rubikpara, because no test credentials were available.
- **Unconfirmed signature:** the request signature follows Rubikpara's docs but has not been confirmed with a real call. It is an HMAC-SHA256 of PublicKey+Nonce, then of SecretKey+ConversationId+Nonce+securityData, keyed with the Base64-decoded secret.
- **Undocumented answers:** Rubikpara does not document the full answers for link status and refund. The module reads `linkPaymentStatus`, `linkStatus` and `isSuccess`.
- **Live address:** the live API address is not public.

## Troubleshooting

- **The method does not show:** check **Enabled**, the live API address (in live mode), the countries and the currencies.
- **Signature or authentication error in the log:** check the merchant number, public key and secret key. The secret must be the Base64 value from the panel.
- **The order stays in Pending Payment:** check that cron is running and that `/mbrubik/payment/callback` can be reached from outside.

## Uninstall

1. Turn off **Enabled**.
2. Wait until no orders are in Pending Payment.
3. Run:

   ```bash
   bin/magento module:disable MageBooster_Rubik
   composer remove magebooster/module-rubik
   bin/magento setup:upgrade
   bin/magento cache:flush
   ```

Rubikpara is a trademark of its owner. This module is not made or endorsed by Rubikpara.

## Licence & copying

Commercial licence, see `LICENSE.txt`. Copyright (c) 2026 WebWorks 101 OÜ (MageBooster). All rights reserved.

- One serial number = one production installation (one store domain). Staging and development copies of that same store are allowed.
- Each serial number and each download is tied to the customer who bought it. Luma and Hyvä editions are separate products with separate serial numbers.
- You may modify the code for your own licensed store. Keep the copyright and licence notices.
- Not allowed: copying to other stores, redistributing, reselling, sublicensing, publishing in public repositories, marketplaces or forums, removing licence checks or these notices, sharing serial numbers.
- In case of unauthorised copying or use, the serial number is revoked without refund, updates and support stop, and WebWorks 101 OÜ may claim damages and take legal action. Estonian law applies; the court is Harju County Court.

## Support

info@magebooster.com
