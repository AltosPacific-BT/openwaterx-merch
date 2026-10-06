# Claude in Chrome prompt: OWUnion Elementor Site Settings

Use on WP Engine staging, logged in to wp-admin. Paste everything below the line into Claude in Chrome. When it finishes, paste `owunion-site.css` into Site Settings, Custom CSS yourself: Elementor's code editor auto closes braces, so typed CSS comes out mangled. Source: `docs/reference/portlandgear-to-elementor.md`, sections 2 to 5.

---

You are applying global styles to a WordPress site running WooCommerce, Elementor Pro, and the Hello Elementor theme. Work only on the site in the current tab.

Rules:

1. Before changing any field, write down its current value. Report every change as: panel, field, old value, new value.
2. Change only the fields listed here. Do not edit pages, templates, products, plugins, or other settings.
3. Save after each numbered step and confirm the save worked before moving on.
4. If a field is missing or named differently, skip it and list it at the end. Do not guess.
5. Stop and ask me if anything asks to publish, delete, update a plugin, or overwrite a template.

Step 0, rollback. Go to Elementor, Tools, Import/Export (or Website Templates), and export a kit with Site Settings included. Tell me the file name.

Step 1, font loading. In wp-admin, Elementor, Settings:
* Performance tab: Load Google Fonts Locally = Active
* Advanced tab: Google Fonts Load = Swap

Step 2, colors. Open any page with Edit with Elementor, then open Site Settings, Global Colors.
* System colors: Primary #0E0E0E, Secondary #303030, Text #0E0E0E, Accent #0E0E0E
* Add custom colors: OWU Paper #FFFFFF, OWU Border #DBDBDB, OWU Tile #EBEBEB, OWU Light #F5F5F5, OWU Muted #767676, OWU Sale #CB2B2B, OWU Footer Link #FFFFFFA6

Step 3, Global Fonts.
* Primary: Barlow Condensed, weight 700, transform uppercase, line height 1, letter spacing 0, size empty
* Secondary: Inter, weight 700, transform none, line height 1.25, letter spacing -0.02em, size empty
* Text: Inter, weight 400, size 16px, line height 1.4, letter spacing 0
* Accent: Inter, weight 500, size 16px, line height 1, transform none, letter spacing 0

Step 4, Theme Style, Typography.
* Body: text color = Text global, typography = Text global
* Link: normal #0E0E0E, hover #303030
* H1: custom typography, Barlow Condensed 700, uppercase, size 48px desktop, 40px tablet, 30px mobile, line height 1, letter spacing 0
* H2: custom typography, Barlow Condensed 700, uppercase, size 26px desktop, 23px tablet, 20px mobile, line height 1.05, letter spacing 0
* H3: Secondary global, size 16px, line height 1.25
* H4: Inter 700, size 18px desktop, 16px tablet, 14px mobile, line height 1.25, letter spacing -0.02em
* H5: Inter 700, size 24px desktop, 20px tablet, 16px mobile, line height 1.2, letter spacing -0.02em
* H6: Secondary global, size 16px, line height 1.25

Step 5, Theme Style, Buttons, Form Fields, Images.
* Buttons: typography = Accent global. Normal: text #FFFFFF, background #0E0E0E, border none, border radius 60px all corners, padding 16px top and bottom, 24px left and right. Hover: text #FFFFFF, background #303030
* Form Fields: label typography Inter 400 12px, label color Text global. Field typography = Text global. Normal: text #0E0E0E, background #FFFFFF, border solid 1px #DBDBDB, radius 60px. Focus: border color #0E0E0E. Padding 12px top and bottom, 20px left and right
* Images: border radius 0

Step 6, Layout. Site Settings, Layout: Content Width 1360px. Gaps 20px row and column. Leave Container Padding unchanged.

Step 7, check. Open the shop page and one product page in a new tab. Tell me: heading font, body font, button color and shape, and anything still purple, olive, or pink.

Finish with the full change list and the list of skipped fields.
