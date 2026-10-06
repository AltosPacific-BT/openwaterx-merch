# Claude in Chrome prompt: OWUnion Blocksy Customizer

Use on WP Engine staging, logged in to wp-admin, after you create a WP Engine backup point. Paste everything below the line into Claude in Chrome. When it finishes, review the preview, paste `owunion-additional.css` into Additional CSS yourself, then Publish. Source: `docs/reference/portlandgear-to-blocksy.md`, sections 2 to 4, 6, and 7.

---

You are applying theme settings to a WordPress site running WooCommerce and the Blocksy theme with Blocksy Companion Pro. Work only in Appearance, Customize, on the site in the current tab.

Rules:

1. Before changing any field, write down its current value. Report every change as: panel, field, old value, new value.
2. Change only the fields listed here. Do not touch the header builder, footer builder, widgets, menus, pages, products, plugins, or Elementor.
3. Never click Publish. After each numbered step, use the arrow next to Publish and choose Save Draft, then confirm the draft saved.
4. If a field is missing or named differently, skip it and list it at the end. Do not guess.
5. Stop and ask me if anything asks to publish, delete, reset, import, or update a plugin.

Step 1, colors. Colors, Global Color Palette:
Color 1 #0E0E0E, Color 2 #303030, Color 3 #0E0E0E, Color 4 #0E0E0E, Color 5 #DBDBDB, Color 6 #EBEBEB, Color 7 #FFFFFF, Color 8 #FFFFFF.
Then check Global Colors still point to the palette: Base Text Color 3, Links Color 1 and hover Color 2, Borders Color 5, All Headings Color 4, Site Background Color 7. Report any that do not.

Step 2, typography. General, Typography:
* Base Font: Inter, weight 400, size 16px, line height 1.4, letter spacing 0, transform none
* H1: Barlow Condensed, 700, size 48px desktop, 40px tablet, 30px mobile, line height 1, letter spacing 0, transform uppercase
* H2: Barlow Condensed, 700, size 26px desktop, 23px tablet, 20px mobile, line height 1.05, letter spacing 0, uppercase
* H3: Inter, 700, 16px on all devices, line height 1.25, letter spacing -0.02em, transform none
* H4: Inter, 700, 18px desktop, 16px tablet, 14px mobile, line height 1.25, letter spacing -0.02em
* H5: Inter, 700, 24px desktop, 20px tablet, 16px mobile, line height 1.2, letter spacing -0.02em
* H6: Inter, 700, 16px on all devices, line height 1.25, letter spacing -0.02em
* Buttons: Inter, 500, 16px, line height 1, letter spacing 0, transform none

Step 3, buttons. General, Buttons: Min Height 46px, Font Color #FFFFFF initial and hover, Background Color Color 1 initial and Color 2 hover, Border none, Padding 0 top and bottom and 24px left and right, Border Radius 60px, Hover Effect none.

Step 4, form fields. General, Form Elements: type Classic, Height 46px, Border Size 1px, Border Radius 60px, Font Inter 400 16px, Font Color #0E0E0E, Border Color #DBDBDB initial and #0E0E0E focus, Background Color #FFFFFF initial and focus.

Step 5, layout. General, Layout: Maximum Site Width 1360px. Content Edge Spacing 2vw desktop, 3vw tablet, 5vw mobile.

Step 6, WooCommerce general. WooCommerce, General:
* Messages: Success text #67672B, background #E4E4D5. Info text #A0563F, background #F5ECE9. Error text #AE2525, background #F3CCCC. Button font #FFFFFF and button background #0E0E0E for all three
* Product Badges: Sale Badge text #FFFFFF, background #CB2B2B. Out of Stock Badge text #0E0E0E, background #D9D9D9

Step 7, product page. WooCommerce, Single Product:
* Page Title: Product Title switch off, so the title and breadcrumbs appear only in the summary
* Product Gallery: Container Width 55%, Image Ratio 1:1, Lightbox on. If a gallery type choice exists, tell me the options; do not change it
* Product Elements: Sticky Container on. Title Font Barlow Condensed 700, 38px desktop, 34px tablet, 30px mobile, line height 1.05, uppercase. Price Font Inter 400, 20px, line height 28px. Breadcrumbs Font Inter 400, 14px
* Layers, in this order and enabled: Breadcrumbs, Title, Price, Add to Cart, Additional Info, Divider, Short Description. Disable Star Rating, Payment Methods, and Meta. List any other layers you see
* Add to Cart: Button Width 100%, Button Height 58px desktop and tablet, 52px mobile

Step 8, shop. WooCommerce, Product Archives:
* Columns 4 desktop, 3 tablet, 2 mobile. Columns Gap 20px, Rows Gap 32px
* Card image ratio 1:1. Card Add to Cart off
* Card Title font Inter 500, 15px, line height 1.3, transform none. Card Price font Inter 400, 15px
* Tell me which card types are offered; do not change the type

Step 9, check. In the preview, open the shop page and one product page. Tell me the heading font, body font, button color and shape, field shape, and anything that still shows a default blue.

Finish with the full change list, the skipped fields, and the options you were asked to report.
