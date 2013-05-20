<?php /* Widget template */

class Upcoming_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'upcoming', 
			'description' => __("Visar lunchföreläsningar och events från kalendern"));

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );

		parent::__construct('it_upcoming_widget', __("Kommande event"), $widget_ops);
	}

	function flush_widget_cache() {
		wp_cache_delete('it_upcoming_widget', 'widget');
	}

	function widget( $args, $instance) {
		extract($args);

		$cache = wp_cache_get('it_upcoming_widget', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}
		/* Do pre-widget stuff here */

		$lunchCount = $instance['lunchCount'];
		$eventCount = $instance['eventCount'];
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

        $post_query = array(
			"cat"              => get_category_by_slug("lunchforelasningar")->term_id,
			"posts_per_page"   => $lunchCount,
            "meta_query"       => array(
                array( // Only get future lunch lectures
                    "key"     => IT_PREFIX."event_date",
                    "value"   => date("Y-m-d"),
                    "compare" => ">=",
                )
            )
		);
        $lunch_lectures = new WP_Query($post_query);
		?>

		<?php if($lunch_lectures) : ?>
			<h2 class="section-heading">Lunchföreläsningar</h2>
			<ul class="list lunch-lectures">
                <?php if($lunch_lectures->post_count == 0) : ?>
                <li>Inga planerade lunchföreläsningar</li>
                <?php endif; ?>

				<?php while($lunch_lectures->have_posts()) : $lunch_lectures->the_post(); ?>
                <?php $lecture = $lunch_lectures->post ?>
                <?php $date = get_post_meta($lecture->ID, IT_PREFIX."event_date", true);?>
				<li>
					<h3><?php echo the_title();?></h3>
					<ul class="meta">
						<li><time datetime="<?php echo $date;?>">
							<?php echo date("j F", strtotime($date));?>,
							<?php echo get_post_meta($lecture->ID, IT_PREFIX."event_start_time", true);?></time>
						</li>
						<li>
							<?php echo get_post_meta($lecture->ID, IT_PREFIX."event_location", true);?>
						</li>
						<li><?php the_author_posts_link(); ?></li>
						<li>
							<a href="<?php echo get_permalink($lecture->ID);?>" class="read-more">Läs mer</a>
						</li>
					</ul>
				</li>
				<?php endwhile;?>

			</ul>
			<?php endif;?>

			<h2 class="section-heading">Evenemang</h2>
			
			<?php echo do_shortcode('[google-calendar-events id="1" type="list" max="'.$eventCount.'"]' ); ?>


		<?php
		echo $after_widget;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('it_upcoming_widget', $cache, 'widget');

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
		if ( isset($alloptions['it_upcoming_widget']) )
			delete_option('it_upcoming_widget');
		
		return $instance;
	}

	function form($instance) {
		$defaults = array(
			"title" => "Kommande",
			"lunchCount" => "3",
			"eventCount" => "3"
			// Define default key-value pairs
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		// Get any external data needed for the form
		?>
		<input type="hidden" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" value="<?php echo $instance['title']; ?>" />
		<p><label for="<?php echo $this->get_field_id('lunchCount'); ?>"><?php _e("Antal lunchevent");?>:</label>
			<input type="number" min="0" id="<?php echo $this->get_field_id('lunchCount'); ?>" name="<?php echo $this->get_field_name('lunchCount'); ?>" value="<?php echo $instance['lunchCount']; ?>" />
		</p>
		<p><label for="<?php echo $this->get_field_id('eventCount'); ?>"><?php _e("Antal kalenderevent");?>:</label>
			<input type="number" min="0" id="<?php echo $this->get_field_id('eventCount'); ?>" name="<?php echo $this->get_field_name('eventCount'); ?>" value="<?php echo $instance['eventCount']; ?>" />
		</p>

		<?php
	}
}


?>
