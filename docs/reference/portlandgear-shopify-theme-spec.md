# Portland Gear storefront: theme and design spec

Source: portlandgear.com product page (Studio Faded Hoodie), captured September 7, 2026. Values read from the live CSS and computed styles, not estimated.

## 1. Theme

Theme: Prestige by Maestrooo, schema version 10.6.0 (Shopify Theme Store, paid, currently around $400 one time). Theme instance name is "CURRENT/LIVE (AB Tested / Menu Upgrade)", which means they run duplicates for A/B tests. Prestige 10.x is the Online Store 2.0 rebuild. Buy it from the Shopify Theme Store, not a marketplace copy, so you get updates.

Most of what follows maps to Prestige theme settings (Theme editor, Theme settings). Items that require code edits are flagged.

## 2. Typography

Typeface: Neue Helvetica Pro (Linotype). Three weights, self hosted as WOFF2 files uploaded to the theme assets folder, not Shopify fonts:

| Role | File | CSS weight |
|---|---|---|
| Body | NeueHelveticaPro55Roman.woff2 | 400 |
| Buttons, announcement, medium text | NeueHelveticaPro65Medium.woff2 | 500 |
| Headings, nav, accordions | NeueHelveticaPro75Bold.woff2 | 700 |

Fallback stack: 'Helvetica Neue', Helvetica, Arial, sans-serif.

Licensing note: Neue Helvetica Pro requires a paid web font license from Monotype/Linotype, priced by pageviews. Two ways to get the same look:

1. Buy the web license and self host. Requires a code edit: upload the three WOFF2 files to Assets, add @font-face rules in a snippet included from theme.liquid, and override the heading and body font family variables (see section 8).
2. Use a Shopify hosted font at no cost. Closest matches in the Shopify font picker: Helvetica (Shopify offers Helvetica in the picker on some plans), otherwise Inter, Roboto, or Work Sans with tight letter spacing on headings. The tight negative tracking on headings does more for the look than the exact typeface.

Theme settings (Typography):

| Setting | Value |
|---|---|
| Heading font weight | 700 (Bold) |
| Heading text transform | None |
| Heading letter spacing | -0.05em (Prestige setting: -5) |
| Body font weight | 400 |
| Body letter spacing | 0 |
| Button font weight | 500 |
| Button text transform | None |
| Button letter spacing | 0 |
| Heading size factor | 1 (default) |

Base type scale (rem, 16px root):

| Token | Mobile | Desktop |
|---|---|---|
| H0 (hero) | 3rem (48px) | 6rem (96px) |
| H1 | 1.875rem (30px) | 3rem (48px) |
| H2 | 1.25rem (20px) | 1.625rem (26px) |
| H3 | 1rem | 1rem |
| H4 | 0.875rem | 1.125rem |
| H5 | 1rem | 1.5rem |
| H6 | 1rem | 1rem |
| text xs | 0.75rem (12px) | |
| text sm | 0.9375rem (15px) | |
| text base | 1rem (16px) | |
| text lg | 1.125rem (18px) | |
| text xl | 1.25rem (20px) | |

Rendered values on the product page (desktop, 1127px viewport):

| Element | Weight | Size / line height | Letter spacing | Color |
|---|---|---|---|---|
| Product title (h1) | 700 | 38px / 40px | -1.9px (-0.05em) | #0E0E0E |
| Price | 400 | 20px / 28px | 0 | #0E0E0E |
| Body / description | 400 | 16px / 22.4px (1.4) | 0 | #0E0E0E |
| Description bold lead | 700 | 16px | 0 | #0E0E0E |
| Main nav links | 700 | 16px / 19.2px | -0.8px | #0E0E0E, active item #FF8922 (orange) |
| Announcement bar | 500 | 12px / 19.2px | 0 | #FFFFFF on #0E0E0E |
| Option label (Color, Size) | 400 | 12px | 0 | #0E0E0E |
| Size pill text | 400 | 12px | 0 | #0E0E0E |
| Accordion titles | 700 | 16px | -0.8px | #0E0E0E |
| Add to Cart button | 500 | 18px / 18px | 0 | #FFFFFF on #0E0E0E |
| Footer links | 400 | 14px | 0 | white at 65% opacity |
| Breadcrumb | 400 | 16px | 0 | #0E0E0E |

## 3. Color

Prestige uses named color schemes. The store defines about 20. The ones that define the look:

