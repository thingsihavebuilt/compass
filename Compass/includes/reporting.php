<?php
$charts = '';
$time = 100;
if($_REQUEST['groupr']):unset($_REQUEST['staffr']); $title = ' - '.get_the_title($_REQUEST['groupr']);
elseif($_REQUEST['roler']):unset($_REQUEST['groupr']); $title = ' - '.get_the_title($_REQUEST['roler']);
elseif($_REQUEST['staffr']):unset($_REQUEST['groupr']);unset($_REQUEST['roler']); $title = ' - '.get_user_meta($_REQUEST['staffr'],'Name',true);
endif;?>

<header>
<h1>Reporting<?php echo $title;?></h1>
</header>
<div class="items">
<form action="" method="post">
<select name="roler" id="roler"><option value="">Choose a role</option>
<?php
if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'General Manager'):
$args = array( 'posts_per_page' => 200,  'post_type' => 'role', 'orderby' => 'post_title', 'order' => 'ASC','meta_key'     => 'Manager',
	'meta_value'   => array(get_current_user_id(),get_user_meta(get_current_user_id(),'Manager',true)),
	'meta_compare' => 'IN' );
 elseif(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):
 $args = array( 'posts_per_page' => 200,  'post_type' => 'role', 'orderby' => 'post_title', 'order' => 'ASC','meta_key'     => 'Manager',
	'meta_value'   => array(get_current_user_id()),
	'meta_compare' => 'IN' );
 else:
 $args = array( 'posts_per_page' => 200,  'post_type' => 'role', 'orderby' => 'post_title', 'order' => 'ASC' );
 endif;
$myposts = get_posts( $args );
foreach ( $myposts as $post ) : 
if($post->ID==$_REQUEST['roler']):$checked='selected="selected"'; else: $checked='';endif;
echo '<option value="'.$post->ID.'" '.$checked.'>' . get_the_title($post->ID) . '</option>';
endforeach;
wp_reset_postdata();?>
</select>
OR 
<select name="staffr" id="staffr"><option value="">Choose a staff member</option>
<?php
if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'General Manager'):
 $args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	'meta_key'     => 'Manager',
	'meta_value'   => array(get_current_user_id(),get_user_meta(get_current_user_id(),'Manager',true)),
	'meta_compare' => 'IN',
	'fields'       => 'all',
	
 );
 elseif(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):
 $args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	'meta_key'     => 'Manager',
	'meta_value'   => get_current_user_id(),
	'meta_compare' => '=',
	'fields'       => 'all',
	
 );
 else:
 $args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	
	'fields'       => 'all',
	
 );
 endif;
$blogusers = get_users( $args );
// Array of WP_User objects.
foreach ( $blogusers as $user ):
if($user->ID==$_REQUEST['staffr']):$checked='selected="selected"'; else: $checked='';endif;
echo '<option value="'.$user->ID.'" '.$checked.'>' . get_user_meta($user->ID,'Name',true) . '</option>';
endforeach;
?>

</select>
OR
<?php
echo '<select name="groupr"  id="groupr"><option value="">Choose a group</option>';
if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'General Manager'):
$args = array( 'posts_per_page' => 200,  'post_type' => 'group', 'orderby' => 'post_title', 'order' => 'ASC','meta_key'     => 'Manager',
	'meta_value'   => array(get_current_user_id(),get_user_meta(get_current_user_id(),'Manager',true)),
	'meta_compare' => 'IN' );
 elseif(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):
 $args = array( 'posts_per_page' => 200,  'post_type' => 'group', 'orderby' => 'post_title', 'order' => 'ASC','meta_key'     => 'Manager',
	'meta_value'   => array(get_current_user_id()),
	'meta_compare' => 'IN' );
 else:
 $args = array( 'posts_per_page' => 200,  'post_type' => 'group', 'orderby' => 'post_title', 'order' => 'ASC' );
 endif;
$myposts = get_posts( $args );
foreach ( $myposts as $post ) : 
if($post->ID==$_REQUEST['groupr']):$checked='selected="selected"'; else: $checked='';endif;
echo '<option value="'.$post->ID.'" '.$checked.'>' . get_the_title($post->ID) . '</option>';
endforeach;
wp_reset_postdata();?>
<input type="submit" name="submit" value="View report" class="btn btn-primary btn-sm text-center btn-log mt-20 submit" />
</form>


