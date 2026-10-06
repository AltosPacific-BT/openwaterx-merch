---
title: "Portland Gear look on OWUnion: Blocksy and WooCommerce application"
created: 2026-10-06
updated: 2026-10-06
status: draft
type: guide
author: "Bryan Temmermand"
tags: [owunion, blocksy, woocommerce, typography, design, portland-gear]
ai_summary: "Translates the Portland Gear Shopify dissection into Blocksy Customizer settings, Blocksy Pro features, and a short Additional CSS file for the OWUnion WooCommerce store. Theme settings first; Elementor only for page content. Barlow Condensed headings and Inter UI, decided."
---

# Portland Gear look on OWUnion: what to set in Blocksy

**Theme v1.0.** Applied from 2026-10-06. Later changes go in `docs/theme/v1.1-changes.md`.

Source: `docs/reference/portlandgear-shopify-theme-spec.md` (the dissection). Decisions: spec v0.3 section 4 (free fonts, monochrome parent, stores bring their own accent). Version 2: the site runs Blocksy with Blocksy Companion Pro, so theme settings come first. Version 1 assumed Elementor Site Settings and Hello Elementor.

Files:

* `blocksy/owunion-additional.css`, paste into Appearance, Customize, Additional CSS
* `blocksy/claude-in-chrome-customizer.md`, prompt that applies sections 2 to 4 in the browser

Labels: `S` confirmed in Blocksy 2.1.58 or Blocksy Companion 2.1.58 source. `T` tested in Chromium against Blocksy's stylesheets. `K` working knowledge, mostly Pro features whose code is not public. Check on staging. Customizer panel names can differ slightly by version.

## 1. Order of authority

1. **Blocksy Customizer.** Fonts, palette, buttons, form fields, layout, header, footer, shop, and product page.
2. **Blocksy Pro features.** Local fonts, stacked gallery, swatches, conditional headers.
3. **Additional CSS.** Four small items, section 8.
4. **Elementor, only for page content** such as the home page and landing pages, if it stays installed.

Why, from the source code:

* Blocksy removes WooCommerce's default stylesheet and loads its own, driven by Customizer settings. WooCommerce styling therefore belongs in the Customizer, not in CSS. `S`
* Blocksy switches off Elementor's default colors and fonts on first run, so Elementor widgets inherit the theme. It also offers the Blocksy palette inside Elementor's color picker. `S`
* Blocksy sets Elementor's breakpoints to its own: 690px and 1000px. `S`
* Leave Elementor Site Settings (Global Colors, Global Fonts, Theme Style) empty. Values set there override the theme inside Elementor content and create a second source of truth.
* Do not run Elementor Theme Builder headers or footers alongside Blocksy's header and footer builder on the same pages.

## 2. Fonts (decided: Barlow Condensed and Inter)

Barlow Condensed for display headings was confirmed by Bryan on 2026-10-06. Inter replaces Neue Helvetica Pro in the same three weights and roles.

**Loading.** Turn on Blocksy Pro's Local Google Fonts extension (Blocksy dashboard, Extensions) so no request goes to Google. `K`

**Customize, General, Typography.** `S` for the fields.

| Element | Family | Weight | Desktop | Tablet | Mobile | Line height | Spacing | Transform |
|---|---|---|---|---|---|---|---|---|
| Base Font | Inter | 400 | 16px | | | 1.4 | 0 | None |
| H1 | Barlow Condensed | 700 | 48px | 40px | 30px | 1 | 0 | Uppercase |
| H2 | Barlow Condensed | 700 | 26px | 23px | 20px | 1.05 | 0 | Uppercase |
| H3 | Inter | 700 | 16px | 16px | 16px | 1.25 | -0.02em | None |
| H4 | Inter | 700 | 18px | 16px | 14px | 1.25 | -0.02em | None |
| H5 | Inter | 700 | 24px | 20px | 16px | 1.2 | -0.02em | None |
| H6 | Inter | 700 | 16px | 16px | 16px | 1.25 | -0.02em | None |
| Buttons | Inter | 500 | 16px | | | 1 | 0 | None |

Tablet in Blocksy is 690px to 999px; mobile is under 690px.

