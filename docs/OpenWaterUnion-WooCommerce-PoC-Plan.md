---
title: "OpenWaterUnion.com WooCommerce Proof of Concept Plan"
created: 2026-10-05T00:30:00
updated: 2026-10-05T02:30:00
status: draft
type: project-status
author: "Bryan Temmermand"
tags: [openwaterunion, open-water-x, merchandise, woocommerce, shopify, proof-of-concept, planning]
ai_summary: "Four week plan to prove WooCommerce can run the OpenWaterUnion.com premium merchandise store, compare it to Shopify, and test demand with past swimmers, with go/no-go gates."
last_session_date: 2026-10-05
project_version: "0.2.0"
project_state: planning
one_line_summary: "OpenWaterUnion.com WooCommerce PoC: 4 week plan, platform scorecard, soft launch to past swimmers"
---

# OpenWaterUnion.com WooCommerce Proof of Concept

**State**: Planning. Plan v0.2, assumptions confirmed, four inputs still open (section 8).
**Session**: [[06-sessions/2026-10-05-openwaterunion-woocommerce-poc-planning]]
**Decision**: [[decisions/002-openwaterunion-brand-and-platform-evaluation]]
**Stack, design, categories, functionality**: [[open-water-x/OpenWaterUnion-Store-Stack-and-Design-Spec]] (fulfillment agnostic)

## 1. Purpose

OpenWaterUnion.com replaces Open Water X as the merchandise brand of Pacific Open Water Swim Co. This proof of concept answers two questions:

1. Can WooCommerce run the store as specified in [[open-water-x/Open_Water_X_Merchandise_Strategy]], with no workarounds that cost more than Shopify?
2. Do past swimmers buy at the premium prices and order volume the strategy assumes?

The PoC does not decide the final platform by itself. It produces evidence for a go/no-go decision (section 7).

## 2. Scope

**In scope**

* Catalog across several stores inside one site (WooCommerce Brands) and four product types: Hoodies, Swim Caps, Tee Shirts, Stickers. See [[open-water-x/OpenWaterUnion-Store-Stack-and-Design-Spec]]
* Cart, checkout, payments, sales tax, shipping rules
* The $15 swimmer reward: DEFERRED (2026-10-05). Design and categories come first
* Free shipping over $100, contiguous United States only
* Order emails, refunds, mobile checkout, basic analytics
* Soft launch to past swimmers

**Out of scope (deferred)**

* Warehouse or stocked inventory. Fulfillment is print on demand
* Integration with the booking system, so reward codes are issued manually in the PoC (printed on cards)
* Email marketing automation
* International shipping, wholesale, memberships, courses
* Final design polish beyond the theme spec

## 3. Starting facts from the vault

| Item | Value | Source |
|---|---|---|
| Company hoodie / tee / cap | $79 / $28 / $20 | Merchandise Strategy |
| Destination hoodie / tee / cap | $89 / $35 / $25 | Merchandise Strategy |
| Unit cost, hoodie / tee / cap | $20 / $9 / $3 | Merchandise Strategy |
| Target volume | 3.5 orders per week, 182 per year | Merchandise Strategy |
| Average order value | $110 to $120 | Merchandise Strategy |
| Contribution | About $73 per order, $13,200 per year before overhead | Merchandise Strategy |
| Existing storefront spec | Shopify, Prestige theme, Portland Gear look | [[open-water-x/portlandgear-shopify-theme-spec]] |
| Existing WordPress hosting | WP Engine (named in the MemberPress analysis) | [[20326-02-17-memberpress-plugin-analysis-sylvia]] |

Note: the $73 contribution leaves little room for subscriptions. A platform that costs $60 more per month removes about 10 percent of annual contribution. Platform cost is a scored criterion.

## 4. Platform scorecard (WooCommerce vs Shopify)

Score each 1 to 5 at the end of week 2. Weights are proposed.

