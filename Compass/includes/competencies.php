<?php
if($_POST['Delete'] && $_POST['ConfirmDelete'] == 'DELETE'):
wp_trash_post( $_POST['Delete']  );
$notification =  '<p class="notification success">Competency deleted</p>';
elseif($_POST['Delete'] && $_POST['ConfirmDelete'] != 'DELETE'):
$notification =  '<p class="notification fail">You did not confirm the Deletion</p>';
endif;
?>
<header>
<h1><?php the_title();?></h1>
<?php echo $notification;?>
</header>
<div class="items">
<?php
$j = 1;
$args = array( 'posts_per_page' => 200,  'post_type' => 'competency', 'orderby' => 'id', 'order' => 'ASC' );
$myposts = get_posts( $args );
if(count($myposts)==0):?>
<p>No Competencies have been added. <a href="add_competency/">Start here</a>.</p>
<?php else:?>
<table class="table table-bordered">
<tbody>
<?php

foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
<tr>
<td><?php echo $j;?></td>
<td><?php the_title();?></td>
<td><a href="<?php  echo esc_url( home_url( '/' ));?>/view_competency/?cid=<?php echo $post->ID;?>">Details</a> <?php if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'ADMIN' || get_user_meta(get_current_user_id(),'AccessLevel',true) == 'CEO' || is_super_admin()): ?>| <a href="<?php  echo esc_url( home_url( '/' ));?>/edit_competency/?cid=<?php echo $post->ID;?>">Edit</a><?php endif;?> | <a class="inline " href="#Delete-<?php echo $post->ID;?>">Delete</a>
<div style="display:none">
<div id="Delete-<?php echo $post->ID;?>"><form action="" method="post" class="popup">
<header><h1>Confirm deletion</h1></header>
<label>To delete this record enter the word DELETE below and submit</label>
<input type="text" name="ConfirmDelete" />
<input type="hidden" name="Delete" value="<?php echo $post->ID;?>" />
<button type="submit" name="submit"  class="btn btn-primary btn-sm text-center btn-log mt-20" >Delete</button>

</form></div>
</div>
</td>
</tr>
<?php $j++; endforeach; 
wp_reset_postdata();?>
</tbody>
</table>
<?php endif;?>
</div>