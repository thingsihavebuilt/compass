<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Compass
 * @since Compass 1.0
 */

get_header(); ?>

	
 <section id="searchresults">
<div class="inner">    
<h1>Search results:</h1>
<?php while ( have_posts() ) : the_post(); ?>
<div class="searchitem">
<h2><?php the_title();?></h2>
<?php the_excerpt();?>
<p><a href="<?php the_permalink();?>">Read more &gt;</a></p>
</div>
<?php endwhile; // end of the loop. ?>
</div>
</section>
	
<?php get_footer(); ?>