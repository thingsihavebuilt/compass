<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Compass
 * @since Compass 1.0
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); global $post;global  $wpdb; $custom = get_post_custom($post->ID);  ?>

<?php if(get_the_content() != ''): $i = 1; $tc = 0;?>
<?php
$start_limiter = '[gallery';
$end_limiter = ']';
$haystack = get_the_content();
$start_pos = strpos($haystack,$start_limiter);
$end_pos = strpos($haystack,$end_limiter,$start_pos);
$galldata = substr($haystack, $start_pos+1, ($end_pos-1)-$start_pos);
$ng = explode('ids="',$galldata);
?>
<section id="content-main" <?php if(!empty($ng) && count($ng) > 1): echo 'class="full"'; endif;?>  <?php if($custom['Block1BgColour'][0] != ''): echo' style="background-color:'.$custom['Block1BgColour'][0].'"'; endif;?>>
<?php if($post->ID != 1622):?><div class="inner"><h1><?php the_title();?></h1></div><?php endif;?>
<?php
if(!empty($ng) && count($ng) > 1):

$toget = array_reverse(explode(',',str_replace('"','',$ng[1])));

$args =  array (
		'include' => $toget,
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		'orderby' => 'post__in',
		
		
	);

$j = 0;
	$myposts = get_posts( $args );
?>

<div  id="sl1" class=" slideshow-wrap count<?php echo count($myposts);?>" data-count="<?php echo count($myposts);?>">
        <?php
		$controls = '';
	$total = count($myposts);
	$k = 1;
	foreach ( $myposts as $image ) : setup_postdata( $image ); $ic =get_post_custom($image->ID); $tc++;
	if($k == 1):
	
			$controls .= '<input type="radio" id="button-'.$i.'" class="button  bcr'.$k.'" name="controls" checked="checked"/><label for="button-'.$i.'" class="buttonclick bc'.$i.' bck'.$k.'" rel="'.$i.'"></label><label for="button-'.$i.'" class="arrows" id="arrow-'.$i.'">></label>';
			else:
			$controls .= '<input type="radio" id="button-'.$i.'" class="button bcr'.$k.'" name="controls"/><label for="button-'.$i.'" class="buttonclick bc'.$i.' bck'.$k.'" rel="'.$i.'"></label><label for="button-'.$i.'" class="arrows" id="arrow-'.$i.'">></label>';
			endif;
			$i++;
			$k++;
	endforeach;
	
	echo $controls;
	?>
        <div class="slideshow-inner" id="slideshow-inner1">
            <ul>
            <?php		
$i = $i - $total;
foreach ( $myposts as $image ) : setup_postdata( $image ); $ic =get_post_custom($image->ID); $tc++;
$img = wp_get_attachment_image_src( $image->ID, 'full' );		
			
			?>
            <li class="slide<?php echo $i;?>" <?php if($ic['_gallery_link_additional_css_classes'][0] != ''):?>style="background:<?php echo $ic['_gallery_link_additional_css_classes'][0];?>"<?php endif;?>>
                     <?php 
if ($ic['_gallery_link_url'][0] != ''): ?>
<a class="" href="<?php echo $ic['_gallery_link_url'][0];?>"><img src="<?php echo $img[0];?>" /></a>
<? else: ?>
<img src="<?php echo $img[0];?>" />
<?php endif;?>
                   
                </li>
               
            <?php 
			
			
		
       $i++;
		endforeach;wp_reset_query();
		

?>
                
               
            </ul>
          
        </div>
    </div>
    
<script type="text/javascript">
var play1 = 1;
var start1 = 1;
function autoplay1(){
if(play1 == 1){
start1++;
if(jQuery("#sl1 .bck"+start1).length){
jQuery("#sl1 input").attr('checked', '');
} else {start1=1;}

jQuery("#sl1 .bcr"+start1).attr('checked', 'checked');

//setInterval(function () {autoplay();}, 3000);	
}
}
var myVar1 = setInterval(function(){myTimer1()},4000);
function myTimer1() {
autoplay1();
}
jQuery("#sl1 label, #sl1 input").click(function(e) {
  clearInterval(myVar1);  
});
</script>
<style type="text/css">

