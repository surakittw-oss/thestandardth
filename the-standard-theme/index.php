<?php
/**
 * Fallback template — renders the homepage.
 *
 * WordPress uses front-page.php for the site's front page; index.php is the
 * required catch-all and mirrors it here (also injects live posts).
 *
 * @package the-standard
 */

get_header();
$ts_posts = ts_home_posts( 24 );
?>
<script>window.TS_POSTS = <?php echo wp_json_encode( $ts_posts ); ?>;</script>
<?php
get_template_part( 'template-parts/home-app' );
get_footer();
