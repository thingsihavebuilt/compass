<?php
if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):
$fields = array('Name','Description','Email','DOB','Gender','Role','Access Level','Competencies');
$fieldsr = array('Name','Description','Email','DOB','Gender','Access Level');
else:
$fields = array('Name','Description','Email','DOB','Gender','Role','Manager','Access Level','Competencies');
$fieldsr = array('Name','Description','Email','DOB','Gender','Access Level');
endif;
$user_id = $_GET['sid'];
$user_info = get_userdata($_GET['sid']);
 
if(isset($_POST['submit'])):
$error = 0;
foreach($fieldsr as $fi):
if($_POST[str_replace(' ','',$fi)] == ''): $error = 1; endif;
endforeach;
if($error==1):
$notification =  '<p class="notification fail">Please fill in all the fields</p>';
else:
$fields = array('Name','Description','DOB','Gender','Role','Manager','Access Level','Group','Competencies');
if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):
$_POST['Manager']=get_current_user_id();
endif;
$userdata = array(
	'ID' => $user_id,
    'user_login'  =>  esc_attr($_POST['Email']),
    'user_email'  =>  esc_attr($_POST['Email']),
   
);
if($_POST['Password'] != ''):
$userdata['user_pass']=$_POST['Password'];
endif;
	if ( !$userdata['user_login'] ):
		$error = 'An email is required.';
		
	elseif ( username_exists($userdata['user_login']) && $user_info->user_login !=  $userdata['user_login']):
		#$error = 'Sorry, that email is being used by someone else!'; 
	elseif ( !is_email($userdata['user_email'], true) ):
		$error = 'You must enter a valid email address.'; 
	elseif ( email_exists($userdata['user_email'])  && $user_info->user_email !=  $userdata['user_email'] ) :
		$error = 'Sorry, that email is being used by someone else!';
	else:
	$error = '';
	endif;
	
if($error == ''):

wp_update_user( $userdata ) ;
foreach($fields as $fi):
$p = $_POST[str_replace(' ','',$fi)]; 
if($fi=='Role'): $p = $_POST[str_replace(' ','',$fi).'R']; endif;
update_user_meta( $user_id,str_replace(' ','',$fi), $p);

endforeach;

$notification =  '<p class="notification success">Staff member updated</p>';

else:

$notification =  '<p class="notification fail">'.$error.'</p>';
endif;

endif;
endif;

if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):
$fields = array('Name','Description','Email','DOB','Gender','Role','Access Level','Group','Competencies');
else:
$fields = array('Name','Description','Email','DOB','Gender','Role','Manager','Access Level','Group','Competencies');
endif;
?>
<header>
<h1>Edit staff - <?php echo get_user_meta($user_id,'Name',true)?></h1>
<?php echo $notification;?>
</header>
<?php


global $blog_id;
?>
<div class="items">
<form action="" method="post"  >
<input name="type" id="type" type="hidden" value="editstaff" />
<input name="Blog" id="Blog" type="hidden" value="<?php echo $blog_id;?>" />
<input name="user_id" id="user_id" type="hidden" value="<?php echo $_GET['sid'];?>" />
<h3 class="ptitle">Personal Details</h3>
<?php
foreach($fields as $fi):
if($fi=='Password'):
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label><input  autocomplete="off" type="password" name="'.str_replace(' ','',$fi).'" value=""/><br />';
elseif($fi=='Manager' ):
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label>';
echo '<select name="'.str_replace(' ','',$fi).'"><option value="">select</option>';
if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'ADMIN' || is_super_admin()):
echo '<option value="'.get_current_user_id().'">'.get_option('contact').'</option>';
endif;

$args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	'role'         => 'subscriber',
	'fields'       => 'all',
	'meta_key'     => 'AccessLevel',
	'meta_value'   => array('General Manager','Line Manager'),
	'meta_compare' => 'IN',
 );
 if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'ADMIN' || is_super_admin()):
 $args['meta_value']=array('CEO','General Manager','Line Manager');
 endif;
