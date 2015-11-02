<?php
if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):
$fields = array('Name','Description','Email','DOB','Gender','Role','Access Level','Competencies');
else:
$fields = array('Name','Description','Email','DOB','Gender','Role','Manager','Access Level','Competencies');
endif;
$user_id = $_GET['sid'];
$user_info = get_userdata($_GET['sid']);
    
if(isset($_POST['submit'])):
$error = 0;
foreach($fields as $fi):
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
		$error = 'A username is required for registration.';
		
	elseif ( username_exists($userdata['user_login']) && $user_info->user_login !=  $userdata['user_login']):
		$error = 'Sorry, that username already exists!'; 
	elseif ( !is_email($userdata['user_email'], true) ):
		$error = 'You must enter a valid email address.'; 
	elseif ( email_exists($userdata['user_email'])  && $user_info->user_email !=  $userdata['user_email'] ) :
		$error = 'Sorry, that email address is already used!';
	else:
	$error = '';
	endif;
	
if($error == ''):


$user_id = wp_update_user( $userdata ) ;
foreach($fields as $fi):
update_user_meta( $user_id,str_replace(' ','',$fi), $_POST[str_replace(' ','',$fi)]);
endforeach;
$notification =  '<p class="notification success">Staff member updated</p>';

else:

$notification =  '<p class="notification fail">'.$error.'</p>';
endif;

endif;
endif;

if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):
$fields = array('Name','Description','Email','DOB','Gender','Role','Access Level','Group','Competencies','Password');
else:
$fields = array('Name','Description','Email','DOB','Gender','Role','Manager','Access Level','Group','Competencies','Password');
endif;
?>
<header>
<h1>Edit staff - <?php echo get_user_meta($user_id,'Name',true)?></h1>
<?php echo $notification;?>
</header>
<?php



?>
<div class="items">
<form action="" method="post"  >
<?php
foreach($fields as $fi):
if($fi=='Password'):
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label><input  autocomplete="off" type="password" name="'.str_replace(' ','',$fi).'" value=""/><br />';
elseif($fi=='Manager' ):
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label>';
echo '<select name="'.str_replace(' ','',$fi).'">';
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

$val = maybe_unserialize(get_user_meta($user_id,str_replace(' ','',$fi),true));
$val2 = maybe_unserialize(get_post_meta(get_user_meta($user_id,'Role',true),'Competencies',true));

$options = array('N/A' => '', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5');

echo '<p>Set competency scores required for this role</p>
<table class="table table-bordered">
<tbody>
<tr><td></td><td colspan="6"><strong>Role</strong></td><td colspan="6"><strong>Staff</strong></td></tr>';
$args = array( 'posts_per_page' => 200,  'post_type' => 'competency', 'orderby' => 'post_title', 'order' => 'ASC' );
$myposts = get_posts( $args );
foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
<tr><td><?php the_title();?></td>
<?php
$i = 0;
foreach($options as $op => $v):
if($v==$val2[$post->ID]):$checked = 'checked="checked"'; else: $checked = ''; endif;
echo '<td class="bg-grade-'.$i.'"><label><input type="radio"  class="pd" onclick="return false;" name="Role'.str_replace(' ','',$fi).'['.$post->ID.']" value="'.$v.'" '.$checked.' />'.$op.'</label></td>';
$i++;
endforeach;
$i = 0;
foreach($options as $op => $v):
if($v==$val[$post->ID]):$checked = 'checked="checked"'; else: $checked = ''; endif;
echo '<td class="bg-grade-'.$i.'"><label><input type="radio" name="'.str_replace(' ','',$fi).'['.$post->ID.']" value="'.$v.'" '.$checked.' />'.$op.'</label></td>';
$i++;
endforeach;
?>
</tr>
<?php endforeach; 
wp_reset_postdata();
echo '</table>';
elseif($fi=='Access Level'):
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label>';
echo '<select name="'.str_replace(' ','',$fi).'">';
if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):
$options = array('Staff Member');
elseif(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'General Manager'):
$options = array('Line Manager','Staff Member');
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
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label>';
echo '<select name="'.str_replace(' ','',$fi).'">';
$args = array( 'posts_per_page' => 200,  'post_type' => 'role', 'orderby' => 'post_title', 'order' => 'ASC' );
$myposts = get_posts( $args );
foreach ( $myposts as $post ) : 
if($post->ID==get_user_meta($user_id,str_replace(' ','',$fi),true)):$checked = 'selected="selected"'; else: $checked = ''; endif;
echo '<option value="'.$post->ID.'" '.$checked.'>' . get_the_title($post->ID) . '</option>';
endforeach;
echo '</select>';
echo'<br />';
elseif($fi=='Group'):
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label>';
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
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label><input  autocomplete="off" type="text" name="'.str_replace(' ','',$fi).'" value="'.get_user_meta($user_id,str_replace(' ','',$fi),true).'"/><br />';
endif;
endforeach;
?>
<input type="submit" name="submit" value="Edit Staff Member" class="btn btn-primary btn-sm text-center btn-log mt-20 submit" />  <input type="reset" name="reset" value="Revert" class="btn btn-primary btn-sm text-center btn-log mt-20 submit" />
</form></div>