**Fonts set elsewhere.** Each is a field in the Customizer. `S`

| Where | Field | Value |
|---|---|---|
| Header, Menu element | Font | Inter 700, 16px, -0.02em, no uppercase |
| Header, Text element (announcement) | Font | Inter 500, 12px |
| WooCommerce, Single Product, Product Elements | Title Font | Barlow Condensed 700, 38px, 34px, 30px, line height 1.05, uppercase |
| Same | Price Font | Inter 400, 20px, line height 28px |
| Same | Breadcrumbs Font | Inter 400, 14px |
| WooCommerce, Product Archives, Card Options, Title | Font | Inter 500, 15px, line height 1.3, no uppercase |
| Same, Price | Font | Inter 400, 15px |

Portland Gear tracks headings at -0.05em. That suits mixed case Helvetica, not condensed capitals, and Inter is wider, so -0.02em at 16px is as tight as it should go.

## 3. Colors

**Customize, Colors, Global Color Palette.** Blocksy's defaults already point text, links, buttons, borders, headings, and the site background at these palette slots, so setting the palette does most of the work. `S`

| Slot | Blocksy uses it for | Value |
|---|---|---|
| Color 1 | Buttons, links, focus, accents | #0E0E0E |
| Color 2 | Button and link hover | #303030 |
| Color 3 | Base text | #0E0E0E |
| Color 4 | Headings | #0E0E0E |
| Color 5 | Borders | #DBDBDB |
| Color 6 | Light fills | #EBEBEB |
| Color 7 | Site background (some installs point it at Color 8; both are white) | #FFFFFF |
| Color 8 | White | #FFFFFF |

**Customize, WooCommerce, General.** `S` for the fields.

| Panel | Setting | Value |
|---|---|---|
| Messages, Success | Text, Background | #67672B on #E4E4D5 |
| Messages, Info | Text, Background | #A0563F on #F5ECE9 |
| Messages, Error | Text, Background | #AE2525 on #F3CCCC |
| Messages, all three | Button text, Button background | #FFFFFF on #0E0E0E |
| Product Badges, Sale Badge | Text, Background | #FFFFFF on #CB2B2B (Archive store only) |
| Product Badges, Out of Stock Badge | Text, Background | #0E0E0E on #D9D9D9 |
| Store Notice | Font, Background | #FFFFFF on #0E0E0E |

The message colors are the reference values with the text darkened to pass contrast. See section 9.

## 4. Buttons, form fields, layout

**Customize, General, Buttons.** `S`

| Setting | Value |
|---|---|
| Min Height | 46px |
| Font Color | #FFFFFF, hover #FFFFFF |
| Background Color | Color 1, hover Color 2 (the defaults) |
| Border | None |
| Padding | 0 24px |
| Border Radius | 60px |
| Hover Effect | None |

**Customize, General, Form Elements.** `S`

| Setting | Value |
|---|---|
| Form type | Classic (the radius setting only shows for Classic) |
| Height | 46px |
| Border Size | 1px |
| Border Radius | 60px. The CSS file keeps textareas at 16px |
| Font | Inter 400, 16px (under 16px makes iOS zoom on focus) |
| Font Color | #0E0E0E |
| Border Color | #DBDBDB, focus #0E0E0E |
| Background Color | #FFFFFF |

**Customize, General, Layout.** Maximum Site Width 1360px. Content Edge Spacing is relative to screen width, not pixels: 1.5 desktop, 3 tablet, 5 mobile, which is about 20px on a 1280px laptop and on a 390px phone. Wider screens are governed by the 1360px cap. `S`, applied in the first Chrome run

**Customize, General, Breadcrumbs.** Breadcrumbs Source: Default (a Yoast or Rank Math source bypasses the store crumb). Home Page Text "OWUnion". Shop Page in Breadcrumbs off. Single Page/Post Title off. Archive Taxonomy Title on. With the store status plugin 0.2.0, product pages read OWUnion / Swim Alcatraz / Hoodies. `S`

## 5. Header and footer

**Customize, Header** (the header builder). `S` for the elements.

