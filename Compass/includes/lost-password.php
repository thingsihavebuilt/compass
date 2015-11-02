<?php
		global $wpdb;
		
		$error = '';
		$success = '';
		
		// check if we're in reset form
		if( isset( $_POST['action'] ) && 'reset' == $_POST['action'] ) 
		{
			$email = trim($_POST['user_login']);
			
			if( empty( $email ) ) {
				$error = 'Enter a username or e-mail address..';
			} else if( ! is_email( $email )) {
				$error = 'Invalid username or e-mail address.';
			} else if( ! email_exists( $email ) ) {
				$error = 'There is no user registered with that email address.';
			} else {
				
				$random_password = wp_generate_password( 12, false );
				$user = get_user_by( 'email', $email );
				
				$update_user = wp_update_user( array (
						'ID' => $user->ID, 
						'user_pass' => $random_password
					)
				);
				
				// if  update user return true then lets send user an email containing the new password
				if( $update_user ) {
					$to = $email;
					$subject = 'Your new password';
					$sender = get_option('name');
					
					$message = 'Your new password is: '.$random_password;
					
					$headers[] = 'MIME-Version: 1.0' . "\r\n";
					$headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers[] = "X-Mailer: PHP \r\n";
					$headers[] = 'From: '.$sender.' < '.$email.'>' . "\r\n";
					
					$mail = wp_mail( $to, $subject, $message, $headers );
					if( $mail )
						$success = 'Check your email address for you new password.';
						
				} else {
					$error = 'Oops something went wrong updaing your account.';
				}
				
			}
			
			if( ! empty( $error ) )
				$notification = '<p class="notification error"><strong>ERROR:</strong> '. $error .'</p>';
			
			if( ! empty( $success ) )
				$notification = '<p class="notification success">'. $success .'</p>';
		}
	?>
<header><h1>Last password</h1></header>
<?php echo $notification;?>
<div class="items">


	<!--html code-->
	<form method="post">
		<fieldset>
			<p>Please enter your username or email address and we will email you a new password.</p>
			<p><label for="user_login">Username or E-mail:</label>
				<?php $user_login = isset( $_POST['user_login'] ) ? $_POST['user_login'] : ''; ?>
				<input type="text" name="user_login" id="user_login" value="<?php echo $user_login; ?>" /></p>
			<p>
				<input type="hidden" name="action" value="reset" />
				<input type="submit" value="Get New Password" class="btn btn-primary btn-sm text-center btn-log mt-20 submit" id="submit" />
			</p>
		</fieldset> 
	</form>

</div>