<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Compass
 * @since Compass 1.0
 */

get_header(); ?>

<section id="content-main" <?php if($custom['Block1BgColour'][0] != ''): echo' style="background-color:'.$custom['Block1BgColour'][0].'"'; endif;?>>
<div class="inner">	
 <h1>Blog</h1>
		<?php $i = 1; while ( have_posts() ) : the_post();  global $post; $custom = get_post_custom();  ?>

<?php
?>
<div class="omsc-one-third <?php if($i == 3): echo 'omsc-last';$i=0; endif;?>">
<article>
<p><a href="<?php the_permalink();?>"><?php the_post_thumbnail('medium');?></a></p>

<h3><?php the_title();?></h3>
<?php the_excerpt();?>
<p><a href="<?php the_permalink();?>">View full details &rsaquo;</a></p>
  </article>    
</div>
<?php if($i==0):?><div class="omsc-clear"></div><?php endif;?>
<?php 
$i++;?>
       
    
			<?php endwhile; // end of the loop. ?>
               
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
				<div id="nav-below" class="navigation">
					<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentyten' ) ); ?></div>
					<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
				</div><!-- #nav-below -->
<?php endif; ?>
	</div>
    </section>
<?php get_footer(); ?>