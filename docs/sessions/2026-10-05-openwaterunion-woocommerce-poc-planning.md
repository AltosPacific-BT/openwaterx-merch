---
title: "OpenWaterUnion WooCommerce PoC Planning"
created: 2026-10-05T00:15:00
updated: 2026-10-05T03:30:00
session_end: 2026-10-05T03:30:00
duration_minutes: 195
status: complete
type: session
author: "Bryan Temmermand"
tags: [session, planning, openwaterunion, open-water-x, woocommerce, shopify, merchandise]
ai_summary: "Drafted a 4 week WooCommerce proof of concept plan for OpenWaterUnion.com, which replaces Open Water X, with a platform scorecard, risks, and go/no-go gates. Decision 002 logged."
outcome: partial
---

# OpenWaterUnion WooCommerce PoC Planning #planning

**Date**: 2026-10-05 @ 12:15 AM PT
**Type**: Planning
**Machine**: cloud session

## Context

New project. Bryan wants a plan for an OpenWaterUnion.com online store, built as a WooCommerce proof of concept. The vault had no record of OpenWaterUnion, only the Open Water X merchandise strategy and a Shopify theme spec.

## Goals

1. Establish how OpenWaterUnion relates to Open Water X and the Shopify direction
2. Produce a PoC plan with scope, phases, risks, and gates
3. Log the brand and platform decision

## Intake answers

| Question | Answer |
|---|---|
| Relation to Open Water X | Replaces it |
| Why WooCommerce | Test it against Shopify |
| PoC success | Commerce flow works, customer demand |
| Constraints | None given, assumed 4 weeks and low spend |

## Work Done

* Reviewed the merchandise strategy, Shopify theme spec, and WP Engine context
* Wrote [[open-water-x/OpenWaterUnion-WooCommerce-PoC-Plan]] v0.1
* Wrote [[decisions/002-openwaterunion-brand-and-platform-evaluation]]
* Updated [[_meta/work-queue]]

## Follow up (same session): Bryan's answers

* Assumptions confirmed: 4 weeks, under $500
* Fulfillment: print on demand, so the "hand fulfillment" assumption is dropped
* Email list size unknown, and Bryan may not use email, so the demand test moved to reward cards with QR codes, signage, and existing web and social channels. The demand gate now measures orders against cards handed out
* Plan updated to v0.2. New risks: POD costs above the strategy's unit costs, separate parcels on mixed baskets, POD vendor may lack the specified hoodie blank or silicone caps
* Still open: swimmers served per week, why WooCommerce, Destination collections timing, cap sourcing

## Follow up 2: focus shift to stack and functionality

* Bryan redirected the work to the technology stack, design, categories, and WooCommerce functionality. Fulfillment (print on demand) does not affect site functionality, so the new spec is fulfillment agnostic
* Wrote [[open-water-x/OpenWaterUnion-Store-Stack-and-Design-Spec]]: stack, design tokens translated from the Portland Gear spec, category and attribute model, 19 row functionality map, implementation sequence
* Category model: collections as product categories, Garment as a global attribute with archives
* Corrected an error in plan v0.2: the reward coupon should be Individual use only, because free shipping is a shipping method. Free shipping minimum must ignore coupon discounts so the two stack
* Found the reference accent #FF8922 fails WCAG AA text contrast (about 2.4 to 1)
* Gaps beyond core: bulk unique reward codes, QR auto apply, free shipping progress message
* woocommerce.com was blocked from direct fetch, so several V labels rest on search result summaries. Verify in build

## Follow up 3: design, categories, and stores within the store

* Bryan deferred the reward code, chose free fonts, supplied the logo, and set product types: Hoodies, Swim Caps, Tee Shirts, Stickers
* Bryan wants several stores inside one store (department store model: one site, one cart, per store header and navigation)
* Spec rewritten: [[open-water-x/OpenWaterUnion-Store-Stack-and-Design-Spec]] now leads with the store model, taxonomy, and design
* Taxonomy decision: stores as core WooCommerce Brands (product_brand, core since 9.6), product types as categories. Replaces the earlier collections as categories and Garment attribute model
* Per store headers: swap block theme template parts with a small must use plugin using render_block_data. Cart, checkout, and account keep the parent header
* Logo is monochrome, so no accent color is needed. Fonts: Barlow Condensed headings, Inter body, shortlist to test. Logo is a JPG, so a vector, transparent PNG, and reversed version are needed. Copied to open-water-x/assets/
* Removed the Archive reward question at Bryan's request
* Assumed list of stores needs Bryan's confirmation
* Added a new store: 24 Hour Relay SF (event). Spec updated to eight stores
* Relay merchandise changes by year and stays in its store: Event year attribute, filterable. Past years stay at full price unless the Archive only sale rule changes
* Bryan wants to disable stores: added spec section 2A with three states (Active, Closed, Hidden), behavior rules, and a no code fallback. Needs custom code, size estimated at a few hundred lines. Page caching is the main trap
* Logged [[decisions/003-store-model-brands-status-and-relay-year]]