| Criterion | Weight | What to test |
|---|---|---|
| Total cost, first year | 25% | Hosting, theme, plugins, payment fees, apps. Use current published prices, verified in week 0 |
| Reward mechanics | 15% | Native coupon: $75 minimum, one use per customer email, expiry, free shipping stacking |
| Tax and shipping rules | 15% | California and multi state tax, contiguous US lock, free shipping threshold |
| Fulfillment fit | 15% | Vendor plugin or app for hoodies, tees, caps |
| Maintenance load | 15% | Updates, security, backups, one person operation |
| Look and speed | 10% | Match to theme spec, mobile load time |
| Data ownership and lock in | 5% | Export, portability |

Bryan's stated reasons for testing WooCommerce are not recorded, so ownership and cost are assumed. Confirm in the review.

## 5. Phased plan (4 weeks, assumed)

### Week 0, decisions (before any build)

* [ ] Confirm brand: OpenWaterUnion replaces Open Water X. Update all vault references.
* [ ] Run a trademark and name conflict search for "Open Water Union". "Union" can read as a labor or membership body. Check state and federal records.
* [ ] Confirm whether the existing WP Engine site is the live domain or a development install, and that a staging environment exists. The PoC is not the swim booking site.
* [ ] Pick the print on demand vendor (fulfillment is POD, confirmed 2026-10-05). Check each candidate for: the Independent Trading 10 oz hoodie or an equal blank, a WooCommerce integration, shipping cost and time to the contiguous US, and whether they offer silicone caps. Order samples of the hoodie and tee before launch.
* [ ] Replace the strategy's unit costs ($20 hoodie, $9 tee, $3 cap) with real vendor quotes. Recompute contribution per order. POD costs usually run higher than bulk, so the $73 figure may fall.
* [ ] Decide the cap: source a cap from a separate supplier, or leave it out of the PoC. Without a cap the 45 percent attachment KPI cannot be tested.
* [ ] Verify current prices for WooCommerce hosting, theme, tax tool, and Shopify Basic. Do not rely on memory.
* [ ] Budget cap: under $500 before launch (confirmed 2026-10-05). Sample orders count against it.

### Week 1, build

* WordPress and WooCommerce on a clean install, SSL, backups, security hardening
* Build on the existing WP Engine site (WooCommerce and Elementor Pro) using Elementor Theme Builder. Audit it first, work on staging. Monochrome design from the OpenWaterUnion logo, free fonts (Barlow Condensed and Inter recommended). Structure follows the Portland Gear spec. Do not copy Prestige code
* Payments: Stripe, plus one wallet option
* Tax: configure origin and destination rules. Confirm nexus with an accountant
* Shipping: free over $100, flat rate below, ship to contiguous states only
* Catalog: stores as Brands, four types as categories, variants for size and color, 6 to 10 products across at least two stores
* Reward: deferred. Earlier notes: coupon of $15, $75 minimum, one use per customer, one year expiry, individual use on, free shipping minimum set to ignore coupon discounts
* Policies: shipping, returns, privacy, terms

### Week 2, test

* 20 test orders across all paths: single item, hoodie and cap, free shipping edge cases at $99.99 and $100, reward at $74.99 and $75, refund, partial refund, out of state address, Alaska and Hawaii block
* Mobile checkout on iOS and Android
* Page speed, accessibility check, email deliverability
* Fill the scorecard against a Shopify trial store built to the same spec, or against its documented features if time is short

### Weeks 3 and 4, soft launch

Email is not assumed. List size is unknown and Bryan may not use email. The PoC reaches swimmers through channels that need no list:

* **Reward card:** hand each swimmer a card with a QR code and a unique reward code after a completed swim. This matches the strategy, which distributes the $15 reward after a completed swim. The count of cards handed out is the denominator for demand. Deferred with the reward. Until it returns, use QR signage and existing web and social channels.
* **On water and dock:** QR code on signage at check in and aboard the vessel
* **Existing web and social:** banner on the swim sites, company social accounts, Google Business profile
* **Optional later:** email or text to past swimmers, only if Bryan decides to use it and the list is sized