<?php

$x = count($myposts);
echo '
#slideshow-inner1>ul { width: '.($x*100).'%;}
#slideshow-inner1>ul>li {width: '.(100/$x).'%;}';

$t = $i-1;
$tw = $x * 100;
$w - $tw/$x;
$l = 0;
$m = -18* ceil(($total-1)/2);
for ($x = ($i - $total); $x <= $t; $x++) {
echo '.slideshow-wrap input[type=radio]#button-'.$x.':checked~label[for=button-'.$x.'] { background-color: rgba(100,100,100,1) }
 .slideshow-wrap input[type=radio]#button-'.$x.':checked~#slideshow-inner1>ul { left: -'.($l*100).'% }
 .slideshow-wrap label[for=button-'.$x.'] { margin-left: '.$m.'px }

 ';
 $m=$m+18;
$l++;

} 
?>




</style>    
    
    
<!--<div class="gallery fade<?php echo count($myposts);?>">

<?php		

	
	
foreach ( $myposts as $image ) : setup_postdata( $image ); $ic =get_post_custom($image->ID); $tc++;
$img = wp_get_attachment_image_src( $image->ID, 'full' );		
			$j++;
			
			?>
           <div class="item" <?php if($ic['_gallery_link_additional_css_classes'][0] != ''):?>style="background:<?php echo $ic['_gallery_link_additional_css_classes'][0];?>"<?php endif;?>><a class="" href="<?php echo $ic['_gallery_link_url'][0];?>"> <?php 
if ($ic['_gallery_link_url'][0] != ''): ?>
<a class="" href="<?php echo $ic['_gallery_link_url'][0];?>"><img src="<?php echo $img[0];?>" /></a>
<? else: ?>
<img src="<?php echo $img[0];?>" />
<?php endif;?></a></div>
            <?php 
			
			
		endforeach; wp_reset_query();
		

?>

</div>-->
<?php else:?>
<div <?php if (strpos(get_the_content(),'script.js') !== false):?> <?php else:?>class="inner"<?php endif;?>><?php the_content();?></div>
<?php endif;?>
</section>
<?php endif;?>
<?php if($custom['_secondary_html_1708'][0] != ''):
$start_limiter = '[gallery';
$end_limiter = ']';
$haystack = $custom['_secondary_html_1708'][0];
$start_pos = strpos($haystack,$start_limiter);
$end_pos = strpos($haystack,$end_limiter,$start_pos);
$galldata = substr($haystack, $start_pos+1, ($end_pos-1)-$start_pos);
$ng = explode('ids="',$galldata);
?>
<section id="content-2"  <?php if($custom['Block2BgColour'][0] != ''): echo' style="background-color:'.$custom['Block2BgColour'][0].'"'; endif;?><?php if(!empty($ng) && count($ng) > 1): echo 'class="full"'; endif;?>>

<?php 


if(!empty($ng) && count($ng) > 1):

$toget = array_reverse(explode(',',str_replace('"','',$ng[1])));

$args =  array (
		'include' => $toget,
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		'orderby' => 'post__in',
		
		
	);

$j = 0;
	$myposts = get_posts( $args );
