# OWUnion Store

Naming: the store is now **OWUnion**. OpenWaterUnion, Open Water X, and openwaterx are synonyms from earlier work, kept as written. See `docs/NAMING.md`.

WooCommerce store for OpenWaterUnion.com on WP Engine: one site, one cart, eight branded stores (core Brands), four product types, Blocksy theme with per store headers by Blocksy conditions.

## Start here

1. `docs/OpenWaterUnion-Store-Stack-and-Design-Spec.md`, the main spec (v0.3)
2. `docs/decisions/003-store-model-brands-status-and-relay-year.md` and `004-blocksy-theme-and-conditional-store-headers.md`
3. `docs/theme/v1.1-changes.md`, open theme changes after v1.0
4. `docs/sessions/2026-10-05-openwaterunion-woocommerce-poc-planning.md`, next steps

## Layout

* `docs/`, spec and PoC plan
* `docs/decisions/`, decision records 002 to 004
* `docs/theme/`, theme change lists after v1.0, starting with `v1.1-changes.md`
* `docs/sessions/`, planning session notes
* `docs/reference/`, merchandise strategy, Portland Gear theme spec, and its application to the Blocksy theme (`portlandgear-to-blocksy.md`)
* `blocksy/`, Additional CSS and Claude in Chrome prompts for the Blocksy Customizer, header, and footer
* `woocommerce/`, Claude in Chrome prompts for stores (Brands), categories, attributes, and test products
* `assets/`, logo (JPG only, vectors pending)
* `plugin/`, store status plugin, Active and Hidden (spec section 3)

## Status

Planning complete to the point of site access. Next: read only audit of the WP Engine site through the MCP server, then build on staging per spec section 7.

Origin: planned in `AltosPacific-BT/altosx-business`, branch `claude/epic-bohr-65kae8`. Wiki links in the docs (`[[...]]`) point to that vault.
