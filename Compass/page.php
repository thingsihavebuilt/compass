<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Compass
 * @sincludese Compass 1.0
 */

global $blog_id;
get_header(); 

?>
<section id="content">

<?php while ( have_posts() ) : the_post(); global $post;global  $wpdb; $custom = get_post_custom();  ?>
<article>
<?php
if($blog_id==1):?>
<div class="inner">
<?php the_content();?>
</div>
<?php

else:
if($post->ID == 3):include('includes/welcome.php'); endif;?>
<div class="inner">
<?php 

if($post->ID == 3):include('includes/login.php');
elseif($post->ID == 4):include('includes/competencies.php');
elseif($post->ID == 5):include('includes/addcompetency.php');
elseif($post->ID == 6):include('includes/editcompetency.php');
elseif($post->ID == 7):include('includes/viewcompetency.php');
elseif($post->ID == 8):include('includes/staff.php');
elseif($post->ID == 9):include('includes/addstaff.php');
elseif($post->ID == 10):include('includes/editstaff.php');
elseif($post->ID == 11):include('includes/viewstaff.php');
elseif($post->ID == 12):include('includes/roles.php');
elseif($post->ID == 13):include('includes/addrole.php');
elseif($post->ID == 14):include('includes/editrole.php');
elseif($post->ID == 15):include('includes/viewrole.php');
elseif($post->ID == 16):include('includes/groups.php');
elseif($post->ID == 17):include('includes/addgroup.php');
elseif($post->ID == 18):include('includes/editgroup.php');
elseif($post->ID == 19):include('includes/viewgroup.php');
elseif($post->ID == 20):include('includes/success.php');
elseif($post->ID == 21):include('includes/search.php');
elseif($post->ID == 22):include('includes/reporting.php');
elseif($post->ID == 23):include('includes/account.php');
elseif($post->ID == 35 || get_the_title($post->ID) == 'Lost Password'):include('includes/lost-password.php');
elseif($post->ID == 67):
$args = array( 'posts_per_page' => 200,  'post_type' => 'page', 'orderby' => 'post_title', 'order' => 'ASC' );
$myposts = get_posts( $args );
foreach ( $myposts as $post ) : setup_postdata( $post );
?>
<article>
<?php
if($post->ID == 3):include('includes/welcome.php'); endif;?>
<div class="inner">
<?php 

if($post->ID == 3):include('includes/login.php');
elseif($post->ID == 4):include('includes/competencies.php');
elseif($post->ID == 5):include('includes/addcompetency.php');
elseif($post->ID == 6):include('includes/editcompetency.php');
elseif($post->ID == 7):include('includes/viewcompetency.php');
elseif($post->ID == 8):include('includes/staff.php');
elseif($post->ID == 9):include('includes/addstaff.php');
elseif($post->ID == 10):include('includes/editstaff.php');
elseif($post->ID == 11):include('includes/viewstaff.php');
elseif($post->ID == 12):include('includes/roles.php');
elseif($post->ID == 13):include('includes/addrole.php');
elseif($post->ID == 14):include('includes/editrole.php');
elseif($post->ID == 15):include('includes/viewrole.php');
elseif($post->ID == 16):include('includes/groups.php');
elseif($post->ID == 17):include('includes/addgroup.php');
elseif($post->ID == 18):include('includes/editgroup.php');
elseif($post->ID == 19):include('includes/viewgroup.php');
elseif($post->ID == 20):include('includes/success.php');
elseif($post->ID == 21):include('includes/search.php');
elseif($post->ID == 22):include('includes/reporting.php');
elseif($post->ID == 23):include('includes/account.php');
elseif($post->ID == 56):include('includes/lost-password.php');
else:?>
<?php the_content();?>
<?php endif;?>
</div>
</article>
<?php
endforeach;
else:?>

<?php the_content();?>
<?php endif;?>
</div>
</article>
<?php endif;
endwhile; // end of the loop. 
?>
</section>
<?php get_footer(); ?>