?>
<div id="sl2" class=" slideshow-wrap count<?php echo count($myposts);?>" data-count="<?php echo count($myposts);?>">
        <?php
		$controls = '';
	$total = count($myposts);
	$k = 1;
	foreach ( $myposts as $image ) : setup_postdata( $image ); $ic =get_post_custom($image->ID); $tc++;
	if($k == 1):
	
			$controls .= '<input type="radio" id="button-'.$i.'" class="button  bcr'.$k.'" name="controls2" checked="checked"/><label for="button-'.$i.'" class="buttonclick bc'.$i.' bck'.$k.'" rel="'.$i.'"></label><label for="button-'.$i.'" class="arrows" id="arrow-'.$i.'">></label>';
			else:
			$controls .= '<input type="radio" id="button-'.$i.'" class="button bcr'.$k.'" name="controls2"/><label for="button-'.$i.'" class="buttonclick bc'.$i.' bck'.$k.'" rel="'.$i.'"></label><label for="button-'.$i.'" class="arrows" id="arrow-'.$i.'">></label>';
			endif;
			$i++;
			$k++;
	endforeach;
	
	echo $controls;
	?>
        <div class="slideshow-inner" id="slideshow-inner2">
            <ul>
            <?php		
$i = $i - $total;
foreach ( $myposts as $image ) : setup_postdata( $image ); $ic =get_post_custom($image->ID); $tc++;
$img = wp_get_attachment_image_src( $image->ID, 'full' );		
			$j++;
			?>
            <li class="slide<?php echo $i;?>" <?php if($ic['_gallery_link_additional_css_classes'][0] != ''):?>style="background:<?php echo $ic['_gallery_link_additional_css_classes'][0];?>"<?php endif;?>>
                     <?php 
if ($ic['_gallery_link_url'][0] != ''): ?>
<a class="" href="<?php echo $ic['_gallery_link_url'][0];?>"><img src="<?php echo $img[0];?>" /></a>
<? else: ?>
<img src="<?php echo $img[0];?>" />
<?php endif;?>
                   
                </li>
               
            <?php 
			
			
		
       $i++;
		endforeach;wp_reset_query();
		

?>
                
               
            </ul>
          
        </div>
    </div>
    <script type="text/javascript">
var play2 = 1;
var start2 = 1;
function autoplay2(){
if(play2 == 1){
start2++;
if(jQuery("#sl2 .bck"+start2).length){
jQuery("#sl2 input").attr('checked', '');
} else {start2=1;}
jQuery("#sl2 .bcr"+start2).attr('checked', 'checked');
//setInterval(function () {autoplay();}, 3000);	
}
}
var myVar2 = setInterval(function(){myTimer2()},4000);
function myTimer2() {
autoplay2();
}
jQuery("#sl2 label, #sl2 input").click(function(e) {
  clearInterval(myVar2);  
});
</script>
<style type="text/css">
<?php


$x = count($myposts);
echo '
#slideshow-inner2>ul { width: '.($x*100).'%;}
#slideshow-inner2>ul>li {width: '.(100/$x).'%;}';
$t = $i-1;
$tw = $x * 100;
$w - $tw/$x;
$l = 0;
$m = -18* ceil(($total-1)/2);
for ($x = $i - $total; $x <= $t; $x++) {
echo '.slideshow-wrap input[type=radio]#button-'.$x.':checked~label[for=button-'.$x.'] { background-color: rgba(100,100,100,1) }
 .slideshow-wrap input[type=radio]#button-'.$x.':checked~#slideshow-inner2>ul { left: -'.($l*100).'% }
 .slideshow-wrap label[for=button-'.$x.'] { margin-left: '.$m.'px }

 ';
 $m=$m+18;
$l++;

} 
?>




</style>    
<?php else:?>
<div class="inner"><?php echo apply_filters('the_content',$custom['_secondary_html_1708'][0]);?></div>
<?php endif;?>
</section><?php endif;?>
<?php if($custom['_secondary_html_1709'][0] != ''):

