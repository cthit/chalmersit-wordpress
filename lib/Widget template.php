<?php /* Widget template */

class X_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'it_X_widget', 
			'description' => __("En widget som är en mall"));

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );

		parent::__construct('it_X_widget', __("Template widget"), $widget_ops);
	}

	function flush_widget_cache() {
		wp_cache_delete('it_X_widget', 'widget');
	}

	function widget( $args, $instance) {
		extract($args);

		$cache = wp_cache_get('it_X_widget', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}
		/* Do pre-widget stuff here */

		/*
			Get parameters by
			$Variable = $instance['param_name'];
		*/

		$title = apply_filters('widget_title', $instance['title']);

		ob_start();

		echo $before_widget;

		if($title) {
			echo $before_title . $title . $after_title;
		}

		?>

		<div><p>Insert HTML output here</p></div>

		<?php
		echo $after_widget;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('it_X_widget', $cache, 'widget');

	}

	function update($new_instance, $old_instance) {
		// Does not need modification short from changing widget ID under alloptions
		$new_instance = (array) $new_instance;
		$instance = array();
		foreach ( $instance as $field => $val ) {
			if ( isset($new_instance[$field]) )
				$instance[$field] = 1;
		}
		foreach($new_instance as $field => $val) {
			$instance[$field] = $val;
		}
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['it_X_widget']) )
			delete_option('it_X_widget');
		
		return $instance;
	}

	function form($instance) {
		$defaults = array(
			"title" => ""
			// Define default key-value pairs
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		// Get any external data needed for the form
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e("Titel"); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>
		<p> Add other parameter fields </p>

		<?php
	}
}


?>