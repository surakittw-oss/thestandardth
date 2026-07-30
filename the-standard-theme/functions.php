<?php
/**
 * THE STANDARD theme — setup & asset loading.
 *
 * WordPress owns the data: posts, categories and tags are queried in PHP and
 * handed to the React components (loaded via CDN + Babel Standalone) as JSON.
 * The theme bundles no demo content.
 *
 * @package the-standard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

define( 'TS_THEME_VERSION', '2.1.0' );

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
	$uri  = get_template_directory_uri();
	$navb = ts_nav_bootstrap();
	?>
	<script>
		window.TS_THEME_URI    = <?php echo wp_json_encode( $uri ); ?>;
		window.TS_ARTICLE_BASE = <?php echo wp_json_encode( ts_article_base() ); ?>;
		/* Everything below comes from WordPress — the theme ships no demo content. */
		window.ARTICLES   = <?php echo wp_json_encode( ts_recent_posts( 24 ) ); ?>;
		window.NAV_ITEMS  = <?php echo wp_json_encode( $navb['nav'] ); ?>;
		window.MEGA_MENU  = <?php echo wp_json_encode( $navb['mega'] ); ?>;
		window.CATEGORIES = <?php echo wp_json_encode( $navb['categories'] ); ?>;
	</script>
	<script src="https://unpkg.com/react@18.3.1/umd/react.production.min.js" crossorigin="anonymous"></script>
	<script src="https://unpkg.com/react-dom@18.3.1/umd/react-dom.production.min.js" crossorigin="anonymous"></script>
	<script src="https://unpkg.com/@babel/standalone@7.29.0/babel.min.js" crossorigin="anonymous"></script>
	<script type="text/babel" src="<?php echo esc_url( $uri . '/assets/js/components.jsx' ); ?>?ver=<?php echo esc_attr( TS_THEME_VERSION ); ?>"></script>
	<?php
}

/**
 * Resolve a card image URL for a post: featured image, else the first inline
 * <img> in the content, else empty string.
 *
 * @param int    $post_id Post ID.
 * @param string $size    Image size.
 * @return string
 */
function ts_post_image_url( $post_id, $size = 'large' ) {
	$url = get_the_post_thumbnail_url( $post_id, $size );
	if ( $url ) {
		return $url;
	}
	$post = get_post( $post_id );
	if ( $post && preg_match( '/<img[^>]+src=["\']([^"\']+)["\']/i', $post->post_content, $m ) ) {
		return $m[1];
	}
	return '';
}

/**
 * Clean plain-text excerpt for the dek / card teaser.
 *
 * WordPress' get_the_excerpt() auto-generates from the *filtered* content,
 * which on single posts includes plugin-injected boxes (e.g. the AI summary),
 * and it leaves HTML entities (&nbsp;, [&hellip;]) as literal text. Since we
 * render the dek as plain React text, we build it from the raw post_content
 * instead (no the_content filters → no injected boxes) and decode entities.
 *
 * @param int $post_id Post ID.
 * @param int $words   Max words when auto-generating.
 * @return string
 */
function ts_clean_excerpt( $post_id = null, $words = 40 ) {
	$post_id = $post_id ? $post_id : get_the_ID();

	if ( has_excerpt( $post_id ) ) {
		$text = get_post_field( 'post_excerpt', $post_id );
	} else {
		$text = get_post_field( 'post_content', $post_id );
		$text = strip_shortcodes( $text );
		$text = wp_strip_all_tags( $text );
		$text = wp_trim_words( $text, $words, '…' );
	}

	$text = wp_strip_all_tags( $text );
	$text = html_entity_decode( $text, ENT_QUOTES, 'UTF-8' );

	return trim( preg_replace( '/\s+/u', ' ', $text ) );
}

/**
 * Estimated reading time for a post, from its raw content.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function ts_read_time( $post_id ) {
	$raw     = wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', $post_id ) ) );
	$minutes = max( 1, (int) ceil( mb_strlen( $raw ) / 400 ) );
	return $minutes . ' ' . __( 'นาที', 'the-standard' );
}

/**
 * Build one post in the article shape the React components expect.
 *
 * Takes a post (or ID) rather than relying on loop state, so it can be used
 * from any query without disturbing the main loop.
 *
 * @param int|WP_Post|null $post Post or ID.
 * @return array
 */
function ts_post_card( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return array();
	}

	$cats = get_the_category( $post->ID );

	return array(
		'id'       => (string) $post->ID,
		'url'      => get_permalink( $post ),
		'category' => ! empty( $cats ) ? $cats[0]->name : '',
		'title'    => get_the_title( $post ),
		'excerpt'  => ts_clean_excerpt( $post->ID, 28 ),
		'image'    => ts_post_image_url( $post->ID, 'large' ),
		'time'     => get_the_date( '', $post ),
		'author'   => get_the_author_meta( 'display_name', $post->post_author ),
		'readTime' => ts_read_time( $post->ID ),
	);
}