$start_limiter = '[gallery';
$end_limiter = ']';
$haystack = $custom['_secondary_html_1709'][0];
$start_pos = strpos($haystack,$start_limiter);
$end_pos = strpos($haystack,$end_limiter,$start_pos);
$galldata = substr($haystack, $start_pos+1, ($end_pos-1)-$start_pos);
$ng = explode('ids="',$galldata);
?>
<section id="content-3"  <?php if($custom['Block3BgColour'][0] != ''): echo' style="background-color:'.$custom['Block3BgColour'][0].'"'; endif;?><?php if(!empty($ng) && count($ng) > 1): echo 'class="full"'; endif;?>>

<?php 


if(!empty($ng) && count($ng) > 1):

$toget = array_reverse(explode(',',str_replace('"','',$ng[1])));

$args =  array (
		'include' => $toget,
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		'orderby' => 'post__in',
		
		
	);

$j = 0;
	$myposts = get_posts( $args );
?>
<div  id="sl3" class=" slideshow-wrap count<?php echo count($myposts);?>" data-count="<?php echo count($myposts);?>">
        <?php
		$controls = '';
	$total = count($myposts);
	$k = 1;
	foreach ( $myposts as $image ) : setup_postdata( $image ); $ic =get_post_custom($image->ID); $tc++;
	if($k == 1):
	
			$controls .= '<input type="radio" id="button-'.$i.'" class="button  bcr'.$k.'" name="controls3" checked="checked"/><label for="button-'.$i.'" class="buttonclick bc'.$i.' bck'.$k.'" rel="'.$i.'"></label><label for="button-'.$i.'" class="arrows" id="arrow-'.$i.'">></label>';
			else:
			$controls .= '<input type="radio" id="button-'.$i.'" class="button bcr'.$k.'" name="controls3"/><label for="button-'.$i.'" class="buttonclick bc'.$i.' bck'.$k.'" rel="'.$i.'"></label><label for="button-'.$i.'" class="arrows" id="arrow-'.$i.'">></label>';
			endif;
			$i++;
			$k++;
	endforeach;
	
	echo $controls;
	?>
        <div class="slideshow-inner" id="slideshow-inner3">
            <ul>
            <?php		
$i = $i - $total;
foreach ( $myposts as $image ) : setup_postdata( $image ); $ic =get_post_custom($image->ID); $tc++;
$img = wp_get_attachment_image_src( $image->ID, 'full' );		
			$j++;
			?>
            <li class="slide<?php echo $i;?>" <?php if($ic['_gallery_link_additional_css_classes'][0] != ''):?>style="background:<?php echo $ic['_gallery_link_additional_css_classes'][0];?>"<?php endif;?>>
                     <?php 
if ($ic['_gallery_link_url'][0] != ''): ?>
<a class="" href="<?php echo $ic['_gallery_link_url'][0];?>"><img src="<?php echo $img[0];?>" /></a>
<? else: ?>
<img src="<?php echo $img[0];?>" />
<?php endif;?>
                   
                </li>
               
            <?php 
			
			
		
       $i++;
		endforeach;wp_reset_query();
		

?>
                
               
            </ul>
          
        </div>
    </div>
<script type="text/javascript">
var play3 = 1;
var start3 = 1;
function autoplay3(){
if(play3 == 1){
start3++;
if(jQuery("#sl3 .bck"+start3).length){
jQuery("#sl3 input").attr('checked', '');
} else {start3=1;}
jQuery("#sl3 .bcr"+start3).attr('checked', 'checked');
//setInterval(function () {autoplay();}, 3000);	
}
}
var myVar3 = setInterval(function(){myTimer3()},4000);
function myTimer3() {
autoplay3();
}
jQuery("#sl3 label, #sl3 input").click(function(e) {
  clearInterval(myVar3);  
});
</script>  
<style type="text/css">
<?php

