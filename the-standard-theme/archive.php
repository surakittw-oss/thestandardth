<?php
/**
 * Generic archive (date, author, custom) — keeps these URLs from falling through to the homepage.
 *
 * @package the-standard
 */

get_header();
ts_the_archive_data();
get_template_part( 'template-parts/archive-app' );
get_footer();
