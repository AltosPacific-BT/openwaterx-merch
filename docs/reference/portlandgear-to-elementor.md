---
title: "Portland Gear look on OWUnion: Elementor and WooCommerce application"
created: 2026-10-06
status: draft
type: guide
author: "Bryan Temmermand"
tags: [owunion, elementor, woocommerce, typography, design, portland-gear]
ai_summary: "Translates the Portland Gear Shopify dissection into Elementor Pro Site Settings, Theme Builder layouts, and one paste in CSS file for the OWUnion WooCommerce store. Fonts first, then the layout features that are easy in WordPress. Includes contrast fixes and a Claude in Chrome prompt."
---

# Portland Gear look on OWUnion: what to set in Elementor

Source: `docs/reference/portlandgear-shopify-theme-spec.md` (the dissection). Decisions: spec v0.3 section 4 (free fonts, monochrome parent, stores bring their own accent). Store name per `docs/NAMING.md`.

Files:

* `elementor/owunion-site.css`, paste into Elementor, Site Settings, Custom CSS
* `elementor/owunion-gallery-grid.php`, optional, for the two column gallery
* `elementor/claude-in-chrome-site-settings.md`, prompt that applies sections 2 to 5 in the browser

Labels: `T` tested in Chromium against WooCommerce 11.1.2, Hello Elementor 3.5.1, and Elementor's own kit selectors. `K` working knowledge, check on staging.

## 1. What carries over and what does not

| Portland Gear | OWUnion | Why |
|---|---|---|
| Neue Helvetica Pro, 400, 500, 700 | Inter, 400, 500, 700 | Free (OFL). Same weights and roles |
| Bold headings, sentence case, tracking -0.05em | Barlow Condensed 700, uppercase, tracking 0 for H1 and H2. Inter 700 for H3 to H6 and nav | Decided in spec 4.3 to echo the logo. Negative tracking suits mixed case Helvetica, not condensed capitals |
| Near black #0E0E0E on white | Same | Logo black samples at #0A0A0A, visually identical |
| Orange accent #FF8922 | None on the parent. Each store gets one | Fails text contrast (2.4:1). Parent is monochrome |
| Pill buttons and inputs, 60px radius | Same | |
| Square product photos on #EBEBEB | Same | Photography rule, spec 4.6 |
| 72px header, logo left, nav centered, icons right, 43px black announcement bar | Same | |
| Two column product page, 2 up gallery grid, sticky details | Same, gallery grid needs a snippet | |
| Compare at price #777777 | #767676 | #777777 is 4.48:1, just under the 4.5:1 minimum |
| Notice colors (success, warning, error) | Same backgrounds, darker text | All three reference pairs fail 4.5:1. See 6 |
| 17 apps, Shop Pay, chat bubble, size finder, A/B tests | Not carried over | Apparel and Shopify specific |

## 2. Fonts (do first)

**Loading.** Elementor, Settings, Performance: Load Google Fonts Locally, Active (Elementor 3.27 or later). Elementor, Settings, Advanced: Google Fonts Load, Swap. No font files to upload, and no request goes to Google. Browsers download only the weights a page uses. `K` for the exact tab names.

**Global Fonts.** Open any page in Elementor, then Site Settings, Global Fonts.

| Global | Family | Weight | Size | Transform | Line height | Letter spacing | Used for |
|---|---|---|---|---|---|---|---|
| Primary | Barlow Condensed | 700 | leave empty | Uppercase | 1 | 0 | Display headings |
| Secondary | Inter | 700 | leave empty | None | 1.25 | -0.02em | Nav, accordions, small headings |
| Text | Inter | 400 | 16px | None | 1.4 | 0 | Body |
| Accent | Inter | 500 | 16px | None | 1 | 0 | Buttons, announcement bar |

Inter is wider than Helvetica. -0.02em at 16px gives the tight look without letters touching; Portland Gear's -0.05em would be too tight on Inter.

**Theme Style, Typography.** Elementor globals cannot be partly overridden, so set H1 and H2 as custom values.

| Element | Family, weight | Desktop | Tablet | Mobile | Line height | Other |
|---|---|---|---|---|---|---|
| Body | Text global | 16px | | | 1.4 | Color: Text global |
| Link | | | | | | Color #0E0E0E, hover #303030. CSS underlines links in running text |
| H1 | Barlow Condensed 700 | 48px | 40px | 30px | 1 | Uppercase, spacing 0 |
| H2 | Barlow Condensed 700 | 26px | 23px | 20px | 1.05 | Uppercase, spacing 0 |
| H3 | Secondary global | 16px | 16px | 16px | 1.25 | |
| H4 | Inter 700 | 18px | 16px | 14px | 1.25 | -0.02em |
| H5 | Inter 700 | 24px | 20px | 16px | 1.2 | -0.02em |
| H6 | Secondary global | 16px | 16px | 16px | 1.25 | |