1. **Top row, the announcement bar.** Height 43px, background #0E0E0E. One Text element, centered: "Free shipping on orders over $100 to the lower 48 states and DC." (spec section 6, item 9). Inter 500, 12px, white, underlined link.
2. **Main row.** Height 72px, white, no border or shadow.
   * Left: Logo. Blocksy sizes logos by height: Logo Height 49px desktop and tablet, 39px mobile, which gives 140px and 112px wide (the logo is 2.87 times wider than tall)
   * Middle: Menu, Inter 700 16px, no uppercase, active item marked by an underline indicator in #0E0E0E
   * Right: Search, Account, and Cart, icon only, about 20px
3. **Mobile.** Trigger left, logo center, cart right. The off canvas panel holds the Mobile Menu.

**Customize, Footer** (the footer builder). Rows on #0E0E0E with white text, links at 65 percent white (#FFFFFFA6), Inter 14px. Widget areas:

* An Image block with the logo, Additional CSS class `owu-logo-reversed`, which turns the black JPG white with no visible box. Use it until reversed vector logos exist
* A Shortcode block with `[owunion_active_stores layout="list" exclude="owunion"]` from the store status plugin, so hidden stores drop out
* Help links (Shipping, Returns, Size guide, Contact) and Legal links (Privacy, Terms)
* Bottom row Copyright element: replace Blocksy's default, which credits the theme maker, with "© {current_year} Pacific Open Water Swim Co."

Row by row prompts for Claude in Chrome: `blocksy/claude-in-chrome-header-footer.md`.

**Per store headers (decided 2026-10-06, decision 004; replaces spec section 5.3).** Blocksy Pro can keep several headers and pick one by display conditions. `K` for the multiple header screen. The conditions engine is in Companion and supports exactly what the spec needs. `S`

* "Product Brands" matches any store page (`is_tax('product_brand')`)
* "Taxonomy ID", set to one brand, matches that store's page
* "Single Product with Taxonomy ID" matches products in that store

Cart, checkout, and account match none of these, so they keep the parent header, as spec rule 2 requires. This removes the open question in the spec about whether Elementor offers a Product Brand condition. It also keeps one header system instead of two.

## 6. Product page

**Customize, WooCommerce, Single Product.**

| Panel | Setting | Value | Label |
|---|---|---|---|
| Page Title | Product Title switch | Off. Otherwise the title and breadcrumbs show twice, once above the product and once in the summary | `S` |
| Product Gallery | Gallery type | Stacked (Pro). Two columns if your version offers it, otherwise one | `K` |
| Product Gallery | Container Width | 55% | `S` |
| Product Gallery | Image Ratio | 1:1 | `S` |
| Product Gallery | Lightbox | On | `S` |
| Product Elements | Sticky Container | On. The summary column stays in view while the gallery scrolls | `S` |
| Product Elements | Order | Breadcrumbs, Title, Price, Add to Cart, Additional Info, Divider, Short Description | `S` |
| Product Elements | Star Rating, Payment Methods, Meta, second Divider | Off for now | `S` |
| Product Tabs | Type | Type 3, the accordion. Module Placement: Summary, so it sits in the right column like the reference's accordions. First Tab Expanded off. Title font Inter 700, 16px, -0.02em | `S` |
| Related and Upsells | Module | On (spec functionality 15). Module Title Font Barlow Condensed 700, uppercase, 26px desktop, 20px mobile | `S` |
| Add to Cart | Button Width | 100% | `S` |
| Add to Cart | Button Height | 58px desktop and tablet, 52px mobile | `S` |
| Additional Info | Title, Items | No title (clear the default "Reasons to Buy"). One line for now, truck icon: "Free shipping on orders over $100 to the lower 48 states and DC." The returns line follows in v1.1 (1.1-06) | `S` |

**Swatches and size pills.** Blocksy Pro's WooCommerce extension has variation swatches: Color as round color swatches, Size as button swatches. Use it instead of a separate swatch plugin. `K`

**Description copy rule.** Bold lead sentence, bullets, bold closing line.

## 7. Shop and store archives

**Customize, WooCommerce, Product Archives.** `S` for the fields.