/**
 * Recent published posts, in card shape. Cached per-request because the nav's
 * mega-menu "featured" column needs it on every page.
 *
 * @param int $limit Number of posts.
 * @return array
 */
function ts_recent_posts( $limit = 24 ) {
	static $cache = array();
	if ( isset( $cache[ $limit ] ) ) {
		return $cache[ $limit ];
	}

	$q = new WP_Query(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => $limit,
			'no_found_rows'  => true,
		)
	);

	$out = array();
	foreach ( $q->posts as $p ) {
		$out[] = ts_post_card( $p );
	}

	$cache[ $limit ] = $out;
	return $out;
}

/**
 * Navigation built from the site's real categories.
 *
 * HOME plus each top-level category (linking to its archive). A category with
 * children gets a mega panel listing them; the components fall back to a plain
 * link when it has none.
 *
 * @return array{nav:array,mega:array,categories:array}
 */
function ts_nav_bootstrap() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}

	$nav = array(
		array(
			'label' => __( 'HOME', 'the-standard' ),
			'url'   => home_url( '/' ),
			'cat'   => null,
			'mega'  => null,
		),
	);
	$mega       = array();
	$cat_labels = array( 'ALL' );

	$parents = get_categories(
		array(
			'parent'     => 0,
			'hide_empty' => true,
			'orderby'    => 'count',
			'order'      => 'DESC',
			'number'     => 9,
		)
	);

	foreach ( $parents as $cat ) {
		$cat_labels[] = $cat->name;
		$cat_url      = get_category_link( $cat->term_id );

		$topics   = array();
		$children = get_categories(
			array(
				'parent'     => $cat->term_id,
				'hide_empty' => true,
				'number'     => 12,
			)
		);
		foreach ( $children as $child ) {
			$topics[] = array(
				'label' => $child->name,
				'url'   => get_category_link( $child->term_id ),
			);
		}

		if ( $topics ) {
			$mega[ $cat->name ] = array(
				'label'  => $cat->name,
				'color'  => '#E6332A',
				'url'    => $cat_url,
				'layout' => 'list',
				'topics' => $topics,
			);
		}

		$nav[] = array(
			'label' => $cat->name,
			'url'   => $cat_url,
			'cat'   => $cat->name,
			'mega'  => $topics ? $cat->name : null,
		);
	}

	$cache = array(
		'nav'        => $nav,
		'mega'       => $mega,
		'categories' => $cat_labels,
	);
	return $cache;
}

/**
 * Emit the current archive (category / tag / search / date) for the React app.
 *
 * Reads the main query without touching loop state, so templates can call this
 * before handing off to template-parts/archive-app.php.
 */
function ts_the_archive_data() {
	global $wp_query;

	if ( is_tag() ) {
		$kind  = 'tag';
		$label = __( 'แท็ก', 'the-standard' );
		$title = single_term_title( '', false );
	} elseif ( is_category() ) {
		$kind  = 'category';
		$label = __( 'หมวดหมู่', 'the-standard' );
		$title = single_term_title( '', false );
	} elseif ( is_search() ) {
		$kind  = 'search';
		$label = __( 'ผลการค้นหา', 'the-standard' );
		$title = get_search_query();
	} else {
		$kind  = 'archive';
		$label = __( 'คลังบทความ', 'the-standard' );
		$title = wp_strip_all_tags( get_the_archive_title() );
	}

	$posts = array();
	foreach ( (array) $wp_query->posts as $p ) {
		$posts[] = ts_post_card( $p );
	}

	$paged = max( 1, (int) get_query_var( 'paged' ) );
	$total = max( 1, (int) $wp_query->max_num_pages );

	$archive = array(
		'kind'        => $kind,
		'kindLabel'   => $label,
		'title'       => $title,
		'description' => trim( wp_strip_all_tags( term_description() ) ),
		'count'       => (int) $wp_query->found_posts,
		'homeUrl'     => home_url( '/' ),
	);

	$pagination = array(
		'current' => $paged,
		'total'   => $total,
		'isPaged' => $paged > 1,
		'prev'    => $paged > 1 ? get_pagenum_link( $paged - 1 ) : '',
		'next'    => $paged < $total ? get_pagenum_link( $paged + 1 ) : '',
	);
	?>
	<script>
		window.TS_ARCHIVE            = <?php echo wp_json_encode( $archive ); ?>;
		window.TS_ARCHIVE_POSTS      = <?php echo wp_json_encode( $posts ); ?>;
		window.TS_ARCHIVE_PAGINATION = <?php echo wp_json_encode( $pagination ); ?>;
	</script>
	<?php
}