$blogusers = get_users( $args );
// Array of WP_User objects.
foreach ( $blogusers as $user ) {
if($user->ID==get_user_meta($user_id,str_replace(' ','',$fi),true)):$checked = 'selected="selected"'; else: $checked = ''; endif;
echo '<option value="'.$user->ID.'" '.$checked.'>' . get_user_meta($user->ID,'Name',true) . '</option>';
}
echo '</select>';
echo'<br />';
elseif($fi=='Gender'):
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label>';
echo '<select name="'.str_replace(' ','',$fi).'">';
$options = array('Male','Female');
foreach($options as $op):
if($op==get_user_meta($user_id,str_replace(' ','',$fi),true)):$checked = 'checked="checked"'; else: $checked = ''; endif;
echo '<option value="'.$op.'"  '.$checked.'>' . esc_html( $op  ) . '</option>';
endforeach;
echo '</select>';
echo'<br />';
elseif($fi == 'Competencies'):
global $blog_id;
$_POST['Blog']=$blog_id;
$_POST['user_id']=$_GET['sid'];
$_POST['RoleID']=get_user_meta($user_id,'Role',true);
$_POST['type']='editstaff';
echo '<div id="ajax-result">';
include($_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/Compass/includes/ajax.php');
echo '</div>';
elseif($fi=='Access Level'):
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label>';
echo '<select name="'.str_replace(' ','',$fi).'">';
if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):
$options = array('Staff Member');
elseif(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'General Manager'):
$options = array('Line Manager','Staff Member');
elseif(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'ADMIN' || is_super_admin()):
$options = array('CEO','General Manager','Line Manager','Staff Member');
else:
$options = array('General Manager','Line Manager','Staff Member');
endif;
foreach($options as $op):
if($op==get_user_meta($user_id,str_replace(' ','',$fi),true)):$checked = 'selected="selected"'; else: $checked = ''; endif;
echo '<option value="'.$op.'"  '.$checked.'>' . esc_html( $op  ) . '</option>';
endforeach;
echo '</select>';
echo'<br />';
elseif($fi=='DOB'):
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).' (dd/mm/yyyy)</label>';
echo '<input  autocomplete="off" type="text" name="'.str_replace(' ','',$fi).'" value="'.get_user_meta($user_id,str_replace(' ','',$fi),true).'"/><br />';
elseif($fi=='Role'):
echo '<h3 class="ptitle">Company Details</h3>';
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label>';
echo '<select id="Ajax'.str_replace(' ','',$fi).'" name="'.str_replace(' ','',$fi).'R"><option value="">select</option>';
$args = array( 'posts_per_page' => 200,  'post_type' => 'role', 'orderby' => 'post_title', 'order' => 'ASC' );
$myposts = get_posts( $args );
foreach ( $myposts as $post ) : 
if($post->ID==get_user_meta($user_id,str_replace(' ','',$fi),true)):$checked = 'selected="selected"'; else: $checked = ''; endif;
echo '<option value="'.$post->ID.'" '.$checked.'>' . get_the_title($post->ID) . '</option>';
endforeach;
echo '</select>';
echo'<br />';
elseif($fi=='Group'):
echo '<label>Special '.ucfirst(str_replace('_',' ',$fi)).'</label>';
/*echo '<select name="'.str_replace(' ','',$fi).'"><option>None</option>';
$args = array( 'posts_per_page' => 200,  'post_type' => 'group', 'orderby' => 'post_title', 'order' => 'ASC' );
$myposts = get_posts( $args );
foreach ( $myposts as $post ) : 
if($post->ID==get_user_meta($user_id,str_replace(' ','',$fi),true)):$checked = 'selected="selected"'; else: $checked = ''; endif;
echo '<option value="'.$post->ID.'" '.$checked.'>' . get_the_title($post->ID) . '</option>';
endforeach;
echo '</select>';*/
$vals = maybe_unserialize(get_user_meta($user_id,str_replace(' ','',$fi),true));


$args = array( 'posts_per_page' => 200,  'post_type' => 'group', 'orderby' => 'post_title', 'order' => 'ASC' );
$myposts = get_posts( $args );
foreach ( $myposts as $post ) : 
if(in_array($post->ID,$vals)):$checked = 'checked="checked"'; else: $checked = ''; endif;
echo '<label><input type="checkbox" name="'.str_replace(' ','',$fi).'[]" class="checkbox" value="'.$post->ID.'" '.$checked.'>' . get_the_title($post->ID) . '</label>';
endforeach;
echo'<br />';
else:
$val = get_user_meta($user_id,str_replace(' ','',$fi),true);
if($fi=='Description'): $val = get_user_meta($user_id,'description',true); endif;
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label><input  autocomplete="off" type="text" name="'.str_replace(' ','',$fi).'" value="'.$val.'"/><br />';
endif;
endforeach;
?>

<input type="submit" name="submit" value="Edit Staff Member" class="btn btn-primary btn-sm text-center btn-log mt-20 submit" />  <input type="reset" name="reset" value="Revert" class="btn btn-primary btn-sm text-center btn-log mt-20 submit" />
</form></div>