<?php if(isset($_REQUEST['staffr'])  && $_REQUEST['staffr'] != ''):?>
<?php if ( get_user_meta($_REQUEST['staffr'],'Role',true) != ''):?>
<h3>Current role: <?php echo get_the_title(get_user_meta($_REQUEST['staffr'],'Role',true));?></h3>
<?php
$show = 1;
$competencies = maybe_unserialize(get_user_meta($_REQUEST['staffr'],'Competencies',true));
$jobcompetencies = maybe_unserialize(get_post_meta(get_user_meta($_REQUEST['staffr'],'Role',true),'Competencies',true));

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
$show = 1;
if($show==1):

?>
<div id="dual_x_div_<?php echo 1;?>" style="width: 100%; height: 500px; padding-bottom:30px; margin-bottom:30px; border-bottom:1px solid #000"></div>

<script type="text/javascript">

<?php $time = $time + 300; $charts .='setTimeout(function(){  drawStuff_1();  }, '.$time.');';?>
      function drawStuff_<?php echo 1;?>() {
        var data = new google.visualization.arrayToDataTable([
          ['Competency', 'Required Score', 'Actual'],
		    <?php
			$count = 1;
		    foreach($jobcompetencies as $jc => $v):
			if($competencies[$jc] == ''):$uc = 0; else: $uc = $competencies[$jc]; endif;
			echo " [".$count.",".$v.",".$uc."],";
			$count++;
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

      var chart = new google.charts.Bar(document.getElementById('dual_x_div_<?php echo 1;?>'));
      chart.draw(data, google.charts.Bar.convertOptions(options));
    };

	</script>
<?php
echo '<label><strong>Current competencies: </strong></label>
';
$val = maybe_unserialize(get_user_meta($_REQUEST['staffr'],'Competencies',true));
$val2 = maybe_unserialize(get_post_meta(get_user_meta($_REQUEST['staffr'],'Role',true),'Competencies',true));

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
echo '<td class="bg-grade-'.$i.'  bg2"><label><input  type="radio" class="pd" onclick="return false;" name="'.str_replace(' ','',$fi).'['.$post->ID.']" value="'.$v.'" '.$checked.' />'.$op.'</label></td>';
$i++;
endforeach;
?>
</tr>
<?php endforeach; 
wp_reset_postdata();
echo '</table>';
 endif;endif;endif;?>
<h3>Relevant roles</h3>
<?php
$competencies = maybe_unserialize(get_user_meta($_REQUEST['staffr'],'Competencies',true));
$count = 0;
if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'General Manager'):
$args = array( 'posts_per_page' => 200,  'post_type' => 'role', 'orderby' => 'post_title', 'order' => 'ASC','meta_key'     => 'Manager',
	'meta_value'   => array(get_current_user_id(),get_user_meta(get_current_user_id(),'Manager',true)),
	'meta_compare' => 'IN' );
 elseif(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):
 $args = array( 'posts_per_page' => 200,  'post_type' => 'role', 'orderby' => 'post_title', 'order' => 'ASC','meta_key'     => 'Manager',
	'meta_value'   => array(get_current_user_id()),
	'meta_compare' => 'IN' );
 else:
 $args = array( 'posts_per_page' => 200,  'post_type' => 'role', 'orderby' => 'post_title', 'order' => 'ASC' );
 endif;
 if(isset($_REQUEST['roler'])  && $_REQUEST['roler'] != ''):$args['include']=$_REQUEST['roler']; endif;
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
$count++;
echo '<p><a href="'.esc_url( home_url( '/' )).'/edit_role/?rid='.$post->ID.'">'.get_the_title($post->ID).'</a></p>';
?>
<div id="dual_x_div_<?php echo $post->ID;?>" style="width: 100%; height: 500px; padding-bottom:30px; margin-bottom:30px; border-bottom:1px solid #000"></div>

<script type="text/javascript">
<?php $time = $time + 300; $charts .='setTimeout(function(){  drawStuff_'.$post->ID.'();  }, '.$time.');';?>

      function drawStuff_<?php echo $post->ID;?>() {
        var data = new google.visualization.arrayToDataTable([
          ['Competency', 'Required Score', 'Actual'],
		    <?php
			$count = 1;
		    foreach($jobcompetencies as $jc => $v):
			if($v != ''):
			if($competencies[$jc] !=''):$val = $competencies[$jc]; else: $val = 0; endif;
			echo " [".$count.",".$v.",".$val."],";
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
if($count==0): echo'<p>No roles match</p>'; endif;

?>




<?php elseif(isset($_REQUEST['groupr']) && $_REQUEST['groupr'] != ''): ?>

<h3>Group / Job Averages</h3>
<?php
if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'General Manager'):
  $args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	
	'fields'       => 'all',
	'relation' => 'AND', // Optional, defaults to "AND"
	array(
		'key'     => 'Manager',
		'value'   => array(get_current_user_id(),get_user_meta(get_current_user_id(),'Manager',true)),
		'compare' => 'IN'
	),
	array(
		'key'     => 'Group',
		'value'   => $_REQUEST['groupr'],
		'compare' => '='
	)
 );
 elseif(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):
 $args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	
	'fields'       => 'all',
	'relation' => 'AND', // Optional, defaults to "AND"
	array(
		'key'     => 'Manager',
		'value'   => array(get_current_user_id(),get_user_meta(get_current_user_id(),'Manager',true)),
		'compare' => 'IN'
	),
	array(
		'key'     => 'Group',
		'value'   => $_REQUEST['groupr'],
		'compare' => '='
	)
 );
 else:
 $args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	
	'fields'       => 'all',
	'relation' => 'AND', // Optional, defaults to "AND"
	
	array(
		'key'     => 'Group',
		'value'   => $_REQUEST['groupr'],
		'compare' => '='
	)
 );
 endif;
