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

define( 'TS_THEME_VERSION', '2.6.0' );

// Appearance → Homepage settings screen.
require_once get_template_directory() . '/inc/homepage-settings.php';

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
 * Posts for the homepage hero.
 *
 * Chosen explicitly in Appearance → Homepage (drag to order — the first row
 * leads) rather than via WordPress' native "sticky" posts: stick_post()
 * always appends to the sticky list, so pinning a new lead story would push
 * it to the *back* of the queue instead of the front. Recent posts fill any
 * slots left over, so the hero is never short.
 *
 * @param int|null $limit Override the configured hero size.
 * @return array
 */
function ts_featured_posts( $limit = null ) {
	$settings = ts_home_settings();
	$limit    = $limit ? (int) $limit : (int) $settings['hero_count'];
	$limit    = max( 1, $limit );

	static $cache = array();
	if ( isset( $cache[ $limit ] ) ) {
		return $cache[ $limit ];
	}

	$ids = array_slice( array_map( 'intval', $settings['hero_posts'] ), 0, $limit );

	if ( count( $ids ) < $limit ) {
		$fill = new WP_Query(
			array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'post__not_in'        => $ids,
				'posts_per_page'      => $limit - count( $ids ),
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);
		foreach ( $fill->posts as $p ) {
			$ids[] = (int) $p->ID;
		}
	}

	$out = array();
	foreach ( $ids as $id ) {
		$out[] = ts_post_card( $id );
	}

	$cache[ $limit ] = $out;
	return $out;
}

/**
 * Homepage content blocks, one per category / tag.
 *
 * Configured in Appearance → Homepage (drag to reorder, set the heading and how
 * many posts each block shows). With nothing configured it falls back to the
 * four busiest top-level categories, so a fresh install still looks complete.
 *
 * Blocks whose term no longer exists or has no published posts are skipped.
 *
 * @return array
 */
function ts_home_sections() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}

	$specs    = array();
	$settings = ts_home_settings();

	foreach ( $settings['sections'] as $row ) {
		if ( empty( $row['term'] ) || false === strpos( $row['term'], ':' ) ) {
			continue;
		}
		list( $tax, $term_id ) = explode( ':', $row['term'], 2 );
		$term                  = get_term( (int) $term_id, $tax );
		if ( ! $term || is_wp_error( $term ) ) {
			continue;
		}
		$link = get_term_link( $term );

		$specs[] = array(
			'label'    => '' !== $row['label'] ? $row['label'] : $term->name,
			'url'      => is_wp_error( $link ) ? '' : $link,
			'sub'      => '',
			'count'    => max( 1, (int) $row['count'] ),
			'taxonomy' => $tax,
			'term_id'  => (int) $term_id,
		);
	}

	if ( ! $specs ) {
		$cats = get_categories(
			array(
				'parent'     => 0,
				'hide_empty' => true,
				'orderby'    => 'count',
				'order'      => 'DESC',
				'number'     => 4,
			)
		);
		foreach ( $cats as $cat ) {
			$specs[] = array(
				'label'    => $cat->name,
				'url'      => get_category_link( $cat->term_id ),
				'sub'      => '',
				'count'    => 6,
				'taxonomy' => 'category',
				'term_id'  => (int) $cat->term_id,
			);
		}
	}

	$sections = array();
	foreach ( $specs as $spec ) {
		if ( ! $spec['taxonomy'] || ! $spec['term_id'] ) {
			continue;
		}

		$q = new WP_Query(
			array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => $spec['count'],
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
				'tax_query'           => array(
					array(
						'taxonomy' => $spec['taxonomy'],
						'field'    => 'term_id',
						'terms'    => array( $spec['term_id'] ),
					),
				),
			)
		);
		if ( ! $q->posts ) {
			continue;
		}

		$posts = array();
		foreach ( $q->posts as $p ) {
			$posts[] = ts_post_card( $p );
		}

		$sections[] = array(
			'label' => $spec['label'],
			'url'   => $spec['url'],
			'sub'   => $spec['sub'],
			'posts' => $posts,
		);
	}

	$cache = $sections;
	return $cache;
}

/**
 * The optional "latest posts" block at the foot of the homepage.
 *
 * Skips anything already shown in the hero so the block doesn't repeat it.
 *
 * @return array|null Null when disabled or empty.
 */
function ts_home_latest() {
	$settings = ts_home_settings();
	if ( empty( $settings['show_latest'] ) ) {
		return null;
	}

	$hero_ids = array_map( 'intval', wp_list_pluck( ts_featured_posts(), 'id' ) );

	$q = new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'post__not_in'        => $hero_ids,
			'posts_per_page'      => (int) $settings['latest_count'],
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
	if ( ! $q->posts ) {
		return null;
	}

	$posts = array();
	foreach ( $q->posts as $p ) {
		$posts[] = ts_post_card( $p );
	}

	return array(
		'label' => '' !== $settings['latest_label'] ? $settings['latest_label'] : __( 'ข่าวล่าสุด', 'the-standard' ),
		'posts' => $posts,
	);
}

