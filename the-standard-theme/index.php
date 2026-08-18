<?php
/**
 * Fallback template — renders the homepage.
 *
 * WordPress uses front-page.php for the site's front page; index.php is the
 * required catch-all and mirrors it here.
 *
 * @package the-standard
 */

get_header();
ts_the_home_data();
get_template_part( 'template-parts/home-app' );
get_footer();
