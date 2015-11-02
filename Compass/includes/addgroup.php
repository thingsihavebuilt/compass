<?php
$fields = array('Name','Description');
$user_id = get_current_user_id();
if(isset($_POST['submit'])):
$error = 0;
foreach($fields as $fi):
if($_POST[str_replace(' ','',$fi)] == ''): $error = 1; endif;
endforeach;
if($error==1):
$notification =  '<p class="notification fail">Please fill in all the fields</p>';
else:
$my_post = array(
  'post_title'    => esc_attr($_POST['Name']),
  'post_content'  => esc_attr($_POST['Description']),
  'post_status'   => 'publish',
  'post_author'   => $user_id,
  'post_type' => 'group'
);
$GID = wp_insert_post( $my_post );
update_post_meta($GID, 'Manager', $user_id);
$notification =  '<p class="notification success">Group added</p>';

foreach($_POST['Staff'] as $st):
$vals = maybe_unserialize(get_user_meta($st,'Group',true));
$vals[]=$GID;
update_user_meta( $st,'Group', $vals);
endforeach;

unset($_POST);
endif;
endif;
?>
<header>
<h1>Add group</h1>
<?php echo $notification;?>
</header>
<?php



?>
<div class="items">
<form action="" method="post"  >
<?php
foreach($fields as $fi):
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label><input type="text" name="'.str_replace(' ','',$fi).'" value="'.$_POST[str_replace(' ','',$fi)].'"/><br />';
endforeach;
?>
<p>Add staff to this group</p>
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
if(in_array($user->ID,$_POST['Staff'])):$checked = 'checked="checked"'; else: $checked = ''; endif;
echo '<label><input type="checkbox" name="Staff[]" class="checkbox" value="'.$user->ID.'" '.$checked.'>' . get_user_meta($user->ID,'Name',true) . '</label>';
endforeach;
?>
<input type="submit" name="submit" value="Add Group" class="btn btn-primary btn-sm text-center btn-log mt-20 submit" />
</form>
</div>