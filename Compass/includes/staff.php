<?php
if($_POST['Delete'] && $_POST['ConfirmDelete'] == 'DELETE'):
include($_SERVER['DOCUMENT_ROOT'].'/wp-admin/includes/user.php');
wp_delete_user( $_POST['Delete'], 1 );
$notification =  '<p class="notification success">Staff member deleted</p>';
elseif($_POST['Delete'] && $_POST['ConfirmDelete'] != 'DELETE'):
$notification =  '<p class="notification fail">You did not confirm the Deletion</p>';
endif;
?>
<header>
<h1><?php the_title();?></h1>
<?php echo $notification;?>
</header>
<div class="items">
<?php
$args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	
	'fields'       => 'all',
	
 );
 if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'General Manager'):
 $args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	'meta_key'     => 'Manager',
	'meta_value'   => array(get_current_user_id(),get_user_meta(get_current_user_id(),'Manager',true)),
	'meta_compare' => 'IN',
	'fields'       => 'all',
	
 );
 elseif(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):
 $args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	'meta_key'     => 'Manager',
	'meta_value'   => get_current_user_id(),
	'meta_compare' => '=',
	'fields'       => 'all',
	
 );
 else:
 $args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	
	'fields'       => 'all',
	
 );
 endif;

$blogusers = get_users( $args );
if(count($blogusers)==0):?>
<p>No Staff have been added. <a href="add_staff/">Start here</a>.</p>
<?php else:?>


<strong>Filter:</strong><br />
<a class="filter" rel="all">See all</a>  |  
<?php if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'ADMIN' ||  is_super_admin()): ?><a class="filter" rel="ceo">CEO</a>  |  <?php endif;?> 
<?php if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'ADMIN' ||  is_super_admin() || get_user_meta(get_current_user_id(),'AccessLevel',true) == 'CEO'):?><a class="filter" rel="generalmanager">General Managers (GM)</a>  |  <?php endif;?> 
<?php if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'ADMIN' ||  is_super_admin() || get_user_meta(get_current_user_id(),'AccessLevel',true) == 'CEO' || get_user_meta(get_current_user_id(),'AccessLevel',true) == 'General Manager'):?><a class="filter" rel="linemanager">Line Managers (LM)</a>  |  <?php endif;?><a class="filter" rel="staffmember">Staff (S)</a><br /><br />
<table class="table table-bordered">
<tbody>
<tr class=" ">
<td>Name</td>
<td>Role</td>
<td>Job No</td>
<td>Access Level</td>
<td>Actions</td>
</tr>
<?php

// Array of WP_User objects.
foreach ( $blogusers as $user ):
?>
<tr class="all <?php echo strtolower(str_replace(' ','',get_user_meta($user->ID,'AccessLevel',true)));?>">
<td><?php echo get_user_meta($user->ID,'Name',true);?></td>
<td><?php if(get_user_meta($user->ID,'Role',true) != ''): echo get_the_title(get_user_meta($user->ID,'Role',true)); endif;?></td>
<td><?php if(get_user_meta($user->ID,'Role',true) != ''): echo get_post_meta(get_user_meta($user->ID,'Role',true),'JobNumber',true); endif;?></td>

<td><?php echo str_replace('Staff Member','S',str_replace('Line Manager','LM',str_replace('General Manager','GM',get_user_meta($user->ID,'AccessLevel',true))));?></td>
<td><a href="<?php  echo esc_url( home_url( '/' ));?>/view_staff/?sid=<?php echo $user->ID;?>">Details</a> |  <a href="<?php  echo esc_url( home_url( '/' ));?>/edit_staff/?sid=<?php echo $user->ID;?>">Edit</a> | <a href="<?php  echo esc_url( home_url( '/' ));?>/reporting/?staffr=<?php echo $user->ID;?>">Report</a> | <a class="inline " href="#Delete-<?php echo $user->ID;?>">Delete</a>
<div style="display:none">
<div id="Delete-<?php echo $user->ID;?>"><form action="" method="post" class="popup">
<header><h1>Confirm deletion</h1></header>
<label>To delete this record enter the word DELETE below and submit</label>
<input type="text" name="ConfirmDelete" />
<input type="hidden" name="Delete" value="<?php echo $user->ID;?>" />
<button type="submit" name="submit"  class="btn btn-primary btn-sm text-center btn-log mt-20" >Delete</button>

</form></div>
</div>
</td>
</tr>
<?php endforeach; 
wp_reset_postdata();?>
</tbody>
</table>
<?php endif;?>
</div>