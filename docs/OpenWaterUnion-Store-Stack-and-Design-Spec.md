---
title: "OpenWaterUnion Store: Design, Categories, Stack, and WooCommerce Functionality Spec"
created: 2026-10-05T01:30:00
updated: 2026-10-05T03:30:00
status: draft
type: guide
author: "Bryan Temmermand"
tags: [openwaterunion, woocommerce, elementor, design, categories, brands, multi-store, technology-stack, specification]
ai_summary: "Fulfillment agnostic spec for OpenWaterUnion.com on the existing WP Engine site with WooCommerce and Elementor Pro: several branded stores as core Brands, four product types, per store headers through Elementor Theme Builder conditions, a store on and off switch, monochrome design from the logo, and free fonts. Reward codes deferred."
---

# OpenWaterUnion Store: Design, Categories, Stack, and Functionality

Companion to [[open-water-x/OpenWaterUnion-WooCommerce-PoC-Plan]]. Fulfillment agnostic. Version 0.3.

**Scope.** Design and categories first. The $15 reward code is deferred, see section 8.

**Evidence labels.** `V` means confirmed in search results during this session. `K` means working knowledge, to be verified on the site. Sources are in section 10.

**What changed in 0.3.** The site already exists on WP Engine with WooCommerce, Elementor Pro, and an MCP server. The earlier block theme plan is replaced by an Elementor Pro plan. Per store headers now use Elementor Theme Builder conditions, which shrinks the custom code.

## 1. Store model: several stores inside one store

One site, one cart, one checkout. Each store feels separate through its own header, logo, navigation, and accent color. Shoppers can mix items from different stores in one order.

| Store | Role |
|---|---|
| OpenWaterUnion | Parent. Home, all products, cart, checkout, account, policies |
| Pacific Open Water Swim Co. | Company store |
| Swim Alcatraz | Destination store |
| Swim Tahoe | Destination store |
| Alcatraz Swim Club | Club store |
| Marathon Swim Club | Club store |
| 24 Hour Relay SF | Event store. Slug `24-hour-relay-sf` |
| Archive | Retired designs and overstock. The only store where sale prices are set |

Names come from [[open-water-x/Open_Water_X_Merchandise_Strategy]] and Bryan's additions. List still to be confirmed.

**Rules**

1. A product belongs to exactly one store. Archive items keep their origin as a tag.
2. Cart, checkout, account, and policy pages use the parent OpenWaterUnion header, because a cart can mix stores.
3. Every store page links back to OpenWaterUnion.
4. 24 Hour Relay SF merchandise changes by year and stays in its store (decided 2026-10-05). Each year's designs carry an Event year attribute. Consequence: sale prices are otherwise only allowed in Archive, so past relay years stay at full price unless the rule changes.
5. Any store can be switched off. See section 3.
6. Each store has its own logo and color (confirmed 2026-10-05). Assets are not ready yet, see 4.1.

## 2. Taxonomy and categories

WooCommerce core has a Brands taxonomy, `product_brand`, on by default since version 9.6, with brand archive pages such as `/brand/name/` and block templates for them (V). Stores are Brands. Product categories stay free for product type.

### 2.1 The model

| Axis | Mechanism | Values |
|---|---|---|
| Store | Brands (`product_brand`), core | The eight stores in section 1 |
| Product type | Product categories | Hoodies, Swim Caps, Tee Shirts, Stickers. Stickers has two child categories: Individual Stickers and Sticker Packs |
| Color, Size | Global attributes for variations | See 2.2 |
| Event year | Global attribute, archives on, not used for variations | 24 Hour Relay SF products only |
| Origin or design | Product tags, optional | Archive origin, search |

### 2.2 Variations by type

| Type | Attributes | Product type in WooCommerce |
|---|---|---|
| Hoodies | Color, Size | Variable |
| Tee Shirts | Color, Size | Variable |
| Swim Caps | Color | Variable, or simple if one color |
| Individual Stickers | None, or a Size option | Simple |
| Sticker Packs | None | Simple. The pack is one product that lists what it contains |

Sticker packs as simple products need no extra plugin. A build your own pack feature would need a bundles extension, so it is out of scope for now.

