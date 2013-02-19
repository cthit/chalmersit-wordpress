<?php

class Sponsor_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'it_sponsor_widget', 'description' => __("Visar en sponsor med bild från länklista") );

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
		
		parent::__construct( 'it_sponsor_widget', __("Sponsorwidget"), $widget_ops);
	}
	
	
	function flush_widget_cache() {
		wp_cache_delete('it_sponsor_widget', 'widget');
	}
	

	function widget( $args, $instance ) {
		extract( $args );
		
		$cache = wp_cache_get('it_sponsor_widget', 'widget');
		$hide_name = ($instance['show_name'] == 0) ? 'visuallyhidden' : "";

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		$link = get_bookmark($instance['sponsor']);
		if(!$link)	return;

		ob_start();
		
		echo $before_widget;
		
		$title = apply_filters('widget_title', $instance['title'] );
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		
		?>

		<a href="<?php echo $link->link_url;?>">
			<img src="<?php echo $link->link_image;?>" alt="<?php echo $link->link_name;?>" />
		</a>
		
		<?php echo $after_widget;
		
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('it_sponsor_widget', $cache, 'widget');
	}
	
	

	function update( $new_instance, $old_instance ) {
		$new_instance = (array) $new_instance;
		$instance = array( 'show_name' => 0);
		foreach ( $instance as $field => $val ) {
			if ( isset($new_instance[$field]) )
				$instance[$field] = 1;
		}
		$instance['sponsor'] = $new_instance['sponsor'];
		$instance['title'] = $new_instance['title'];
		$instance['orderby'] = $new_instance['orderby'];
		
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['it_sponsor_widget']) )
			delete_option('it_sponsor_widget');
		
		return $instance;
		
	}




	function form( $instance ) {
		$defaults = array( 
			"title" => "",
			"sponsor" => 1,
			"orderby" => "name",
			"show_name" => false
		);
		
		$args = array(
			"hide_empty" => false
		);

		$links = get_bookmarks();
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e("Titel"); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id("sponsor");?>"><?php _e("Sponsor");?>:</label>
			<select id="<?php echo $this->get_field_id("sponsor");?>" name="<?php echo $this->get_field_name("sponsor");?>" class="widefat">
			<?php foreach($links as $link):?>
			
				<option value="<?php echo $link->link_id;?>" <?php selected($link->link_id, $instance['sponsor']); ?>>
					<?php echo $link->link_name;?>
				</option>
			
			<?php endforeach;?>
			</select>
		</p>
		
		<p>
			<label>
			<input id="<?php echo $this->get_field_id("show_name");?>" name="<?php echo $this->get_field_name("show_name");?>"
				type="checkbox" class="checkbox" <?php checked($instance['show_name'], true); ?>
			 />
			<?php _e("Visa sponsors titel");?>
			</label>
			
		</p>
	<?php 
	}
}


?>