Track:

* Cards handed out, codes redeemed, orders, average order value, hoodie share, cap attachment, free shipping share
* Store visits and visit to order rate
* Support questions and checkout drop offs

Fulfillment runs through the POD vendor integration. Place at least one real paid order end to end before launch.

Open item: the number of swimmers served in weeks 3 and 4. If it is small, extend the soft launch rather than judge demand on a thin sample.

## 6. Risks and blind spots

| Risk | Likelihood | Impact | Mitigation |
|---|---|---|---|
| Brand name conflict or confusion ("Union") | Medium | High | Search before building, week 0 |
| Destination names (Alcatraz) used on merchandise raise trademark or National Park Service questions | Medium | High | Counsel check before selling Destination items |
| California gift certificate and promotion rules: a free reward with an expiry date needs correct wording | Medium | Medium | Label as a promotional reward, state terms, counsel review. Verify current statute |
| Sales tax nexus beyond California | Medium | Medium | Accountant confirmation before launch |
| Prop 65 warning duties on apparel and silicone items | Low | Medium | Supplier documentation and a legal check |
| Sample too small to judge demand in two weeks | High | Medium | Define the demand gate as a rate against reward cards handed out, extend the soft launch if swimmer volume is low |
| POD costs and shipping exceed strategy assumptions, which cuts contribution | High | High | Replace assumed unit costs with vendor quotes in week 0 and recompute the $73 figure |
| POD multi item orders ship in separate parcels, which breaks the $6 shipping subsidy assumption | Medium | Medium | Test shipping on mixed baskets, adjust the free shipping threshold if needed |
| POD vendor lacks the specified blank or silicone caps | Medium | Medium | Check in week 0, substitute blank or add a separate cap supplier |
| Plugin maintenance and security on a one person operation | Medium | Medium | Minimal plugin list, managed hosting, backups, update schedule |
| Free reward cannibalizes margin | Low | Low | $15 on $75 or more keeps margin above 60 percent per order |
| Vendor integration eats the PoC schedule | Medium | Medium | Hand fulfill during the PoC |

## 7. Go/no-go gates

Thresholds are proposed. Bryan confirms in review.

**Technical gate (end of week 2)**

* All 20 test orders pass
* Reward and shipping rules behave exactly as specified with no custom code
* Weighted scorecard favors WooCommerce, or ties with lower first year cost

**Demand gate (end of week 4)**

* Reward redemption plus orders: at least 10 percent of swimmers handed a reward card place an order (proposed, tune after seeing swimmer volume), or 3.5 orders per week once traffic is steady
* Average order value of $110 or more
* Hoodies in 60 percent or more of orders, cap attachment of 45 percent or more (Year One targets from the strategy)

**Outcomes**

* Both gates pass: build the full store on WooCommerce, write the decision record
* Technical passes, demand fails: keep the platform, revisit pricing and audience before spending more
* Technical fails: reassess Shopify, and log why

## 8. Inputs from Bryan

**Settled (2026-10-05)**

* Assumptions stand: 4 weeks, under $500 before launch
* Fulfillment: print on demand
* Past swimmer email list: size unknown, email may not be used. Demand is tested through reward cards, QR codes, and existing web and social channels

**Still open**

1. How many swimmers does the company serve per week in the next 4 to 6 weeks? This sets the demand sample
2. Why WooCommerce: cost, ownership, WordPress fit, or other
3. Whether destination collections launch in the PoC or hold for the trademark check
4. Cap: add a separate supplier or leave it out of the PoC

## 9. Next steps

* [ ] Bryan reviews plan v0.1 and answers section 8
* [ ] Run week 0 checks
* [ ] Create the PoC site and begin week 1
* [ ] Update [[_meta/work-queue]] with outcomes