Swatches: WooCommerce 10.9 adds a Color/Image attribute type as an experimental feature in block themes (V). Elementor Pro's single product template may handle variations its own way, so test swatch display on the live version (K). If it is not available, use a swatch plugin.

### 2.3 Navigation behavior

| Page | URL | Shows |
|---|---|---|
| Parent shop | `/shop/` | All products, filters for store and type |
| Type, all stores | `/product-category/hoodies/` | Hoodies from every store |
| Store home | `/brand/swim-alcatraz/` | Store hero, products, type sections |
| Store and type | `/shop/?product_brand=swim-alcatraz&product_cat=hoodies`, or section links on the store page | Query parameter filtering to be verified (K) |
| Relay by year | Store page with the Event year filter | Newest year first |
| Product | `/product/swim-alcatraz-hoodie/` | Header follows the product's store |

### 2.4 SKU and URL conventions

| Item | Pattern | Example |
|---|---|---|
| SKU | `OWU-{STORE}-{TYPE}-{DESIGN}-{COLOR}-{SIZE}` | `OWU-ALC-HD-ESC-BLK-L` |
| Relay SKU | Design segment carries the year | `OWU-RLY-HD-Y26-BLK-L` |
| Brand base | `/brand/` | `/brand/swim-tahoe/` |
| Category base | `/product-category/` | `/product-category/swim-caps/` |

Type codes: HD hoodie, CP swim cap, TE tee shirt, ST sticker. Store codes, proposed: OWU, POW, ALC, TAH, ASC, MSC, RLY, ARC.

### 2.5 Pricing reference

From the merchandise strategy. Not the focus now.

| Store group | Hoodie | Tee | Cap |
|---|---|---|---|
| Company | $79 | $28 | $20 |
| Destination | $89 | $35 | $25 |
| Archive | Sale price allowed | Sale price allowed | Sale price allowed |

Stickers and sticker packs have no price in the strategy. Set later.

## 3. Store status: turning stores on and off

WooCommerce core has no store level switch. This is custom code in a small plugin.

### 3.1 States

| State | Shoppers see | Build |
|---|---|---|
| Active | Normal store | Launch |
| Hidden | The store does not exist. Brand page and its products return 404, and it drops out of menus, tiles, search, and filters | Launch |
| Closed | The store page stays up with a "closed, back soon" notice. Products cannot be bought. Hidden from search and shop lists | Second, after launch |

Decided 2026-10-05: two states for launch, Closed second, no open and close dates. Nothing is deleted in any state. Re-enabling restores everything.

### 3.2 Behavior rules

1. The switch is a status field on the Brand edit screen.
2. Hidden stores are excluded from shop, search, filters, related products, store tiles, and menus.
3. Products in a Hidden store are not purchasable (`woocommerce_is_purchasable` returns false, K).
4. If a store is switched off while items are in a cart, checkout validation removes them with a message (K).
5. Past orders, order emails, and refunds still work.
6. Store tiles and the parent menu are built from the list of Active stores through a plugin shortcode placed in an Elementor Shortcode widget, so a disabled store drops out with no manual edits (K). Hand built menu links would point to a 404.
7. Hidden stores are removed from the sitemap or set to noindex. Check how the SEO plugin handles it (K).
8. Page caching: a status change must purge the affected pages. WP Engine runs its own page cache, so test purge on save (K).

### 3.3 No code fallback

Bulk edit the store's products to Draft. Works for the PoC, but the brand page still resolves as an empty page and re enabling means republishing each product.

### 3.4 Size of the work

A status field, query exclusions, purchasability, redirects, cart cleanup, and the Active stores shortcode. Estimate: a few hundred lines. The per store header swap is no longer part of it, see 5.3.

## 4. Design

### 4.1 Logo and store assets

Logo: black on white. "OPEN WATER" in a heavy condensed face on an arched baseline, "UNION" below, flanked by two stars. File: `open-water-x/assets/openwaterunion-logo.jpg` (1502 by 523 pixels, raster).

What it implies:

* **Monochrome identity.** Ink on white. The parent site needs no accent color. Stores bring their own colors (confirmed).
* **Athletic and varsity feel.** Heavy condensed capitals, arch, stars. Headings and badges echo it.
* **Star motif.** Use the star as a divider, bullet, and badge icon.

