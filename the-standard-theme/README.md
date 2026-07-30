# THE STANDARD — WordPress Theme

THE STANDARD homepage & article redesign, packaged as a WordPress theme.

## What this is

**WordPress owns the data.** Posts, categories and tags are queried in PHP and
handed to React components (loaded from a CDN + Babel Standalone) as JSON. The
theme bundles **no demo content** — everything you see is what's in the database.

Screens:

| Template | Renders |
|---|---|
| `front-page.php` / `index.php` | Homepage — hero + latest posts |
| `single.php` | Article — full post content, byline, share, related posts |
| `category.php` | Category archive |
| `tag.php` | Tag archive |
| `archive.php` | Date / author / other archives |
| `search.php` | Search results |
| `page-article.php` | Optional "Article" Page template (see below) |

## File structure

```
the-standard-theme/
├── style.css                     # Theme header (metadata only)
├── functions.php                 # Setup, assets, and all WP → JSON data helpers
├── header.php / footer.php       # wp_head / wp_footer + scroll-reveal
├── front-page.php, index.php     # Homepage
├── single.php                    # Article
├── category.php, tag.php,        # Archives
│   archive.php, search.php
├── page-article.php              # Page template: "THE STANDARD — Article"
├── template-parts/
│   ├── home-app.php              # Homepage React mount
│   ├── article-app.php           # Article React mount
│   └── archive-app.php           # Archive React mount
└── assets/
    ├── css/main.css              # Full stylesheet
    ├── js/components.jsx         # React components
    └── images/                    # logo
```

### Data helpers (functions.php)

| Function | Purpose |
|---|---|
| `ts_post_card( $post )` | One post in the shape the components expect (id, url, category, title, excerpt, image, time, author, readTime) |
| `ts_recent_posts( $limit )` | Recent posts → `window.ARTICLES` (per-request cached) |
| `ts_nav_bootstrap()` | Nav + mega menu + category list from real categories |
| `ts_the_archive_data()` | Current archive → `window.TS_ARCHIVE*` |
| `ts_clean_excerpt()` | Excerpt from **raw** content, so plugin-injected boxes and HTML entities don't leak in |
| `ts_post_image_url()` | Featured image, else first inline `<img>` |
| `ts_read_time()` | Estimated minutes from raw content |

## Install

1. Zip the `the-standard-theme` folder (top-level folder must be
   `the-standard-theme`, containing `style.css`).
2. **Appearance → Themes → Add New → Upload Theme** → Activate.
   (Or copy the folder into `wp-content/themes/`.)
3. **Settings → Permalinks** — pick anything other than "Plain" so category and
   tag archives get pretty URLs.

That's it. The nav is generated from your categories; posts appear as you publish.

### Optional: the "Article" Page template

`page-article.php` exists for the standalone-prototype flow, where cards link to
`<article-page>?id=<id>`. With real WordPress posts, cards link straight to their
permalinks and `single.php` renders them — so **this page is no longer needed**.
Create it only if you want that URL to work: add a Page with the slug `article`
and assign the "THE STANDARD — Article" template.

## Editing

- **Layout / components** — `assets/js/components.jsx`
- **Styles** — `assets/css/main.css`
- **Data shape** — the `ts_*` helpers in `functions.php`

Bump `TS_THEME_VERSION` in `functions.php` after editing JS or CSS, or browsers
will serve the cached copy.

## Notes / limitations

- **Babel in the browser** compiles the JSX on each page load — fine for a
  prototype, not ideal for production traffic. To go live, precompile
  `components.jsx` (and the inline `text/babel` apps) to plain JS and drop the
  Babel CDN.
- **Requires a network connection** at runtime: React and Babel load from unpkg.com.
- **No `page.php`** — regular Pages currently fall through to `index.php` and
  render the homepage. Add a `page.php` if the site needs static pages.
- `components.jsx` still contains components the theme doesn't render (Popular,
  Opinion, Video, Shorts, Events) because it is shared with the standalone
  prototype. They ship as unused code but never appear on the site.
