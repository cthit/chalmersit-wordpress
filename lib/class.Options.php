<?php
/**
 * Create and manage options pages for Wordpress
 *
 * Support input types: text, textarea, checkbox, radio, select
 *
 * @author Alison (http://alisothegeek.com/)
 *			Modified by Johan Brook (http://johanbrook.com)
 * @link http://alisothegeek.com/2011/01/wordpress-settings-api-tutorial-1/
 * @version: 1.0
 *
 * @license GNU General Public License
 */


class Theme_Options{

	/* Array of sections for the theme options page */
	private $sections = array();
	private $settings;
	private $prefix;
	private $admin_page;	// Placeholder for the admin page

	/* Initialize */
	function __construct($name = "mytheme", $sections = array()) {
		// This will keep track of the checkbox options for the validate_settings function.
		$this->checkboxes = array();
		$this->settings = array();
		$this->prefix = $name;
		#$this->get_settings();

		if(!empty($sections)){
			// Loop through sections and add them
			foreach($sections as $section){
				$this->add_section($section['handle'], $section['title']);
			}
		}else{
			// Add a default 'general' section:
			$this->add_section("general",  __("General settings"));
		}

		add_action( 'admin_menu', array( &$this, 'add_pages' ) );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );

		if ( ! get_option( $this->prefix.'_options' ) )
			$this->initialize_settings();
	}


	/* Initialize settings to their default values */
	public function initialize_settings() {
		$default_settings = array();
		foreach ( $this->settings as $id => $setting ) {
			if ( $setting['type'] != 'heading' )
				$default_settings[$id] = $setting['std'];
		}

		update_option( $this->prefix.'_options', $default_settings );

	}


	/* Register settings via the WP Settings API */
	public function register_settings() {

		register_setting( $this->prefix.'_options', $this->prefix.'_options', array ( &$this, 'validate_settings' ) );

		foreach ( $this->sections as $slug => $title )
			add_settings_section( $slug, $title, array( &$this, 'display_section' ), $this->prefix.'-options' );

		#$this->get_settings();

		foreach ( $this->settings as $id => $setting ) {
			$setting['id'] = $id;
			$this->create_setting( $setting );
		}
	}


	/* Add page(s) to the admin menu */
	public function add_pages() {
		$this->admin_page = add_submenu_page(
			'options-general.php',			// The parent menu
			'Temainställningar', 			// Page title
			'Informationsteknik',			// Heading
			'manage_options', 				// Capability level
			$this->prefix.'-options', 		// Unique identifier
			array( &$this, 'display_page' ) // Callback
		);

	}




	/* Create settings field */
	public function create_setting( $args = array() ) {

		$defaults = array(
			'id'      => 'default_field',
			'title'   => 'Default Field',
			'desc'    => 'This is a default description.',
			'std'     => '',
			'type'    => 'text',
			'min'	  => "0",
			'step'    => "1",
			'section' => 'general',
			'choices' => array(),
			'class'   => ''
		);

		extract( wp_parse_args( $args, $defaults ) );

		$field_args = array(
			'type'      => $type,
			'id'        => $id,
			'desc'      => $desc,
			'std'       => $std,
			'min'		=> $min,
			'step'		=> $step,
			'choices'   => $choices,
			'label_for' => $id,
			'class'     => $class
		);
    if (isset($multiple)) {
      $field_args['multiple'] = $multiple;
    }

		if ( $type == 'checkbox' )
			$this->checkboxes[] = $id;

		add_settings_field( $id, $title, array( $this, 'display_setting' ), $this->prefix.'-options', $section, $field_args );

	}



	/* HTML to display the theme options page */
	public function display_page() {
		?>

		<div class="wrap">
			<div class="icon32" id="icon-options-chalmersit" style="background-image:url('<?php echo ASSET_PATH;?>/images/logo_notext.png')">
			</div>
			<h2><?php _e("Temainställningar för Chalmers.it");?></h2>

			<form action="options.php" method="POST">
				<?php settings_fields($this->prefix."_options");?>

				<?php if(count($this->sections) > 3 ) : ?>
				<div class="ui-tabs">
					<ul class="ui-tabs-nav subsubsub">

					<?php $count = 0; ?>
				<?php foreach ( $this->sections as $section_slug => $section ):?>
						<li><a href="#<?php echo $section_slug;?>"><?php echo $section;?></a> <?php if(!$count == count($this->sections)) echo "|"; ?></li>
				<?php $count++; endforeach;?>

					</ul>
				</div>
				<?php endif;?>

				<?php do_settings_sections( $_GET['page'] ); ?>


				<p class="submit">
					<input name="Submit" type="submit" class="button-primary" value="<?php _e("Spara");?>" />
				</p>
			</form>
		</div>
		<?php
	}




	/* HTML output for individual settings */
	public function display_setting( $args = array() ) {
		extract( $args );

		$options = get_option( $this->prefix.'_options' );

		if ( ! isset( $options[$id] ) && 'type' != 'checkbox' )
			$options[$id] = $std;
		elseif ( ! isset( $options[$id] ) )
			$options[$id] = 0;

		$field_class = "";
		if ( $class != '' )
			$field_class = " " .$class;

		switch ( $type ) {
			case 'heading':
				echo '</td></tr><tr valign="top"><td colspan="2"><h4>' . $desc . '</h4>';
			break;


			case 'checkbox':

				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="'.$this->prefix.'_options[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> <label for="' . $id . '">' . $desc . '</label>';

				break;


			case 'select':
				$multi = ($multiple) ? "multiple" : "";
				$expect_array = ($multiple) ? "[]" : "";

				echo '<select class="select' . $field_class . '" name="'.$this->prefix.'_options[' . $id . ']'.$expect_array.'" '.$multi.'>';

				foreach ( $choices as $value => $label ) {
					$selected = selected( $options[$id], $value, false );
					if($multiple) {
						$selected = selected(true, in_array($value, $options[$id]), false);
					}

					echo '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $label . '</option>';
				}
				echo '</select>';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;


			case 'radio':
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="'.$this->prefix.'_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;

			case 'textarea':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="'.$this->prefix.'_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;


			case 'password':

				echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="'.$this->prefix.'_options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

			break;





			case 'text':
			case 'number':
			default:
			$additional_attr = "";
			if($type == "number"){
				$additional_attr = 'min="'. $min .'" max="20" step="'. $step .'"';
			}

			echo '<input class="regular-text' . $field_class . '" type="'. $type .'" id="' . $id . '" name="'.$this->prefix.'_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '"'. $additional_attr .' />';

			if ( $desc != '' )
				echo '<br /><span class="description">' . $desc . '</span>';
			break;
		}


	}


	/**
	*	Add a setting.
	*
	*	@param String $settings_id. The id of your setting. This is the one your refer to from the template files ('pf_option("my_option")')
	*	@param Array $setting_fields. All the fields in an array:
	*
	*		'title'   => 'The label of the field',
	*		'desc'    => 'Description (optional)',
	*		'std'     => 'Standard value (optional)',
	*		'type'    => 'The type of form input: input, textarea, password, select, radio, checkbox, or heading',
	*		'section' => 'The section this field belongs to',
	*		'choices' => '(array). For select and radio types',
	*		'class'   => 'A classname (optional)'
	*/
	public function add_setting($setting_id, $setting_fields = array()) {
		$this->settings[$setting_id] = $setting_fields;
	}



	/**
	*	Add a section.
	*
	*	@param String $section_id. The handle for the section (ex. "general").
	*	@param String $section_name. The actual name/title of the section (ex. __("General settings") )
	*/
	public function add_section($section_id, $section_name){
		$this->sections[$section_id] = $section_name;
	}



	public function add_contextual_help(){
		add_filter("contextual_help", array(&$this, "_context_help_handler"), 10, 3);
	}

	public function _context_help_handler($html, $screen_id, $screen){
		if($screen_id == $this->admin_page){
 			return "Temainställningar för Projektforum. Beskrivning finns under formulären. Tryck <strong>Spara</strong> för att spara dina ändringar.";
		}

		return false;
	}









	/* Description for section */
	public function display_section() {
		// code
	}




	public function validate_settings( $input ) {

		if ( ! isset( $input['reset_theme'] ) ) {
			$options = get_option( $this->prefix.'_options' );

			foreach ( $this->checkboxes as $id ) {
				if ( isset( $options[$id] ) && ! isset( $input[$id] ) )
					unset( $options[$id] );
			}

			return $input;
		}
		return false;

	}


}

?>
