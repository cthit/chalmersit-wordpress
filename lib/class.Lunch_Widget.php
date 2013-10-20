<?php

class Lunch_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'lunch', 'description' => __("Hämtar lunchmenyer och visar veckans mat") );

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
		
		parent::__construct( 'it_lunch_widget', __("Lunchwidget"), $widget_ops);
	}
	
	
	function flush_widget_cache() {
		wp_cache_delete('it_lunch_widget', 'widget');
	}
	

	function widget( $args, $instance ) {
		extract( $args );
		
		$cache = wp_cache_get('it_lunch_widget', 'widget');
		$hide_name = ($instance['show_name'] == 0) ? 'visuallyhidden' : "";

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		$lunch_menu = get_todays_meals();


		if($lunch_menu) :
			ob_start();
			
			echo $before_widget;
			$title = "Lunch <small>".$lunch_menu['date']."</small>";

			echo $before_title . $title . $after_title;
			
			foreach($lunch_menu['places'] as $place) : ?>
			<h2 class="section-heading"><?php echo $place['name'];?></h2>
			<ul class="simple-list">
				<?php if (count($place['dishes']) < 1) : ?>
				<li><em>Ingen lunch idag</em></li>
				<?php endif;
				foreach($place['dishes'] as $dish) : ?>
				<li><?php echo $dish;?></li>
				<?php endforeach; ?>
			</ul>
			
			<?php endforeach;
			
			echo $after_widget;
			$cache[$args['widget_id']] = ob_get_flush();
			wp_cache_set('it_lunch_widget', $cache, 'widget');
		endif;
	}
	
	

	function update( $new_instance, $old_instance ) {
		$new_instance = (array) $new_instance;
		$instance = array( 'show_name' => 0);
		foreach ( $instance as $field => $val ) {
			if ( isset($new_instance[$field]) )
				$instance[$field] = 1;
		}		
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['it_lunch_widget']) )
			delete_option('it_lunch_widget');
		
		return $instance;
		
	}




	function form( $instance ) {
		
	}
}


?>