<?php
/**
 * Tag archive — posts tagged with one term.
 *
 * @package the-standard
 */

get_header();
ts_the_archive_data();
get_template_part( 'template-parts/archive-app' );
get_footer();