$userlist = '<p><strong>Group members:</strong></p><table class="table table-bordered">
<tbody>';

$blogusers = get_users( $args );
// Array of WP_User objects.
$roles = array();
$groupcompetencies = array();
$groupcompetenciescount = array();
foreach ( $blogusers as $user ):
$vals = maybe_unserialize(get_user_meta($user->ID,'Group',true));
if(in_array($_REQUEST['groupr'],$vals)): 
#$userlist .= '<a href="'.esc_url( home_url( '/' )).'staff/view_staff/?sid='.$user->ID.'">'.get_user_meta($user->ID,'Name',true).'</a><br />';
#$userlist .= '<a href="'.esc_url( home_url( '/' )).'staff/view_staff/?sid='.$user->ID.'">'.get_user_meta($user->ID,'Name',true).'</a><br />';
$roles[get_user_meta($user->ID,'Role',true)]++;
endif;
endforeach;


foreach ( $blogusers as $user ):
$vals = maybe_unserialize(get_user_meta($user->ID,'Group',true));
if(in_array($_REQUEST['groupr'],$vals)): 
if(count($roles) == 1):
foreach ( $roles as $k => $v ):
$role = $k;
endforeach;
else:
$role = get_user_meta($user->ID,'Role',true);
endif;
$competencies = maybe_unserialize(get_user_meta($user->ID,'Competencies',true));
if($_REQUEST['roler'] != ''):$role = $_REQUEST['roler']; endif;



$jobcompetencies = maybe_unserialize(get_post_meta($role,'Competencies',true));
#$userlist .= '<a class="inline " href="#View-'.$user->ID.'">'.get_user_meta($user->ID,'Name',true).'</a><br />';
$userlist .= '<tr class="all '.strtolower(str_replace(' ','',get_user_meta($user->ID,'AccessLevel',true))).'">
<td>'. get_user_meta($user->ID,'Name',true).'</td>';
$userlist .= '<td>';
if(get_user_meta($user->ID,'Role',true) != ''): $userlist .=  get_the_title(get_user_meta($user->ID,'Role',true)); endif;
$userlist .=  '</td>
<td>'. get_user_meta($user->ID,'AccessLevel',true).'</td>
<td><a href="'. esc_url( home_url( '/' )).'/view_staff/?sid='. $user->ID.'">Details</a> |  <a href="'. esc_url( home_url( '/' )).'/edit_staff/?sid='. $user->ID.'">Edit</a> | <a href="'. esc_url( home_url( '/' )).'/reporting/?staffr='.$user->ID.'">Report</a> | <a class="inline " href="#Delete-'. $user->ID.'">Delete</a>
<div style="display:none">
<div id="Delete-'. $user->ID.'"><form action="" method="post" class="popup">
<header><h1>Confirm deletion</h1></header>
<label>To delete this record enter the word DELETE below and submit</label>
<input type="text" name="ConfirmDelete" />
<input type="hidden" name="Delete" value="'. $user->ID.'" />
<button type="submit" name="submit"  class="btn btn-primary btn-sm text-center btn-log mt-20" >Delete</button>

</form></div>
</div>
</td>
</tr>';
?>

<div class="hidden">
<div id="View-<?php echo $user->ID;?>">
<header><h1><?php echo get_user_meta($user->ID,'Name',true);?> Stats</h1></header>
<div id="dual_x_div_<?php echo $user->ID;?>" style="width: 100%; height: 500px; padding-bottom:30px; margin-bottom:30px; border-bottom:1px solid #000"></div>
</div>
</div>


