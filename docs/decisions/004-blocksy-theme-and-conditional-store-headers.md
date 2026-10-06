---
title: "Decision 004: Blocksy Theme First, Per Store Headers by Blocksy Conditions"
created: 2026-10-06
updated: 2026-10-06
status: active
type: decision
author: "Bryan Temmermand"
tags: [decision, owunion, blocksy, elementor, headers, brands, typography]
ai_summary: "The OWUnion store runs on Blocksy with Blocksy Companion Pro. Theme settings come first, Elementor is for page content only, and each store gets its own header through Blocksy Pro conditional headers matched on the store's Brand. Barlow Condensed and Inter are confirmed."
---

# Decision 004: Blocksy Theme First, Per Store Headers by Blocksy Conditions

**Decision date:** 2026-10-06
**Builds on:** [[decisions/003-store-model-brands-status-and-relay-year]]
**Supersedes:** decision 003, item 5 (per store headers through Elementor Theme Builder)

## Decision

1. **Theme:** the site runs Blocksy with Blocksy Companion Pro. Blocksy's Customizer sets fonts, colors, buttons, forms, header, footer, shop, and product page. Additional CSS covers only what the Customizer cannot.
2. **Elementor:** page content only, such as the home page and landing pages. Its Site Settings colors, fonts, and Theme Style stay empty. No Elementor Theme Builder headers or footers.
3. **Per store headers:** Blocksy Pro conditional headers. The parent header applies everywhere. Each store header applies on that store's Brand page ("Taxonomy ID") and on products in that store ("Single Product with Taxonomy ID"). Cart, checkout, and account keep the parent header.
4. **Fonts:** Barlow Condensed for display headings and Inter for body and interface, confirmed after testing beside the logo.
5. **Theme versions:** the Portland Gear application (`docs/reference/portlandgear-to-blocksy.md`) is theme v1.0. Later changes are listed by version under `docs/theme/`.

## Rationale

* Blocksy removes WooCommerce's stylesheet and styles the store from its own settings, and it switches off Elementor's default colors and fonts. A second styling system would fight it. Confirmed in Blocksy 2.1.58 source.
* Blocksy Companion's conditions engine matches Product Brands, a single brand term, and products within a brand term. Confirmed in Companion 2.1.58 source. This answers the open question in spec 5.3 about Elementor's brand condition.
* One header system is easier to maintain than two.

## Consequences

* The store status plugin stays as is. Its `store-{slug}` body class still drives per store CSS.
* Header conditions point at brand term IDs. If a brand is deleted and recreated, its header conditions must be reset.
* The multiple header screen is a Pro feature whose code is not public. Verify it on staging with the first store before building the rest.
* Spec sections 5.2 and 5.3 describe the Elementor plan and are superseded where they conflict.

## Alternatives considered

* Elementor Pro Theme Builder headers: rejected. A second header system, and the Product Brand condition was unverified.
* A custom header swap in the store status plugin: rejected. Blocksy does it without code.

## Revisit

If Blocksy Pro is not renewed, or if the conditional header screen cannot target brand terms on staging.