| Scheme | Background | Text | Border | Button bg | Button text |
|---|---|---|---|---|---|
| Scheme 1 (default, page) | #FFFFFF | #0E0E0E | #DBDBDB | #0E0E0E | #FFFFFF |
| Scheme 2 (same as 1) | #FFFFFF | #0E0E0E | #DBDBDB | #0E0E0E | #FFFFFF |
| Scheme 3 (inverse, footer / announcement) | #0E0E0E | #FFFFFF | #323232 | #FFFFFF | #0E0E0E |
| Scheme 4 (transparent header over hero) | transparent | #FFFFFF | #262626 | #FFFFFF | #1C1C1C |
| Off white | #FDFDFD | #0E0E0E | #D9D9D9 | #0E0E0E | #FFFFFF |
| Light gray | #F5F5F5 | #0E0E0E | #D2D2D2 | #0E0E0E | #FFFFFF |
| Mid gray | #E6E6E6 | #0E0E0E | #C6C6C6 | #0E0E0E | #FFFFFF |
| Charcoal | #303030 | #FDFDFD | #4F4F4F | #0E0E0E | #FDFDFD |
| Dark charcoal | #2C2C2C | #FFFFFF | #4C4C4C | #2C2C2C | #FFFFFF |
| Tan | #CFBFA3 | #0E0E0E | #B2A48D | #0E0E0E | #FFFFFF |
| Brown | #6E5C4C | #FFFFFF | #847467 | #0E0E0E | #FFFFFF |
| Dusty pink | #E6CCCF | #FFFFFF | #EAD4D6 | #0E0E0E | #FFFFFF |
| Pale blue | #CCD8DC | #000000 | #ADB8BB | #0E0E0E | #FFFFFF |
| Red | #BE393C | #FFFFFF | #C85759 | #0E0E0E | #FFFFFF |
| Bright red | #C72A2A | #FDFDFD | #CF4A4A | #0E0E0E | #FFFFFF |
| Orange (brand accent) | #FF8922 | #FFFFFF | #FF9B43 | #0E0E0E | #FFFFFF |
| Acid yellow | #F4FF91 | #000000 | | #0E0E0E | #FFFFFF |
| Black with lime text | #0E0E0E | #E7FE9A | #2F3223 | #0E0E0E | #FFFFFF |

