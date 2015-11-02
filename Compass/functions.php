<?php
 function init_sessions() {
	
    if (!session_id()) {
        session_start();
    }
	
 }
	add_action('init', 'init_sessions');

function twentytwelve_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'twentytwelve' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'twentytwelve_wp_title', 10, 2 );

/**
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since Compass 1.0
 */
function twentytwelve_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentytwelve_page_menu_args' );


add_action('admin_menu', 'mt_add_pages');


add_theme_support( 'menus' );

add_theme_support( 'post-thumbnails' );

add_action('init', 'competency_register');
 
function competency_register() {
 
	$labels = array(
		'name' => _x('Competency', 'post type general name'),
		'singular_name' => _x('competency Item', 'post type singular name'),
		'add_new' => _x('Add New', 'competency item'),
		'add_new_item' => __('Add New competency Item'),
		'edit_item' => __('Edit competency Item'),
		'new_item' => __('New competency Item'),
		'view_item' => __('View competency Item'),
		'search_items' => __('Search competency'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
 
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		
		'rewrite' => true,

		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','thumbnail', 'page-attributes')
	  ); 
 
	register_post_type( 'competency' , $args );
}
add_action('init', 'role_register');
 
function role_register() {
 
	$labels = array(
		'name' => _x('Role', 'post type general name'),
		'singular_name' => _x('role Item', 'post type singular name'),
		'add_new' => _x('Add New', 'role item'),
		'add_new_item' => __('Add New role Item'),
		'edit_item' => __('Edit role Item'),
		'new_item' => __('New role Item'),
		'view_item' => __('View role Item'),
		'search_items' => __('Search role'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
 
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','thumbnail', 'page-attributes')
	  ); 
 
	register_post_type( 'role' , $args );
}
 add_action('init', 'group_register');
 
function group_register() {
 
	$labels = array(
		'name' => _x('Group', 'post type general name'),
		'singular_name' => _x('group Item', 'post type singular name'),
		'add_new' => _x('Add New', 'group item'),
		'add_new_item' => __('Add New group Item'),
		'edit_item' => __('Edit group Item'),
		'new_item' => __('New group Item'),
		'view_item' => __('View group Item'),
		'search_items' => __('Search group'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
 
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','thumbnail', 'page-attributes')
	  ); 
 
	register_post_type( 'group' , $args );
}
add_action("admin_init", "admin_initB");
 
function admin_initB(){

add_meta_box("Details", "Details", "Details", "product", "normal", "low");

}


function Details(){

	echo '<style type="text/css">
.wp-media-buttons {margin-left:100px}
</style>';
	 global $post;
  $custom = get_post_custom($post->ID);
  
  if (get_post_type() == 'totalcare'):
  $fields = array('Type','Years','Code','From Price','To Price','Cost');
foreach($fields as $fi):
if ($fi == 'Type'):
$options = array('iPad','Mac');

 echo '<label class="adminlabel">'.$fi.':</label><br /><select name="'.str_replace(' ','-',$fi).'" class="admininput"><option value="">Select</option>';
 foreach ($options as $op):
  echo '<option value="'.$op.'"';
  if ($custom[str_replace(' ','-',$fi)][0] == $op): echo ' selected="selected"'; endif;
  echo '>'.$op.'</option>';
 endforeach;
 echo ' </select> <br />';
  echo '<br />';
  else:
  echo '<label class="adminlabel">'.$fi.':</label><br /><input type="text" name="'.str_replace(' ','-',$fi).'" value="'.$custom[str_replace(' ','-',$fi)][0].'" /><br /><br />';
  
endif;
endforeach;
  endif;
  
 
  }
 

function save_details(){

  global $post;

  $post_id = $post->ID;

  // to prevent metadata or custom fields from disappearing...
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
    return $post_id;
	
	
	if (get_post_type() == 'page'):
$fields = array('Main Menu');
foreach($fields as $f):
update_post_meta($post_id,str_replace(' ','-',$f),$_POST[str_replace(' ','-',$f)]);	
endforeach;
endif;	

 }
 
 add_action('save_post', 'save_details');


add_filter('wp_mail_from','yoursite_wp_mail_from');
function yoursite_wp_mail_from($content_type) {
  
  return 'noreply@compass.co.uk';
 
}
add_filter('wp_mail_from_name','yoursite_wp_mail_from_name');
function yoursite_wp_mail_from_name($name) {
  return 'Compass';
}

add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));

function prevent_admin_access() {
	
	global $current_user;
      get_currentuserinfo();

	global $user_level;
	
      $current_user = wp_get_current_user();

      

    if (strpos(strtolower($_SERVER['REQUEST_URI']), 'wp-admin') !== false) {
	if (!is_super_admin()){

        wp_redirect(get_bloginfo('url'));
		}
    }
}

add_action('init', 'prevent_admin_access', 0);


show_admin_bar(!is_admin() && current_user_can('edit_posts'));
global $current_user;
      get_currentuserinfo();

	global $user_level;
	
      $current_user = wp_get_current_user();
if ($current_user->ID != 1):
  show_admin_bar(false);
endif;

add_action('admin_menu', 'mt_add_pages');

// action function for above hook
function mt_add_pages() {

// Add a new top-level menu (ill-advised):

add_menu_page('CreateClient', 'Create Client', 'delete_posts', 'CreateClient', 'CreateClient', '', '1' );

}

function CreateClient() { 
global $wpdb, $base;
global $current_site;
$fields = array('contact','tel','email');

require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-admin/admin.php' );
if ( isset($_REQUEST['action']) && 'add-site' == $_REQUEST['action'] ) {
	#check_admin_referer( 'add-blog', '_wpnonce_add-blog' );

	if ( ! is_multisite() )
	wp_die( __( 'Multisite support is not enabled.' ) );

if ( ! current_user_can( 'manage_sites' ) )
	wp_die( __( 'You do not have sufficient permissions to add sites to this network.' ) );

	get_current_screen()->add_help_tab( array(
		'id'      => 'overview',
		'title'   => __('Overview'),
		'content' =>
			'<p>' . __('This screen is for Super Admins to add new sites to the network. This is not affected by the registration settings.') . '</p>' .
			'<p>' . __('If the admin email for the new site does not exist in the database, a new user will also be created.') . '</p>'
) );

get_current_screen()->set_help_sidebar(
	'<p><strong>' . __('For more information:') . '</strong></p>' .
	'<p>' . __('<a href="http://codex.wordpress.org/Network_Admin_Sites_Screen" target="_blank">Documentation on Site Management</a>') . '</p>' .
	'<p>' . __('<a href="https://wordpress.org/support/forum/multisite/" target="_blank">Support Forums</a>') . '</p>'
);

if ( isset($_REQUEST['action']) && 'add-site' == $_REQUEST['action'] ) {
	check_admin_referer( 'add-blog', '_wpnonce_add-blog' );

	if ( ! is_array( $_POST['blog'] ) )
		wp_die( __( 'Can&#8217;t create an empty site.' ) );

	$blog = $_POST['blog'];
	$domain = '';
	if ( preg_match( '|^([a-zA-Z0-9-])+$|', $blog['domain'] ) )
		$domain = strtolower( $blog['domain'] );

	// If not a subdomain install, make sure the domain isn't a reserved word
	if ( ! is_subdomain_install() ) {
		/** This filter is documented in wp-includes/ms-functions.php */
		$subdirectory_reserved_names = apply_filters( 'subdirectory_reserved_names', array( 'page', 'comments', 'blog', 'files', 'feed' ) );
		if ( in_array( $domain, $subdirectory_reserved_names ) )
			wp_die( sprintf( __('The following words are reserved for use by WordPress functions and cannot be used as blog names: <code>%s</code>' ), implode( '</code>, <code>', $subdirectory_reserved_names ) ) );
	}

	$title = $blog['title'];

	if ( empty( $domain ) )
		wp_die( __( 'Missing or invalid site address.' ) );

	if ( isset( $blog['email'] ) && '' === trim( $blog['email'] ) ) {
		wp_die( __( 'Missing email address.' ) );
	}

	$email = sanitize_email( $blog['email'] );
	if ( ! is_email( $email ) ) {
		wp_die( __( 'Invalid email address.' ) );
	}

	if ( is_subdomain_install() ) {
		$newdomain = $domain . '.' . preg_replace( '|^www\.|', '', $current_site->domain );
		$path      = $current_site->path;
	} else {
		$newdomain = $current_site->domain;
		$path      = $current_site->path . $domain . '/';
	}

	$password = 'N/A';
	$user_id = email_exists($email);
	if ( !$user_id ) { // Create a new user with a random password
		$password = wp_generate_password( 12, false );
		$user_id = wpmu_create_user( $domain, $password, $email );
		update_user_meta( $user_id, 'AccessLevel', 'ADMIN' );
		if ( false == $user_id )
			wp_die( __( 'There was an error creating the user.' ) );
		else
			wp_new_user_notification( $user_id, $password );
	}

	$wpdb->hide_errors();
	$id = wpmu_create_blog( $newdomain, $path, $title, $user_id , array( 'public' => 1 ), $current_site->id );
	$wpdb->show_errors();
	if ( !is_wp_error( $id ) ) {
		if ( !is_super_admin( $user_id ) && !get_user_option( 'primary_blog', $user_id ) )
			update_user_option( $user_id, 'primary_blog', $id, true );
			
		$content_mail = sprintf( __( 'New site created by %1$s

Address: %2$s
Name: %3$s' ), $current_user->user_login , get_site_url( $id ), wp_unslash( $title ) );
		wp_mail( get_site_option('admin_email'), sprintf( __( '[%s] New Site Created' ), $current_site->site_name ), $content_mail, 'From: "Site Admin" <' . get_site_option( 'admin_email' ) . '>' );
		wpmu_welcome_notification( $id, $user_id, $password, $title, array( 'public' => 1 ) );
		#wp_redirect( add_query_arg( array( 'update' => 'added', 'id' => $id ), 'site-new.php' ) );
		#exit;
	} else {
		wp_die( $id->get_error_message() );
	}


$pages = array('Welcome','Competencies','Add Competency','Edit Competency','View Competency','Staff','Add Staff','Edit Staff','View Staff','Roles','Add Role','Edit Role','View Role','Special Group','Add Special Group','Edit Special Group','View Special Group','Succession Plan','Search','Reporting','My Account','Lost Password');
$i = 3;
foreach($pages as $page):
if($page=='Add Competency' || $page=='Edit Competency' || $page=='View Competency'):$parent=4; 
elseif($page=='Add Staff' || $page=='Edit Staff' || $page=='View Staff'):$parent=8; 
elseif($page=='Add Role' || $page=='Edit Role' || $page=='View Role'):$parent=12; 
elseif($page=='Add Special Group' || $page=='Edit Special Group' || $page=='View Special Group'):$parent=16; 
else:
$parent = 0;
endif;

$dbase = 'wp_'.$id.'_';
$second_post_guid = get_option('home') . '/?page_id='.$i;
$wpdb->insert( $dbase.'posts', array(
'post_author' => $user_id,
'post_parent' => $parent,
'post_date' => $now,
'post_date_gmt' => $now_gmt,
'post_content' => '',
'post_excerpt' => '',
'post_title' => $page,
'post_name' => _x(strtolower(str_replace(' ','_',$page)), 'Default page slug'),
'post_modified' => $now,
'post_modified_gmt' => $now_gmt,
'guid' => $second_post_guid,
'post_type' => 'page',
'to_ping' => '',
'pinged' => '',
'post_content_filtered' => '',
'menu_order' => ($i-2)
));
$i++;
$wpdb->insert( $dbase.'postmeta', array( 'post_id' => $i, 'meta_key' => 'menu_order', 'meta_value' => ($i-2)) );

endforeach;
// THEN NEED TO INSERT A FEW GLOBAL OPTIONS
		
		foreach($fields as $fi):
		$wpdb->insert( $dbase.'options', array( 'option_name' => $fi, 'option_value' => maybe_serialize($_POST[$fi]), 'autoload' => 'yes' ) );
		endforeach;
		$wpdb->insert( $dbase.'options', array( 'option_name' => 'active', 'option_value' => 1, 'autoload' => 'yes' ) );
		$wpdb->query(
	"
	UPDATE ".$dbase."options 
	SET option_value  = 'compass'
	WHERE option_name = 'template'
	
	"
);
	$wpdb->query(
	"
	UPDATE ".$dbase."options 
	SET option_value  = '3'
	WHERE option_name = 'page_on_front'
	
	
	"
);
$wpdb->query(
	"
	UPDATE ".$dbase."options 
	SET option_value  = ''
	WHERE option_name = 'blogdescription'
	
	
	"
);

$wpdb->query(
	"
	UPDATE ".$dbase."options 
	SET option_value  = 'Compass'
	WHERE option_name = 'stylesheet'
	
	
	"
);
$wpdb->query(
	"
	UPDATE ".$dbase."options 
	SET option_value  = 'Compass'
	WHERE option_name = 'current_theme'
	
	
	"
);
$wpdb->query(
	"
	UPDATE ".$dbase."options 
	SET option_value  = 'page'
	WHERE option_name = 'show_on_front'

	"
);


		echo '<h2>Site Created!</h2>';
		echo '<p><a href="http://compass-app.co/'.$_POST['blog']['domain'].'/">Visit new site</a><br >';
		echo 'Site url is: http://compass-app.co/'.$_POST['blog']['domain'].'/</p>';
			
		exit;
	} else {
		wp_die( $id->get_error_message() );
	}
}
?>
<form method="post" action="/wp-admin/admin.php?page=CreateClient&action=add-site">
<?php wp_nonce_field( 'add-blog', '_wpnonce_add-blog' ) ?>
	<table class="form-table">
		<tr class="form-field form-required">
			<th scope="row"><?php _e( 'Site Address' ) ?></th>
			<td>
			<?php if ( is_subdomain_install() ) { ?>
				<input name="blog[domain]" type="text" class="regular-text" title="<?php esc_attr_e( 'Domain' ) ?>"/><span class="no-break">.<?php echo preg_replace( '|^www\.|', '', $current_site->domain ); ?></span>
			<?php } else {
				echo $current_site->domain . $current_site->path ?><input name="blog[domain]" class="regular-text" type="text" title="<?php esc_attr_e( 'Domain' ) ?>"/>
			<?php }
			echo '<p>' . __( 'Only the characters a-z and 0-9 recommended.' ) . '</p>';
			?>
			</td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><?php _e( 'Site Title' ) ?></th>
			<td><input name="blog[title]" type="text" class="regular-text" title="<?php esc_attr_e( 'Title' ) ?>"/></td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row"><?php _e( 'Admin Email' ) ?> </th>
			<td><input name="blog[email]" type="text" class="regular-text" title="<?php esc_attr_e( 'Email' ) ?>"/> <p>(email of clients admin e.g. troy@triggersolutions.co.uk) - This will add this person as a new admin user so they can process orders.</p></td>
		</tr>
		<?php 
	foreach($fields as $fi):
	
	
	echo '
	<tr class="form-field form-required">';
	
	echo '<th scope="row">'.ucfirst(str_replace('_',' ',$fi)).'</th>';
	
				
	echo '<td>';
	
	echo '<input name="'.$fi.'" type="text" class="regular-text" value=""/>';
	
	echo'</td>
		</tr>';
		
	endforeach;
	?>
		<tr class="form-field">
			<td colspan="2"><?php _e( 'A new user will be created if the above email address is not in the database.' ) ?><br /><?php _e( 'The username and password will be mailed to this email address.' ) ?></td>
		</tr>
	</table>
	
	<?php submit_button( __('Add Site'), 'primary', 'add-site' ); ?>
	</form>
    <?php

}

add_action( 'wp_login_failed', 'pu_login_failed' ); // hook failed login

function pu_login_failed( $user ) {
  	// check what page the login attempt is coming from
  	$referrer = $_SERVER['HTTP_REFERER'];

  	// check that were not on the default login page
	if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') && $user!=null ) {
		// make sure we don't already have a failed login attempt
		if ( !strstr($referrer, '?login=failed' )) {
			// Redirect to the login page and append a querystring of login failed
	    	wp_redirect( $referrer . '?login=failed');
	    } else {
	      	wp_redirect( $referrer );
	    }

	    exit;
	}
}
add_action( 'authenticate', 'pu_blank_login');

function pu_blank_login( $user ){
  	// check what page the login attempt is coming from
  	$referrer = $_SERVER['HTTP_REFERER'];

  	$error = false;

  	if($_POST['log'] == '' || $_POST['pwd'] == '')
  	{
  		$error = true;
  	}

  	// check that were not on the default login page
  	if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') && $error ) {

  		// make sure we don't already have a failed login attempt
    	if ( !strstr($referrer, '?login=failed') ) {
    		// Redirect to the login page and append a querystring of login failed
        	wp_redirect( $referrer . '?login=failed' );
      	} else {
        	wp_redirect( $referrer );
      	}

    exit;

  	}
}
function insert_logo($file_handler,$post_id,$setthumb='false') {

  // check to make sure its a successful upload
  if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

  require_once(ABSPATH . "wp-admin" . '/includes/image.php');
  require_once(ABSPATH . "wp-admin" . '/includes/file.php');
  require_once(ABSPATH . "wp-admin" . '/includes/media.php');

  $attach_id = media_handle_upload( $file_handler, $post_id );

  if ($setthumb) update_post_meta($post_id,'_thumbnail_id',$attach_id);
  return $attach_id;
}
?>