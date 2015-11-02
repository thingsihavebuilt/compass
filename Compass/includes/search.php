<?php if($_POST['submit'] && $_POST['searchterm'] == ''):
$notification =  '<p class="notification fail">You did not enter a search term</p>';
endif;

?>
<header>
<h1>Search</h1>
<?php echo $notification;?>
</header>
<div class="items">
<form action="" method="post">
<p>Enter a search term</p>
<input type="text" name="searchterm" value="<?php echo $_POST['searchterm'];?>" />
<input type="submit" name="submit" value="Search" class="btn btn-primary btn-sm text-center btn-log mt-20 submit" />
<?php
if($_POST['submit'] && $_POST['searchterm'] != ''):
echo '<p>Possible  matches:</p>';
else:
#echo '<p>Or use these dropdowns to search:</p>';
endif;
?>



<?php
if($_POST['submit'] && $_POST['searchterm'] != ''):
$jobnumbers = array();
if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'General Manager'):
$args = array( 'posts_per_page' => 200,  'post_type' => 'role', 'orderby' => 'post_title', 'order' => 'ASC','meta_key'     => 'Manager',
	'meta_value'   => array(get_current_user_id(),get_user_meta(get_current_user_id(),'Manager',true)),
	'meta_compare' => 'IN' );
	if($_POST['submit'] && $_POST['searchterm'] != ''):
	$args["s"] = esc_attr($_POST['searchterm']);
	endif;
	
 elseif(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):
 $args = array( 'posts_per_page' => 200,  'post_type' => 'role', 'orderby' => 'post_title', 'order' => 'ASC','meta_key'     => 'Manager',
	'meta_value'   => array(get_current_user_id()),
	'meta_compare' => 'IN' );
	if($_POST['submit'] && $_POST['searchterm'] != ''):
	$args["s"] = esc_attr($_POST['searchterm']);
	endif;
 else:
 $args = array( 'posts_per_page' => 200,  'post_type' => 'role', 'orderby' => 'post_title', 'order' => 'ASC' );
 	if($_POST['submit'] && $_POST['searchterm'] != ''):
	$args["s"] = esc_attr($_POST['searchterm']);
	endif;
 endif;
$myposts = get_posts( $args );
if(count($myposts) > 0): ?>
<h3>Roles</h3>
<table class="table table-bordered">
<tbody>
<?php
foreach ( $myposts as $post ) :  ?>
<tr>
<td><?php the_title();?></td>
<td><a href="<?php  echo esc_url( home_url( '/' ));?>/view_role/?rid=<?php echo $post->ID;?>">Details</a> | <a href="<?php  echo esc_url( home_url( '/' ));?>/edit_role/?rid=<?php echo $post->ID;?>">Edit</a>  | <a href="<?php  echo esc_url( home_url( '/' ));?>/reporting/?roler=<?php echo $post->ID;?>">Report</a> | <a class="inline " href="#Delete-<?php echo $post->ID;?>">Delete</a>
<div style="display:none">
<div id="Delete-<?php echo $post->ID;?>"><form action="" method="post" class="popup">
<header><h1>Confirm deletion</h1></header>
<label>To delete this record enter the word DELETE below and submit</label>
<input type="text" name="ConfirmDelete" />
<input type="hidden" name="Delete" value="<?php echo $post->ID;?>" />
<button type="submit" name="submit"  class="btn btn-primary btn-sm text-center btn-log mt-20" >Delete</button>

</form></div>
</div>
</td>
</tr>
<?php
endforeach;
wp_reset_postdata();?>
</tbody>
</table>
<?php endif;
endif;
if($_POST['submit'] && $_POST['searchterm'] != ''):
	if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'General Manager'):
	$args = array( 'posts_per_page' => 200,  'post_type' => 'role', 'orderby' => 'post_title', 'order' => 'ASC',
	'meta_query' => array(
	'relation' => 'AND',
	array(
		'key' => 'Manager',
		'value' => array(get_current_user_id(),get_user_meta(get_current_user_id(),'Manager',true)),
		'compare' => 'IN',
	),
	array(
		'key' => 'JobNumber',
		'value' => esc_attr($_POST['searchterm']),
		'compare' => 'LIKE',
	)));
	
	
	elseif(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):

	$args = array( 'posts_per_page' => 200,  'post_type' => 'role', 'orderby' => 'post_title', 'order' => 'ASC',
	'meta_query' => array(
	'relation' => 'AND',
	array(
		'key' => 'Manager',
		'value' => array(get_current_user_id()),
		'compare' => 'IN',
	),
	array(
		'key' => 'JobNumber',
		'value' => esc_attr($_POST['searchterm']),
		'compare' => 'LIKE',
	)));
 	else:

 	$args = array( 'posts_per_page' => 200,  'post_type' => 'role', 'orderby' => 'post_title', 'order' => 'ASC',
	'meta_query' => array(
	'relation' => 'AND',
	
	array(
		'key' => 'JobNumber',
		'value' => esc_attr($_POST['searchterm']),
		'compare' => 'LIKE',
	)));
 endif;
 $myposts = get_posts( $args );
