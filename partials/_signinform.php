<form name="loginform" action="/loggain?redirect_to=<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post">
	<input type="hidden" name="redirect_to" value="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" />
	<input type="hidden" name="user-cookie" value="1" />
	
	<p>
	<input type="text" name="log" placeholder="E-postadress eller nick"
		<?php if(isset($_POST['user_email'])) echo 'value="'. $_POST['user_email'] .'" autofocus' ?>
		 />

	<input type="password" name="pwd" placeholder="Lösenord" />
	</p>

	<p>
		<label><input type="checkbox" id="rememberme" name="rememberme" value="forever" checked="checked" />
		Håll mig inloggad</label>

		<input type="submit" name="submit" class="small" value="Logga in" /> 
	</p>
	
	
</form>