Product title on the product page: Product Title widget, Barlow Condensed 700, 38px desktop, 34px tablet, 30px mobile, line height 1.05.

**Heading font test.** Spec 4.3 asks for Barlow Condensed, Oswald, Bebas Neue, and Anton set beside the logo before committing. The OWUnion reference page shows that comparison. Switching later is one change to the Primary global.

## 3. Global Colors

Site Settings, Global Colors.

| Name | Value | Note |
|---|---|---|
| Primary (system) | #0E0E0E | Ink. Headings, icons |
| Secondary (system) | #303030 | Charcoal. Not white: some widgets default text to Secondary, and white would vanish on a white page |
| Text (system) | #0E0E0E | |
| Accent (system) | #0E0E0E | Buttons and hovers default to Accent. Stores override it, see 8 |
| OWU Paper | #FFFFFF | |
| OWU Border | #DBDBDB | Dividers, inputs |
| OWU Tile | #EBEBEB | Product photo background |
| OWU Light | #F5F5F5 | Section fill |
| OWU Muted | #767676 | Compare at price, captions |
| OWU Sale | #CB2B2B | Archive store sale price and badge |
| OWU Footer Link | #FFFFFFA6 | White at 65 percent on the black footer |

## 4. Buttons, form fields, images

**Theme Style, Buttons.** Typography: Accent global. Normal: text #FFFFFF, background #0E0E0E, border none, radius 60px, padding 16px 24px. Hover: text #FFFFFF, background #303030.

**Theme Style, Form Fields.** Label: Inter 400, 12px, color Text. Field typography: Text global (16px keeps iOS from zooming on focus). Normal: text #0E0E0E, background #FFFFFF, border solid 1px #DBDBDB, radius 60px. Focus: border #0E0E0E. Padding 12px 20px. The CSS file rounds textareas to 16px, because a pill textarea looks broken.

**Theme Style, Images.** Radius 0.

## 5. Layout and Custom CSS