$x = count($myposts);
echo '
#slideshow-inner3>ul { width: '.($x*100).'%;}
#slideshow-inner3>ul>li {width: '.(100/$x).'%;}';
$t = $i-1;
$tw = $x * 100;
$w - $tw/$x;
$l = 0;
$m = -18* ceil(($total-1)/2);
for ($x = $i - $total; $x <= $t; $x++) {
echo '.slideshow-wrap input[type=radio]#button-'.$x.':checked~label[for=button-'.$x.'] { background-color: rgba(100,100,100,1) }
 .slideshow-wrap input[type=radio]#button-'.$x.':checked~#slideshow-inner3>ul { left: -'.($l*100).'% }
 .slideshow-wrap label[for=button-'.$x.'] { margin-left: '.$m.'px }

 ';
 $m=$m+18;
$l++;

} 
?>




</style>    
<?php else:?>
<div class="inner"><?php echo apply_filters('the_content',$custom['_secondary_html_1709'][0]);?></div>
<?php endif;?></section><?php endif;?>
<?php if($custom['_secondary_html_1710'][0] != ''):

$start_limiter = '[gallery';
$end_limiter = ']';
$haystack = $custom['_secondary_html_1710'][0];
$start_pos = strpos($haystack,$start_limiter);
$end_pos = strpos($haystack,$end_limiter,$start_pos);
$galldata = substr($haystack, $start_pos+1, ($end_pos-1)-$start_pos);
$ng = explode('ids="',$galldata);
?>
<section id="content-4"  <?php if($custom['Block4BgColour'][0] != ''): echo' style="background-color:'.$custom['Block4BgColour'][0].'"'; endif;?> <?php if(!empty($ng) && count($ng) > 1): echo 'class="full"'; endif;?>>

<?php 


if(!empty($ng) && count($ng) > 1):

$toget = array_reverse(explode(',',str_replace('"','',$ng[1])));

$args =  array (
		'include' => $toget,
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		'orderby' => 'post__in',
		
		
	);

$j = 0;
	$myposts = get_posts( $args );
?>
<div  id="sl4" class=" slideshow-wrap count<?php echo count($myposts);?>" data-count="<?php echo count($myposts);?>">
        <?php
		$controls = '';
	$total = count($myposts);
	$k = 1;
	foreach ( $myposts as $image ) : setup_postdata( $image ); $ic =get_post_custom($image->ID); $tc++;
	if($k == 1):
	
			$controls .= '<input type="radio" id="button-'.$i.'" class="button  bcr'.$k.'" name="controls4" checked="checked"/><label for="button-'.$i.'" class="buttonclick bc'.$i.' bck'.$k.'" rel="'.$i.'"></label><label for="button-'.$i.'" class="arrows" id="arrow-'.$i.'">></label>';
			else:
			$controls .= '<input type="radio" id="button-'.$i.'" class="button bcr'.$k.'" name="controls4"/><label for="button-'.$i.'" class="buttonclick bc'.$i.' bck'.$k.'" rel="'.$i.'"></label><label for="button-'.$i.'" class="arrows" id="arrow-'.$i.'">></label>';
			endif;
			$i++;
			$k++;
	endforeach;
	
	echo $controls;
	?>
        <div class="slideshow-inner" id="slideshow-inner4">
            <ul>
            <?php		
$i = $i - $total;
foreach ( $myposts as $image ) : setup_postdata( $image ); $ic =get_post_custom($image->ID); $tc++;
$img = wp_get_attachment_image_src( $image->ID, 'full' );		
			$j++;
			?>
            <li class="slide<?php echo $i;?>" <?php if($ic['_gallery_link_additional_css_classes'][0] != ''):?>style="background:<?php echo $ic['_gallery_link_additional_css_classes'][0];?>"<?php endif;?>>
                     <?php 
if ($ic['_gallery_link_url'][0] != ''): ?>
<a class="" href="<?php echo $ic['_gallery_link_url'][0];?>"><img src="<?php echo $img[0];?>" /></a>
<? else: ?>
<img src="<?php echo $img[0];?>" />
<?php endif;?>
                   
                </li>
               
            <?php 
			
			
		
       $i++;
		endforeach;wp_reset_query();
		

