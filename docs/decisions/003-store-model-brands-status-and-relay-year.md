---
title: "Decision 003: Stores as Brands, Store Status Switch, Relay Merchandise Stays by Year"
created: 2026-10-05T03:00:00
updated: 2026-10-05T03:30:00
status: active
type: decision
author: "Bryan Temmermand"
tags: [decision, openwaterunion, woocommerce, brands, stores, relay]
ai_summary: "OpenWaterUnion is one WooCommerce site with several stores as core Brands, four product types as categories, a per store on and off status, and 24 Hour Relay SF merchandise kept in its store by year."
---

# Decision 003: Stores as Brands, Store Status Switch, Relay Merchandise Stays by Year

**Decision date:** 2026-10-05
**Builds on:** [[decisions/002-openwaterunion-brand-and-platform-evaluation]]

## Decision

1. **Store model:** one site, one cart, one checkout, with several branded stores inside it. Stores are core WooCommerce Brands. Product types (Hoodies, Swim Caps, Tee Shirts, Stickers) are product categories.
2. **Store status:** any store can be switched off. Two states for launch (Active, Hidden), Closed built second, no open and close dates. Nothing is deleted.
3. **24 Hour Relay SF:** merchandise changes by year and stays in its own store. Designs carry an Event year attribute. Past years are not moved to Archive.
4. **Fonts:** free fonts, Barlow Condensed and Inter recommended. **Reward codes:** deferred.
5. **Platform base:** the existing WP Engine site with WooCommerce and Elementor Pro. Per store headers use Elementor Theme Builder conditions, so the custom plugin covers only store status and the Active stores list.
6. **Stores:** each store has its own logo and color. Vector assets are not ready, so the PoC uses the JPG logo and builds two stores first.
7. **Stickers:** individual stickers and sticker packs, both simple products.

## Rationale

* Core Brands avoids a plugin and keeps categories free for product type.
* A shared cart keeps checkout simple for shoppers who buy across stores.
* A status switch handles seasonal and event stores without deleting data.
* Keeping relay years in the store preserves the event identity and lets shoppers browse by year.

## Consequences

* A small custom plugin is required for store status. It must be owned and tested.
* Elementor adds page weight and builder lock in. Keep global styles and few templates.
* Sale prices are otherwise allowed only in Archive, so past relay years stay at full price unless the rule changes.
* Page caching must purge on status changes.

## Alternatives considered

* Separate installs per store: rejected, separate carts.
* Stores as top level categories with a menu plugin: rejected, core Brands does it without extra plugins.

## Revisit

After the PoC build, or if the custom plugin proves costly to maintain.