**Settings, Layout.** Content width 1360px (the reference's widest container). Gaps 20px row and column. Leave Container Padding at its default; Elementor applies it to nested containers too. Instead give each template's outer container 20px left and right padding.

**Site Settings, Custom CSS.** Paste `elementor/owunion-site.css`. It covers what Site Settings cannot reach, all `T`:

* WooCommerce buttons (cart, checkout, notices, shop cards) as ink pills, replacing WooCommerce purple and gray
* Variation selects, quantity, and the checkout country field (Select2) as pills
* Square product tiles on #EBEBEB, in the shop grid and the product gallery
* Card titles in Inter (WooCommerce marks them H2, which would otherwise pick up Barlow capitals)
* Prices in ink instead of WooCommerce olive, sale price and badge in #CB2B2B
* Notices with the contrast fixes in section 6
* Helper classes, added in Elementor under Advanced, CSS Classes:

| Class | Put it on | Does |
|---|---|---|
| `owu-announcement` | Announcement bar container | Black bar, white 12px Inter 500, underlined white link |
| `owu-inverse` | Footer container | Black background, white text, links at 65 percent white |
| `owu-logo-reversed` | Logo widget on any black bar | Turns the black JPG logo white with no visible box. Use until reversed vector logos exist |
| `owu-sticky` | Right column of the product template | Stays in view while the gallery scrolls. Desktop only |
| `owu-clean-grid` | Products or Archive Products widget | Optional. Hides Add to Cart buttons on cards |

Also: Appearance, Customize, WooCommerce, Product Images: thumbnail cropping 1:1. WooCommerce regenerates thumbnails in the background. `K`

If pink (#CC3366) shows anywhere, it is Hello Elementor's reset stylesheet. Sections 2 to 4 replace it on links, buttons, and fields.

## 6. Contrast fixes to the reference

| Pair | Reference | Ratio | OWUnion | Ratio |
|---|---|---|---|---|
| Compare at price on white | #777777 | 4.48 | #767676 | 4.54 |
| Success text on #E4E4D5 | #808036 | 3.23 | #67672B | 4.61 |
| Warning text on #F5ECE9 | #AD5D44 | 4.08 | #A0563F | 4.63 |
| Error text on #F3CCCC | #CB2B2B | 3.65 | #AE2525 | 4.63 |
| Footer links, white 65% on #0E0E0E | | 8.41 | unchanged | |
| Orange accent on white | #FF8922 | 2.37 | not used | |

Ink on white is 19.3:1. WCAG AA needs 4.5:1 for body text.

## 7. Layout features in Theme Builder

Ranked by effort. Build on staging; templates are drafts until published.

### Easy, settings only

**Header** (Theme Builder, Header, condition Entire Site):

1. Container, class `owu-announcement`, full width, min height 43px, content centered. Text Editor widget: "Free shipping on orders over $100 to the lower 48 states and DC." (spec section 6, item 9). One message for now.
2. Container, boxed, min height 72px, 20px side padding, row, space between, centered vertically, white, no border or shadow.
   * Site Logo widget, width 140px desktop and 112px mobile (the logo is 2.87 times wider than tall, so 140px wide is 49px tall)
   * Nav Menu widget, centered, Secondary global at 16px, no uppercase, pointer Underline in #0E0E0E, dropdown at the tablet breakpoint
   * Right group, 16px gap: Search widget (icon only), Icon widget linked to My Account, Menu Cart widget (icon only, subtotal hidden). Outline icons at 20px

**Footer** (condition Entire Site): container with class `owu-inverse`, 48px top and bottom, 20px sides. Logo image with class `owu-logo-reversed`. Columns: Stores (Shortcode widget, `[owunion_active_stores layout="list" exclude="owunion"]` from the store status plugin, so hidden stores drop out), Help (Shipping, Returns, Size guide, Contact), Legal (Privacy, Terms). Inter 14px.

**Single Product template:**

1. Outer container, boxed, row on desktop, column on mobile, 40px gap, 24px top, 48px bottom, 20px sides.
2. Left container, 55 percent: Product Images widget.
3. Right container, 45 percent, class `owu-sticky`, in this order: WooCommerce Breadcrumbs (16px), Product Title (section 2), Product Price (Inter 400, 20px, line height 28px), Add to Cart (the CSS makes the button full width, 18px, padding 20px 24px), Icon List with two trust lines (free shipping over $100, and your returns policy once written), Divider 1px #DBDBDB, Short Description.
4. Description copy rule: bold lead sentence, bullets, bold closing line.

**Shop and store archives** (Product Archive template): Archive Title (H1), Archive Products widget, 4 columns desktop, 3 tablet, 2 mobile, 20px column gap, 32px row gap. Card titles and prices come from the CSS. The column counts are an OWUnion choice; the dissection covered only the product page.

### Medium, one plugin or snippet

| Feature | How | Note |
|---|---|---|
| Two column gallery grid | `elementor/owunion-gallery-grid.php` in Code Snippets or `mu-plugins`. It turns off the slider, serves full size images, and switches on the grid CSS | Filters confirmed in WooCommerce 11.1.2 source. Check that Elementor Pro's Product Images widget respects it `K` |
| Color swatches and size pills | Variation Swatches for WooCommerce (CartFlows, free): Color as circles with a 1px ring, Size as buttons | WooCommerce core swatches are block theme only and experimental, so they do not reach Elementor templates |
| Rotating announcement messages | Swap the Text Editor for a carousel widget with autoplay | Add when there is a second message |
| Per store accent | One CSS line per store, see 8 | After store colors exist |

### Skip for now

Seasonal tinted color schemes, shoppable video, size finder, bundles, loyalty, chat bubble, Shop Pay installment messaging, A/B testing.

## 8. Per store accent (later)

The store status plugin adds `store-{slug}` to the body on brand and product pages. One line per store in Custom CSS overrides Elementor's Accent global on that store's pages only:

```css
body.store-swim-alcatraz { --e-global-color-accent: #RRGGBB; }
```

Every widget set to Accent (buttons, nav hover, icons) follows. Check white text on the accent at 4.5:1 before using it on buttons. `K` until the first store is built.

## 9. Order of work, and Chrome versus MCP

**Before anything:** this is site wide and takes effect on save. Work on WP Engine staging, or if staging is not ready, export a kit first: Elementor, Tools, Import/Export (Website Templates in newer versions), Export, with Site Settings checked. That is the rollback.

| Step | Work | Tool |
|---|---|---|
| 1 | Font loading settings | Chrome |
| 2 | Global Colors and Global Fonts | Chrome |
| 3 | Typography, Buttons, Form Fields, Images, Layout | Chrome |
| 4 | Paste Custom CSS, set 1:1 product thumbnails | Chrome or by hand, two minutes |
| 5 | Header and footer templates | By hand, or Chrome one container at a time |
| 6 | Single Product and Archive templates | By hand, or Chrome one container at a time |
| 7 | Gallery snippet and swatch plugin | By hand |

**Use Claude in Chrome for steps 1 to 4.** It is a one time pass through about 40 visual settings, you can watch it work, and it needs no setup. The prompt is in `elementor/claude-in-chrome-site-settings.md`. It records each value before changing it, so the report doubles as a rollback list.

**Save the MCP server for the catalog.** It pays off on repeated, structured work: the read only site audit (spec section 7, step 1), then brands, categories, attributes, and draft products. Elementor stores Site Settings as a block of metadata on a hidden kit post; writing that through MCP is possible but one malformed write can reset the styles, which is a poor trade for a one time setup. `K`

To connect it: add the server at claude.ai, Customize, Connectors, then start a new session. Connectors load when a session starts.
