<?php
if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):
$fields = array('Name','Description','Job Number','Competencies');
else:
$fields = array('Name','Description','Job Number','Competencies');
endif;
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
  'post_type' => 'role'
);
$pid = wp_insert_post( $my_post );
update_post_meta($RID, 'JobNumber', esc_attr($_POST['JobNumber']));
if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):
update_post_meta($pid, 'Manager', $user_id);
else:
update_post_meta($pid, 'Manager', esc_attr($_POST['Manager']));
endif;

update_post_meta($pid, 'Competencies', $_POST['Competencies']);
unset($_POST);
$notification =  '<p class="notification success">Role created</p>';
#wp_redirect(get_site_url().'/roles/');
endif;
endif;
?>
<header>
<h1>Add Role</h1>
<?php echo $notification;?>
</header>

<div class="items">
<form action="" method="post"  >
<?php
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
echo '<option value="'.$user->ID.'">' . get_user_meta($user->ID,'Name',true) . '</option>';
}
echo '</select>';
echo'<br />';
elseif($fi == 'Competencies'):
$options = array('N/A' => '', '0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4');

echo '<p>Set competency scores required for this role</p>
<table class="table table-bordered">
<tbody>';
$args = array( 'posts_per_page' => 200,  'post_type' => 'competency', 'orderby' => 'post_title', 'order' => 'ASC' );
$myposts = get_posts( $args );
$count = 1;
foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
<tr><td><?php echo $count; $count++;?></td><td><?php the_title();?></td>
<?php
$i = 0;
foreach($options as $op => $v):

echo '<td class="bg-grade-'.$i.'"><label><input type="radio" name="'.str_replace(' ','',$fi).'['.$post->ID.']" value="'.$v.'" '.$checked.' />'.$op.'</label></td>';
$i++;
endforeach;
?>
</tr>
<?php endforeach; 
wp_reset_postdata();
echo '</table>';
else:
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label><input type="text" name="'.str_replace(' ','',$fi).'" value="'.$_POST[str_replace(' ','',$fi)].'"/><br />';
endif;
endforeach;
?>
<input type="submit" name="submit" value="Add Role" class="btn btn-primary btn-sm text-center btn-log mt-20 submit" />
</form></div>