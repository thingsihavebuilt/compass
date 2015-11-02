<div class="items">
<?php
if(is_user_logged_in() && get_user_meta(get_current_user_id(),'AccessLevel',true) == 'Staff Member'):
?>
<div class="outer login">
<h2 class="head-title">View your succession plan now.</h2>

</div>
<?php
elseif(is_user_logged_in()):
?>

<div class="outer login">
<?php  if(get_user_meta(get_current_user_id(),'AccessLevel',true) == 'ADMIN' || get_user_meta(get_current_user_id(),'AccessLevel',true) == 'CEO' || is_super_admin()):?>
<h2 class="head-title">Enter your Competencies now.</h2>
<a href="add_competencies/" class="enterg">Enter competencies</a>
<?php endif;?>
<div class="outer">
<div class="col col3">
<a href="competencies" class="icon">
<img src="/wp-content/themes/Compass/img/icon_puzzle_alt.svg" alt="image">
<span>Competency</span>
</a>
</div>
<div class="col col3">
<a href="reporting/" class ="icon">
<img src="/wp-content/themes/Compass/img/icon_datareport_alt.svg" alt="image">
<span>Reports</span>
</a>
</div>
<div class="col col3">
<a href="staff/" class="icon">
<img src="/wp-content/themes/Compass/img/icon_id-2_alt.svg" alt="image">
<span>Staff & Roles</span>
</a>
</div>
</div>  
</div>

    
<?php
else:
?>
<header><h1>Welcome to <?php echo get_bloginfo('name');?></h1></header>
<p>Please login to access the site</p>
<?php
$args = array( 'redirect' => site_url() );

if(isset($_GET['login']) && $_GET['login'] == 'failed')
{
	?>
		<div id="login-error" style="background-color: #FFEBE8;border:1px solid #C00;padding:5px;">
			<p>Login failed: You have entered an incorrect Username or password, please try again.</p>
           
		</div>
	<?php
}

wp_login_form( $args );


endif;
?>
</div>
<?php
if(!is_user_logged_in()):

echo ' <p><a href="lost-password/">Forgotten your password?</a></p>';
endif;
?>