| Setting | Value |
|---|---|
| Cards type | The plainest type, image on top and text below |
| Columns | 4 desktop, 3 tablet, 2 mobile |
| Columns Gap, Rows Gap | 20px, 32px |
| Card image ratio | 1:1, set inside Card Options, Product Image layer, Image Ratio |
| Card Add to Cart | Off, for a clean grid like the reference |
| Card title font, price font | Section 2 |

The column counts are an OWUnion choice. The dissection covered only the product page.

## 8. Additional CSS

Paste `blocksy/owunion-additional.css` into Appearance, Customize, Additional CSS. Four items, `T` against Blocksy 2.1.58:

1. Pill fields get 20px side padding so text clears the curve. Textareas keep a 16px radius
2. Product photos sit on #EBEBEB, so transparent or off size images still read as tiles
3. The `owu-logo-reversed` class for the footer logo
4. A commented template for per store accents. Blocksy builds its button and link colors from palette Color 1 on `:root`, so a store override must also sit on `:root`, using `:root:has(> body.store-{slug})`. Tested: a store override recolors buttons on that store's pages only

## 9. Contrast fixes to the reference

| Pair | Reference | Ratio | OWUnion | Ratio |
|---|---|---|---|---|
| Success text on #E4E4D5 | #808036 | 3.23 | #67672B | 4.61 |
| Warning or info text on #F5ECE9 | #AD5D44 | 4.08 | #A0563F | 4.63 |
| Error text on #F3CCCC | #CB2B2B | 3.65 | #AE2525 | 4.63 |
| Orange accent on white | #FF8922 | 2.37 | not used | |

Ink on white is 19.3:1; WCAG AA needs 4.5:1 for body text. Blocksy shows the compare at price at 70 percent of the text color, which works out to about 7:1 on white, so the reference's #777777 problem does not arise. Blocksy's default link style underlines links in body text, which matters because link color equals text color.

## 10. Order of work, Chrome versus MCP

**Rollback first.** Create a WP Engine backup point. Then work in the Customizer and use Save Draft, not Publish, until the preview looks right. The Customizer previews every change before it goes live, which makes it safer than Elementor Site Settings.

| Step | Work | Tool |
|---|---|---|
| 1 | Local Google Fonts extension | By hand, one switch |
| 2 | Palette, typography, buttons, forms, layout (sections 2 to 4) | Claude in Chrome |
| 3 | WooCommerce messages and badges (section 3) | Claude in Chrome |
| 4 | Additional CSS | By hand. The Customizer code box auto closes braces, so typed CSS comes out mangled |
| 5 | Header and footer builder (section 5) | By hand, or Chrome one row at a time |
| 6 | Product page and archives (sections 6 and 7) | Claude in Chrome |
| 7 | Stacked gallery type and swatches (Pro) | By hand, after Chrome reports the options it found |

**Use Claude in Chrome for the Customizer passes.** The work is a fixed list of fields, done once, with a live preview to check. The prompt is in `blocksy/claude-in-chrome-customizer.md`. It records each value before changing it and saves as a draft.

**Save the MCP server for the catalog.** It suits repeated, structured writes: the read only site audit (spec section 7, step 1), then brands, categories, attributes, and draft products. To connect it, add it at claude.ai, Customize, Connectors, then start a new session.

## 11. What carries over from Portland Gear

| Portland Gear | OWUnion on Blocksy |
|---|---|
| Neue Helvetica Pro 400, 500, 700 | Inter 400, 500, 700 |
| Bold headings, sentence case, -0.05em | Barlow Condensed 700 uppercase on H1, H2, and product titles. Inter 700 elsewhere |
| #0E0E0E on white | Same. The logo black samples at #0A0A0A, visually identical |
| Orange accent #FF8922 | None on the parent. Each store gets one later |
| Pill buttons and inputs | Same, through Customizer settings |
| Square photos on #EBEBEB | Same, card and gallery ratio 1:1 |
| 43px announcement bar, 72px header | Header builder top row and main row |
| Stacked gallery, sticky details, full width Add to Cart, trust badges | Pro stacked gallery, Sticky Container, Button Width 100%, Additional Info |
| 17 apps, Shop Pay, chat, size finder, A/B tests | Not carried over |
