# GrowthLab Theme 03

Custom WordPress base theme built by GrowthLab SEO for client sites. It is a classic PHP theme (with a `theme.json` for editor settings) whose page sections are ACF PRO blocks, plus a Customizer-driven color scheme and font selector.

- **Theme name / text domain:** `GrowthLab Theme 03` / `growthlabseotheme03`
- **Repository:** https://github.com/GrowthLab-SEO/growthlabseotheme03
- **License:** GPL-3.0-or-later

---

> **Child themes and site building:** see [CHILD-THEMES.md](CHILD-THEMES.md) (what the theme loads from a child theme, building it with this theme's gulp, helpers, SCSS tokens, CSS conventions and content guidelines). Files shared by the three GrowthLab themes come from the growthlab-theme-core repository: edit them there (or pull them into it) so the themes stay equal.

## Contents

1. [Overview](#overview)
2. [Requirements](#requirements)
3. [Installation and child themes](#installation-and-child-themes)
4. [Development workflow](#development-workflow)
5. [Project structure](#project-structure)
6. [Blocks](#blocks)
7. [Theme helpers reference](#theme-helpers-reference)
8. [Customizer: colors and fonts](#customizer-colors-and-fonts)
9. [Multilingual setup](#multilingual-setup)
10. [Media and performance](#media-and-performance)
11. [Security notes](#security-notes)
12. [Troubleshooting](#troubleshooting)
13. [License](#license)

---

## Overview

- **Classic theme + `theme.json`.** Templates are PHP (`header.php`, `page.php`, `home.php`, ...). `theme.json` sets the layout sizes (content 780px, wide 1240px), fluid font sizes, spacing and border controls, and the theme declares `appearance-tools` (border, color, spacing and typography controls on core blocks). The color palette and font families in `theme.json` are replaced at runtime by the Customizer values (`wp_theme_json_data_theme` filter in `functions.php`), and the core default palette, gradients, font sizes and spacing sizes are disabled.
- **ACF PRO blocks.** Every section block lives in `blocks/<block>/` (`block.json` + `block.php` template + SCSS/JS) and is registered automatically. Field groups, the Site Options page fields and the custom post types (Case Results `case-result`, Team Members `team`, Testimonials `testimonial`) are stored as ACF JSON in `acf-json/`.
- **Site Options.** An ACF options page, **Site Options** (`site_options`, registered on `acf/init` with the `manage_options` capability), holds the logo symbol, header/callout settings, phone/email, offices, social links, default hero images and the footer sections (form, menu, locations, social networks, copyright). Per-page options (hero style, custom header, footer section toggles, Hide Sidebar) live in the "Page - Options" and "Page - Sidebar" field groups.
- **Page-level fields come from the queried page.** `header.php` and the templates read them with `get_page_field()`: the queried post on singular views and the posts page on the blog index. Archives, search results and 404 pages have no page options, so the first post of the loop never leaks its settings into the page.
- **Heroes.** The page's `hero_style` field picks the hero template part: `home`, `home_2` ... `home_6` (`template-parts/hero-homepage*.php`), `default` (`hero-default.php`) or `nohero`. The blog index, archives and search results always use the default hero, and 404 pages have none. A custom `hero_title` is a WYSIWYG field printed as written (through `wp_kses_post()`): editors choose its tag there, so it isn't always the page `h1` (SEO or styling reasons). Without a custom title the page, term, archive, author, date or search title is printed as the `h1` (`get_page_hero_title()`). Hero taglines/subtitles use their own tag fields and never fall back to `h1`.
- **Accessibility basics.** `header.php` prints a translatable "Skip to content" link as the first focusable element (visible on focus), and every template wraps its content in `<main id="main-content">`. The mobile menu toggles are real `<button>`s (`aria-expanded`/`aria-controls`, Escape closes the menu). FAQ questions and accordion location card headings are exposed as buttons (`role="button"`, `aria-expanded`, `aria-controls`) and respond to Enter and Space.

### Theme 03 identity

Defaults defined in `theme-functions/fonts-selector.php` and `theme-functions/color-scheme.php` (all can be changed in the Customizer):

| Setting | Default |
| --- | --- |
| Primary font | Figtree (sans-serif fallback) |
| Secondary font | Khand (serif fallback) |
| Primary / dark / light | `#0e5db8` / `#031b3a` / `#2b7de0` |
| Secondary / dark / light | `#f4f8fd` / `#b0d3f9` / `#FFFFFF` |
| Tertiary / dark / light | `#0b3293` / `#071d3a` / `#869db7` |
| Text | `#232323` |

Theme 01, 02 and 03 share the same core features; they differ mainly in colors, fonts and styling.

---

## Requirements

From the `style.css` header:

- **PHP:** 8.3 or higher (the code uses named arguments, union types and `str_*` functions)
- **WordPress:** 6.9 or higher (tested up to 7.1)

Plugins:

| Plugin | Status | Why |
| --- | --- | --- |
| Advanced Custom Fields PRO | Required | Blocks, repeaters, options page, field groups and CPTs from `acf-json/`. Without ACF the blocks and options do not work. |
| Gravity Forms | Required for forms | Contact Form block and footer form. Rendered through `render_gravity_form()`, which outputs nothing if Gravity Forms is inactive. |
| ACF Gravity Forms add-on (provides the ACF `forms` field type) | Required for forms | The `contact_form` fields in the Contact Form block and Site Options use the `forms` field type. |
| LuckyWP ACF Menu Field | Required | Provides the ACF `menu` field type used in Page - Options (custom header menu, footer links menu), Page - Sidebar (sidebar menus) and the Content Box Grid block. |
| WP Pusher | Recommended | Deployment. The ACF JSON auto-sync runs on its `wppusher_theme_was_updated` / `wppusher_theme_was_installed` hooks. |
| EWWW Image Optimizer (or any tool that writes `image.jpg.webp` siblings) | Recommended | The picture helpers add a WebP `<source>` only when a `<file>.webp` exists next to the original. |

Build tools (only needed to change SCSS/JS): Node.js with npm. No version is pinned in `package.json`; the build dependencies need Node 14 or newer, so use a current LTS.

---

## Installation and child themes

### Installing

1. Deploy the theme to `wp-content/themes/growthlabseotheme03/` (WP Pusher, Git clone or upload). The compiled CSS/JS is committed, so no build step is needed on the server.
2. Install and activate the plugins listed above.
3. Activate **GrowthLab Theme 03** (Appearance > Themes), or a child theme of it.
4. Make sure the field groups and post types are in the database: on WP Pusher deploys they are imported automatically (see below); otherwise go to **ACF > Field Groups** (and **ACF > Post Types**) and run **Sync available**.
5. Fill in **Site Options**, set the custom logo (Customizer > Site Identity), assign the **Main Menu** location and pick colors/fonts in the Customizer.
6. Optional, once per site: `import_theme_images_to_folder()` in `functions.php` copies `assets/icons/*.svg` to `uploads/theme-icons/` and adds them to the Media Library. Its `?import_theme_images=1` trigger is commented out; enable it temporarily, run it once and comment it out again, since every run adds the icons again.

### ACF JSON load and save points

Defined in `theme-functions/acf-functions.php`:

- **Load:** ACF's default load path is removed and field groups are loaded from the parent theme's `acf-json/` and, when a child theme is active, also from the child theme's `acf-json/`.
- **Save:** field groups are saved to the **active** theme's `acf-json/` (`get_stylesheet_directory()`), i.e. the child theme when one is active. The folder is created automatically on the first save.

### ACF sync on WP Pusher deploys

An ACF sync closure in `theme-functions/acf-functions.php` runs on `wppusher_theme_was_updated` and `wppusher_theme_was_installed`:

- It only runs when this theme's folder is the active stylesheet or template, and only if ACF is loaded.
- It hashes the parent's `acf-json/group_*.json`, `post_type_*.json` and `taxonomy_*.json` files and compares the hash with the `acf_json_parent_sync_hash` option. If nothing changed, it stops.
- Otherwise it imports, in order: ACF post types, ACF taxonomies and JSON field groups. Existing database posts are updated in place (their IDs are reused), and groups marked `"active": false` stay disabled.
- **Skipped:** field groups that a child theme overrides (a file with the same key in the child's `acf-json/`), field groups that are not loaded from JSON, unreadable or invalid JSON files (counted as warnings), and anything left when the 30-second time budget runs out.
- JSON saving is turned off during the import so the sync does not rewrite files.
- The result is written to the PHP error log as `[ACF sync:growthlabseotheme03] ... synced: N, skipped: N, warnings: N`.
- The new hash is stored **before** importing, so items cut off by the time limit are not retried until the JSON changes again; finish them with **ACF > Field Groups > Sync available**.
- Multisite is not supported.

### Creating a child theme

Client sites always run a child theme. From this theme's folder (see [CHILD-THEMES.md](CHILD-THEMES.md) for the full guide):

1. `npm install` once, to install gulp and Sass. Without it the next commands fail ("gulp" is not recognized).
2. `npm run create-child -- --name="Client Name"` creates `../growthlabseotheme03-client-name` and builds it.
3. Activate the child theme under Appearance > Themes.
4. After every change to its styles, scripts or blocks: `npm run build:child -- --child=../growthlabseotheme03-client-name` (or `npm run watch:child -- --child=…` to rebuild on save). The theme loads the child's compiled files only, and they are committed with the child theme.

### Child themes

- Blocks in the child theme's `blocks/*/block.json` are registered too; a child block with the same `name` as a parent block replaces it. Use the `growthlabseotheme03-blocks` category in `block.json`.
- The child theme's `style.css` is inlined in `<head>` after the parent's `styles/main-min.css` (see [Inlined CSS](#media-and-performance)).
- With a child theme active, `<body>` gets the `theme-child-<slug>` and `theme-parent-<slug>` classes.
- Functions wrapped in `function_exists()` checks (most helpers, block registration, ACF JSON paths, font and color functions) can be overridden by declaring the same function in the child theme's `functions.php`, which loads before the parent's.

---

## Development workflow

### Setup and build

```bash
cd wp-content/themes/growthlabseotheme03
npm install
npm run build   # build everything once (gulp build)
npm run watch   # build once, then rebuild on changes (gulp default task, same as npx gulp)
npm run create-child -- --name="Client"         # new child theme from child-theme-starter/, built
npm run build:child -- --child=../<child theme>   # build a child theme (also watch:child, see CHILD-THEMES.md)
npm run verify  # before pushing: PHP syntax, build, local pages with the parent and the compat-test child theme
```

`gulpfile.js` exports two tasks: `build` compiles everything once, in parallel, and `default` runs `build` and then **keeps watching** for changes (stop it with Ctrl+C).

| Source | Output | Notes |
| --- | --- | --- |
| `styles/**/*.scss` (non-partials) | `styles/**/<name>-min.css` + `.map` | Sass compressed, Autoprefixer. Produces `styles/main-min.css`, `styles/page-templates/*-min.css` and `styles/vendor/tiny-mce/tiny-mce-styles-min.css`. Partials (`_*.scss`) are pulled in via `@use`. |
| `blocks/**/*.scss` | `blocks/**/block-min.css` + `.map` | Same pipeline. Block styles `@use` the global variables and mixins, so changes in `styles/` also rebuild the block CSS. |
| `js/**/*.js` (except `*-min.js` / `*.min.js`) | `js/**/<name>-min.js` + `.map` | Terser. Includes `js/vendor/` files. |
| `blocks/**/*.js` (except `*-min.js` / `*.min.js`) | `blocks/**/block-min.js` + `.map` | Terser. |

Source maps point to the source files with paths relative to the stylesheet/script folders (no absolute local paths). The theme only loads the `-min` files. **Compiled files and source maps are committed**: run the build and commit the generated files together with the source change.

### Conventions

- **Commit messages:** commitlint-style `type: Description` in English, for example `feat: Add items title tag option to posts grid`, `fix: ...`, `perf: ...`, `chore: ...`, `docs: ...`. One commit per logical change. (This is a team convention; there is no commitlint/husky setup in the repo.)
- **Code comments:** in English.
- **CSS:** BEM-style class names (`block__element`, `block--modifier`). Breakpoints are defined twice and do not fully match: `$GLOBALS['breakpoints']` in `functions.php` (used by the picture helpers: tablet 768px, ldpi 1024px, mdpi 1280px, hdpi 1920px) and the SCSS variables in `styles/globals/_variables.scss` (mobile 480px, tablet 768px, ldpi 1024px, mdpi 1200px, hdpi 1440px). `styles/globals/_picture-optimization.scss` uses the `functions.php` values. `js/main.js` uses the SCSS values and shares them as `window.themeBreakpoints` for the block scripts (which fall back to the same numbers).
- **Translatable strings:** use the literal `growthlabseotheme03` text domain (not `get_text_domain()`, which translation tools can't read) and `sprintf()` placeholders for dynamic parts. Texts used by the JS files are passed from PHP: `siteData.i18n` (`main.js`), `styledSelectData` (`styled-select-fields.js`) and `postsFiltersData.i18n` (`posts-filters.js`).
- **New theme functions file:** add it to `theme-functions/` and to the `$includes` array in `functions.php`.
- **Theme 01/02/03 parity:** shared features should be ported to the other two themes.

---

## Project structure

```text
growthlabseotheme03/
├── acf-json/            ACF field groups (group_*.json) and post types (post_type_*.json)
├── assets/
│   ├── icons/           SVG icons (included inline by templates; importable to the Media Library)
│   └── img/             Theme images (backgrounds, placeholders)
├── blocks/              ACF blocks, one folder each (block.json, block.php, block.scss, block.js, compiled -min files)
├── fonts/               Bundled default fonts (figtree-v9-latin, khand-v22-latin, woff2)
├── js/
│   ├── main.js                    Header/menu, accordions, block extraction, lazy maps, video player
│   ├── picture-optimization.js    Lazy loading for <picture>/<img class="lazy-image">
│   ├── posts-filters.js           Blog/archive filters (REST API)
│   ├── styled-select-fields.js    Custom-styled <select> fields
│   └── vendor/                    Splide, IMask, TinyMCE line-height plugin
├── languages/           Translation files (.mo/.po) for the growthlabseotheme03 text domain
├── page-templates/
│   └── template-full-width.php    "Template Full Width" (content only, no sidebar wrapper)
├── styles/
│   ├── main.scss                  Global styles -> main-min.css (inlined in <head>)
│   ├── globals/                   Variables, mixins, normalize, forms, picture optimization
│   ├── template-parts/            Component partials (header, footer, cards, hero, sidebar...)
│   ├── page-templates/            template-default, template-blog, template-team-member
│   └── vendor/                    Splide core CSS, TinyMCE editor styles
├── template-parts/      Heroes, cards (post, team, result, testimonial, location, default), footer parts,
│                        posts filters/pagination, Google Maps, social networks, Splide navigation
├── theme-functions/
│   ├── acf-functions.php          Block registration and CSS inlining, options page, ACF JSON paths and sync,
│   │                              ACF color picker palette, unsafe HTML filters, Google Maps field encoding
│   ├── color-scheme.php           Default colors, Customizer color settings, CSS variables
│   ├── fonts-selector.php         Default fonts, Customizer font settings, bundled @font-face rules
│   ├── helpers.php                General helpers (see reference below), [phonenumber] shortcode
│   ├── picture-optimization.php   <picture> generation with breakpoints, WebP and lazy loading
│   ├── security-functions.php     Generator tag removed, XML-RPC disabled
│   ├── svg-support.php            SVG uploads, sanitization, inline SVG output
│   ├── theme-optimization.php     Head cleanup, emojis, dashicons, delayed form scripts, lazy maps
│   └── tiny-mce.php               Classic/ACF WYSIWYG toolbar, fonts, sizes, line heights, colors
├── functions.php        Theme setup, theme.json overrides, inlined CSS, scripts, sidebars, Theme Tools
├── header.php / footer.php / sidebar.php / sidebar-blog.php
├── index.php / page.php / single.php / single-team.php / home.php / archive.php / search.php / 404.php
├── theme.json           Editor settings (layout, font sizes, spacing, borders)
├── gulpfile.js / package.json / package-lock.json
├── style.css            Theme header only
└── LICENSE              GPL-3.0
```

Templates and assets worth knowing:

- `home.php` (blog) shows the posts filters; `home.php`, `archive.php`, `search.php` and `single.php` use the blog sidebar. `search.php` reuses the archive listing and shows a "No results found" message when nothing matched; `index.php` (last fallback) renders the archive listing.
- The blog index, archives and search results load `template-default-min.css`, `template-blog-min.css` and `posts-filters-min.js`. The filters query the posts REST API with the same page size as the server listing (Settings > Reading `posts_per_page`); a month without a year is filtered on the server through the theme's `pf_month` REST parameter (that month in every year). A new request cancels the previous one, and the filter labels and messages come from `postsFiltersData.i18n`.
- **Hide Sidebar:** pages and single posts hide their sidebar with their own `hide_sidebar` field; the blog index, archives and search results follow the posts page's setting (with the sidebar shown, cards use the horizontal layout).
- Pages and single posts load `template-default-min.css` (except the Full Width template); single team members also load `template-team-member-min.css`.
- `404.php` prints "Page not found" as its single `h1` inside `<main id="main-content">`, with no hero and a non-sticky header.
- Registered image sizes: `cover-desktop` 1920x1080, `cover-tablet` 1280x960, `cover-mobile` 768x432, `cover-tablet-portrait` 960x1280 (crop), `cover-mobile-portrait` 768x1100 (crop), `content` 800x600, `featured-small` 480x360. `medium_large`, `1536x1536` and `2048x2048` are removed and thumbnail cropping is disabled.
- Menus: `main` (Main Menu) plus `main_<slug>` per extra language. Sidebars: `sidebar-default`, `sidebar-blog` plus `sidebar-default-<slug>` and `sidebar-blog-<slug>` per extra language.

---

## Blocks

All blocks are in the **GrowthLab Theme 03 Blocks** category (`growthlabseotheme03-blocks`), use `renderTemplate: block.php`, support custom class names, wide alignment and spacing (margin, padding, block gap), and default to full alignment.

| Folder | Block (`name` / title) | What it does | Notable options |
| --- | --- | --- | --- |
| `block-contact-form` | `acf/contact-form` / Contact Form | Title, content and a Gravity Forms form, with optional side image | Background light/dark/image, reverse columns, form picker (`forms` field) |
| `block-content-box-grid` | `acf/content-box-grid` / Content Box Grid | Grid of content boxes, or one box per item of a WordPress menu | Pretitle/title tags, "is a menu" toggle + `menu` field, per-item title link and tag |
| `block-content-intro` | `acf/content-intro` / Content Intro | Two columns (featured image + content) with an inner CTA box (up to two links; the second link, `cta_link_2`, gets a phone icon when it is a `tel:` link) | Background light/dark/image, image position (`columns.featured_image_position`), Site Options logo symbol |
| `block-cta-box` | `acf/cta-box` / CTA Box | Call-to-action box with title, optional text and buttons | Box style simple/full, background light/dark/image/video (video lazy-loaded, respects reduced motion), side image or logo symbol |
| `block-faq` | `acf/faq` / FAQ | Numbered accordion of questions/answers split into two columns, plus CTA | Background light/dark/image, side image (falls back to the Site Options logo symbol of the current language), per-item question tag |
| `block-featured-video` | `acf/featured-video` / Featured Video | Video with cover and play button, lazy-loaded on click | YouTube URL or uploaded file, inline or lightbox, cover image or cached YouTube thumbnail; editors see a notice for non-video YouTube URLs |
| `block-locations` | `acf/office-locations` / Office Locations | Grid of office location cards | Show all offices from Site Options (current language) or a custom list, accordion items, CTA link |
| `block-logos-carousel` | `acf/logos-carousel` / Logos Carousel | Splide carousel of logos, optionally linked | Background light/dark, fewer logos per view when placed in a sidebar |
| `block-posts-carousel` | `acf/posts-carousel` / Posts Carousel | Splide carousel of Case Results, Team Members (with synced thumbnails carousel), Blog Posts, Testimonials, selected posts of any type, or custom items | Carousel type, items title tag, links target, background light/dark/image; editors see a notice when nothing is selected |
| `block-posts-grid` | `acf/posts-grid` / Posts Grid | Grid of the same content types, paginated | Grid type, show all vs. selection, items title tag; "show all" lists 12 per page, newest first, except testimonials and case results, which show every item (testimonials newest first, case results by `numerical_amount`, highest first, then newest, with results without an amount last); selections show every selected item in the selected order; several grids on one page paginate independently; editors see a notice when nothing is selected |
| `block-selling-points` | `acf/selling-points` / Selling Points | Title/subtitle/text with icon items (optionally linked) and CTA | Background light/dark/image, items background image (falls back to `assets/img/figure_selling_points.webp`) |
| `block-two-columns` | `acf/two-columns` / Two Columns | Text column and image column | Background light/dark/img, reverse order, top logo separator |

Every block also has the common fields `toggle_block` (the block renders nothing when off), `block_id` (HTML id), `extract_block_from_content` and `place` (before/after).

### Shared block conventions

A typical `block.php`:

```php
if (get_field('toggle_block')):
    foreach (get_block_fields($block) as $key => $value) $$key = $value;
    $background_type = get_valid_field_choice('background_type', $background_type ?? '');
?>
    <section<?= get_block_id_attribute($block_id ?? '') ?>
        class="block my-block <?= esc_attr($background_type ?: 'light') ?><?= !empty($block['className']) ? ' ' . esc_attr($block['className']) : '' ?>"
        <?= block_style_attribute($block); ?>
        <?= get_block_extract_attribute($extract_block_from_content ?? false, $place ?? '') ?>>
        <?php print_title($title, $title_tag, 'my-block__title'); ?>
    </section>
<?php endif; ?>
```

- **Field extraction:** `get_block_fields($block)` returns the block's values with every field of its field groups present; fields never saved (e.g. added after the block was inserted) get their ACF default (`[]` for repeaters/galleries/relationships/checkboxes, sub-field defaults for groups, booleans for true/false, `null` when there is no default). They are extracted into variables with `$$key`.
- **Repeaters and groups** are read as arrays (`$item['title'] ?? ''`, `$columns['featured_image']`), not with `have_rows()`/`get_sub_field()`.
- **Wrapper attributes:** `get_block_id_attribute()` prints an escaped `id`; `block_style_attribute($block)` turns the editor's spacing settings into a `style` attribute (spacing presets such as `var:preset|spacing|40` become `var(--wp--preset--spacing--40)` through `css_preset_value()`); `get_block_extract_attribute()` adds `data-extract="before|after"`.
- **Moving a block out of the content:** with `extract_block_from_content` on, `main.js` moves the block before or after the page content wrapper (`.page__inner`). This only applies on pages using the default template.
- **Select/radio values:** pass them through `get_valid_field_choice('field', $value)` (or `'group.sub_field'`) before printing them in attributes; unknown values fall back to the field default.
- **Headings:** use `print_title($title, $tag, $classes)`; the tag is limited to h1-h6/p/span/div and the title is filtered with `wp_kses_post()`.
- **Escaping:** `esc_url()` for URLs, `esc_attr()` for attributes (including link targets and classes), `esc_html()` for plain text such as link titles, `wp_kses_post()` for WYSIWYG/content fields. Print a link `target` only when it is set, and add `rel="noopener noreferrer"` to `_blank` links.
- **Icons:** print theme SVGs with `get_theme_svg('assets/icons/<file>.svg')` (child theme first, cached per request) instead of including the file.
- **Editor notices:** `print_block_notice($message)` shows a dashed notice only to users who can `edit_posts` (visitors get nothing). Used by Posts Carousel and Posts Grid when nothing is selected, and by Featured Video for invalid YouTube URLs.
- **Assets:** `block.json` declares `"style": ["file:./block-min.css"]` and, when needed, `"viewScript": ["file:./block-min.js"]`, so block scripts only run on the front end (not in the editor, where `main.js` and `splideData` don't exist). Block scripts are moved to the footer with `defer`. Each `block.json` also declares `"textdomain": "growthlabseotheme03"`.

### Adding a block

1. Create `blocks/block-<name>/` with `block.json` (`"name": "acf/<name>"`, `"textdomain": "growthlabseotheme03"`, `"category": "growthlabseotheme03-blocks"`, `"style": ["file:./block-min.css"]`, `"viewScript": ["file:./block-min.js"]` if it has a script, `acf.renderTemplate: "block.php"`), `block.php` and `block.scss` (plus `block.js` if needed).
2. Run `npm run build` to generate the `-min` files.
3. Create the field group in ACF with location "Block is equal to <title>" and include the common fields (`toggle_block`, `block_id`, `extract_block_from_content`, `place`). Its JSON is saved to `acf-json/`.
4. Commit the block folder (including compiled files) and the new `acf-json` file.

There is nothing to register in PHP: `register_acf_blocks()` finds every `blocks/*/block.json`.

---

## Theme helpers reference

`theme-functions/helpers.php`:

| Function | Description |
| --- | --- |
| `get_text_domain()` | Returns the theme text domain (`growthlabseotheme03`). Translation functions use the literal domain instead. |
| `get_theme_svg($relative_path)` | Markup of a theme SVG file (path relative to the theme root, child theme first), or `''` if it doesn't exist. Cached per request. |
| `get_field_options($field_name, $format_value = true)` | `get_field($name, 'option')` with a per-request static cache. |
| `get_block_fields($block)` | Block field values merged over the ACF defaults of its field groups. |
| `get_acf_field_default($field)` | Default value of an ACF field as templates expect it. |
| `fill_acf_group_defaults($value, $selector, $post_id = false)` | Merges a group value (e.g. `options`, `hero_properties`) over its sub-field defaults. |
| `get_post_fields_with_defaults($post_id = false)` | A post's ACF values merged over the defaults of its field groups. |
| `get_valid_field_choice($field_name, $value)` | Returns `$value` if it is a valid choice of the select/radio field, otherwise the default. |
| `get_block_id_attribute($block_id)` | ` id="..."` (escaped) or an empty string. |
| `get_block_extract_attribute($enabled, $place)` | ` data-extract="before\|after"` or an empty string. |
| `block_style_attribute($block = [])` | Builds the ` style="..."` attribute from the block's spacing/style supports. |
| `css_preset_value($value)` | Turns an editor preset value (`var:preset\|spacing\|40`) into its CSS variable (`var(--wp--preset--spacing--40)`); other values are returned unchanged. |
| `print_title($title, $tag = 'p', $classes = '', $is_hero = false)` / `get_wrapped_title(...)` | Wraps a title in a safe tag (invalid tags fall back to `h1` for heroes, `p` otherwise). |
| `print_block_notice($message)` | Editor-only notice in place of a block. |
| `get_posts_grid_page_arg()` | Pagination query arg per posts grid (`''` for the first grid, then `grid-2-page`, `grid-3-page`...). |
| `get_current_post_blocks()` | Flattened blocks (inner blocks follow their parent) of the current singular post, cached per post. Synced patterns (`core/block`) are expanded once each, so their blocks count as used on the page; falls back to the queried post when a secondary loop changed the global `$post`. Returns `[]` on non-singular requests. |
| `get_page_options_id()` | ID of the page whose "Page - Options" fields apply: the queried post on singular views, the posts page on the blog index, `0` on archives, search results and 404. |
| `get_page_field($selector)` | `get_field()` on `get_page_options_id()`, or `null` when the request has no such page. |
| `is_listing_page()` | `true` on the blog index, archives and search results (they use the default hero and the blog assets). |
| `get_page_hero_title()` | Fallback hero title: posts page title (or "Blog"), "Search results for: ...", the singular title, term, post type archive or archive title. |
| `get_languages_map()` / `get_current_language()` / `get_current_language_suffix()` / `get_current_language_options()` / `filterContentByLanguage($lang)` | Language detection (see [Multilingual setup](#multilingual-setup)). |
| `render_gravity_form(...$args)` | Calls `gravity_form()` only if Gravity Forms is active. |
| `get_flat_number($phone)` | Strips everything but digits. |
| `get_phone_href($phone)` | Escaped `tel:` URL. US numbers (10 digits, or 11 starting with 1) get the `+1` country code once; numbers written with another `+` prefix keep it; anything else is dialed as written. Returns `''` when there are no digits. |
| `format_number_abbreviated($number)` | 1500 -> `1.5K`, 2000000 -> `2M`, etc. |
| `get_yt_code($url)` | 11-character ID of a single YouTube video, or `false` (channels, playlists, etc.). |
| `get_youtube_thumbnail($video_id, $size = 'hqdefault')` | Best available thumbnail URL from `$size` down, cached in a transient. |
| `[phonenumber]` shortcode | Main phone number from the current language's Site Options as a `tel:` link built with `get_phone_href()`. Attributes: `target` (`_blank` adds `rel="noopener noreferrer"`), `link_id`, `link_class`, `wrapper`, `text` (fallback). |

Images (`theme-functions/picture-optimization.php`, `svg-support.php`):

| Function | Description |
| --- | --- |
| `img_print_picture_tag(...)` / `img_generate_picture_tag(...)` | Echo/return a responsive `<picture>`. Named arguments: `img`, `mobile_img`, `tablet_img`, `max_size` (default `full`), `min_size`, `classes`, `id`, `alt_text`, `is_cover`, `img_attr` (preferably an array, its values are escaped), `is_priority`, `fetch_priority` (`false` for priority images that aren't the LCP element, e.g. the logo). SVGs are delegated to `image_to_svg()`. |
| `image_to_svg($image, $classes = '')` | Inlines a local SVG file (wrapped in a `<div>`), or returns an `<img>` for other files. Accepts an ACF image array or a URL; the inlined SVG keeps the image's alt text (`role="img"` + `aria-label`, or `aria-hidden` when decorative). |

```php
img_print_picture_tag(
    img: $background_image,
    is_cover: true,
    classes: 'my-block__bg bg-image'
);

img_print_picture_tag(img: $image, max_size: 'content', min_size: 'featured-small', classes: 'my-block__image');
```

Colors and fonts: `theme_get_customizer_css()`, `hex_to_rgb()`, `theme_get_fonts_css()`, `theme_get_all_fonts()`, `theme_get_default_font_faces()`, `theme_get_selected_default_font_faces()`, `get_theme_color_palette_for_acf()`.

---

## Customizer: colors and fonts

### Colors

Customizer > **Colors** has ten settings (`theme-functions/color-scheme.php`): `primary_color`, `primary_color_dark`, `primary_color_light`, the same three for `secondary` and `tertiary`, and `text_color`. Defaults come from `$default_colors`.

The same values feed:

- **CSS variables** inlined in `<head>`: `--primary`, `--primary-dark`, `--primary-light`, `--secondary`, `--secondary-dark`, `--secondary-light`, `--tertiary`, `--tertiary-dark`, `--tertiary-light`, `--text`. Values are RGB triplets (e.g. `14, 93, 184`), so use them as `rgb(var(--primary))` or `rgba(var(--primary), 0.5)`. `hex_to_rgb()` expands 3-digit colors (`#fff`) and replaces empty or invalid values with the theme default.
- **Block editor palette** (`theme.json` via `wp_theme_json_data_theme`): slugs `primary-color`, `primary-color-dark`, ... `text-color`.
- **ACF color picker palette** (`get_theme_color_palette_for_acf()` in `acf-functions.php`).
- **Classic editor / ACF WYSIWYG** text color map and `editor-color-palette` (`tiny-mce.php`).

To change the theme defaults, edit `$default_colors` in `color-scheme.php`; everything above follows.

### Fonts

Customizer > **Fonts** has `font_primary` and `font_secondary` selects (`theme-functions/fonts-selector.php`). The choices are:

1. Fonts installed through the WordPress **Font Library** (listed first), and
2. The theme's bundled defaults, Figtree and Khand.

The selection is output as `--font-primary` / `--font-secondary` and as the `primary` / `secondary` font families in `theme.json`. The TinyMCE font menu lists the theme and Font Library fonts.

Bundled font files in `fonts/` are only declared (`@font-face`) while their family is selected as the primary or secondary font; selecting Font Library fonts loads none of them. A copy of the theme font families saved by the Site Editor in the global styles is dropped (`wp_theme_json_data_user` filter) so it cannot force all bundled fonts to load.

Font Library fonts are filtered the same way on the front end: `theme_print_selected_font_faces()` replaces WordPress' `wp_print_font_faces()` and prints `@font-face` rules only for the selected families (faces without a `font-display` get `swap`). The block editor still loads every installed font, so all of them can be previewed there.

**Font preloads (header and hero only).** `theme_get_above_fold_font_variants()` in `theme-functions/fonts-selector.php` lists the role (primary/secondary), weight and style of the text the header and the hero render above the fold, measured on the theme's templates on desktop and mobile; variants can be limited to some hero styles (`hero_styles`) or viewports (`media`). `theme_print_font_preloads()` (priority 1 in `wp_head`) maps each variant to the file of the font selected in the Customizer, bundled or from the Font Library, picking the face the browser would use (nearest weight per the CSS rules, one file for variable fonts), with the same URL as its `@font-face` rule, and preloads at most 6 files in the list order (hero title first). After the header and hero variants come those of the first block rendered after the hero (`get_first_content_block_name()`, which skips blocks switched off with `toggle_block`), taken from the per-block table in `theme_get_first_block_font_variants()` (measured with each block placed first; core blocks share one entry and unlisted blocks, e.g. from plugins, use a default). Adjust them with the `theme_above_fold_font_variants` filter, which also receives the hero style and the first block name (an empty array disables them), and the limit with `theme_font_preload_limit`. A variant can also depend on the page content with `when`: `hero_title_bold` only preloads the bold weight of the hero title when the title has `<strong>` or `<b>` text (other conditions can be added with the `theme_font_variant_condition` filter). Font files over 300KB (`theme_font_preload_max_bytes` filter) are never preloaded, since they would take the bandwidth of the hero image (e.g. a Japanese font from Google Fonts with all its characters); users who can edit the theme options see an HTML comment in the head naming each skipped file, and the fix is a subset with only the characters the site uses (e.g. the latin files from google-webfonts-helper).

**Adding a bundled font face:** put the `.woff2` file in `fonts/<family-folder>/` and add an entry to `theme_get_default_font_faces()`, keyed by the family name exactly as it is selected in the Customizer:

```php
'Figtree' => [
    ['weight' => '800', 'style' => 'normal', 'file' => 'figtree-v9-latin/figtree-v9-latin-800.woff2', 'display' => 'swap'],
    // ...
],
```

The faces to preload come from `theme_get_above_fold_font_variants()`, not from this list. A new family must also be selectable: either install it through the Font Library or add it to the base list in `theme_get_all_fonts()` (and to `$default_fonts` if it should be the default).

---

## Multilingual setup

The theme does not use a translation plugin. Languages are configured in **Site Options > Site Options by Language** (`options_by_language` repeater), where each row has:

- `language` - display name (e.g. `Spanish`)
- `url_language_slug` - URL prefix (e.g. `es`)
- `options` - that language's copy of the site options

How it works (`theme-functions/helpers.php`):

- `get_current_language()` checks whether the request path is `/<slug>` or starts with `/<slug>/` for each configured slug. If none match, the language is `English` with an empty slug.
- `get_current_language_options()` returns the matching row's `options`, falling back to the main `options` group. The header, footer, social networks, Office Locations block ("show all"), the blog filters' default image and `[phonenumber]` use it; card default images and the logo symbol (logo separator, FAQ, Content Intro, CTA Box, Posts Carousel) also fall back to the main options when the language row leaves them empty.
- For every configured language the theme registers a `main_<slug>` menu location and `sidebar-default-<slug>` / `sidebar-blog-<slug>` sidebars; `header.php`, `sidebar.php` and `sidebar-blog.php` pick them by the current slug. The header menu is only printed when the current language's location has a menu (it never falls back to a list of pages). The logo links to `home_url('/<slug>')`, or to the page's `logo_link` when its custom header is on.
- `header.php` prints `<html <?php language_attributes(); ?>>`. A `language_attributes` filter in `helpers.php` replaces the `lang` value with the current language slug when the slug looks like a language code (`es`, `pt-br`...), since language slugs switch content without changing the WordPress locale.

**String translations:** `load_theme_textdomain('growthlabseotheme03', get_template_directory() . '/languages')` runs in `after_setup_theme`. Theme templates, blocks and admin strings use the `growthlabseotheme03` text domain, and the JS texts (video lightbox labels, styled select placeholder, blog filter messages, pagination labels) are passed from PHP through `siteData.i18n`, `styledSelectData` and `postsFiltersData.i18n`, so they are translatable too. The blog filters keep the English month values (`posts-filters.js` maps them to month numbers) and only translate the visible text; dates in filtered cards are formatted with the page's `lang`. Put compiled translations in `languages/` named by locale (e.g. `languages/es_ES.mo`, with the `.po` source). The folder currently only contains `.gitkeep`.

---

## Media and performance

### SVG

- SVG uploads are allowed (`theme-functions/svg-support.php`) only for files named `*.svg`; the Media Library gets their dimensions from the root `<svg>` `width`/`height` or `viewBox`.
- Every uploaded or sideloaded SVG (detected by extension or content) is sanitized with DOMDocument before WordPress stores it: entity declarations, compressed files and non-SVG documents are rejected; comments, processing instructions, unknown elements, `on*` handlers, external `href`s (only `#id` and embedded raster `data:image/...` are kept), `javascript:` values and unsafe CSS (`@import`, external `url()`, etc.) are removed. Files that cannot be made safe are rejected with an error.
- `image_to_svg()` and the picture helpers inline local SVGs; `the_content` also inlines local SVG `<img>` tags (skipped when the content has more than 20 images).

### Picture optimization

- `img_print_picture_tag()` builds a `<picture>` with one `<source>` per breakpoint (`$GLOBALS['breakpoints']` in `functions.php`: tablet 768px, ldpi 1024px, mdpi 1280px, hdpi 1920px). Each breakpoint gets the widest generated size that fits it, never smaller than `min_size` (what phones get) nor larger than `max_size`; phones never get a larger file than desktops. Breakpoints that would repeat the file of the next smaller one are dropped.
- Image sizes are read from the attachment metadata, which has the real width of each file (sizes without hard crop keep each image's proportions, so they can be narrower than the registered width).
- `is_cover: true` (hero backgrounds) uses `full`, `cover-desktop`, `cover-tablet` (1024-1279px screens), `cover-tablet-portrait` and `cover-mobile-portrait`. A `tablet_img` is only used for 768-1023px screens; larger screens always get the main image. Phones get the `mobile_img`, else the `tablet_img`, else the main image, in a portrait crop; without one, the smallest file at least 1000px tall (or the tallest file), so a very large original isn't sent to phones when a generated size would do.
- A URL of a generated size (`photo-800x533.jpg`) is resolved to its attachment; URLs outside the media library are printed as a plain lazy image.
- WebP `<source>` elements are added when the image is WebP or a `.webp` copy exists (`image.jpg.webp`).
- Non-priority images use `data-src` / `data-srcset` and the `lazy-image` class; `js/picture-optimization.js` swaps them in with an IntersectionObserver (100px margin) only when they approach the viewport, and picks up images added later (e.g. Splide clones); browsers without IntersectionObserver load every image at once. A `<noscript>` copy is printed for browsers without JavaScript.
- Lazy images get `--ar-{breakpoint}-w/h` custom properties with the real size of the file each breakpoint serves; `styles/globals/_picture-optimization.scss` turns them into an `aspect-ratio`, so their space is reserved before they load. Its breakpoint list must match `$GLOBALS['breakpoints']`.
- `is_priority: true` loads the image eagerly with `fetchpriority="high"` (the hero background and side portrait, which can be the LCP element on phones); the header logo passes `fetch_priority: false`, so it loads eagerly without competing with them.
- Images with `fetchpriority="high"` are also preloaded at the start of the `<head>`: one `<link rel="preload" as="image">` per file of the picture, limited to the screens that file serves (e.g. `(min-width: 768px) and (max-width: 1023px)`) and in WebP when a WebP copy exists, exactly like the `<source>` elements, so each file is downloaded once. The `<head>` is printed before the images, so `wp_head` prints a placeholder (priority 0) that a front-end output buffer (`template_redirect`) replaces with the collected links.

### CSS loading

- `inline_main_critical_css()` (`functions.php`) prints one `<style id="main-css">` in `<head>` with the font CSS, the color variables, `styles/main-min.css`, the active theme's `style.css`, the Splide core CSS (only when the request renders a Logos/Posts Carousel in the content, a synced pattern or a sidebar block widget) and the CSS of the theme blocks used on the page. `{theme-path}` in the CSS is replaced with the theme URL. Comments and whitespace are stripped (spaces around `:` and `,` are kept, since they can be significant). The result is cached in a transient for a week, keyed by the modification times of the CSS files and of `functions.php`, the theme URL, the blocks in use, the Splide flag and the font/color CSS; Customizer previews skip the cache.
- Pieces of the inline CSS that depend on the page: the **hero** styles of the homepage variants are split out of `main-min.css` by the build into `styles/heroes/` and only the page hero's files are printed (`theme_get_hero_css_files()`, filter `theme_hero_css_files`); the **card** styles live in `styles/cards/` and only the cards the page renders are printed (the `get_template_part` action marks them, and an output buffer fills a placeholder where the block styles start); a **child theme**'s compiled `styles/main-min.css` is printed after the parent styles and before its `style.css` (see CHILD-THEMES.md).
- On singular pages (blocks inside synced patterns included, see `get_current_post_blocks()`) the stylesheets of the blocks in use are dequeued (their `block-min.css` is inlined instead), and the styles and scripts of every unused non-core block, plugin blocks included, are dequeued.
- Page-template stylesheets (`styles/page-templates/*-min.css`) are enqueued as regular files.
- Theme asset URLs are versioned with the file modification time.

### JavaScript

- `main-min.js`, `styled-select-fields-min.js`, `picture-optimization-min.js` and `posts-filters-min.js` load deferred in the footer; block scripts (`viewScript`, front end only) are moved to the footer with `defer`. None of them is preloaded.
- jQuery is moved to the footer and `jquery-migrate` is removed from the `jquery` dependencies (deregistering it would make WordPress skip jQuery and every Gravity Forms script). Gravity Forms CSS and theme editor styles are disabled and its init scripts go to the footer. `wp-i18n` is left alone: WordPress only prints it when a script needs it (e.g. `wp-a11y` for Gravity Forms).
- **Delayed form scripts:** jQuery, Gravity Forms, IMask and their dependencies (e.g. `wp-a11y`, `wp-i18n`) are printed as inert `type="text/plain"` tags (inline parts and translations included, `script_loader_tag` in `theme-functions/theme-optimization.php`). A small inline loader runs them in their original order when a Gravity Forms form gets within 600px of the viewport or on the first click, key press, touch or focus, so pages don't download or run them up front. Any script that depends on a delayed one is delayed too. Gravity Forms initialises through `gform.initializeOnLoaded()`, which waits for its own scripts, and the forms submit without JavaScript as well.
- Splide (`js/vendor/splide/splide-min.js`) is injected on demand by the carousel block scripts (`window.loadSplide`, which retries after a failed load), when a carousel scrolls into view. The carousels read their breakpoints from `window.themeBreakpoints` (set by `main.js`).
- IMask (`js/vendor/imask/imask-min.js`) is enqueued only when a rendered Gravity Forms form has a phone field (`gform_enqueue_scripts`). It masks the phone inputs (`.ginput_container_phone input`) as `(000) 000-0000` on load and again after Gravity Forms re-renders a form (AJAX submissions, multi-page forms), skipping inputs that are already masked.

### Other optimizations

- Head cleanup: RSD, wlwmanifest, shortlink, REST API link, oEmbed discovery/host JS and feed links are removed.
- Emojis disabled; a direct Dashicons enqueue is dequeued on the front end unless the admin bar is showing (it is not deregistered, so plugin styles that depend on it keep it); `wc-blocks-style` dequeued.
- Google Maps: known Maps JS API handles are dequeued; map iframes in content become `.gmap-lazy` placeholders loaded by `main.js`. ACF fields named `google_maps_embed_code` are base64-encoded in the browser before saving (to avoid ModSecurity 403s) and decoded in `template-parts/google-maps.php`.
- YouTube thumbnails: `get_youtube_thumbnail()` checks sizes with HEAD requests once and caches the result in a `gl_yt_thumb_*` transient (one month, or one day for the `hqdefault` fallback).
- Fonts: only the faces the header and hero use are preloaded (see [Fonts](#fonts)); `transparent-bg.webp` is lazy loaded with the `.bg-gradient` sections, not preloaded. Only the selected Font Library families get `@font-face` rules.
- Featured Video and CTA Box background videos only load the player/video on demand.

---

## Security notes

- `theme-functions/security-functions.php` removes the WordPress generator meta tag and disables XML-RPC.
- **Theme Tools (admin bar):** visible to users with `manage_options`.
  - **Clear block cache** - `wp-admin/index.php?clear_block_cache=1` with a `clear_block_cache` nonce; rebuilds the cached list of `block.json` files.
  The action checks the capability and the nonce.
- **Escaping:** templates and blocks escape URLs with `esc_url()`, attributes with `esc_attr()` and plain text with `esc_html()`, and print WYSIWYG/content fields through `wp_kses_post()`; select values go through `get_valid_field_choice()`; titles go through `print_title()` (`wp_kses_post()`). Link targets are only printed when set, and `_blank` links get `rel="noopener noreferrer"`. The `[phonenumber]` shortcode whitelists its wrapper tags and targets and escapes all attributes.
- **ACF "unsafe HTML" is intentionally enabled.** `acf-functions.php` returns `true` for `acf/shortcode/allow_unsafe_html`, `acf/the_field/allow_unsafe_html` and `acf/the_sub_field/allow_unsafe_html`, and hides ACF's escaped-HTML admin notice. This lets text, textarea and WYSIWYG fields carry markup such as `<span>`, `<a>` and `<strong>` (e.g. highlighted words in titles). The theme's own output filters content fields with `wp_kses_post()`, but values printed with `the_field()`, `the_sub_field()` or the `[acf]` shortcode are not escaped by ACF. Only give block/options editing rights to trusted users.
- SVG uploads are sanitized (see [SVG](#svg)).

---

## Troubleshooting

**A new or renamed block does not appear in the inserter.**
The list of block folders is cached in a transient for 24 hours. It is rebuilt automatically when a `block.json` path or modification time changes, on every admin request when `WP_DEBUG` is on, and on theme switch (for both the new and the previous theme). If it is still missing, use **Theme Tools > Clear block cache** in the admin bar, then reload the editor. Also check that `block.json` has a `name` and that ACF PRO is active.

**ACF shows "Sync available" for field groups or post types.**
The JSON in `acf-json/` is newer than the database copy (for example after a Git pull without WP Pusher, or when the deploy sync hit its time limit). Run **Sync** in ACF > Field Groups (and ACF > Post Types). On a child theme, edits are saved to the child's `acf-json/`, and those groups are skipped by the deploy sync.

**CSS or JS changes do not show up.**
The theme only loads the `-min` files: run `npm run build` (or keep `npm run watch` running) and commit the compiled output. Global and block CSS is inlined in `<head>`, so clear any page cache after deploying.

**A block's "extract" setting does nothing.**
Block extraction only works on pages using the default template (`.page-template-default`), not on the Full Width template or posts.

**A Posts Carousel/Grid or Featured Video shows a dashed notice.**
That notice is only visible to editors: select posts/create items, or use a single-video YouTube URL. Visitors see nothing.

---

## License

GPL-3.0-or-later. See [`LICENSE`](LICENSE).