?>
                
               
            </ul>
          
        </div>
    </div>
 <script type="text/javascript">
var play4 = 1;
var start4 = 1;
function autoplay4(){
if(play4 == 1){
start4++;
if(jQuery("#sl4 .bck"+start4).length){
jQuery("#sl3 input").attr('checked', '');
} else {start4=1;}
jQuery("#sl4 .bcr"+start4).attr('checked', 'checked');
//setInterval(function () {autoplay();}, 4000);	
}
}
var myVar4 = setInterval(function(){myTimer4()},4000);
function myTimer4() {
autoplay4();
}
jQuery("#sl4 label, #sl4 input").click(function(e) {
  clearInterval(myVar4);  
});
</script>  
<style type="text/css">
<?php

$x = count($myposts);
echo '
#slideshow-inner4>ul { width: '.($x*100).'%;}
#slideshow-inner4>ul>li {width: '.(100/$x).'%;}';
$t = $i-1;
$tw = $x * 100;
$w - $tw/$x;
$l = 0;
$m = -18* ceil(($total-1)/2);
for ($x = $i - $total; $x <= $t; $x++) {
echo '.slideshow-wrap input[type=radio]#button-'.$x.':checked~label[for=button-'.$x.'] { background-color: rgba(100,100,100,1) }
 .slideshow-wrap input[type=radio]#button-'.$x.':checked~#slideshow-inner4>ul { left: -'.($l*100).'% }
 .slideshow-wrap label[for=button-'.$x.'] { margin-left: '.$m.'px }

 ';
 $m=$m+18;
$l++;

} 
?>




</style>    
<?php else:?>
<div class="inner"><?php echo apply_filters('the_content',$custom['_secondary_html_1710'][0]);?></div>
<?php endif;?></section><?php endif;?>
<?php if($custom['_secondary_html_1711'][0] != ''):
$start_limiter = '[gallery';
$end_limiter = ']';
$haystack = $custom['_secondary_html_1711'][0];
$start_pos = strpos($haystack,$start_limiter);
$end_pos = strpos($haystack,$end_limiter,$start_pos);
$galldata = substr($haystack, $start_pos+1, ($end_pos-1)-$start_pos);
$ng = explode('ids="',$galldata);
?>
<section id="content-5" <?php if($custom['Block5BgColour'][0] != ''): echo' style="background-color:'.$custom['Bloc5BgColour'][0].'"'; endif;?> <?php if(!empty($ng) && count($ng) > 1): echo 'class="full"'; endif;?>>

<?php 


if(!empty($ng) && count($ng) > 1):

$toget = array_reverse(explode(',',str_replace('"','',$ng[1])));

$args =  array (
		'include' => $toget,
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		'orderby' => 'post__in',
		
		
	);

$j = 0;
	$myposts = get_posts( $args );
?>
<div  id="sl5" class=" slideshow-wrap count<?php echo count($myposts);?>" data-count="<?php echo count($myposts);?>">
        <?php
		$controls = '';
	$total = count($myposts);
	
	$k = 1;
	foreach ( $myposts as $image ) : setup_postdata( $image ); $ic =get_post_custom($image->ID); $tc++;
	if($k == 1):
	
			$controls .= '<input type="radio" id="button-'.$i.'" class="button  bcr'.$k.'" name="controls5" checked="checked"/><label for="button-'.$i.'" class="buttonclick bc'.$i.' bck'.$k.'" rel="'.$i.'"></label><label for="button-'.$i.'" class="arrows" id="arrow-'.$i.'">></label>';
			else:
			$controls .= '<input type="radio" id="button-'.$i.'" class="button bcr'.$k.'" name="controls5"/><label for="button-'.$i.'" class="buttonclick bc'.$i.' bck'.$k.'" rel="'.$i.'"></label><label for="button-'.$i.'" class="arrows" id="arrow-'.$i.'">></label>';
			endif;
			$i++;
			$k++;
	endforeach;
	
	echo $controls;
	?>
        <div class="slideshow-inner" id="slideshow-inner5">
            <ul>
            <?php		
