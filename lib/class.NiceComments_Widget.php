<?php /* Widget template */

class NiceComments_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'latest-comments', 
			'description' => __("Snyggare variant av Recent comments"));

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );

		parent::__construct('it_nicecomments_widget', __("Recent Comments IT"), $widget_ops);
	}

	function flush_widget_cache() {
		wp_cache_delete('it_nicecomments_widget', 'widget');
	}

	function widget( $args, $instance) {
		extract($args);

		$cache = wp_cache_get('it_nicecomments_widget', 'widget');

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

		$comments = get_comments(array("status" => "approve", "number" => $instance['count']));


		ob_start();
		
		echo $before_widget;

		if($title) { ?> 
			<header class="panel-header">
				<h1><?php echo $title; ?></h1>
			</header>
			<?php
		}
		if($comments) :
			?>

				<ul class="simple-list">
					<?php foreach($comments as $comment) : ?>
					<?php $p = get_post($comment->comment_post_ID);?>

					<li>
						<p class="comment-author">
							<?php echo get_avatar($comment->user_id, 32);?>
							<strong><?php echo $comment->comment_author;?></strong>
						</p>
						på <a href="<?php echo get_permalink($p->ID);?>#comment-<?php echo $comment->comment_ID;?>"><?php echo $p->post_title;?></a>
						<time><?php echo human_time_diff(strtotime($comment->comment_date_gmt));?> sedan</time>
					</li>
					<?php endforeach;?>
				</ul>

			<?php
		
		else :
			?>
				<p>Inga kommentarer</p>


			<?php
		endif;

		echo $after_widget;	
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('it_nicecomments_widget', $cache, 'widget');

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
		if ( isset($alloptions['it_nicecomments_widget']) )
			delete_option('it_nicecomments_widget');
		
		return $instance;
	}

	function form($instance) {
		$defaults = array(
			"title" => "Senaste kommentarerna",
			"count" => "10"
			// Define default key-value pairs
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
		// Get any external data needed for the form
		?>

		<input type="hidden" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" value="<?php echo $instance['title']; ?>" />
		<p>
			<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e("Antal kommentarer"); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'count' ); ?>" type="number" min="0"
				name="<?php echo $this->get_field_name( 'count' ); ?>" 
				value="<?php echo $instance['count']; ?>" class="widefat" />
		</p>

		<?php
	}
}


?>
