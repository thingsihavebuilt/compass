<form action="" method="post">
<p>Select a role to view</p>
<?php
$RID = $_POST['rolep'];
echo '<select name="rolep">';
$args = array( 'posts_per_page' => 200,  'post_type' => 'role', 'orderby' => 'post_title', 'order' => 'ASC' );
$myposts = get_posts( $args );
foreach ( $myposts as $post ) : 
if($post->ID==$_POST['rolep']):$checked='selected="selected"'; else: $checked='';endif;
echo '<option value="'.$post->ID.'" '.$checked.'>' . get_the_title($post->ID) . '</option>';
endforeach;
echo '</select>';
?>
<input type="submit" name="submit" value="View Matches" class="btn btn-primary btn-sm text-center btn-log mt-20 submit" />
</form>
<?php
if(isset($_POST['rolep'])):
$RID = $_POST['rolep'];
if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'ADMIN' || is_super_admin()):$access = array('CEO','General Manager','Line Manager','Staff');
elseif(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'CEO'):$access = array('General Manager','Line Manager','Staff');
  elseif(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'General Manager'):$access = array('Line Manager','Staff');
  elseif(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):$access = array('Staff');
  elseif(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Staff Member'):$access = array();
  elseif(is_super_admin()):$access = array('General Manager','Line Manager','Staff');
  else:$access = array('Staff');
  endif;
echo '<h3>Relevant staff</h3>';?>
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
$args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	
	'fields'       => 'all',
	'relation' => 'AND', // Optional, defaults to "AND"
	array(
		'key'     => 'AccessLevel',
		'value'   => $access,
		'compare' => 'IN'
	),
	array(
		'key'     => 'Role',
		'value'   => $RID,
		'compare' => '!='
	)
 );

$blogusers = get_users( $args );

// Array of WP_User objects.
foreach ( $blogusers as $user ):

$competencies = maybe_unserialize(get_user_meta($user->ID,'Competencies',true));


$show = 1;
if ($RID == get_user_meta($user->ID,'Role',true)): $show = 0; endif;
$jobcompetencies = maybe_unserialize(get_post_meta($RID,'Competencies',true));

if(!empty($competencies) && $competencies != ''  && !empty($jobcompetencies)):
foreach($jobcompetencies as $jc => $v):

$val = $competencies[$jc];
$min = $v-1;
$max = $v+1;

if($v != ''):
if($val >= $min && $val <= $max):
else:
$show = 0;
endif;

endif;
endforeach;
if($show==1):
/*echo '<p>
'.get_user_meta($user->ID,'Name',true).' | '.get_the_title(get_user_meta($user->ID,'Role',true)).' | '.get_post_meta(get_user_meta($user->ID,'Role',true),'JobNumber',true).' | '.get_user_meta(get_user_meta($user->ID,'Manager',true),'Name',true).' | <a class="inline " href="#View-'.$user->ID.'">View</a></p>';*/
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
endif;
endif;
endforeach;
echo '</tbody>
</table>';
endif;
?>