**Asset status.** Vector files, a transparent PNG, and reversed versions are not ready yet (Bryan, 2026-10-05). Interim plan for the PoC:

* Use the JPG on white headers
* On dark bars, invert the logo with CSS (a white logo on a black bar), checking the bar color matches the inverted background, or use pure black for dark bars
* Stores without logo files use the store name set in the display font
* Build two stores fully first, the parent and one other, so missing assets do not block the PoC

Per store asset list, needed later: logo (SVG, transparent PNG, reversed), accent color hex, hero image.

### 4.2 Design tokens

Set in Elementor Site Settings as Global Colors and Global Fonts, and in CSS variables (K). Structure follows [[open-water-x/portlandgear-shopify-theme-spec]]: white page, square tiles, pill buttons.

| Token | Value | Note |
|---|---|---|
| Ink | #0E0E0E | Confirm against the logo black in the source file |
| Paper | #FFFFFF | Page |
| Border | #DBDBDB | Dividers, inputs |
| Tile gray | #EBEBEB | Product photo background |
| Inverse | #0E0E0E background, #FFFFFF text | Footer, announcement bar |
| Store accent | One per store | Fills, borders, and icons. Text use only if it passes 4.5 to 1 contrast on its background |
| Button radius | 60px, full pill | Inputs too |
| Gutter | 20px | Full width pages |
| Section spacing | 2rem, stack gap 1.5rem | |
| Image ratio | 1:1 | Product grid and gallery |
| Status colors | Success #E4E4D5 on #808036, warning #F5ECE9 on #AD5D44, error #F3CCCC on #CB2B2B | From the reference spec |

Ink on paper is about 19 to 1 contrast. Each store accent must be checked against white before it is used for text.

### 4.3 Typography (free fonts, decided)

| Role | Recommendation | Why |
|---|---|---|
| Headings | Barlow Condensed, ExtraBold or Bold, uppercase | Heavy condensed capitals with soft corners, closest free match to the logo mood. SIL Open Font License (V) |
| Body and UI | Inter, regular and medium | Neutral and readable (K) |

Test in the first week by setting "OPEN WATER UNION" beside the logo: Barlow Condensed, Oswald, Bebas Neue, Anton. Pick the one that sits best with the logo.

Delivery in Elementor: version 3.27 and later can load Google Fonts locally, under Elementor, Settings, Performance (V). Elementor Pro Custom Fonts can instead upload font files (V). Prefer local loading so no request goes to Google. Keep the license file with any uploaded fonts.

Scale on a 16px root: H1 1.875rem mobile to 3rem desktop, H2 1.25 to 1.625, body 1, button weight 500. Headings uppercase, body sentence case.

### 4.4 Page map

| Page | Purpose |
|---|---|
| Home (parent) | Announcement bar, hero, tiles for each Active store, featured products |
| Shop (all) | Product archive with filters for store and type |
| Store pages | Hero, type sections, featured products, store header |
| Type pages | All Hoodies, Swim Caps, Tee Shirts, Stickers across stores |
| Product | Two column. Left, 2 up square image grid. Right, sticky: breadcrumb with store, title, price, swatches, size pills, full width Add to Cart, trust badges, description |
| Cart, Checkout | Parent header |
| Account, order tracking | Parent header |
| Size guide, Shipping, Returns, Privacy, Terms | Required policy pages |
| About OpenWaterUnion | Brand story and the stores |
| Contact | Form and email |

### 4.5 Header and footer

Header 72px, logo left, navigation centered, icons only for account, search, cart. Announcement bar 43px, inverse scheme. Footer inverse, with the reversed logo, store links from the Active stores list, and policy links.

### 4.6 Photography rule

Every product image on one flat light gray background, square crop, product centered, across all stores. Independent of fulfillment. Stickers and packs follow the same rule.

## 5. Technology stack

### 5.1 Starting point (reported by Bryan, 2026-10-05)

WP Engine site is up with WooCommerce and Elementor Pro, with MCP server access. Not yet known:

* WordPress, WooCommerce, and Elementor Pro versions. Brands needs WooCommerce 9.6 or later (V)
* The active theme. Hello Elementor is the usual base for Elementor Pro (K)
* Whether it is the live domain or a development install, and whether WP Engine staging exists
* Which MCP server it is (the WordPress MCP Adapter with an Elementor extension is one common setup, V), what tools it exposes, and what access level it has
* Existing products, pages, and plugins

### 5.2 Stack

| Layer | Choice | Notes |
|---|---|---|
| Hosting | WP Engine, existing | Use its staging environment for build and tests (K) |
| Commerce | WooCommerce core | Brands in core (V) |
| Page builder | Elementor Pro | Theme Builder for headers, footers, product templates, archives (V). WooCommerce builder widgets for single product, archives, cart, checkout (V) |
| Theme | Hello Elementor or the current theme if it is a clean base | Keep the theme thin and put design in Theme Builder |
| Custom code | One small plugin for store status and the Active stores shortcode | Section 3 |
| Payments | Stripe gateway with Apple Pay and Google Pay | Test wallet buttons against Elementor's checkout widget, which is classic, versus the WooCommerce Checkout block (K) |
| Tax | Manual rates to start, or an automated service | Accountant confirms nexus (K) |
| Shipping | Core zones, flat rate, free shipping method | (K) |
| Email | SMTP or API sender, SPF, DKIM, DMARC | (K) |
| Analytics | WooCommerce Analytics, GA4 through Site Kit | (K) |
| Performance | WP Engine cache, CDN, WebP. Exclude cart, checkout, account | Elementor adds page weight, test mobile speed (K) |
| Security | 2FA, limited logins, backups, tested restore | (K) |

Elementor trade offs, stated plainly: faster visual building and per condition templates, against more page weight, builder lock in, and extra update risk. Mitigation: global styles, a small set of reusable templates, no per page one offs.

### 5.3 How the header changes per store

Elementor Pro Theme Builder applies a header template by display conditions, including category and taxonomy conditions (V). Single product templates can target a product category (V). Plan:

1. Build a parent header template, condition: entire site.
2. Build one header template per store. Conditions: the store's brand archive, and products in that brand.
3. Elementor applies the most specific match (K). Cart, checkout, and account match no store condition and fall back to the parent header.
4. Each store header carries its own logo, navigation, and accent.

To verify first: whether Product Brand appears as a condition for archives and for singular products (K). If it does not, fall back to a small custom condition or a code snippet that swaps the header, both small.

Store accent color: Elementor can set colors per template, or a body class `store-{slug}` added by the small plugin can drive CSS variables (K). Pick the simpler one after the first store is built.

### 5.4 Using the MCP server

The site's MCP server could speed up repetitive setup: creating categories, brands, attributes, and draft products, and building template pages (V for Elementor MCP tools in general, from search). Rules:

1. It is not connected to this Claude session. Bryan connects it, then Claude can use it.
2. Work on staging first. Never write to production until the staging result is checked.
3. Use a least privilege WordPress user, and keep a backup or rollback point before bulk changes.
4. Read first: inventory the site (versions, theme, plugins, existing content) before any write.

## 6. Functionality map

| # | Requirement | Method | Class | Evidence |
|---|---|---|---|---|
| 1 | Catalog with variations, images, categories, attributes | Core | Native | K |
| 2 | Stores as Brands, with brand archives | Core Brands | Native | V |
| 3 | Per store header, navigation, accent | Elementor Theme Builder conditions | Elementor Pro | V for conditions, K for the brand condition |
| 4 | Four product types, with sticker child categories | Core product categories | Native | K |
| 5 | Product page, shop, archives, cart, checkout layouts | Elementor Pro WooCommerce widgets | Elementor Pro | V |
| 6 | Filters for store, type, color, size, price, event year | Filter widgets and blocks, Loop Grid or Products widget queries | Native or Elementor, brand filter to verify | K |
| 7 | Guest checkout, mixed store carts | Core | Native | K |
| 8 | Card, Apple Pay, Google Pay | Stripe gateway | Plugin, free | K |
| 9 | Free shipping over $100, lower 48 states and DC | Shipping zone by state, Free shipping with a minimum, Flat rate | Native | K |
| 10 | No sitewide sales | Sale prices only in the Archive store, no sale plugins | Policy | n/a |
| 11 | Sales tax | Core tax settings or an automated service | Native or service | K |
| 12 | Branded transactional email | Core email settings and SMTP sender | Native plus service | K |
| 13 | Inventory | Stock management off, or on if stocked | Native | K |
| 14 | Reviews | Core product reviews | Native | K |
| 15 | Upsell and cross sell | Core linked products | Native | K |
| 16 | Refunds | Core order screen | Native | K |
| 17 | Analytics | WooCommerce Analytics and GA4 | Native plus free plugin | K |
| 18 | Hide a store, later close it | Brand status field and rules in the small plugin | Custom code | K |
| 19 | Year browsing for 24 Hour Relay SF | Event year attribute with a filter | Native | K |
| 20 | Free shipping progress message | Not in core. Plugin or custom | Plugin or custom | K, deferred |
| 21 | Accessibility and compliance | Template choices, policy pages, consent | Process | K |

