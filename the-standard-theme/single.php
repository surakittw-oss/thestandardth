<?php
/**
 * Single post — THE STANDARD article page.
 *
 * Pulls the real post from the WordPress database (title, content, author,
 * featured image, category, tags, related posts) and hands it to the React
 * <ArticlePage> component via window.TS_ARTICLE / window.TS_RELATED.
 *
 * @package the-standard
 */

get_header();

$ts_article = null;
$ts_related = array();

while ( have_posts() ) :
	the_post();

	$cats    = get_the_category();
	$cat     = ! empty( $cats ) ? $cats[0] : null;
	$content = apply_filters( 'the_content', get_the_content() );
	$minutes = max( 1, (int) ceil( mb_strlen( wp_strip_all_tags( $content ) ) / 400 ) );

	$tags     = array();
	$tag_urls = array();
	foreach ( (array) get_the_tags() as $t ) {
		if ( $t instanceof WP_Term ) {
			$tags[]     = $t->name;
			$tag_urls[] = get_tag_link( $t->term_id );
		}
	}

	$thumb = get_the_post_thumbnail_url( null, 'large' );

	$ts_article = array(
		'id'          => get_the_ID(),
		'category'    => $cat ? $cat->name : '',
		'categoryUrl' => $cat ? get_category_link( $cat->term_id ) : home_url( '/' ),
		'homeUrl'     => home_url( '/' ),
		'title'       => get_the_title(),
		'excerpt'     => wp_strip_all_tags( get_the_excerpt() ),
		'content'     => $content,
		'image'       => $thumb ? $thumb : '',
		'author'      => get_the_author(),
		'authorBio'   => get_the_author_meta( 'description' ),
		'time'        => get_the_date(),
		'readTime'    => $minutes . ' ' . __( 'นาที', 'the-standard' ),
		'tags'        => $tags,
		'tagUrls'     => $tag_urls,
	);

	// Related posts from the same category.
	if ( $cat ) {
		$rq = new WP_Query(
			array(
				'category__in'        => array( $cat->term_id ),
				'post__not_in'        => array( get_the_ID() ),
				'posts_per_page'      => 4,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);
		while ( $rq->have_posts() ) :
			$rq->the_post();
			$rcats  = get_the_category();
			$rthumb = get_the_post_thumbnail_url( null, 'medium' );
			$ts_related[] = array(
				'id'       => get_the_ID(),
				'url'      => get_permalink(),
				'category' => ! empty( $rcats ) ? $rcats[0]->name : '',
				'title'    => get_the_title(),
				'image'    => $rthumb ? $rthumb : '',
				'time'     => get_the_date(),
			);
		endwhile;
		wp_reset_postdata();
	}

endwhile;
?>
<script>
	window.TS_ARTICLE = <?php echo wp_json_encode( $ts_article ); ?>;
	window.TS_RELATED = <?php echo wp_json_encode( $ts_related ); ?>;
</script>
<?php
get_template_part( 'template-parts/article-app' );
get_footer();
