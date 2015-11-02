<?php

$fields = array('Name','Description');
$GID = $_GET['gid'];
$data=get_post($GID);
if(isset($_POST['submit'])):

$error = 0;
foreach($fields as $fi):
if($_POST[str_replace(' ','',$fi)] == ''): $error = 1; endif;
endforeach;
if($error==1):
$notification =  '<p class="notification fail">Please fill in all the fields</p>';
else:
$my_post = array(
  'ID' => $GID,
  'post_title'    => esc_attr($_POST['Name']),
  'post_content'  => esc_attr($_POST['Description']),
  
);
wp_update_post( $my_post );
update_post_meta($GID, 'Manager', esc_attr($_POST['Manager']));

foreach($_POST['AllStaff'] as $st):
if(in_array($st,$_POST['Staff'])):
$vals = maybe_unserialize(get_user_meta($st,'Group',true));
if(!is_array($vals)):
$vals = array();
endif;
if(!in_array($GID,$vals)):
$vals[]=$GID;
update_user_meta( $st,'Group', $vals);
endif;
else:
$vals = maybe_unserialize(get_user_meta($st,'Group',true));
if(in_array($GID,$vals)):
if(($key = array_search($GID, $vals)) !== false) {
    unset($vals[$key]);
}

update_user_meta( $st,'Group', $vals);
endif;
endif;

endforeach;

$notification =  '<p class="notification success">Group updated</p>';
$data=get_post($GID);


endif;
endif;
$fields = array('Name','Description','Manager');
?>
<header>
<h1>Edit group - <?php echo $data->post_title;?></h1>
<?php echo $notification;?>
</header>
<?php



?>
<div class="items">
<form action="" method="post"  >
<?php
foreach($fields as $fi):
if($fi=='Name'):
$val = $data->post_title;
elseif($fi=='Description'):
$val = $data->post_content;
elseif($fi=='Competencies'):
$val = maybe_unserialize(get_post_meta($GID,str_replace(' ','',$fi),true));

else:
$val = get_post_meta($GID,str_replace(' ','',$fi),true);
endif;
if($fi=='Manager'):
if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'ADMIN' ||  is_super_admin() || get_user_meta(get_current_user_id(),'AccessLevel',true) == 'CEO'):
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label>';
echo '<select name="'.str_replace(' ','',$fi).'"><option value="">select</option>';
$args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	'role'         => 'subscriber',
	'fields'       => 'all',
	'meta_key'     => 'AccessLevel',
	'meta_value'   => array('General Manager','Line Manager'),
	'meta_compare' => 'IN',
 );
$blogusers = get_users( $args );
// Array of WP_User objects.
foreach ( $blogusers as $user ) {
if($user->ID==$val):$checked = 'selected="selected"'; else: $checked = ''; endif;
echo '<option value="'.$user->ID.'" '.$checked.'>' . get_user_meta($user->ID,'Name',true) . '</option>';
}
echo '</select>';
echo'<br />';
else:
echo '<input type="hidden" name="'.str_replace(' ','',$fi).'" value="'.$val.'" />';
endif;
else:
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label><input type="text" name="'.str_replace(' ','',$fi).'" value="'.$val.'"/><br />';
endif;
endforeach;
?>
<p>Add staff to this group</p>
<strong>Filter:</strong><br />
<a class="filter" rel="all">See all</a>  |  
<?php if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'ADMIN' ||  is_super_admin()): ?><a class="filter" rel="ceo">CEO</a>  |  <?php endif;?> 
<?php if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'ADMIN' ||  is_super_admin() || get_user_meta(get_current_user_id(),'AccessLevel',true) == 'CEO'):?><a class="filter" rel="generalmanager">General Managers (GM)</a>  |  <?php endif;?> 
<?php if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'ADMIN' ||  is_super_admin() || get_user_meta(get_current_user_id(),'AccessLevel',true) == 'CEO' || get_user_meta(get_current_user_id(),'AccessLevel',true) == 'General Manager'):?><a class="filter" rel="linemanager">Line Managers (LM)</a>  |  <?php endif;?><a class="filter" rel="staffmember">Staff (S)</a><br /><br />
<table class="table table-bordered">
<tbody>
<tr class=" ">
<td></td>
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
// Array of WP_User objects.
foreach ( $blogusers as $user ):
$vals = maybe_unserialize(get_user_meta($user->ID,'Group',true));

if(in_array($GID,$vals)):$checked = 'checked="checked"'; else: $checked = ''; endif;
?>
<tr class="all <?php echo strtolower(str_replace(' ','',get_user_meta($user->ID,'AccessLevel',true)));?>">
<td><?php echo '<label><input type="checkbox" name="Staff[]" class="checkbox" value="'.$user->ID.'" '.$checked.'></label><input type="hidden" name="AllStaff[]" value="'.$user->ID.'" />'; ?></td>
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
echo '</table>';
?>
<input type="submit" name="submit" value="Update Group" class="btn btn-primary btn-sm text-center btn-log mt-20 submit" />  <input type="reset" name="reset" value="Revert" class="btn btn-primary btn-sm text-center btn-log mt-20 submit" />
</form></div>