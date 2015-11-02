<?php
$user_id=get_current_user_id();
$user_id=11;
$competencies = maybe_unserialize(get_user_meta($user_id,'Competencies',true));

$args = array( 'posts_per_page' => 200,  'post_type' => 'role', 'orderby' => 'post_title', 'order' => 'ASC' );
$myposts = get_posts( $args );
foreach ( $myposts as $post ) : 
$show = 1;
$jobcompetencies = maybe_unserialize(get_post_meta($post->ID,'Competencies',true));

if(!empty($competencies) && $competencies != '' && !empty($jobcompetencies)):
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
google.setOnLoadCallback(drawStuff_<?php echo $post->ID;?>);

      function drawStuff_<?php echo $post->ID;?>() {
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

      var chart = new google.charts.Bar(document.getElementById('dual_x_div_<?php echo $post->ID;?>'));
      chart.draw(data, google.charts.Bar.convertOptions(options));
    };
	</script>
<?php
endif;
endif;
endforeach;
?>