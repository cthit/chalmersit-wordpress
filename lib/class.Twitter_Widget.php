<?php

class Twitter_Widget extends WP_widget {
	
	function __construct() {
		$widget_ops = array( 'classname' => 'it_twitter', 'description' => __("Visar en användares twitterfeed") );

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
		
		parent::__construct( 'it_twitter_widget', __("Twitterwidget"), $widget_ops);
	}

	function flush_widget_cache() {
		wp_cache_delete('it_twitter_widget', 'widget');
	}

	function widget( $args, $instance ) {
		extract( $args );
		
		$cache = wp_cache_get('it_twitter_widget', 'widget');
		
		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		$niceUser = apply_filters('widget_title', $instance['title']);
		$user = strtolower($niceUser);
		$type = $instance['type'];
		$count = $instance['count'];

		ob_start();

		echo $before_widget;
		?>

		<header class="panel-header">
			<h1>Twitter</h1>
			<a class="header-more" href="http://twitter.com/<?php echo $user; ?>">@<?php echo $niceUser; ?></a>
		</header>
		<meta name="twitter-type" content="<?php echo $type ?>" />
		<meta name="twitter-content" content="<?php echo $user; ?>" />
		<meta name="twitter-count" content="<?php echo $count; ?>" />

		<div id="tweet-list">
		</div>

		<?php

		echo $after_widget;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('it_sponsor_widget', $cache, 'widget');

	}

	function update($new_instance, $old_instance) {
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
		if ( isset($alloptions['it_twitter_widget']) )
			delete_option('it_twitter_widget');
		
		return $instance;
	}

	function form( $instance ) {

		$defaults = array(
			"title" => "",
			"type" => "user",
			"count" => "5"
		);

		$args = array(
			"hide_empty" => false 
		);
		$instance = wp_parse_args( (array) $instance, $defaults); 
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title');?>"><?php _e("Användare"); ?>:</label>
			<input id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('count');?>"><?php _e("Antal tweets"); ?>:</label>
			<input id="<?php echo $this->get_field_id('count');?>" name="<?php echo $this->get_field_name('count');?>" type="number" min="0" value="<?php echo $instance['count']; ?>" />
		</p>
		<input id="<?php echo $this->get_field_id('type');?>" type="hidden" name="<?php echo $this->get_field_name('type');?>" value="user" />

		<?php
	}
}
?>