## Follow up 4: existing site, Elementor Pro, scope answers

* Bryan: two store states for launch, Closed second, no dates. Stores have their own logos and colors. Vector logos not ready. Stickers are individual and packs. The WP Engine site is up with WooCommerce, Elementor Pro, and an MCP server
* Spec v0.3: block theme plan replaced by Elementor Pro. Per store headers use Theme Builder display conditions (V for category and taxonomy conditions, K that Product Brand is a selectable condition). Custom plugin now covers only store status and the Active stores shortcode
* Local Google Fonts supported in Elementor 3.27 and later (V), so Barlow Condensed and Inter load locally
* Added sticker child categories (Individual Stickers, Sticker Packs), both simple products
* MCP server is not connected to this session. Rules: audit read only first, staging first, least privilege, rollback point
* Risk: Elementor checkout widget is classic, so Stripe wallet buttons need testing against it
* Open: store list confirmation, MCP details, live vs development site, versions and theme, sticker pack examples

## Blind spots surfaced

* "Union" in the brand name needs a trademark and confusion check
* Destination names such as Alcatraz on merchandise need a legal check
* California rules on gift certificates and promotions apply to the $15 reward, wording needs counsel review
* Sales tax nexus outside California
* Silicone caps are hard to source through print on demand
* A two week soft launch is too small to judge the 3.5 orders per week target, so the demand gate uses a rate against list size

## Assumptions to confirm

* 4 weeks and under $500 PoC budget
* Hand fulfillment during the PoC
* Proposed gate thresholds

## Scores

| Item | Score | Note |
|---|---|---|
| TCS, PoC plan v0.2 | 0.85 | Complete and linked. Below 0.9: PoC budget, timeline, and gates still rest on assumptions, vendor costs and live prices unverified |
| TCS, store spec v0.3 | 0.84 | Complete and consistent after two corrections. Below 0.9: many items marked K, woocommerce.com was blocked, site versions and Product Brand condition in Elementor unverified |
| TCS, Decisions 002 and 003 | 0.92 | Clear rationale, alternatives, and revisit triggers |
| SQS (proposed) | 3.8 of 5 | Completeness 4, Accuracy 3, Consistency 4, Linkage 4, Actionability 4. Bryan to assess |

**Outcome: partial.** Planning is done to the point where the next step needs site access and Bryan's inputs.

**Calibration:** a standard PoC plan names a hypothesis, a scorecard, and gates, which this does. Two misses worth recording: the first stack draft assumed a block theme without asking what was already deployed, and the early reward coupon rule was wrong (individual use). Both were caught and fixed in session. Practice going forward: ask what exists before specifying a stack, and verify coupon and shipping mechanics before stating them.

## Next steps

1. Bryan: connect the MCP server and name it, say whether the WP Engine site is live or development, confirm the eight stores, give sticker pack examples
2. Read only audit of the WP Engine site: versions, theme, plugins, content, staging, Brands, Product Brand condition in Elementor
3. Week 0 checks from the plan: trademark for "Open Water Union", Alcatraz name use on merchandise, vendor quotes, live prices
4. Build per spec section 7 on staging

## Context for Next Session

### Where We Stopped
* **Document**: `open-water-x/OpenWaterUnion-Store-Stack-and-Design-Spec.md`
* **State**: In progress, awaiting site access and Bryan's inputs (spec section 9)

### Key Decisions Made
| Decision | Why | Alternative Rejected |
|---|---|---|
| OpenWaterUnion replaces Open Water X | Bryan's direction | Separate brand |
| Test WooCommerce against Shopify | Cost and maintenance unproven | Commit now |
| Stores as core Brands, categories as product types | Core feature, no plugin | Stores as top level categories |
| Elementor Pro Theme Builder for per store headers | Site already runs it | Block theme with custom header code |
| Free fonts, Barlow Condensed and Inter | Bryan's choice | Neue Helvetica Pro license |

### Files to Read First Next Session
1. `open-water-x/OpenWaterUnion-Store-Stack-and-Design-Spec.md` (v0.3, the main document)
2. `open-water-x/OpenWaterUnion-WooCommerce-PoC-Plan.md` (v0.2, reward and email parts deferred or outdated)
3. `decisions/003-store-model-brands-status-and-relay-year.md`
4. `decisions/002-openwaterunion-brand-and-platform-evaluation.md`

## Git Checkpoint
* [x] Documentation committed
* [x] Pushed to `claude/epic-bohr-65kae8`

Rename this Claude session to: 2026-10-05-openwaterunion-woocommerce-poc-plan

## Tags
#session #planning #openwaterunion #merchandise

---
*Session ended: 2026-10-05 @ 3:30 AM PT*
*Active work time: ~3.25 hours*
