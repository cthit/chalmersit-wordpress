<form name="loginform" action="<?= wp_login_url(get_permalink());?>" method="post">
	<input type="hidden" name="user-cookie" value="1" />
	
	<p>
	<input type="text" name="log" placeholder="CID eller e-postadress"
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