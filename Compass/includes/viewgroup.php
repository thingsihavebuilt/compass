<?php
$fields = array('Name','Description','Manager');
$GID = $_GET['gid'];
$data=get_post($GID);

?>
<header>
<h1><?php echo $data->post_title;?></h1>
</header>
<div class="items">
<a href="<?php  echo esc_url( home_url( '/' ));?>/special_group/edit_special_group/?gid=<?php echo $GID;?>">Edit</a> | <a class="inline " href="#Delete-<?php echo $GID;?>">Delete</a>
<div style="display:none">
<div id="Delete-<?php echo $GID;?>"><form action="<?php  echo esc_url( home_url( '/' ));?>/special_group/" method="post">
<header><h1>Confirm deletion</h1></header>
<label>To delete this record enter the word DELETE below and submit</label>
<input type="text" name="ConfirmDelete" />
<input type="hidden" name="Delete" value="<?php echo $GID;?>" />
<button type="submit" name="submit"  class="btn btn-primary btn-sm text-center btn-log mt-20" >Delete</button>

</form></div>
</div>
<?php
foreach($fields as $fi):
if($fi=='Name'):
$val = $data->post_title;
elseif($fi=='Description'):
$val = $data->post_content;
elseif($fi=='Manager'):
$val = get_user_meta($GID,'Name',true);

else:
$val = get_post_meta($GID,str_replace(' ','',$fi),true);
endif;
echo '<label><strong>'.ucfirst(str_replace('_',' ',$fi)).': </strong>'.$val.'</label>
';
endforeach;
echo '<label><strong>Staff:</strong></label>';
$args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	'role'         => 'subscriber',
	'fields'       => 'all',
	
 );
$blogusers = get_users( $args );
// Array of WP_User objects.
?>
<strong>Filter:</strong><br />
<a class="filter" rel="all">See all</a>  |  
<?php if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'ADMIN' ||  is_super_admin()): ?><a class="filter" rel="ceo">CEO</a>  |  <?php endif;?> 
<?php if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'ADMIN' ||  is_super_admin() || get_user_meta(get_current_user_id(),'AccessLevel',true) == 'CEO'):?><a class="filter" rel="generalmanager">General Managers (GM)</a>  |  <?php endif;?> 
<?php if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'ADMIN' ||  is_super_admin() || get_user_meta(get_current_user_id(),'AccessLevel',true) == 'CEO' || get_user_meta(get_current_user_id(),'AccessLevel',true) == 'General Manager'):?><a class="filter" rel="linemanager">Line Managers (LM)</a>  |  <?php endif;?><a class="filter" rel="staffmember">Staff (S)</a><br /><br />
<?php
echo '<table class="table table-bordered">
<tbody><tr class="">
<td>Name</td>
<td>Role</td>
<td>Job No</td>
<td>Access Level</td>
<td>Actions</td>
</tr>';
foreach ( $blogusers as $user ) {
$vals = maybe_unserialize(get_user_meta($user->ID,'Group',true));
if(in_array($GID,$vals)): 
?>
<tr class="all <?php echo strtolower(str_replace(' ','',get_user_meta($user->ID,'AccessLevel',true)));?>">
<td><?php echo get_user_meta($user->ID,'Name',true);?></td>
<td><?php if(get_user_meta($user->ID,'Role',true) != ''): echo get_the_title(get_user_meta($user->ID,'Role',true)); endif;?></td>
<td><?php if(get_user_meta($user->ID,'Role',true) != ''): echo get_post_meta(get_user_meta($user->ID,'Role',true),'JobNumber',true); endif;?></td>

<td><?php echo str_replace('Staff Member','S',str_replace('Line Manager','LM',str_replace('General Manager','GM',get_user_meta($user->ID,'AccessLevel',true))));?></td>
<td><a href="<?php  echo esc_url( home_url( '/' ));?>/view_staff/?sid=<?php echo $user->ID;?>">Details</a> |  <a href="<?php  echo esc_url( home_url( '/' ));?>/edit_staff/?sid=<?php echo $user->ID;?>">Edit</a> | <a href="<?php  echo esc_url( home_url( '/' ));?>/reporting/?staffr=<?php echo $user->ID;?>">Report</a> | <a class="inline " href="#Delete-<?php echo $user->ID;?>">Delete</a>
<div style="display:none">
<div id="Delete-<?php echo $user->ID;?>"><form action="" method="post" class="popup">
<header><h1>Confirm deletion</h1></header>
<label>To delete this record enter the word DELETE below and submit</label>
<input type="text" name="ConfirmDelete" />
<input type="hidden" name="Delete" value="<?php echo $user->ID;?>" />
<button type="submit" name="submit"  class="btn btn-primary btn-sm text-center btn-log mt-20" >Delete</button>

</form></div>
</div>
</td>
</tr>
<?php
#echo '<p><a href="'.esc_url( home_url( '/' )).'/view_staff/?sid='.$user->ID.'">' . get_user_meta($user->ID,'Name',true) . '</a></p>';
endif;
}
echo '</table>';
?>
</div>