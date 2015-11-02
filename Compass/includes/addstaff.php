<?php

$fields = array('Name','Description','Email','DOB','Gender','Role','Manager','Access Level','Group','Competencies');
$fieldsr = array('Name','Description','Email','DOB','Gender','Access Level');
$user_id = get_current_user_id();
if(isset($_POST['submit'])):
$error = 0;
foreach($fieldsr as $fi):
if($_POST[str_replace(' ','',$fi)] == ''): $error = 1; endif;
endforeach;
$args = array( 'posts_per_page' => 200,  'post_type' => 'competency', 'orderby' => 'post_title', 'order' => 'ASC' );
$myposts = get_posts( $args );
foreach ( $myposts as $post ) : 
#if(!isset($_POST['Competencies'][$post->ID])): $error = 1; endif;
endforeach;
if($error==1):
$notification =  '<p class="notification fail">Please fill in all the fields</p>';
else:
if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):
$_POST['Manager']=get_current_user_id();
endif;
$pass = wp_generate_password() ;
$userdata = array(
    'user_login'  =>  esc_attr($_POST['Email']),
   'user_email'  =>  esc_attr($_POST['Email']),
    'user_pass'   =>  $pass
);
	if ( !$userdata['user_login'] ):
		$error = 'An email is required for registration.';
		
	elseif ( username_exists($userdata['user_login']) ):
		$error = 'Sorry, that email is being used by someone else.'; 
	elseif ( !is_email($userdata['user_email'], true) ):
		$error = 'You must enter a valid email address.'; 
	elseif ( email_exists($userdata['user_email']) ) :
		$error = 'Sorry, that email is being used by someone else.';
	else:
	$error = '';
	endif;
	
if($error == ''):


$user_id = wp_insert_user( $userdata ) ;
if($_POST[str_replace(' ','','Access Level')] != 'Staff Member'):
wp_new_user_notification($user_id, $pass);
endif;
foreach($fields as $fi):


$p = $_POST[str_replace(' ','',$fi)]; 
if($fi=='Role'): $p = $_POST[str_replace(' ','',$fi).'R']; endif;
update_user_meta( $user_id,str_replace(' ','',$fi), $p);
endforeach;
$notification =  '<p class="notification success">Staff member created</p>';
unset($_POST);
wp_redirect(get_site_url().'/staff/');
else:

$notification =  '<p class="notification fail">'.$error.'</p>';
endif;

endif;
endif;
?>
<header>
<h1>Add staff</h1>
<?php echo $notification;?>
</header>
<?php


global $blog_id;
?>
<div class="items">
<form action="" method="post" class="form-add-staff"  >
<input name="type" type="hidden" id="type" value="addstaff" />
<input name="Blog" type="hidden" id="Blog" value="<?php echo $blog_id;?>" />
<h3 class="ptitle">Personal Details</h3>
<?php
if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):
$fields = array('Name','Description','Email','DOB','Gender','Role','Access Level','Group','Competencies');
else:
$fields = array('Name','Description','Email','DOB','Gender','Role','Manager','Access Level','Group','Competencies');
endif;
foreach($fields as $fi):
if($fi=='Manager'):
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
if($user->ID==$_POST[str_replace(' ','',$fi)]):$checked = 'selected="selected"'; else: $checked = ''; endif;
echo '<option value="'.$user->ID.'" '.$checked.'>' . get_user_meta($user->ID,'Name',true) . '</option>';}
echo '</select>';
echo'<br />';
elseif($fi=='Gender'):
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label>';
echo '<select name="'.str_replace(' ','',$fi).'">';
$options = array('Male','Female');
foreach($options as $op):
if($p==$_POST[str_replace(' ','',$fi)]):$checked = 'selected="selected"'; else: $checked = ''; endif;
echo '<option value="'.$op.'">' . esc_html( $op  ) . '</option>';
endforeach;
echo '</select>';
echo'<br />';
elseif($fi == 'Competencies'):
global $blog_id;
$_POST['Blog']=$blog_id;
$_POST['type']='addstaff';

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
if($op==$_POST[str_replace(' ','',$fi)]):$checked = 'selected="selected"'; else: $checked = ''; endif;
echo '<option value="'.$op.'"  '.$checked.'>' . esc_html( $op  ) . '</option>';
endforeach;
echo '</select>';
echo'<br />';
elseif($fi=='DOB'):
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).' (dd/mm/yyyy)</label>';
echo '<input type="text" name="'.str_replace(' ','',$fi).'" value="'.$_POST[str_replace(' ','',$fi)].'"/><br />';
elseif($fi=='Role'):
echo '<h3 class="ptitle">Company Details</h3>';
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label>';
echo '<select id="Ajax'.str_replace(' ','',$fi).'" name="'.str_replace(' ','',$fi).'R"><option>Please select</option>';
$args = array( 'posts_per_page' => 200,  'post_type' => 'role', 'orderby' => 'post_title', 'order' => 'ASC' );
$myposts = get_posts( $args );
foreach ( $myposts as $post ) : 
if($post->ID==$_POST[str_replace(' ','',$fi)]):$checked = 'selected="selected"'; else: $checked = ''; endif;
echo '<option value="'.$post->ID.'">' . get_the_title($post->ID) . '</option>';
endforeach;
echo '</select>';
echo'<br />';
elseif($fi=='Group'):
echo '<label>Special '.ucfirst(str_replace('_',' ',$fi)).'</label>';
/*echo '<select name="'.str_replace(' ','',$fi).'">';
$args = array( 'posts_per_page' => 200,  'post_type' => 'group', 'orderby' => 'post_title', 'order' => 'ASC' );
$myposts = get_posts( $args );
foreach ( $myposts as $post ) : 
if($post->ID==$_POST[str_replace(' ','',$fi)]):$checked = 'selected="selected"'; else: $checked = ''; endif;

echo '<option value="'.$post->ID.'" '.$checked.'>' . get_the_title($post->ID) . '</option>';
endforeach;
echo '</select>';*/
$args = array( 'posts_per_page' => 200,  'post_type' => 'group', 'orderby' => 'post_title', 'order' => 'ASC' );
$myposts = get_posts( $args );
foreach ( $myposts as $post ) : 
if(in_array($post->ID,$_POST[str_replace(' ','',$fi)])):$checked = 'selected="selected"'; else: $checked = ''; endif;
echo '<label><input type="checkbox" name="'.str_replace(' ','',$fi).'[]" class="checkbox" value="'.$post->ID.'" '.$checked.'>' . get_the_title($post->ID) . '</label>';
endforeach;
echo'<br />';
else:
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label><input type="text" name="'.str_replace(' ','',$fi).'" value="'.$_POST[str_replace(' ','',$fi)].'"/><br />';
endif;
endforeach;
?>
<button type="submit" name="submit"  class="btn btn-primary btn-sm text-center btn-log mt-20" >Add Staff Member</button>
</form></div>