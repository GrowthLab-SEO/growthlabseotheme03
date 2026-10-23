# Changelog

All notable changes to GrowthLab Theme 03. The version is in `style.css` and goes up with every update that is pushed:
patch (1.0.x) for fixes, minor (1.x.0) for new features or changes editors can see.

## 1.1.0 - 2026-10-23

### Added
- Every Gravity Forms field (Contact Form block and footer form in Site Options) has a **Form Type** select:
  Gravity Form, Custom code or None. **Custom code** shows a **Form Code** textarea for the embed code or script of a
  third-party form (e.g. a CRM), printed as is. Forms saved before this keep their Gravity Form.

### Fixed
- Section content of blocks (Main Content, Text Content, FAQ answers, columns, etc.) is no longer passed through
  `wp_kses_post()`, which removed iframes, scripts and forms pasted by editors. Titles and short card texts keep it.
- Dark sections (Two Columns with a dark background) get their background again (`.bg-gradient`).
- Paragraphs with right aligned images float to the right.
- Invalid background gradients of the tertiary buttons and the form submit button.
- The Content Box Grid block no longer changes the homepage hero box (v3, v4, v6).
- The hero badge is centered on mobile.
- Default card title size and link letter spacing, and the footer link hover color.

### Changed
- The location card styles load with the Locations block only, instead of on every page.
- Site Options: "Posts Default Title Tag" and "Posts Default Image" are now labeled "Posts Cards Default Title Tag"
  and "Posts Cards Default Image". They only apply to the post cards of the blog, archive and search listings, not to
  the page title in the hero.

### Check after updating
- Pages with embeds in block content (forms, maps, videos): they print again. Remove any workaround that restored them.
- Contact forms: they keep their Gravity Form. To use a third-party form, set Form Type to Custom code.
- Field groups stored in the database: ACF shows "Sync available" for Site Options and Block - Contact Form.
