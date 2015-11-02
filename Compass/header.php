<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Compass
 * @since Compass 1.0
 */

global $post;
global $wpdb;
$c = get_post_custom($post->ID);
  if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'ADMIN' || get_user_meta(get_current_user_id(),'AccessLevel',true) == 'CEO' || is_super_admin()):$args = array('title_li' => '', 'exclude' => '2,3,6,7,10,11,14,15,18,19,56,24,35,21,23');
  elseif(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'General Manager'):$args = array('title_li' => '', 'exclude' => '2,3,6,7,10,11,14,15,18,19,56,24,35,21,23');
  elseif(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):$args = array('title_li' => '', 'exclude' => '2,3,6,7,10,11,14,15,18,19,56,24,35,21,23');
  elseif(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Staff Member'):$args = array('title_li' => '', 'exclude' => '2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,21,22,56,24,35,21,23');
  elseif(is_super_admin()):$args = array('title_li' => '', 'exclude' => '2,3,6,7,10,11,14,15,18,19,56,24,35,21,23');
  else:$args = array('title_li' => '', 'exclude' => '2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,56,24,35');
  endif;
  $hidden = explode(',',$args['exclude']);
#echo $post->ID;
if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Staff Member'):
wp_logout();
 wp_redirect(esc_url( home_url( '/' ) ));
elseif(in_array($post->ID,$hidden)  && $post->ID != 3 && get_user_meta(get_current_user_id(),'AccessLevel',true) != 'ADMIN'  && get_user_meta(get_current_user_id(),'AccessLevel',true) != 'CEO' && get_user_meta(get_current_user_id(),'AccessLevel',true) != 'CEO' && get_user_meta(get_current_user_id(),'AccessLevel',true) != 'General Manager'):
#wp_redirect(esc_url( home_url( '/' ) ));

elseif (!is_user_logged_in() && $post->ID != 3 && get_the_title($post->ID)!= 'Lost Password'):
 wp_redirect(esc_url( home_url( '/' ) ));
endif;
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta name="viewport"  id="viewport" content="width=980" />
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico?1433756347" /> 
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
 <?php wp_enqueue_script('jquery'); ?>
<?php wp_head(); ?>
<link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<link rel='stylesheet'  href='/wp-content/themes/Compass/style.css?ver=3.5.1' type='text/css' media='all' />
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
google.load('visualization', '1.1', {packages: ['bar']});
jQuery( document ).ready( function() {
	
	<?php if (isset($_POST['groupr']) && $_POST['groupr'] != ''): ?>
	var bc = jQuery('#breadcrumbs').html().replace('Reporting', 'Reporting - <?php echo get_the_title($_POST['groupr']);?>');
	jQuery('#breadcrumbs').html(bc);
	<?php endif;?>
	
	<?php if (isset($_POST['staffr']) && $_POST['staffr'] != ''): ?>
	var bc = jQuery('#breadcrumbs').html().replace('Reporting', 'Reporting - <?php echo get_user_meta($_POST['staffr'],'Name',true);?>');
	jQuery('#breadcrumbs').html(bc);
	<?php endif;?>
	<?php if (isset($_POST['roler']) && $_POST['roler'] != ''): ?>
	var bc = jQuery('#breadcrumbs').html().replace('Reporting', 'Reporting - <?php echo get_the_title($_POST['roler']);?>');
	jQuery('#breadcrumbs').html(bc);
	<?php endif;?>
	
	
	<?php if (isset($_GET['sid'])): ?>
	var bc = jQuery('#breadcrumbs').html().replace('View Staff', 'View Staff - <?php echo get_user_meta($_GET['sid'],'Name',true);?>');
	jQuery('#breadcrumbs').html(bc);
	var bc = jQuery('#breadcrumbs').html().replace('Edit Staff', 'Edit Staff - <?php echo get_user_meta($_GET['sid'],'Name',true);?>');
	jQuery('#breadcrumbs').html(bc);
	<?php endif;?>
	<?php if (isset($_GET['rid'])): ?>
	var bc = jQuery('#breadcrumbs').html().replace('View Role', 'View Role - <?php echo get_the_title($_GET['rid']);?>');
	jQuery('#breadcrumbs').html(bc);
	var bc = jQuery('#breadcrumbs').html().replace('Edit Role', 'Edit Role - <?php echo get_the_title($_GET['rid']);?>');
	jQuery('#breadcrumbs').html(bc);
	<?php endif;?>
	<?php if (isset($_GET['cid'])): ?>
	var bc = jQuery('#breadcrumbs').html().replace('View Competency', 'View Competency - <?php echo get_the_title($_GET['cid']);?>');
	jQuery('#breadcrumbs').html(bc);
	var bc = jQuery('#breadcrumbs').html().replace('Edit Competency', 'Edit Competency - <?php echo get_the_title($_GET['cid']);?>');
	jQuery('#breadcrumbs').html(bc);
	<?php endif;?>
	<?php if (isset($_GET['gid'])): ?>
	var bc = jQuery('#breadcrumbs').html().replace('View Special Group', 'View Special Group - <?php echo get_the_title($_GET['gid']);?>');
	jQuery('#breadcrumbs').html(bc);
	var bc = jQuery('#breadcrumbs').html().replace('Edit Special Group', 'Edit Special Group - <?php echo get_the_title($_GET['gid']);?>');
	jQuery('#breadcrumbs').html(bc);
	<?php endif;?>
jQuery('nav .page-item-4 .children').append('<li><a href="<?php  echo esc_url( home_url( '/' ));?>competencies/">View competencies</a></li>');
jQuery('nav .page-item-8 .children').append('<li><a href="<?php  echo esc_url( home_url( '/' ));?>staff/">View staff</a></li>');
jQuery('nav .page-item-12 .children').append('<li><a href="<?php  echo esc_url( home_url( '/' ));?>roles/">View roles</a></li>');
jQuery('nav .page-item-16 .children').append('<li><a href="<?php  echo esc_url( home_url( '/' ));?>special_group/">View Special Groups</a></li>');
jQuery('nav .page-item-4 a,nav .page-item-8 a,nav .page-item-12 a,nav .page-item-16 a').not('nav .page-item-4 li a,nav .page-item-8 li a,nav .page-item-12 li a,nav .page-item-16 li a').click(function(e){
e.preventDefault();	
});
jQuery('#AjaxRole').change(function () {
	if(jQuery(this).val() != ''){
	 var dataString = "type=" + jQuery('#type').val()+"&user_id=" + jQuery('#user_id').val()+"&Blog=" + jQuery('#Blog').val()+'&RoleID='+jQuery(this).val();
	
								jQuery.ajax({
							type: "POST",
							url: "/wp-content/themes/Compass/includes/ajax.php",
							data: dataString,
							success:  function(data) {
								jQuery("#ajax-result").empty();
								
								jQuery("#ajax-result").html(data);
								
								
								
							    }
    
						});	
	}

});
jQuery('#role').on('change', function() {
window.location.replace('<?php  echo esc_url( home_url( '/' ));?>/view_role/?rid='+this.value);
});
jQuery('#staff').on('change', function() {
window.location.replace('<?php  echo esc_url( home_url( '/' ));?>/view_staff/?sid='+this.value);
});
jQuery('#group').on('change', function() {

window.location.replace('<?php  echo esc_url( home_url( '/' ));?>/view_special_group/?gid='+this.value);
});
jQuery('#jobno').on('change', function() {
window.location.replace('<?php  echo esc_url( home_url( '/' ));?>/view_role/?rid='+this.value);
});
jQuery(".navbar-toggle").click(function(){

jQuery("nav").toggleClass('active');

});
jQuery(".filter").click(function(){

jQuery("table tr.all").hide();
jQuery("table tr."+jQuery(this).attr("rel")).show();

});
jQuery(".inline").colorbox({inline:true, width:"80%", height:"80%"});
});
</script>
</head>
<?php if(is_user_logged_in()):$logged = 'loggedin'; else: $logged = 'loggedout'; endif;?> 
<body <?php body_class($logged);?>>

<nav>
<div  class="inner">
<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) );?>"><?php echo get_bloginfo('name');?></a>
<ul>

  <?php
  
  
  
  wp_list_pages($args);
  
   ?>
   <?php if(is_user_logged_in()): ?>
  <li class="logout"><a href="<?php echo wp_logout_url(home_url( '/' )); ?>" >Logout</a></li>
  <?php wp_list_pages('include=23&title_li=');wp_list_pages('include=21&title_li='); endif;?>
  </ul>
    </div>
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-menu">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    </button>
</nav>
<?php if ( function_exists('yoast_breadcrumb') ) {
	yoast_breadcrumb('<p id="breadcrumbs">','</p>');
} ?>