/**
 * Emit the homepage payload (hero + section blocks + latest) for the React app.
 */
function ts_the_home_data() {
	?>
	<script>
		window.TS_FEATURED      = <?php echo wp_json_encode( ts_featured_posts() ); ?>;
		window.TS_HOME_SECTIONS = <?php echo wp_json_encode( ts_home_sections() ); ?>;
		window.TS_HOME_LATEST   = <?php echo wp_json_encode( ts_home_latest() ); ?>;
	</script>
	<?php
}

/**
 * Parse "key:value; key2:value2" out of a nav menu item's Description field.
 * Lets an editor set a mega-panel accent color / layout per top-level item
 * without touching code — e.g. description "color:#1877F2; layout:visual".
 *
 * @param string $description Menu item description.
 * @return array
 */
function ts_parse_menu_item_opts( $description ) {
	$opts = array();
	if ( ! $description ) {
		return $opts;
	}
	foreach ( explode( ';', $description ) as $pair ) {
		$pair = trim( $pair );
		if ( false === strpos( $pair, ':' ) ) {
			continue;
		}
		list( $key, $val ) = array_map( 'trim', explode( ':', $pair, 2 ) );
		if ( $key ) {
			$opts[ $key ] = $val;
		}
	}
	return $opts;
}

/**
 * Build the nav from a WordPress menu, if one is assigned to the "primary"
 * location (Appearance → Menus). This is what makes the bar drag-to-reorder:
 * item order, nesting (→ mega-menu submenu) and labels all come from there.
 *
 * @return array{nav:array,mega:array,categories:array}|null Null when no menu is assigned.
 */
function ts_nav_from_menu() {
	$locations = get_nav_menu_locations();
	if ( empty( $locations['primary'] ) ) {
		return null;
	}

	$menu = wp_get_nav_menu_object( $locations['primary'] );
	$items = $menu ? wp_get_nav_menu_items( $menu->term_id ) : false;
	if ( ! $items ) {
		return null;
	}

	// Group by parent so drag-to-nest (Appearance → Menus) becomes the mega submenu.
	$children_of = array();
	foreach ( $items as $item ) {
		$children_of[ (int) $item->menu_item_parent ][] = $item;
	}

	$nav        = array();
	$mega       = array();
	$cat_labels = array( 'ALL' );

	foreach ( ( $children_of[0] ?? array() ) as $item ) {
		$label = $item->title;
		$url   = $item->url;
		$opts  = ts_parse_menu_item_opts( $item->description );

		$topics = array();
		foreach ( ( $children_of[ (int) $item->ID ] ?? array() ) as $kid ) {
			$topics[] = array( 'label' => $kid->title, 'url' => $kid->url );
		}

		if ( $topics ) {
			$mega[ $label ] = array(
				'label'  => $label,
				'color'  => isset( $opts['color'] ) ? $opts['color'] : '#E6332A',
				'url'    => $url,
				'layout' => isset( $opts['layout'] ) ? $opts['layout'] : 'list',
				'topics' => $topics,
			);
		}

		$cat_labels[] = $label;
		$nav[]        = array(
			'label' => $label,
			'url'   => $url,
			'cat'   => $label,
			'mega'  => $topics ? $label : null,
		);
	}

	// Prepend Home unless the editor already added their own link to it.
	$home_url = home_url( '/' );
	$has_home = ! empty( $nav ) && untrailingslashit( $nav[0]['url'] ) === untrailingslashit( $home_url );
	if ( ! $has_home ) {
		array_unshift(
			$nav,
			array(
				'label' => __( 'HOME', 'the-standard' ),
				'url'   => $home_url,
				'cat'   => null,
				'mega'  => null,
			)
		);
	}

	return array(
		'nav'        => $nav,
		'mega'       => $mega,
		'categories' => $cat_labels,
	);
}

/**
 * Navigation for the header.
 *
 * Prefers a WordPress menu assigned to the "primary" location (Appearance →
 * Menus — drag to reorder, drag to nest for a mega submenu, add Pages/
 * Categories/custom links freely). Falls back to auto-building HOME + each
 * top-level category when no menu is assigned, so the theme still works with
 * zero setup.
 *
 * @return array{nav:array,mega:array,categories:array}
 */
function ts_nav_bootstrap() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}

	$from_menu = ts_nav_from_menu();
	if ( $from_menu ) {
		$cache = $from_menu;
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
