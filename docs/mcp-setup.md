# MCP setup for a read only staging audit

Purpose: connect Claude Code on Bryan's desktop to the OWUnion STAGING site over MCP, to run the read only audit in spec section 7, step 1 (see `OpenWaterUnion-Store-Stack-and-Design-Spec.md`, section 5.4).

Checked on 2026-10-05 against vendor pages read directly. Each step is labeled Verified (with source) or Unverified. Vendor docs are in developer preview and change often, so recheck before each use.

## Corrections to earlier notes

The earlier session worked from search summaries. Three items were wrong or incomplete.

1. WooCommerce MCP does not use a REST API key or an `X-MCP-API-Key` header on the current path. It uses a WordPress username and an Application Password, through the shared MCP Adapter endpoint. Only the deprecated `/wp-json/woocommerce/mcp` endpoint uses WooCommerce REST API keys, and Woo says not to use it for new work. The `X-MCP-API-Key` header name appears nowhere on the Woo page. Source: https://developer.woocommerce.com/docs/features/mcp/
2. Layers 1 and 3 share one server. WooCommerce registers its abilities on the WordPress MCP Adapter default server. One `claude mcp add` registration covers both. Source: same page.
3. Remote WooCommerce and Adapter access needs a local proxy, `@automattic/mcp-wordpress-remote`, run through `npx`. Source: same page.

## Rules for this audit

* Staging only. Never register the production URL.
* One least privilege user on staging, Shop Manager role, named `claude-audit`.
* Never put secrets in files or in this repo. Application Passwords go in the Claude Code MCP config on the desktop only, and nowhere else.
* Read first. Inventory before any write.
* Revoke every Application Password when the audit ends.

Role caveat. Shop Manager can create and edit products and orders, so the role does not enforce read only. Layer 2 (Elementor) states the agent works within the user's role. The abilities Woo lists include create, update, and delete for products and orders. Enforce read only in Claude Code instead (step 6). Source for abilities: https://developer.woocommerce.com/docs/features/mcp/ (Verified). The conclusion that the role alone is not enough is our inference from that list.

## Before you start

| Item | Status | Source or note |
|---|---|---|
| Staging site on HTTPS, non Plain permalinks | Verified | Woo page, "Remote Sites Using HTTP", https://developer.woocommerce.com/docs/features/mcp/ |
| WordPress 6.8 or higher | Verified | https://elementor.com/help/how-to-connect-elementor-to-an-ai-tool-using-mcp/ |
| Elementor Core and Pro 4.3.0 or higher | Verified | same Elementor page |
| Node.js on the desktop, so `npx` runs | Verified, implied | Woo page runs the proxy through `npx`. The version needed is not stated. |
| Claude Code installed on the desktop | Not covered here | |
| Staging reachable from the desktop (no IP allowlist or basic auth blocking `/wp-json/`) | Unverified | Check in the WP Engine User Portal. Not found in any page read. |

## Step 1: Create the audit user on staging

Do this in staging wp-admin.

1. Users > Add New. Username `claude-audit`, role Shop Manager. Use a dedicated email. Unverified: the exact screen labels were not checked on WP Engine, this is standard WordPress.
2. Log in as, or edit, that user. Open Users > Profile. Under Application Passwords, enter the name `claude-code-audit`, click Add New Application Password, and copy the password. WordPress shows it once.
3. Keep it in a password manager. Do not paste it into a file in this repo.

Verified: dedicated user with minimum capabilities, and the Application Password steps. Source: https://developer.woocommerce.com/docs/features/mcp/ ("WordPress Application Password Requirements").

## Layer 1: WordPress MCP Adapter

What it is. The core MCP server for WordPress. The default server lives at `/wp-json/mcp/mcp-adapter-default-server`. It supports HTTP and STDIO through WP-CLI. Abilities are private unless `meta.public` (or `meta.mcp.public`) is true. Verified. Source: https://github.com/WordPress/mcp-adapter

Step 2. Confirm the adapter is present on staging.

* Woo bundles the adapter and starts it when the `mcp_integration` feature flag is on (Layer 3). Verified. Source: https://developer.woocommerce.com/docs/features/mcp/
* A standalone adapter plugin also exists on wordpress.org. Its install steps were not read. Unverified. Do not install it until Layer 3 is tried, to avoid two copies.

Step 3. Register the server in Claude Code (HTTP through the proxy).

Verified command shape, from https://developer.woocommerce.com/docs/features/mcp/ (replace the placeholders, run it on the desktop, and type the password only into your own shell):

