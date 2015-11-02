<?php
$fields = array('Name','DOB','Gender','Role','Email','Password');
$user_id = get_current_user_id();
if(isset($_POST['submit'])):
if ($_FILES) {

	foreach ($_FILES as $file => $array) {
		$logo = insert_logo($file,3);
		update_post_meta(3,'_thumbnail_id',$logo);
	}
};
if (!filter_var($_POST['Email'], FILTER_VALIDATE_EMAIL) === false):
$fields = array('Name','DOB','Gender');
$userdata = array(
	'ID' => $user_id,
    'user_login'  =>  esc_attr($_POST['Email']),
    'user_email'  =>  esc_attr($_POST['Email']),
   
);
if($_POST['Password'] != ''):
$userdata['user_pass']=$_POST['Password'];
endif;

$user_id = wp_update_user( $userdata ) ;
foreach($fields as $fi):
update_user_meta( $user_id,str_replace(' ','',$fi), $_POST[str_replace(' ','',$fi)]);
endforeach;


$notification =  '<p class="notification success">Details updated</p>';
else:
$notification =  '<p class="notification fail">Please enter a valid email address</p>';
endif;
endif;
?>
<header>
<h1>My <?php echo get_bloginfo('name');?> account</h1>
</header>
<div class="items">
<?php



?>
<form action="" method="post" enctype="multipart/form-data"  >
<?php
foreach($fields as $fi):
if($fi=='Password'):
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label><input type="password" name="'.str_replace(' ','',$fi).'" value=""/><br />';
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
elseif($fi=='Role'):
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label>';

echo '<select name="'.str_replace(' ','',$fi).'" disabled="disabled">';
$args = array( 'posts_per_page' => 200,  'post_type' => 'role', 'orderby' => 'post_title', 'order' => 'ASC' );
$myposts = get_posts( $args );
foreach ( $myposts as $post ) : 
if($post->ID==get_user_meta($user_id,str_replace(' ','',$fi),true)):$checked = 'selected="selected"'; else: $checked = ''; endif;
echo '<option value="'.$post->ID.'">' . get_the_title($post->ID) . '</option>';
endforeach;
echo '</select>';
echo'<br />';
elseif($fi=='DOB'):
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).' (dd/mm/yyyy)</label>';
echo '<input type="text" name="'.str_replace(' ','',$fi).'" value="'.get_user_meta($user_id,str_replace(' ','',$fi),true).'"/><br />';

else:
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label><input type="text" name="'.str_replace(' ','',$fi).'" value="'.get_user_meta($user_id,str_replace(' ','',$fi),true).'"/><br />';
endif;
endforeach;
 if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'ADMIN' || get_user_meta(get_current_user_id(),'AccessLevel',true) == 'CEO' || is_super_admin()):
$logo = wp_get_attachment_image_src( get_post_thumbnail_id(3), 'post'); 

echo '<label>Company Logo</label><img src="'.$logo[0].'"><input type="file" name="Logo" ><br />';
endif;
?>
<input type="submit" name="submit" value="Update" class="btn btn-primary btn-sm text-center btn-log mt-20 submit" />
</form>

</div>