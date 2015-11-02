<?php
include($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

/*$_POST['type']='addstaff';
$_POST['RoleID']='36';
$_POST['Blog']='8';*/
switch_to_blog( $_POST['Blog'] );

if($_POST['type']=='addstaff'):

$options = array('N/A' => '', '0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4');
$val2 = maybe_unserialize(get_post_meta($_POST['RoleID'],'Competencies',true));

echo '<p>Set competency scores required for this role</p>
<table class="table table-bordered">
<tbody><tr><td></td><td colspan="7"><strong>Role</strong></td><td colspan="6"><strong>Staff</strong></td></tr>';
$args = array( 'posts_per_page' => 200,  'post_type' => 'competency', 'orderby' => 'post_title', 'order' => 'ASC' );
$myposts = get_posts( $args );

foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
<tr><td><?php the_title();?></td>
<?php
$i = 0;
foreach($options as $op => $v):
if($v==$val2[$post->ID]):$checked = 'checked="checked"'; else: $checked = ''; endif;
echo '<td class="bg-grade-'.$i.'"><label><input type="radio"  class="pd" onclick="return false;" name="RoleCompetencies['.$post->ID.']" value="'.$v.'" '.$checked.' />'.$op.'</label></td>';
$i++;
endforeach;
$i = 0;
foreach($options as $op => $v):
if($v==$_POST['Competencies'][$post->ID]):$checked = 'checked="checked"'; else: $checked = ''; endif;

echo '<td class="bg-grade-'.$i.'"><label><input type="radio" name="Competencies['.$post->ID.']" value="'.$v.'" '.$checked.' />'.$op.'</label></td>';
$i++;
endforeach;
?>
</tr>
<?php endforeach; 
wp_reset_postdata();
echo '</table>';
elseif($_POST['type']=='editstaff'):
$user_id = $_POST['user_id'];
$val = maybe_unserialize(get_user_meta($user_id,'Competencies',true));
$val2 = maybe_unserialize(get_post_meta($_POST['RoleID'],'Competencies',true));

$options = array('N/A' => '', '0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4');

echo '<p>Set competency scores required for this role</p>
<table class="table table-bordered">
<tbody>
<tr><td></td><td colspan="7"><strong>Role</strong></td><td colspan="6"><strong>Staff</strong></td></tr>';
$args = array( 'posts_per_page' => 200,  'post_type' => 'competency', 'orderby' => 'post_title', 'order' => 'ASC' );
$myposts = get_posts( $args );
$count = 1;
foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
<tr><td><?php echo $count; $count++;?></td><td><?php the_title();?></td>
<?php
$i = 0;
foreach($options as $op => $v):
if($v==$val2[$post->ID]):$checked = 'checked="checked"'; else: $checked = ''; endif;
echo '<td class="bg-grade-'.$i.'"><label><input type="radio"  class="pd" onclick="return false;" name="RoleCompetencies['.$post->ID.']" value="'.$v.'" '.$checked.' />'.$op.'</label></td>';
$i++;
endforeach;
$i = 0;
foreach($options as $op => $v):
if($v==$val[$post->ID]):$checked = 'checked="checked"'; else: $checked = ''; endif;
echo '<td class="bg-grade-'.$i.'  bg2"><label><input type="radio" name="Competencies['.$post->ID.']" value="'.$v.'" '.$checked.' />'.$op.'</label></td>';
$i++;
endforeach;
?>
</tr>
<?php endforeach; 
wp_reset_postdata();
echo '</table>';
endif;

restore_current_blog();
?>