```
claude mcp add \
  --env WP_API_URL=https://STAGING-HOST/wp-json/mcp/mcp-adapter-default-server \
  --env WP_API_USERNAME=claude-audit \
  --env WP_API_PASSWORD='PASTE-APPLICATION-PASSWORD' \
  owunion_staging \
  -- npx -y @automattic/mcp-wordpress-remote@latest
```

Notes.

* The command stores the password in your local Claude Code config. Keep that file out of git.
* `claude mcp add` flag order and `--scope` choices for your installed version: Unverified. Run `claude mcp add --help`.
* STDIO through WP-CLI (`wp mcp-adapter serve --server=mcp-adapter-default-server --user=...`) is Verified (https://raw.githubusercontent.com/WordPress/mcp-adapter/trunk/docs/guides/cli-usage.md) but needs WP-CLI and the site files on the same machine. It does not fit a desktop talking to WP Engine staging. SSH tunnelling to WP Engine WP-CLI was not checked. Unverified.

Step 4. Check the connection.

* In Claude Code, run `/mcp` and confirm `owunion_staging` is connected.
* Ask Claude to list the available tools. The default server exposes three meta tools: `mcp-adapter/discover-abilities`, `mcp-adapter/get-ability-info`, `mcp-adapter/execute-ability`. Verified. Source: https://raw.githubusercontent.com/WordPress/mcp-adapter/trunk/README.md
* If no abilities show beyond core, that is expected until Layer 3 or Layer 2 registers more.

Required WordPress role for the default server: any authenticated user with the `read` capability. Verified. Source: Woo page.

## Layer 2: Elementor MCP (official)

All items in this section are Verified. Sources: https://elementor.com/help/how-to-connect-elementor-to-an-ai-tool-using-mcp/ (updated September 18, 2026) and https://elementor.com/help/how-to-build-and-edit-your-site-using-elementor-mcp/ (updated September 30, 2026).

Menu path. Elementor > Elementor MCP in wp-admin. Also reachable from Elementor > Editor > Elementor MCP in the editor menu. Confirmed.

Requirements. Elementor Core 4.3.0 or higher, WordPress 6.8 or higher. Pro 4.3.0 or higher exposes more tools (forms, popups, loops, ecommerce widgets, Theme Builder). Angie, a free plugin from WordPress.org, adds further tools, including WP Admin customization, bulk content actions, and a Super Admin mode. Angie is optional. For a read only audit, leave Angie off, since its extra tools are write oriented.

Required user role. Elementor does not name a role. The agent inherits the permissions of the WordPress user who authorizes. Confirmed. This corrects the earlier assumption of an administrator only screen: the pages read state no role requirement for the screen. Whether a Shop Manager can open the Elementor > Elementor MCP screen is Unverified. Test it. If the screen is hidden, an administrator can generate the prompt, but that creates an Application Password on the administrator account, which breaks least privilege. In that case stop and decide with Bryan before continuing.

Steps.

1. Log in as `claude-audit`. Go to Elementor > Elementor MCP.
2. Click Enable MCP access.
3. Choose the Claude Code tab.
4. Check the acknowledgment ("I understand that clicking Generate Prompt will create an application password for this site").
5. Click Generate Prompt, then Copy prompt.
6. Paste it into Claude Code on the desktop, as the page directs.

The exact `claude mcp add` command for Elementor is generated by the plugin and was not published on the help pages. Do not guess it. Use the generated prompt. Inspect it before running, and confirm the host is the staging host.

Revoke. Users > Profile > Application Passwords > Revoke on the entry (for example "Elementor MCP – Claude Code"). To turn it off for the whole site, use Turn off MCP access at the bottom of Elementor > Elementor MCP.

Note on the setting. Enabling MCP access is a write to staging settings. Do it, but record it in the audit notes.

## Layer 3: WooCommerce MCP (developer preview)

Source for this section: https://developer.woocommerce.com/docs/features/mcp/ . Status: developer preview, details may change. Verified.

Step 5. Enable the feature.

* Verified methods. A code filter, or WP-CLI:
  `wp option update woocommerce_feature_mcp_integration_enabled yes`
  The filter is `add_filter( 'woocommerce_features', ... $features['mcp_integration'] = true ... )`.
* Settings > Advanced > Features toggle: Unverified. The Woo page does not mention it. Look for an "MCP" toggle there on staging. If it is absent, ask Bryan before using the filter or WP-CLI, since both change staging code or options and WP Engine SSH access is needed for WP-CLI. Unverified: whether the toggle exists in the installed Woo version.

Step 6. Connect.

* Endpoint: `https://STAGING-HOST/wp-json/mcp/mcp-adapter-default-server`. Verified. This is the same server as Layer 1, so the registration in Layer 1 step 3 is all you need. Run `/mcp` again and discover abilities.
* Local proxy needed: Yes, for a remote HTTP site. `@automattic/mcp-wordpress-remote` through `npx`. Verified.
* Authentication: WordPress username and Application Password, not a WooCommerce REST API key and not the account password. Verified. HTTPS required. Verified.
* Deprecated endpoint `/wp-json/woocommerce/mcp` uses WooCommerce REST API keys. Verified. Do not use it. If you ever must, the header name is Unverified, and the earlier `X-MCP-API-Key` name could not be confirmed.

Abilities Woo registers. Product list, create, update, delete. Order list, update status, add notes. Verified. Data privacy: orders and customers can expose personal data. On staging, check whether the data is real customer data. If it is, stop and ask Bryan before reading orders.

Enforcing read only in Claude Code. Do this before the first prompt.

1. Run `/mcp` and list the tool names the server exposes. Tool name prefixes follow the pattern `mcp__<server>__<tool>`. The exact names for your install are Unverified until listed.
2. In Claude Code project or user settings, add permission deny rules for any tool that creates, updates, or deletes, and for `mcp-adapter/execute-ability` calls that target write abilities. Allow only list and read tools. Rule syntax: check `/permissions` in your installed version. Unverified.
3. In the first prompt, say "read only, do not create, update, or delete anything."

## WP Engine's own MCP servers

Smart Search AI MCP server. Verified. Sources: https://wpengine.com/mcp-server/ and https://developers.wpengine.com/docs/smart-search/mcp

* Scope: search and fetch of indexed, published WordPress content through the Managed Vector Database. Two tools only, `search` and `fetch`. It does not manage the site, hosting, installs, backups, plugins, or WooCommerce settings.
* Needs: a Smart Search AI subscription and MCP enabled in the WP Engine User Portal. Whether OWUnion has this: Unverified.
* Claude Code command (Verified, from the developer docs, with your own URL and token from the User Portal):

```
claude mcp add --transport http search_mcp "{your-mcp-url}" --header "Authorization: Bearer {your-mcp-token}"
```

* Use in this audit: not needed. Skip it unless you want content search.

Official WP Engine account API MCP server. Not found. The WP Engine pages read describe only the Smart Search AI MCP, and the developer docs index lists no hosting or account MCP. Treat as: none confirmed. Unverified negative, since absence on those pages does not prove none exists. A community project exists, `github.com/jpollock/wpengine-mcp-ts`, described in its README as a sample server for the WP Engine API, using `WP_ENGINE_API_USERNAME` and `WP_ENGINE_API_PASSWORD` from the Portal's API Access page. It is a personal repository, not published by WP Engine. Do not use it for this audit. It also covers write actions such as backups and cache purges.

Not completed: a search of the `github.com/wpengine` organization for an official MCP repository. The GitHub API call failed in this session. Unverified.

## Audit run order

1. Layer 1 and 3 connected. Discover abilities. Record the list.
2. Layer 2 connected. Record the tools it exposes.
3. Read only inventory: WordPress, Woo, Elementor versions, theme, active plugins, Brands availability, existing products, categories, attributes, pages, Theme Builder templates, shipping zones, payment gateways.
4. Write the inventory to `docs/` with no secrets, no customer data, and no Application Passwords.
5. Revoke all Application Passwords created for `claude-audit`. Delete the user if not needed.

## Open items for Bryan

* Confirm Shop Manager can open Elementor > Elementor MCP, or choose another approach.
* Confirm the Settings > Advanced > Features MCP toggle exists on staging.
* Confirm staging has no real customer data, or approve limits on order access.
* Confirm whether Smart Search AI is licensed.
* Confirm Claude Code permission rule syntax on the installed version.

## Source list

* https://developer.woocommerce.com/docs/features/mcp/ (read 2026-10-05)
* https://github.com/WordPress/mcp-adapter and https://raw.githubusercontent.com/WordPress/mcp-adapter/trunk/README.md (read 2026-10-05)
* https://raw.githubusercontent.com/WordPress/mcp-adapter/trunk/docs/guides/cli-usage.md (read 2026-10-05)
* https://elementor.com/help/how-to-connect-elementor-to-an-ai-tool-using-mcp/ (read 2026-10-05)
* https://elementor.com/help/how-to-build-and-edit-your-site-using-elementor-mcp/ (read 2026-10-05)
* https://wpengine.com/mcp-server/ (read 2026-10-05)
* https://developers.wpengine.com/docs/smart-search/mcp (read 2026-10-05)
* https://github.com/jpollock/wpengine-mcp-ts (read 2026-10-05, community, not official)
