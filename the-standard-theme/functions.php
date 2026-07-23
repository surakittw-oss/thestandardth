<?php
/**
 * THE STANDARD theme — setup & asset loading.
 *
 * Static build: the page content ships in assets/js/data.js and is rendered
 * on the client with React (via CDN) + Babel Standalone. WordPress supplies
 * the shell (head, enqueued styles) and the theme/article-page URLs.
 *
 * @package the-standard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

define( 'TS_THEME_VERSION', '1.1.0' );

/**
 * Theme supports.
 */
function ts_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'automatic-feed-links' );
	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'the-standard' ),
		)
	);
}
add_action( 'after_setup_theme', 'ts_theme_setup' );

/**
 * Enqueue Google Fonts and the compiled stylesheet.
 */
function ts_enqueue_assets() {
	// Google Fonts — same families as the prototype.
	wp_enqueue_style(
		'ts-google-fonts',
		'https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@300;400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600;700&family=Noto+Serif+Thai:wght@400;500;600;700;900&family=IBM+Plex+Serif:ital,wght@0,400;0,600;0,700;1,700;1,900&display=swap',
		array(),
		null
	);

	// Main stylesheet.
	wp_enqueue_style(
		'ts-main',
		get_template_directory_uri() . '/assets/css/main.css',
		array( 'ts-google-fonts' ),
		TS_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'ts_enqueue_assets' );

/**
 * Add preconnect hints for the font CDN.
 */
function ts_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array( 'href' => 'https://fonts.googleapis.com' );
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'ts_resource_hints', 10, 2 );

/**
 * Resolve the base URL used for article links.
 *
 * Homepage cards link to <base>?id=<article-id>. If a Page with the slug
 * "article" exists (recommended — assign it the "THE STANDARD — Article"
 * template), its permalink is used; otherwise we fall back to /article.
 *
 * @return string
 */
function ts_article_base() {
	$page = get_page_by_path( 'article' );
	if ( $page ) {
		return get_permalink( $page );
	}
	return home_url( '/article' );
}

/**
 * Print the client runtime: React, ReactDOM, Babel, data.js and components.jsx,
 * plus the globals the bundle reads (theme URI + article base).
 *
 * Call this inside a template AFTER the #root element and BEFORE the inline
 * `text/babel` App script, so Babel transforms components.jsx first.
 */
function ts_the_runtime_scripts() {
	$uri = get_template_directory_uri();
	?>
	<script>
		window.TS_THEME_URI   = <?php echo wp_json_encode( $uri ); ?>;
		window.TS_ARTICLE_BASE = <?php echo wp_json_encode( ts_article_base() ); ?>;
	</script>
	<script src="https://unpkg.com/react@18.3.1/umd/react.production.min.js" crossorigin="anonymous"></script>
	<script src="https://unpkg.com/react-dom@18.3.1/umd/react-dom.production.min.js" crossorigin="anonymous"></script>
	<script src="https://unpkg.com/@babel/standalone@7.29.0/babel.min.js" crossorigin="anonymous"></script>
	<script src="<?php echo esc_url( $uri . '/assets/js/data.js' ); ?>?ver=<?php echo esc_attr( TS_THEME_VERSION ); ?>"></script>
	<script type="text/babel" src="<?php echo esc_url( $uri . '/assets/js/components.jsx' ); ?>?ver=<?php echo esc_attr( TS_THEME_VERSION ); ?>"></script>
	<?php
}
