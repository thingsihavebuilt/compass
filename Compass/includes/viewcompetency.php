<?php
$fields = array('Name','Description','Level 0 Indicators','Level 0 Definition','Level 1 Indicators','Level 1 Definition','Level 2 Indicators','Level 2 Definition','Level 3 Indicators','Level 3 Definition','Level 4 Indicators','Level 4 Definition');
$CID = $_GET['cid'];

$data=get_post($CID);


?>
<header>
<h1><?php echo $data->post_title;?></h1>
</header>
<div class="items">
<a href="<?php  echo esc_url( home_url( '/' ));?>/competencies/edit_competency/?cid=<?php echo $CID;?>">Edit</a> | <a class="inline " href="#Delete-<?php echo $CID;?>">Delete</a>
<div style="display:none">
<div id="Delete-<?php echo $CID;?>"><form action="<?php  echo esc_url( home_url( '/' ));?>/competencies/" method="post">
<header><h1>Confirm deletion</h1></header>
<label>To delete this record enter the word DELETE below and submit</label>
<input type="text" name="ConfirmDelete" />
<input type="hidden" name="Delete" value="<?php echo $CID;?>" />
<button type="submit" name="submit"  class="btn btn-primary btn-sm text-center btn-log mt-20" >Delete</button>

</form></div>
</div>
<?php
foreach($fields as $fi):
if($fi=='Name'):
$val = $data->post_title;
elseif($fi=='Description'):
$val = $data->post_content;
else:
$val = get_post_meta($CID,str_replace(' ','',$fi),true);
endif;
echo '<label><strong>'.ucfirst(str_replace('_',' ',$fi)).'</strong></label>
<p>'.$val.'</p>';
endforeach;
?>
</div>