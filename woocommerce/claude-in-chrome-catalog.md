# Claude in Chrome prompts: OWUnion stores, categories, attributes, test products

Builds spec step 5 (taxonomy) and a small slice of step 8 (test products) so the store model can be seen working: store pages, the store breadcrumb, store tiles, the Hidden switch, and the Swim Alcatraz header. Source: spec sections 1 and 2, decision 003, decision 004.

**Before you start**

* Take a WP Engine backup point. WooCommerce admin screens save immediately; there is no draft.
* Install the store status plugin 0.2.0 from `plugin/owunion-store-status/`: zip the folder, then Plugins, Add New, Upload. Claude in Chrome cannot upload files from the repo.
* Paste the updated `blocksy/owunion-additional.css` (it now styles the store tiles).

Paste the rules block with each prompt. Run prompt 1, read its report, then prompt 2, then prompt 3.

## Rules (paste with every prompt)

```
You are working in wp-admin on the site in the current tab. WooCommerce screens save immediately, so:
1. Read before you write. If something listed here already exists, do not create a duplicate and do not change it; report it and continue.
2. Never delete, trash, or bulk edit anything. Never change existing products, orders, pages, or settings that this prompt does not name.
3. Report every item you create or change: screen, item, field, old value, new value.
4. If a screen or field is missing or named differently, skip it, list it at the end, and do not guess.
5. Stop and ask me before anything that would publish to the public, delete, import, or change a payment, shipping, or tax setting.
```

## 1. Inventory (read only)

```
Read only. Change nothing. Report:
1. WordPress, WooCommerce, and Blocksy versions (Dashboard, Updates, or Plugins and Themes screens).
2. Whether Products, Brands exists. List every brand with its slug and product count.
3. Whether the plugin "OWUnion Store Status" is active, and its version.
4. Every product category with its slug, parent, and product count, and which one is the default category.
5. Every attribute in Products, Attributes, with its slug, type, whether archives are enabled, and its terms. If the screen offers a swatch type option (Blocksy Pro), list the choices.
6. The number of products by status (published, draft, private).
7. Settings, Permalinks: the product base, product category base, and brand base.
8. Whether any public domain other than *.wpenginepowered.com loads this site, if you can tell from Settings, General.
```

## 2. Build

```
Create only what does not already exist. Use these exact names and slugs.

Step 1, stores. Products, Brands. Leave descriptions empty.
| Name | Slug | Store status |
| OWUnion | owunion | Active |
| Pacific Open Water Swim Co. | pacific-open-water-swim-co | Hidden |
| Swim Alcatraz | swim-alcatraz | Active |
| Swim Tahoe | swim-tahoe | Hidden |
| Alcatraz Swim Club | alcatraz-swim-club | Hidden |
| Marathon Swim Club | marathon-swim-club | Hidden |
| 24 Hour Relay SF | 24-hour-relay-sf | Hidden |
| Archive | archive | Hidden |
Set Store status on each brand's edit screen (field added by the OWUnion Store Status plugin). If the field is missing, stop and tell me.
If the Media Library holds an image clearly made for a store, set it as that brand's thumbnail and tell me which; otherwise leave thumbnails empty.

Step 2, product types. Products, Categories:
| Name | Slug | Parent |
| Hoodies | hoodies | none |
| Swim Caps | swim-caps | none |
| Tee Shirts | tee-shirts | none |
| Stickers | stickers | none |
| Individual Stickers | individual-stickers | Stickers |
| Sticker Packs | sticker-packs | Stickers |
Leave Uncategorized and the default category setting as they are.

Step 3, attributes. Products, Attributes:
| Name | Slug | Enable archives | Terms |
| Color | color | off | Black |
| Size | size | off | S, M, L, XL, 2XL, in that order |
| Event year | event-year | on | 2026 |
If a swatch type option exists, set Color to color swatches (Black is #0E0E0E) and Size to buttons.

Step 4, permalinks. Settings, Permalinks: confirm the product base is /product/, the product category base is product-category, and the brand base is brand. Change only an empty field; report any other value without changing it.

Step 5, test products. Visibility Private on every one, so only logged in staff can see them. Use images already in the Media Library if one clearly fits; otherwise no image.
a. "TEST Swim Alcatraz Escape Hoodie". Brand Swim Alcatraz. Category Hoodies. Variable product: Color Black, Size S, M, L, XL, 2XL, both used for variations. One variation per size, price $89, SKU OWU-ALC-HD-ESC-BLK-S, -M, -L, -XL, -2XL. Parent SKU OWU-ALC-HD-ESC.
b. "TEST Swim Alcatraz Swim Cap". Brand Swim Alcatraz. Category Swim Caps. Simple, $25, SKU OWU-ALC-CP-ESC-BLK.
c. "TEST Swim Alcatraz Sticker Pack". Brand Swim Alcatraz. Category Sticker Packs. Simple, $1, SKU OWU-ALC-ST-PACK.
d. "TEST OWUnion Tee". Brand OWUnion. Category Tee Shirts. Variable: Color Black, Size S to 2XL, price $28, SKUs OWU-OWU-TE-LOGO-BLK-S and so on. Parent SKU OWU-OWU-TE-LOGO.
Short description for each: "Test product for the store build. Not for sale."
Stock management off. Leave shipping class and tax status at their defaults.

Step 6, tiles page. Pages, Add New. Title "TEST Stores". One Shortcode block: [owunion_active_stores layout="tiles" exclude="owunion"]. Visibility Private.
```

## 3. Check

```
Logged in, open each and report what you see, with the URL:
1. /brand/swim-alcatraz/ : which products show, and which header.
2. The TEST Swim Alcatraz Escape Hoodie page: the breadcrumb text exactly, the header, the size choices, and the price.
3. The TEST OWUnion Tee page: the breadcrumb text exactly.
4. The TEST Stores page: which store tiles show. Only Swim Alcatraz should, because the others are Hidden and OWUnion is excluded.
5. /product-category/hoodies/ : which products show.
Then open a private (incognito) window, logged out, and report the response for:
6. /brand/swim-tahoe/ : should be a 404, because Swim Tahoe is Hidden.
7. /brand/swim-alcatraz/ : should load, with no TEST products, because they are Private.
Change nothing in this prompt.
```

## What each check proves

| Check | Proves |
|---|---|
| 1, 5 | Stores and product types are separate axes: one product shows on its store page and its type page |
| 2, 3 | Store breadcrumb (1.1-02): "OWUnion / Swim Alcatraz / Hoodies"; parent store products skip the store crumb |
| 2 | Swim Alcatraz header (1.1-01), once prompt F of the header file has run |
| 4 | Store tiles list only Active stores, with no manual menu edits |
| 6 | The Hidden switch: a hidden store does not exist for shoppers |

Turning a store on later: set its Store status to Active on the brand screen. Its page, tiles, and menu links come back with nothing else to edit.