$i = $i - $total;
foreach ( $myposts as $image ) : setup_postdata( $image ); $ic =get_post_custom($image->ID); $tc++;
$img = wp_get_attachment_image_src( $image->ID, 'full' );		
			$j++;
			?>
            <li class="slide<?php echo $i;?>" <?php if($ic['_gallery_link_additional_css_classes'][0] != ''):?>style="background:<?php echo $ic['_gallery_link_additional_css_classes'][0];?>"<?php endif;?>>
                     <?php 
if ($ic['_gallery_link_url'][0] != ''): ?>
<a class="" href="<?php echo $ic['_gallery_link_url'][0];?>"><img src="<?php echo $img[0];?>" /></a>
<? else: ?>
<img src="<?php echo $img[0];?>" />
<?php endif;?>
                   
                </li>
               
            <?php 
			
			
		
       $i++;
		endforeach;wp_reset_query();
		

?>
                
               
            </ul>
          
        </div>
    </div>
<script type="text/javascript">
var play5 = 1;
var start5 = 1;
function autoplay5(){
if(play5 == 1){
start5++;
if(jQuery("#sl5 .bck"+start5).length){
jQuery("#sl5 input").attr('checked', '');
} else {start5=1;}
jQuery("#sl5 .bcr"+start5).attr('checked', 'checked');
//setInterval(function () {autoplay();}, 5000);	
}
}
var myVar5 = setInterval(function(){myTimer5()},5000);
function myTimer5() {
autoplay5();
}
jQuery("#sl5 label, #sl5 input").click(function(e) {
  clearInterval(myVar5);  
});
</script> 
<style type="text/css">
<?php

$x = count($myposts);
echo '
#slideshow-inner5>ul { width: '.($x*100).'%;}
#slideshow-inner5>ul>li {width: '.(100/$x).'%;}';
$t = $i-1;
$tw = $x * 100;
$w - $tw/$x;
$l = 0;
$m = -18* ceil(($total-1)/2);
for ($x = $i - $total; $x <= $t; $x++) {
echo '.slideshow-wrap input[type=radio]#button-'.$x.':checked~label[for=button-'.$x.'] { background-color: rgba(100,100,100,1) }
 .slideshow-wrap input[type=radio]#button-'.$x.':checked~#slideshow-inner5>ul { left: -'.($l*100).'% }
 .slideshow-wrap label[for=button-'.$x.'] { margin-left: '.$m.'px }

 ';
 $m=$m+18;
$l++;

} 
?>




</style>    
<?php else:?>
<div class="inner"><?php echo apply_filters('the_content',$custom['_secondary_html_1711'][0]);?></div>
<?php endif;?></section><?php endif;?>
<div class="inner" style="text-align:center">
<?php if (get_post_type( get_the_ID() ) == 'post' || get_post_type( get_the_ID() ) == 'events'):?>
          
           
            <div id="rate" style="width:320px; display:block; margin-left:auto; margin-right:auto">
            <h2>Rate this article</h2>
             <?php  
			global $post;
			$count = get_post_meta( $post->ID, 'postview', true );
			$count++;
			 update_post_meta( $post->ID, 'postview', $count );
			 if($count >1):
			 echo '<p>'.$count.' people have read this article.</p>';
			 else:
			 echo '<p>'.$count.' person has read this article.</p>';
			endif;
		
			?>
            <?php echo do_shortcode('[rate]');?>
            
            </div>
<?php endif;?>
<div class="navigation"><p> <?php previous_post('%','&laquo; Previous', 'no'); ?> <?php next_post('%','Next &raquo;', 'no'); ?> </p></div></div>


<?php endwhile; // end of the loop. ?>
<?php get_footer(); ?>
