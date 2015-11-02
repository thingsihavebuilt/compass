<?php
$fields = array('Name','Description','Level 0 Indicators','Level 0 Definition','Level 1 Indicators','Level 1 Definition','Level 2 Indicators','Level 2 Definition','Level 3 Indicators','Level 3 Definition','Level 4 Indicators','Level 4 Definition');
$user_id = get_current_user_id();
if($_POST['submit']):
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
  'post_type' => 'competency'
);
$pid = wp_insert_post( $my_post );
foreach($fields as $fi):
update_post_meta($pid, str_replace(' ','',$fi), esc_attr($_POST[str_replace(' ','',$fi)]));
endforeach;

$notification =  '<p class="notification success">Competency created</p>';
unset($_POST);
wp_redirect(get_site_url().'/competencies/');
endif;
endif;
?>
<header>
<h1>Add Competency</h1>
<?php echo $notification;?>
</header>
<div class="items">
<form action="" method="post"  >
<?php
foreach($fields as $fi):

echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label><textarea  name="'.str_replace(' ','',$fi).'" >'.$_POST[str_replace(' ','',$fi)].'</textarea><br />';

endforeach;
?>
<input type="submit" name="submit" value="Add Competency" class="btn btn-primary btn-sm text-center btn-log mt-20 submit" />
</form>
</div>