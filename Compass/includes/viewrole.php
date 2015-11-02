<?php
$RID = $_GET['rid'];
$data=get_post($RID);
$charts = '';
$time = 100;
?>
 
  
<header>
<h1><?php echo $data->post_title;?></h1>
</header>
<div class="items">
<a href="<?php  echo esc_url( home_url( '/' ));?>/roles/edit_role/?rid=<?php echo $RID;?>">Edit</a> | <a class="inline " href="#Delete-<?php echo $RID;?>">Delete</a>
<div style="display:none">
<div id="Delete-<?php echo $RID;?>"><form action="<?php  echo esc_url( home_url( '/' ));?>/roles/" method="post">
<header><h1>Confirm deletion</h1></header>
<label>To delete this record enter the word DELETE below and submit</label>
<input type="text" name="ConfirmDelete" />
<input type="hidden" name="Delete" value="<?php echo $RID;?>" />
<button type="submit" name="submit"  class="btn btn-primary btn-sm text-center btn-log mt-20" >Delete</button>

</form></div>
</div>
<?php
$fields = array('Name','Description','Job Number','Manager');


?>

<?php
foreach($fields as $fi):
if($fi=='Name'):
$val = $data->post_title;
elseif($fi=='Description'):
$val = $data->post_content;
elseif($fi=='Manager'):
$val = $data->post_content;
elseif($fi=='Competencies'):
$val = get_user_meta(get_post_meta($RID,str_replace(' ','',$fi),true),'Name',true);

else:
$val = get_post_meta($RID,str_replace(' ','',$fi),true);
endif;
echo '<label><strong>'.ucfirst(str_replace('_',' ',$fi)).'</strong>: '.$val.'</label>
';
endforeach;
?>
<h3>Relevant staff</h3>
<?php
$args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	
	'fields'       => 'all',
	
 );
$blogusers = get_users( $args );
// Array of WP_User objects.
foreach ( $blogusers as $user ):
$competencies = maybe_unserialize(get_user_meta($user->ID,'Competencies',true));

$show = 1;
$jobcompetencies = maybe_unserialize(get_post_meta($RID,'Competencies',true));

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
echo '<p><a href="'.esc_url( home_url( '/' )).'/edit_staff/?sid='.$user->ID.'">'.get_user_meta($user->ID,'Name',true).'</a></p>';
?>
<div id="dual_x_div_<?php echo $user->ID;?>" style="width: 100%; height: 500px;"></div>

<script type="text/javascript">
<?php $time = $time + 300; $charts .='setTimeout(function(){  drawStuff_'.$user->ID.'();  }, '.$time.');';?>

      function drawStuff_<?php echo $user->ID;?>() {
        var data = new google.visualization.arrayToDataTable([
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

        var options = {
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

      var chart = new google.charts.Bar(document.getElementById('dual_x_div_<?php echo $user->ID;?>'));
      chart.draw(data, google.charts.Bar.convertOptions(options));
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