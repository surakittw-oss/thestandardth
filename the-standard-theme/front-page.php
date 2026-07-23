<?php
/**
 * Front page — THE STANDARD homepage.
 *
 * Injects recent WordPress posts as window.TS_POSTS; home-app.php uses them
 * to override the static window.ARTICLES so cards render (and link to) live posts.
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
