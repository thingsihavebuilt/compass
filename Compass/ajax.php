<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
if($_POST['sid']):
$args = array(
   
   'post_type' => 'stores',
   'orderby' => 'title',
   'order' => 'ASC',
   
   'numberposts' => 100,
  
);
if($_POST['sid']):
$args['include'] = $_POST['sid'];
endif;
$myposts = get_posts( $args );

foreach( $myposts as $store ) :	
$opening = apply_filters('the_content',$store->post_excerpt);
echo '<h4>'.get_the_title($store->ID).'</h4>';
?>
<table width="100%" border="1" bordercolor="#666666" cellpadding="2">
<tr><td>ID</td><td>Date</td><td>Total</td><td>Customer</td><td>Products</td><td>Actions</td></tr>
<?php
$args = array(
	'post_type' => 'orders',
	'meta_query' => array(
		array(
			'key' => 'status',
			'value' => array('pending','confirmed'),
		),
		array(
			'key' => 'type',
			'value' => "clickandcollect",
		),
		array(
			'key' => 'store',
			'value' => get_the_title($store->ID),
		)
	)
 );
$myposts = get_posts( $args );
foreach ( $myposts as $post ) : setup_postdata( $post );$custom = get_post_custom($post->ID); 
$allprods = get_post_custom_values("allprods", $post->ID);
$allprods = unserialize($allprods[0]);
$userDetails = get_post_custom_values("userDetails", $post->ID);
$userDetails = unserialize($userDetails[0]);
$od = '';
$prods = '';
$ud = '';
$total = 0;
foreach ($allprods as $ap) :
$price = $ap['price']*$custom['vat'][0];
$title =  $ap['title'];
$sku =  $ap['sku'];
$quantity = $ap['quantity'];
$od .= $title.' x '.$quantity.' = &pound;'.number_format($price,2).'<br />';
$prods .= $title.' ('.$sku.') x '.$quantity.' = &pound;'.number_format($price,2).'<br />';
$total = $total+($price*$quantity);
endforeach;
$fields = array('First name','Surname','Address 1','Address 2','Town','Postcode');
foreach($fields as $fi):
$ud .= ''.stripslashes($userDetails[''.str_replace(' ','',$fi)]).'<br />';
endforeach;
?> 
<tr><td><?php echo $custom['orderID'][0];?></td><td><?php echo date('d/m/Y',strtotime($custom['date'][0]));?></td><td>&pound;<?php echo number_format($total,2);?></td><td><?php echo $ud;?></td><td><?php echo $prods;?></td><td>
<form action="" method="post"><?php if($custom['status'][0] == 'pending'):?>
<input type="submit" name="Confirm" value="Email customer to collect" /><br />
<input type="submit" name="Outofstock" value="Email customer out of stock" /><br />
<?php endif;?>
<input type="submit" name="Collected" value="Mark as collected" /><br />

<input type="submit" name="Cancel" value="Mark as cancelled" />
<input type="hidden" name="pid" value="<?php echo $post->ID;?>" />
<input type="hidden" name="od" value="<?php echo $od;?>" />
<input type="hidden" name="opening" value="<?php echo $opening;?>" />

<input type="hidden" name="store" value="<?php echo $custom['store'][0];?>" />
<input type="hidden" name="total" value="<?php echo number_format($total,2);?>" />
<input type="hidden" name="email" value="<?php echo $userDetails['Email'];?>" />
</form></td></tr>

<?php
endforeach;
?>
</table>
<?php
endforeach;

endif;
?>