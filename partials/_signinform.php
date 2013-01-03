<form name="loginform" action="<?php bloginfo("wpurl");?>/wp-login.php" method="post">
	<input type="hidden" name="redirect_to" value="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" />
	<input type="hidden" name="user-cookie" value="1" />
	
	<p>
	<input type="text" name="log" placeholder="E-postadress eller nick"
		<?php if(isset($_POST['user_email'])) echo 'value="'. $_POST['user_email'] .'" autofocus' ?>
		 />

	<input type="password" name="pwd" placeholder="Lösenord" />
	</p>

	<p>
		<input type="checkbox" id="rememberme" name="rememberme" value="forever" checked="checked" />
		<label for="rememberme">Håll mig inloggad</label>

		<input type="submit" name="submit" class="small" value="Logga in" /> 
	</p>
	
	<p class="extra-info">	
		<a href="<?php echo wp_lostpassword_url();?>">Glömt lösenord</a>
	</p>
	
</form>