foreach ( $myposts as $post ) : 
$jobnumbers[$post->ID]=get_post_meta($post->ID,'JobNumber',true);
endforeach;
endif;


if(count($jobnumbers) > 0): ?>
<h3>Job Numbers</h3>
<select name="jobno" id="jobno"><option value="">Choose job number</option>
<?php
foreach ( $jobnumbers as $roleid => $jobno ):
if($roleid==$_POST['jobno']):$checked='selected="selected"'; else: $checked='';endif;
echo '<option value="'.$roleid.'" '.$checked.'>' . $jobno . '</option>';
endforeach;
?>

</select>
<?php endif;
?>

<?php
if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'General Manager'):
 $args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	'meta_key'     => 'Manager',
	'meta_value'   => array(get_current_user_id(),get_user_meta(get_current_user_id(),'Manager',true)),
	'meta_compare' => 'IN',
	'fields'       => 'all',
	
 );
 if($_POST['submit'] && $_POST['searchterm'] != ''):
	$args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	'fields'       => 'all',
    'meta_query'=>
		array(

            array(
			'relation' => 'AND',
			array(
                'key' => 'Name',
                'value' => esc_attr($_POST['searchterm']),
                'compare' => "LIKE"
            ),
			array(
                'key' => 'Manager',
                'value' => array(get_current_user_id(),get_user_meta(get_current_user_id(),'Manager',true)),
                'compare' => "IN"
            )
          )
       )
  );
 endif;
 elseif(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):
 $args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	'meta_key'     => 'Manager',
	'meta_value'   => get_current_user_id(),
	'meta_compare' => '=',
	'fields'       => 'all',
	
 );
 if($_POST['submit'] && $_POST['searchterm'] != ''):
	$args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	'fields'       => 'all',
    'meta_query'=>
		array(

            array(
			'relation' => 'AND',
			array(
                'key' => 'Name',
                'value' => esc_attr($_POST['searchterm']),
                'compare' => "LIKE"
            ),
			array(
                'key' => 'Manager',
                'value' => get_current_user_id(),
                'compare' => "="
            )
          )
       )
  );
 endif;
 else:
 $args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	
	'fields'       => 'all',
	);
	if($_POST['submit'] && $_POST['searchterm'] != ''):
	$args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	'fields'       => 'all',
    'meta_query'=>
		array(

            array(
			'relation' => 'AND',
			array(
                'key' => 'NAME',
                'value' => esc_attr($_POST['searchterm']),
                'compare' => "LIKE"
            )
          )
       )
  );
 endif;
 endif;
$blogusers = get_users( $args );
// Array of WP_User objects.
if($_POST['submit'] && $_POST['searchterm'] != ''):
if(count($blogusers) > 0): ?>
<h3>Staff</h3><br />
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
<?php
endforeach;
?>

</tbody>
</table>
<?php endif; endif;?>
<?php

if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'General Manager'):
$args = array( 'posts_per_page' => 200,  'post_type' => 'group', 'orderby' => 'post_title', 'order' => 'ASC','meta_key'     => 'Manager',
	'meta_value'   => array(get_current_user_id(),get_user_meta(get_current_user_id(),'Manager',true)),
	'meta_compare' => 'IN' );
	if($_POST['submit'] && $_POST['searchterm'] != ''):
	$args["s"] = esc_attr($_POST['searchterm']);
	endif;
 elseif(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):
 $args = array( 'posts_per_page' => 200,  'post_type' => 'group', 'orderby' => 'post_title', 'order' => 'ASC','meta_key'     => 'Manager',
	'meta_value'   => array(get_current_user_id()),
	'meta_compare' => 'IN' );
	if($_POST['submit'] && $_POST['searchterm'] != ''):
	$args["s"] = esc_attr($_POST['searchterm']);
	endif;
 else:
 $args = array( 'posts_per_page' => 200,  'post_type' => 'group', 'orderby' => 'post_title', 'order' => 'ASC' );
 if($_POST['submit'] && $_POST['searchterm'] != ''):
	$args["s"] = esc_attr($_POST['searchterm']);
	endif;
 endif;
$myposts = get_posts( $args );
if(count($myposts) > 0): 
echo '<select name="groupr" id="group"><option value="">Choose a group</option>';
foreach ( $myposts as $post ) : 
if($post->ID==$_POST['groupr']):$checked='selected="selected"'; else: $checked='';endif;
echo '<option value="'.$post->ID.'" '.$checked.'>' . get_the_title($post->ID) . '</option>';
endforeach;
wp_reset_postdata();?>
</select>
<?php endif;?>
</form>
</div>