Rule of the look: near black (#0E0E0E, not pure black) on pure white, one saturated accent (orange #FF8922) used sparingly for the active nav item, seasonal tinted schemes for individual sections. Circle buttons (gallery arrows, close) are always white with #0E0E0E icons.

Product and status colors:

| Token | Value |
|---|---|
| Sale price text | #CB2B2B |
| Compare at price / strikethrough | #777777 |
| Standard price | #0E0E0E |
| Sale badge | #CB2B2B bg, white text |
| Sold out badge | #D9D9D9 bg, black 65% text |
| Custom badge | #0E0E0E bg, white text |
| Star rating | #6AD5C3 (mint, set in Loox) |
| Success | #E4E4D5 bg, #808036 text |
| Warning | #F5ECE9 bg, #AD5D44 text |
| Error | #F3CCCC bg, #CB2B2B text |
| Page overlay | black at 40% |

## 4. Shape and spacing

| Setting | Value |
|---|---|
| Button border radius | 3.75rem (60px, full pill) |
| Input border radius | 3.75rem (full pill) |
| Swatch shape | circle (9999px), 1px #0E0E0E border, 2px padding, so a ring appears around the selected swatch |
| Size selector | pill blocks, 1px border, selected state solid #0E0E0E border |
| Button padding | 20px 24px, full width on product page |
| Input padding | 0.65rem 0.8rem |
| Shadows | sm 0 2px 8px rgba(0,0,0,.05), default 0 5px 15px rgba(0,0,0,.05), md 0 5px 30px rgba(0,0,0,.05). Block shadow off. |
| Container gutter | 1.25rem (20px) |
| Container widths | xxs 27.5rem, xs 42.5rem, sm 61.25rem, md 71.875rem, lg 78.75rem, xl 85rem. Page width setting: 100% (full width). |
| Section vertical spacing | 2rem |
| Section stack gap | 1.5rem |
| Form gap | 1.25rem, fieldset 1rem, control 0.625rem |

## 5. Header and announcement bar

Announcement bar: 43px tall, #0E0E0E background, white 12px medium text, single underlined link, prev/next arrows (rotating messages). Not sticky.

Header: 72px tall, white background, not transparent on product pages. Logo left (100px wide image, stacked wordmark). Primary nav centered, 16px bold, tight tracking, no uppercase. Icons right: account, search, cart, outline style, no labels. Prestige setting: Logo position left, navigation centered. Header separates from content by white space only, no bottom border.

## 6. Product page layout

Two column. Left: gallery as a 2 up grid of square images, no thumbnails, no carousel on desktop, scrolls with the page. Product photos shot on a flat #EBEBEB light gray background, which reads as a tile grid. Right column sticky: breadcrumb, h1, price with Shop Pay installments inline, color swatches (circles), size pills, Wair size finder link, full width black pill Add to Cart, Shop Pay installments line, two icon plus text trust badges (returns, warranty), thin #DBDBDB divider, description with bold lead sentence, bullets, bold closing line.

Prestige settings: Gallery layout "grid" (2 columns), media aspect square, sticky product info on, thumbnails off. Reviews (Loox) render as a full width section below.

Floating chat bubble bottom right: black circle, white icon (Redo).

## 7. Add on apps detected

Confirmed by loaded scripts or app embed blocks:

| App | Purpose | Evidence |
|---|---|---|
| Klaviyo | Email and SMS, popups, hosted Poppins font for forms | static.klaviyo.com |
| Postscript | SMS marketing | sdk.postscript.io |
| Smile.io | Loyalty and rewards | js.smile.io, smile-loader embed |
| Loox | Product reviews and photo reviews, star widget (mint stars) | loox.io, lxs CSS variables |
| Redo | Returns, exchanges, shipping protection, customer support chat widget | getredo.com, redo app embed, bottom right chat bubble |
| Fast Bundle | Product bundles | api.fastbundle.co, fast-bundle-product-bundles embed |
| Back in Stock (AMP / Restock Alerts) | Restock alerts, low stock badge, wishlist, preorder updater | backinstock.useamp.com, back-in-stock embed |
| Wair | AI size recommendation ("What's my size?") | predict-v4.getwair.com |
| Tolstoy | Shoppable video widgets | widget.gotolstoy.com |
| Tapcart | Native mobile app, web bridge SDK | assets.tapcart.com, mobile-embed-scripts |
| Stylux | Product styling / outfit widget | sdk.stylux.io |
| Fondue | Cashback offers | public.getfondue.com |
| Shoplift | A/B testing (theme is labelled "AB Tested", GT Standard font injected from shoplift static uploads) | shoplift static uploads |
| Triple Whale | Attribution and analytics pixel | whale.camera, config-security.com |
| Awin | Affiliate network tracking | awin-shopify-integration-code.js |
| Aggle (Oir) | Ad attribution / retargeting pixel | cdn.aggle.net/oir |
| Shop Pay installments, Shop app | Native Shopify | shop.app |
| Meta Pixel, Microsoft (Bing) UET, Google Tag Manager | Ad tracking | connect.facebook.net, bat.bing.com, googletagmanager.com |
| OpenAI commerce SDK, Shopify WebMCP | Agentic checkout / ChatGPT shopping | bzrcdn.openai.com/sdk/oaiq, storefront/webmcp |

Not present despite keyword hits: Yotpo, Okendo, Attentive, Rebuy (the names appear only inside Fast Bundle and Redo integration code).

That is roughly 17 third party apps. Each adds JavaScript weight. For a swim booking store the relevant subset is Klaviyo, Loox or Judge.me, and possibly Redo for returns. Bundles, size finder, video, and mobile app are apparel specific.

## 8. Implementation order on your store

1. Install Prestige from the Shopify Theme Store, publish to a duplicate, not live.
2. Theme settings, Colors: set Scheme 1 to #FFFFFF / #0E0E0E / border #DBDBDB / button #0E0E0E / button text #FFFFFF. Set Scheme 3 to the inverse. Add one accent scheme with your brand color in place of #FF8922.
3. Theme settings, Typography: heading weight bold, letter spacing -5, body regular, button medium, no uppercase anywhere.
4. Theme settings, Buttons and inputs: border radius 60px (or the maximum slider value for full pill), swatch shape circle.
5. Theme settings, Layout: page width full, gutter 20px.
6. Header: logo left, nav centered, icons only, no border. Announcement bar in Scheme 3.
7. Product page: gallery grid 2 columns, square media, sticky info, thumbnails off, trust badges block, description with bold lead.
8. Optional font code edit (Neue Helvetica Pro or an alternative you license):

```liquid
{%- comment -%} snippets/custom-fonts.liquid, include in theme.liquid before {{ content_for_header }} {%- endcomment -%}
<style>
@font-face{font-family:'Helvetica Neue';font-weight:400;font-style:normal;font-display:swap;src:url('{{ 'NeueHelveticaPro55Roman.woff2' | asset_url }}') format('woff2');}
@font-face{font-family:'Helvetica Neue';font-weight:500;font-style:normal;font-display:swap;src:url('{{ 'NeueHelveticaPro65Medium.woff2' | asset_url }}') format('woff2');}
@font-face{font-family:'Helvetica Neue';font-weight:700;font-style:normal;font-display:swap;src:url('{{ 'NeueHelveticaPro75Bold.woff2' | asset_url }}') format('woff2');}
:root{
  --heading-font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;
  --heading-font-weight:700;
  --heading-letter-spacing:-0.05em;
  --text-font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;
  --text-font-weight:400;
  --button-font:normal 500 var(--text-lg)/1.4 var(--text-font-family);
}
</style>
```

Photography is the other half of this look. Every product image is shot on the same flat light gray (#EBEBEB) background, square crop, product centered. Without consistent imagery the grid layout falls apart.
