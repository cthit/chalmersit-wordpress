<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/

$user_role = reset( $profileuser->roles );
if ( is_multisite() && empty( $user_role ) ) {
	$user_role = 'subscriber';
}

$user_can_edit = false;
foreach ( array( 'posts', 'pages' ) as $post_cap )
	$user_can_edit |= current_user_can( "edit_$post_cap" );
?>

<section class="profile">
	<?php $template->the_action_template_message( 'profile' ); ?>
	<?php $template->the_errors(); ?>

	<form id="your-profile" action="" method="post">
		<?php wp_nonce_field( 'update-user_' . $current_user->ID ) ?>
		<input type="hidden" name="from" value="profile" />
		<input type="hidden" name="checkuser_id" value="<?php echo $current_user->ID; ?>" />

		<?php if ( !$theme_my_login->options->get_option( array( 'themed_profiles', $user_role, 'restrict_admin' ) ) && !has_action( 'personal_options' ) ): ?>

		<!--
		<h2>Inställningar</h2>

		<table class="form-table">
		<?php #do_action( 'personal_options', $profileuser ); ?>
		</table>
		<?php endif; // restrict admin ?>
		-->

		<h2>Namn</h2>

		<table class="form-table">
		<tr>
			<th><label for="user_login">Login</label></th>
			<td><input type="text" name="user_login" id="user_login" value="<?php echo esc_attr( $profileuser->user_login ); ?>" disabled />
				<p class="description">Du kan inte ändra ditt loginnamn</p></td>
		</tr>

		<tr>
			<th><label for="first_name">Förnamn</label></th>
			<td><input type="text" name="first_name" id="first_name" value="<?php echo esc_attr( $profileuser->first_name ) ?>" class="regular-text" /></td>
		</tr>

		<tr>
			<th><label for="last_name">Efternamn</label></th>
			<td><input type="text" name="last_name" id="last_name" value="<?php echo esc_attr( $profileuser->last_name ) ?>" class="regular-text" /></td>
		</tr>

		<tr>
			<th><label for="nickname">Nick <small>(obligatoriskt)</small></label></th>
			<td><input type="text" name="nickname" id="nickname" value="<?php echo esc_attr( $profileuser->nickname ) ?>" class="regular-text" /></td>
		</tr>

		<tr>
			<th><label for="display_name">Visningsnamn</label></th>
			<td>
				<select name="display_name" id="display_name">
				<?php
					$public_display = array();
					$public_display['display_nickname']  = $profileuser->nickname;
					$public_display['display_username']  = $profileuser->user_login;
					if ( !empty( $profileuser->first_name ) )
						$public_display['display_firstname'] = $profileuser->first_name;
					if ( !empty( $profileuser->last_name ) )
						$public_display['display_lastname'] = $profileuser->last_name;
					if ( !empty( $profileuser->first_name ) && !empty( $profileuser->last_name ) ) {
						$public_display['display_firstlast'] = $profileuser->first_name . ' ' . $profileuser->last_name;
						$public_display['display_lastfirst'] = $profileuser->last_name . ' ' . $profileuser->first_name;
					}
					if ( !in_array( $profileuser->display_name, $public_display ) )// Only add this if it isn't duplicated elsewhere
						$public_display = array( 'display_displayname' => $profileuser->display_name ) + $public_display;
					$public_display = array_map( 'trim', $public_display );
					foreach ( $public_display as $id => $item ) {
						$selected = ( $profileuser->display_name == $item ) ? ' selected="selected"' : '';
				?>
						<option id="<?php echo $id; ?>" value="<?php echo esc_attr( $item ); ?>"<?php echo $selected; ?>><?php echo $item; ?></option>
				<?php } ?>
				</select>

				<p class="description">Det namn som visas publikt på webbplatsen</p>
			</td>
		</tr>
		</table>

		<h2>Kontakt</h2>

		<table class="form-table">
		<tr>
			<th><label for="email">E-mail <small>(obligatoriskt)</small></label></th>
			<td><input type="text" name="email" id="email" value="<?php echo esc_attr( $profileuser->user_email ) ?>" class="regular-text" /></td>
		</tr>

		<tr>
			<th><label for="url">Hemsida</label></th>
			<td><input type="text" name="url" id="url" value="<?php echo esc_attr( $profileuser->user_url ) ?>" class="regular-text code" /></td>
		</tr>
		</table>

		<h2>Övrigt</h2>

		<table class="form-table">
		<tr>
			<th><label for="description">Bio</label></th>
			<td><textarea name="description" id="description" rows="5" cols="30"><?php echo esc_html( $profileuser->description ); ?></textarea><br />
			<p class="description">Kort info om dig själv. <em>Kan</em> användas på olika delar på webbplatsen</p></td>
		</tr>

		<?php
		$show_password_fields = apply_filters( 'show_password_fields', true, $profileuser );
		if ( $show_password_fields ) :
		?>
		<tr id="password">
			<th><label for="pass1">Nytt lösenord</label></th>
			<td><input type="password" name="pass1" id="pass1" size="16" value="" autocomplete="off" /> 
				<p class="description">Lämna blankt om du inte vill byta lösenord nu</p>
				<input type="password" name="pass2" id="pass2" size="16" value="" autocomplete="off" placeholder="Bekräfta lösenord" /> 
				
				<p class="description indicator-hint"><strong>Tips:</strong> Lösenordet bör vara minst
					sju tecken långt, innehålla stora och små bokstäver, siffror, och specialtecken. 
					Helst även en smurf också.</p>

				<div id="pass-strength-result">Lösenordets styrka</div>
			</td>
		</tr>
		<?php endif; ?>
		</table>

		<?php
			do_action( 'show_user_profile', $profileuser );
		?>

		<?php if ( count( $profileuser->caps ) > count( $profileuser->roles ) && apply_filters( 'additional_capabilities_display', true, $profileuser ) ) { ?>
		<br class="clear" />
			<table width="99%" style="border: none;" cellspacing="2" cellpadding="3" class="editform">
				<tr>
					<th scope="row"><?php _e( 'Additional Capabilities', 'theme-my-login' ) ?></th>
					<td><?php
					$output = '';
					global $wp_roles;
					foreach ( $profileuser->caps as $cap => $value ) {
						if ( !$wp_roles->is_role( $cap ) ) {
							if ( $output != '' )
								$output .= ', ';
							$output .= $value ? $cap : "Denied: {$cap}";
						}
					}
					echo $output;
					?></td>
				</tr>
			</table>
		<?php } ?>

		<p class="submit">
			<input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr( $current_user->ID ); ?>" />
			<input type="submit" class="large" value="Uppdatera profil" name="submit" />
		</p>
	</form>
</section>
