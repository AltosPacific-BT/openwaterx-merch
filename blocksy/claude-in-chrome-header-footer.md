# Claude in Chrome prompts: OWUnion header, footer, and Swim Alcatraz header

Paste one prompt at a time into Claude in Chrome, on staging. Check the preview after each one before pasting the next. Prompts A to E finish theme v1.0. Prompt F is theme change 1.1-01, run after A to E are published. Source: `docs/reference/portlandgear-to-blocksy.md`, sections 5 and 6, and `docs/theme/v1.1-changes.md`.

Every prompt starts with the same rules block. Paste the rules and the one prompt together.

## Rules (paste with every prompt)

```
You are editing the Blocksy theme in Appearance, Customize, on the site in the current tab. Rules:
1. Before changing a field, write down its current value. Report each change as: panel, field, old value, new value.
2. Change only what this prompt lists. Do not remove elements unless told to. Do not edit pages, products, menus, plugins, or Elementor.
3. Never click Publish. When done, use the arrow next to Publish, choose Save Draft, and confirm it saved.
4. If a field or element is missing or named differently, skip it, list it at the end, and do not guess.
5. Stop and ask me before anything that would publish, delete, reset, or import.
```

## A. Product page title and breadcrumbs

```
1. WooCommerce, Single Product, Page Title: turn the Product Title switch off. This removes the title area above the product so the title and breadcrumbs show only in the right column.
2. WooCommerce, Single Product, Product Elements: confirm Breadcrumbs is the first layer and is on.
3. General, Breadcrumbs: Breadcrumbs Source Default. Home Page Text "OWUnion". Shop Page in Breadcrumbs off. Single Page/Post Title off. Archive Taxonomy Title on.
Then open a product in the preview and tell me exactly what the breadcrumb reads.
```

## B. Header top row, the announcement bar

```
Header (the header builder), Top Row:
1. Row visible on desktop, tablet, and mobile. Row Min Height 43px. Background #0E0E0E. No top or bottom border.
2. If the Top Row already holds elements, list them and stop.
3. Add a Text element to the middle of the Top Row. Text: Free shipping on orders over $100 to the lower 48 states and DC.
4. Text element design: font Inter 500, 12px. Font color #FFFFFF. Link color #FFFFFF.
```

## C. Header main row

```
Header, Main Row:
1. Row Min Height 72px. Background #FFFFFF. No border, no shadow.
2. Left: Logo. Logo Height 49px desktop and tablet, 39px mobile. Site Title and Tagline hidden.
3. Middle: Menu. Font Inter 700, 16px, letter spacing -0.02em, no uppercase. Font color #0E0E0E, hover and active #0E0E0E. Indicator Effect: underline. If the menu has no items, tell me; do not create a menu.
4. Right: Search, Account, and Cart, icon only, about 20px, color #0E0E0E. Cart: hide the subtotal, keep the item count badge.
5. Tell me which elements were in the Main Row before you started and where each one is now.
```

## D. Mobile header

```
Header, switch the builder to Mobile:
1. Top Row: the same Text element as desktop, visible on mobile.
2. Main Row: Trigger on the left, Logo in the middle (39px), Cart on the right.
3. Off canvas panel: Mobile Menu element, using the same menu as desktop.
Report what the mobile header looked like before and after.
```

## E. Footer

```
Footer (the footer builder):
1. Every footer row: Background #0E0E0E. Text color #FFFFFF. Link color #FFFFFFA6, hover #FFFFFF. Font Inter 400, 14px.
2. Middle Row: 4 columns.
   Widget Area 1: an Image block with the Open Water Union logo from the Media Library, width 160px, Additional CSS class owu-logo-reversed.
   Widget Area 2: a Heading "Stores", then a Shortcode block: [owunion_active_stores layout="list" exclude="owunion"]
   Widget Area 3: a Heading "Help", then links to Shipping, Returns, Size guide, and Contact, only for pages that already exist. List the ones that do not.
   Widget Area 4: a Heading "Legal", then links to the Privacy Policy and Terms pages, only if they exist.
3. Bottom Row, Copyright element: replace the text with: © {current_year} Pacific Open Water Swim Co.
Report what each footer row held before you started.
```

The default Blocksy copyright line credits the theme maker, so step 3 replaces it. Confirm the company's legal name before publishing.

## F. Swim Alcatraz header (theme change 1.1-01)

Run after A to E are published, and after the Swim Alcatraz brand exists in Products, Brands.

```
1. In Products, Brands, find Swim Alcatraz. Tell me its term ID and slug. If it does not exist, stop.
2. Back in Customize, Header: open the header selector and create a new header named "Swim Alcatraz" as a copy of the current header. If the screen offers no copy or new header option, describe what you see and stop.
3. Display conditions for the Swim Alcatraz header: Include "Taxonomy ID" set to Swim Alcatraz. Include "Single Product with Taxonomy ID" set to Swim Alcatraz. Add nothing else. Leave the main header's conditions unchanged.
4. In the Swim Alcatraz header only, Main Row: remove the Logo element and put a Text element in its place. Text: Swim Alcatraz, linked to the Swim Alcatraz store page. Font Barlow Condensed 700, uppercase, 28px desktop, 22px mobile, color #0E0E0E. Do not change the Site Title in WordPress settings. Keep the Top Row identical to the main header.
5. Main Row, Menu: if a menu named "Swim Alcatraz" exists, select it; otherwise keep the current menu and tell me. Either way, tell me whether the menu has a link back to the OWUnion home page.
6. Save Draft. Then in the preview open the Swim Alcatraz store page, one Swim Alcatraz product, the shop page, and the cart. Tell me which header each one shows.
```

Expected: the store page and its product show the Swim Alcatraz header. The shop page and the cart show the main OWUnion header.
