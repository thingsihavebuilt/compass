<?php
$fields = array('Name','Description','Email','DOB','Gender','Role','Manager','Group','Competencies');
$user_id = $_GET['sid'];
$user_info = get_userdata($_GET['sid']);
$charts = '';
$time = 100;
?>
<header>
<h1><?php echo get_user_meta($user_id,'Name',true)?></h1>
</header>
<div class="items">
<a href="<?php  echo esc_url( home_url( '/' ));?>/staff/edit_staff/?sid=<?php echo $user_id;?>">Edit</a> | <a class="inline " href="#Delete-<?php echo $user_id;?>">Delete</a>
<div style="display:none">
<div id="Delete-<?php echo $user_id;?>"><form action="<?php  echo esc_url( home_url( '/' ));?>/staff/" method="post">
<header><h1>Confirm deletion</h1></header>
<label>To delete this record enter the word DELETE below and submit</label>
<input type="text" name="ConfirmDelete" />
<input type="hidden" name="Delete" value="<?php echo $user_id;?>" />
<button type="submit" name="submit"  class="btn btn-primary btn-sm text-center btn-log mt-20" >Delete</button>

</form></div>
</div>
<h3 class="ptitle">Personal Details</h3>
<?php
foreach($fields as $fi):
if($fi=='Competencies'):
echo '<label><strong>'.ucfirst(str_replace('_',' ',$fi)).': </strong></label>
';
$val = maybe_unserialize(get_user_meta($user_id,str_replace(' ','',$fi),true));
$val2 = maybe_unserialize(get_post_meta(get_user_meta($user_id,'Role',true),'Competencies',true));

$options = array('N/A' => '', '0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4');

echo '
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
echo '<td class="bg-grade-'.$i.'"><label><input type="radio" class="pd" onclick="return false;"  name="Role'.str_replace(' ','',$fi).'['.$post->ID.']" value="'.$v.'" '.$checked.' />'.$op.'</label></td>';
$i++;
endforeach;
$i = 0;
foreach($options as $op => $v):
if($v==$val[$post->ID]):$checked = 'checked="checked"'; else: $checked = ''; endif;
echo '<td class="bg-grade-'.$i.' bg2"><label><input  type="radio" class="pd" onclick="return false;" name="'.str_replace(' ','',$fi).'['.$post->ID.']" value="'.$v.'" '.$checked.' />'.$op.'</label></td>';
$i++;
endforeach;
?>
</tr>
<?php endforeach; 
wp_reset_postdata();
echo '</table>';
else:
if($fi=='Group'):
$vals = maybe_unserialize(get_user_meta($user_id,str_replace(' ','',$fi),true));

foreach($vals as $k => $v):
$val .= get_the_title($v).', ';
endforeach;

$val =substr($val, 0, -2);
elseif($fi=='Manager'):
$val = get_user_meta((get_user_meta($user_id,str_replace(' ','',$fi),true)),'Name',true);
elseif($fi=='Role'):
echo '<h3 class="ptitle">Company Details</h3>';
if(get_user_meta($user_id,str_replace(' ','',$fi),true)== ''):
$val = '';
else:
$val = get_the_title(get_user_meta($user_id,str_replace(' ','',$fi),true));
endif;
elseif($fi=='Description'):

$val = get_user_meta($user_id,str_replace(' ','','description'),true);
else:
$val = get_user_meta($user_id,str_replace(' ','',$fi),true);
endif;
echo '<label><strong>'.ucfirst(str_replace('_',' ',$fi)).': </strong>'.$val.'</label>
';
endif;
endforeach;
?>
<h3>Relevant roles</h3>
<?php
$competencies = maybe_unserialize(get_user_meta($user_id,'Competencies',true));

$args = array( 'posts_per_page' => 200,  'post_type' => 'role', 'orderby' => 'post_title', 'order' => 'ASC' );
$myposts = get_posts( $args );
foreach ( $myposts as $post ) : 
$show = 1;
$jobcompetencies = maybe_unserialize(get_post_meta($post->ID,'Competencies',true));

if(!empty($competencies) && $competencies != ''):
foreach($jobcompetencies as $jc => $v):

$val = $competencies[$jc];
$min = $v-1;
$max = $v+1;

if($v != ''):
if($val >= $min && $val <= $max):
else:
$show = 0;
endif;

endif;
endforeach;
if($show==1):
echo '<p><a href="'.esc_url( home_url( '/' )).'/edit_role/?rid='.$post->ID.'">'.get_the_title($post->ID).'</a></p>';
?>
<div id="dual_x_div_<?php echo $post->ID;?>" style="width: 100%; height: 500px;"></div>

<script type="text/javascript">

<?php $time = $time + 300; $charts .='setTimeout(function(){  drawStuff_'.$post->ID.'();  }, '.$time.');';?>
      function drawStuff_<?php echo $post->ID;?>() {
        var data_<?php echo $post->ID;?> = new google.visualization.arrayToDataTable([
          ['Competency', 'Required Score', 'Actual'],
		    <?php
			$count = 1;
		    foreach($jobcompetencies as $jc => $v):
			if($v != ''):
			echo " ['".$count."', ".$v.", ".$competencies[$jc]."],";
			$count++;
			endif;
			endforeach;
			?>
         
        ]);

        var options_<?php echo $post->ID;?> = {
          width: 900,
          chart: {
            title: 'Competency Rating',
            subtitle: 'required on the left, actual on the right'
          },
          
colors: ['#685311', '#97192F'],
          
vAxis: {

            viewWindowMode:'explicit',

            viewWindow: {
              max:5,
              min:0
            }
        },
bars: 'vertical',
           // Required for Material Bar Charts.
          
          axes: {
            x: {
              //distance: {label: 'parsecs'}, // Bottom x-axis.
             // brightness: {side: 'top', label: 'apparent magnitude'} // Top x-axis.
            }
          }
        };

      var chart = new google.charts.Bar(document.getElementById('dual_x_div_<?php echo $post->ID;?>'));
      chart.draw(data_<?php echo $post->ID;?>, google.charts.Bar.convertOptions(options_<?php echo $post->ID;?>));
    };

	</script>
<?php
endif;
endif;
endforeach;
?>
</div>
<script type="text/javascript">

function drawcharts(){
	<?php echo $charts;?>	
	}

	
google.setOnLoadCallback(drawcharts());

</script>