<script type="text/javascript">jQuery(function(){

<?php $time = $time + 300; $charts .='setTimeout(function(){  drawStuff_'.$user->ID.'();  }, '.$time.');';?>
      function drawStuff_<?php echo $user->ID;?>() {
        var data = new google.visualization.arrayToDataTable([
          ['Competency', 'Required Score', 'Actual'],
		    <?php
			$count = 1;
		    foreach($jobcompetencies as $jc => $v):
			if($v != ''):
			if($competencies[$jc] == ''):$uc = 0; else: $uc = $competencies[$jc]; endif;
			echo " [".$count.",".$v.",".$uc."],";
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
foreach($competencies as $k => $v):

$groupcompetencies[$k]=$groupcompetencies[$k]+$v;
$groupcompetenciescount[$k]++;
endforeach;
endif;
endforeach;
$userlist .= '  </tbody>
</table>';

if(count($roles) == 1):
foreach ( $roles as $k => $v ):
$role = $k;
endforeach;

$show = 0;

if(!empty($groupcompetencies) && $groupcompetencies != '' && !empty($jobcompetencies) && $jobcompetencies != ''):

$show = 1;
foreach($jobcompetencies as $jc => $v):

$val = $groupcompetencies[$jc];
$min = $v-1;
$max = $v+1;

endforeach;
if($show==1):

?>
<div id="dual_x_div" style="width: 100%; height: 500px; padding-bottom:30px; margin-bottom:30px; border-bottom:1px solid #000 background:#fff; padding:0%"></div>

<script type="text/javascript">
<?php $time = $time + 300; $charts .='setTimeout(function(){  drawStuff();  }, '.$time.');';?>
      function drawStuff() {
        var data = new google.visualization.arrayToDataTable([
          ['Competency', 'Required Score', 'Average'],
		    <?php
			$count = 1;
		    foreach($jobcompetencies as $jc => $v):
			if($v != ''):
			if(ceil($groupcompetencies[$jc]/$groupcompetenciescount[$jc]) !=''):$val = ceil($groupcompetencies[$jc]/$groupcompetenciescount[$jc]); else: $val = 0; endif;
			echo " [".$count.",".$v.",".$val."],";
			$count++;
			endif;
			endforeach;
			?>
         
        ]);

        var options = {
          width: 900,
          chart: {
            title: 'Competency Rating',
            subtitle: 'required on the left, average on the right'
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

      var chart = new google.charts.Bar(document.getElementById('dual_x_div'));
      chart.draw(data, google.charts.Bar.convertOptions(options));
    };
	</script>
    <?php echo $userlist;?>
  
<?php

endif;

else:
echo '<p>No competencies set for this role.</p>';
endif;
else:
echo $userlist;
endif;

?>
<?php elseif(isset($_REQUEST['roler']) && $_REQUEST['roler'] != ''): ?>
<p><?php echo get_the_title($_REQUEST['roler']);?>: Staff/Role averages</p>
<?php
$args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	
	'fields'       => 'all',
	'meta_query'=>

         array(

            array(
	'relation' => 'AND', // Optional, defaults to "AND"
	
	array(
		'key'     => 'Role',
		'value'   => $_REQUEST['roler'],
		'compare' => '='
	)))
 );

$userlist = '<p><strong><br />Staff that match this roles competency</strong></p>';
$userlistpeople = '';
$rolelinks = 'View Individual report:<br />';
$blogusers = get_users( $args );
// Array of WP_User objects.
$groupcompetencies = array();
$groupcompetenciescount = array();

foreach ( $blogusers as $user ):
$userlistpeople .= '<a href="'.esc_url( home_url( '/' )).'staff/view_staff/?sid='.$user->ID.'">'.get_user_meta($user->ID,'Name',true).'</a><br />';
$competencies = maybe_unserialize(get_user_meta($user->ID,'Competencies',true));
$jobcompetencies = maybe_unserialize(get_post_meta($_REQUEST['roler'],'Competencies',true));
$rolelinks .= '<a class="inline " href="#View-'.$user->ID.'">'.get_user_meta($user->ID,'Name',true).'</a><br />';
?>
<div class="hidden">
<div id="View-<?php echo $user->ID;?>">
<header><h1><?php echo get_user_meta($user->ID,'Name',true);?> Stats</h1></header>
<div id="dual_x_div_<?php echo $user->ID;?>" style="width: 100%; height: 500px; padding-bottom:30px; margin-bottom:30px; border-bottom:1px solid #000"></div>
</div>
</div>


<script type="text/javascript"><?php $time = $time + 300; $charts .='setTimeout(function(){  drawStuff_'.$user->ID.'();  }, '.$time.');';?>

      function drawStuff_<?php echo $user->ID;?>() {
        var data = new google.visualization.arrayToDataTable([
          ['Competency', 'Required Score', 'Actual'],
		    <?php
			$count = 1;
		    foreach($jobcompetencies as $jc => $v):
			if($v != ''):
			if($competencies[$jc] == ''):$uc = 0; else: $uc = $competencies[$jc]; endif;
			echo " [".$count.",".$v.",".$uc."],";
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

foreach($competencies as $k => $v):

$groupcompetencies[$k]=$groupcompetencies[$k]+$v;
$groupcompetenciescount[$k]++;
endforeach;
endforeach;

$show = 0;

if(!empty($groupcompetencies) && $groupcompetencies != '' && !empty($jobcompetencies) && $jobcompetencies != ''):

$show = 1;
foreach($jobcompetencies as $jc => $v):

$val = $groupcompetencies[$jc];
$min = $v-1;
$max = $v+1;

endforeach;

if($show==1):

?>
<div id="dual_x_div" style="width: 100%; height: 500px; padding-bottom:30px; margin-bottom:30px; border-bottom:1px solid #000 background:#fff; padding:0%"></div>

<script type="text/javascript">
<?php $time = $time + 300; $charts .='setTimeout(function(){  drawStuff();  }, '.$time.');';?>
      function drawStuff() {
        var data = new google.visualization.arrayToDataTable([
          ['Competency', 'Role Required Score', 'Staff Average'],
		    <?php
			$count = 1;
		    foreach($jobcompetencies as $jc => $v):
			if($v != ''):
			if(ceil($groupcompetencies[$jc]/$groupcompetenciescount[$jc]) !=''):$val = ceil($groupcompetencies[$jc]/$groupcompetenciescount[$jc]); else: $val = 0; endif;
			echo " [".$count.",".$v.",".$val."],";
			$count++;
			endif;
			endforeach;
			?>
         
        ]);

        var options = {
          width: 900,
          chart: {
            title: 'Competency Rating',
            subtitle: 'required on the left, average on the right'
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

      var chart = new google.charts.Bar(document.getElementById('dual_x_div'));
      chart.draw(data, google.charts.Bar.convertOptions(options));
    };
	</script>
    <?php echo $rolelinks; echo $userlist; endif;?>

<?php

if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'General Manager'):
 $args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	'meta_key'     => 'Manager',
	'meta_value'   => array(get_current_user_id(),get_user_meta(get_current_user_id(),'Manager',true)),
	'meta_compare' => 'IN',
	'fields'       => 'all',
	
 );
 elseif(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Line Manager'):
 $args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	'meta_key'     => 'Manager',
	'meta_value'   => get_current_user_id(),
	'meta_compare' => '=',
	'fields'       => 'all',
	
 );
 else:
 $args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	
	'fields'       => 'all',
	
 );
 endif;
$blogusers = get_users( $args );
// Array of WP_User objects.
foreach ( $blogusers as $user ):
$competencies = maybe_unserialize(get_user_meta($user->ID,'Competencies',true));

$show = 1;
$jobcompetencies = maybe_unserialize(get_post_meta($_REQUEST['roler'],'Competencies',true));

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
echo '<p><a href="'.esc_url( home_url( '/' )).'staff/edit_staff/?sid='.$user->ID.'">'.get_user_meta($user->ID,'Name',true).'</a></p>';
?>
<div id="dual_x_div_<?php echo $user->ID;?>" style="width: 100%; height: 500px; padding-bottom:30px; margin-bottom:30px; border-bottom:1px solid #000"></div>

<script type="text/javascript">
<?php $time = $time + 300; $charts .='setTimeout(function(){  drawStuff_'.$user->ID.'();  }, '.$time.');';?>

      function drawStuff_<?php echo $user->ID;?>() {
        var data = new google.visualization.arrayToDataTable([
          ['Competency', 'Required Score', 'Actual'],
		    <?php
			$count = 1;
		    foreach($jobcompetencies as $jc => $v):
			if($v != ''):
			if($competencies[$jc] !=''):$val = $competencies[$jc]; else: $val = 0; endif;
			echo " [".$count.",".$v.",".$val."],";
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

<?php endif;  endif;?>
</div>

<script type="text/javascript">

function drawcharts(){
	<?php echo $charts;?>	
	}

	
google.setOnLoadCallback(drawcharts());

</script>