## 7. Implementation sequence

| Step | Work | Done when |
|---|---|---|
| 1 | Audit the existing site: versions, theme, plugins, content, staging, MCP access level. Read only | Written inventory, Brands available |
| 2 | Staging environment, backup, test email, hardening | Test email delivers, backup restores |
| 3 | WooCommerce settings: location, currency, tax, shipping zones | A $99 and a $100 basket show the right shipping |
| 4 | Fonts, Global Colors and Fonts, logo placement, parent header and footer in Theme Builder | Matches section 4 on desktop and mobile |
| 5 | Categories (four types, sticker children), Brands (stores), attributes, permalinks, SKU scheme | Taxonomy live |
| 6 | Per store header templates with conditions | Header changes by store on brand, product, and type pages. Parent header on cart, checkout, account |
| 7 | Store status plugin: Active and Hidden | Hidden store returns 404 and drops out of search, tiles, and menus. A cart with a newly hidden store's item is cleaned at checkout. Re enable restores everything |
| 8 | Six to ten products across at least two stores, gray tile photos, one sticker pack | Product page matches 4.4 |
| 9 | Stripe and wallets, checkout widget test | Test payments, wallet buttons appear on supported devices |
| 10 | Policy pages, consent, accessibility scan | Pages published, scan clean |
| 11 | Mixed store cart test, twenty test orders, refunds, mobile performance | All pass |
| 12 | Closed state | Closed store shows the notice and blocks purchase |

## 8. Deferred

* $15 reward, reward codes, QR card landing page. Notes remain in the PoC plan.
* Free shipping progress message.
* Closed state (step 12).
* Vector logos and per store logo and color files.

## 9. Open items for Bryan

1. Confirm the list of eight stores
2. Connect the MCP server to this session, and tell me which one it is
3. Is the WP Engine site the live OpenWaterUnion.com domain or a development install? Is a staging environment available?
4. Versions of WordPress, WooCommerce, and Elementor Pro, and the active theme (or let me read them through MCP)
5. Sticker pack examples: how many stickers per pack, and are packs per store or mixed?

## 10. Sources used for V labels (search result summaries, the WooCommerce site was blocked from direct fetch)

* WooCommerce Brands in core: https://developer.woocommerce.com/2024/10/01/introducing-brands/ and https://developer.woocommerce.com/2025/01/17/enabling-brands-update-for-woocommerce-9-6/
* Elementor Theme Builder conditions: https://elementor.com/academy/setting-conditions-theme-builder-tutorial/ and https://elementor.com/old/features/theme-builder-ed/
* Elementor WooCommerce builder: https://elementor.com/help/woocommerce-single-product-builder/ and https://wpmudev.com/blog/elementor-woocommerce/
* Elementor local Google Fonts and custom fonts: https://elementor.com/help/load-google-fonts-locally/ and https://utilizewp.com/use-local-fonts-elementor/
* Elementor MCP tools: https://github.com/msrbuilds/elementor-mcp and https://elementormcp.com/
* Color swatches in core: https://developer.woocommerce.com/2026/06/03/introducing-color-swatches-in-woocommerce-core/
* Barlow Condensed license: https://fontsource.org/fonts/barlow-condensed
