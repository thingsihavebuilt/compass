<?php
$fields = array('Name','Description','Level 0 Indicators','Level 0 Definition','Level 1 Indicators','Level 1 Definition','Level 2 Indicators','Level 2 Definition','Level 3 Indicators','Level 3 Definition','Level 4 Indicators','Level 4 Definition');
$CID = $_GET['cid'];
if(isset($_POST['submit'])):
$error = 0;
foreach($fields as $fi):
if($_POST[str_replace(' ','',$fi)] == ''): $error = 1; endif;
endforeach;
if($error==1):
$notification =  '<p class="notification fail">Please fill in all the fields</p>';
else:
$my_post = array(
      'ID'           => $CID,
      'post_title'    => esc_attr($_POST['Name']),
 	 'post_content'  => esc_attr($_POST['Description']),
  );

// Update the post into the database
  wp_update_post( $my_post );
foreach($fields as $fi):
update_post_meta($CID, str_replace(' ','',$fi), esc_attr($_POST[str_replace(' ','',$fi)]));
endforeach;

$notification =  '<p class="notification success">Competency updated</p>';
#wp_redirect(get_site_url().'/competencies/');
endif;
endif;
$data=get_post($CID);


?>
<header>
<h1>Edit Competency - <?php echo $data->post_title;?></h1>
<?php echo $notification;?>
</header>
<div class="items">
<form action="" method="post"  >
<?php
foreach($fields as $fi):
if($fi=='Name'):
$val = $data->post_title;
elseif($fi=='Description'):
$val = $data->post_content;
else:
$val = get_post_meta($CID,str_replace(' ','',$fi),true);
endif;
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label><input type="text" name="'.str_replace(' ','',$fi).'" value="'.$val.'"/><br />';

endforeach;
?>
<input type="submit" name="submit" value="Update Competency" class="btn btn-primary btn-sm text-center btn-log mt-20 submit" />  <input type="reset" name="reset" value="Revert" class="btn btn-primary btn-sm text-center btn-log mt-20 submit" />
</form></div>