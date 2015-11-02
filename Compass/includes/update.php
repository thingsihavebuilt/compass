<?php
$fields = array('First name','Surname','Address 1','Address 2','Town','Postcode','Telephone','Email');
$fieldsb = array('First name','Surname','Address 1','Address 2','Town','Postcode');
$user_id = get_current_user_id();
?>
<form action="" method="post" name="register" id="register" style="float:left" >
<?php
foreach($fields as $fi):
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label><input type="text" name="'.str_replace(' ','',$fi).'" value="'.get_user_meta($user_id,str_replace(' ','',$fi),true).'"/><br />';
endforeach;
?>

<p><strong>Billing details</strong></p>
<?php
foreach($fieldsb as $fi):
echo '<label>'.ucfirst(str_replace('_',' ',$fi)).'</label><input type="text" name="Billing'.str_replace(' ','',$fi).'" value="'.get_user_meta($user_id,str_replace(' ','','Billing'.$fi),true).'"/><br />';
endforeach;
?>



<input type="hidden" name="action" value="update" />
<div><input type="submit" name="submit" value="Update your details" /></div>
</form>
