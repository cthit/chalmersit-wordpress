<?php

class Twitter_Widget extends WP_widget {

	private $widget_id;
	
	function __construct() {
		$widget_ops = array( 'classname' => 'it_twitter', 'description' => __("Visar en användares twitterfeed") );

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
		
		parent::__construct( 'it_twitter_widget', __("Twitterwidget"), $widget_ops);
	}

	function flush_widget_cache() {
		wp_cache_delete('it_twitter_widget', 'widget');
		delete_transient($this->widget_id);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$this->widget_id = $widget_id;
		
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
		<div class="tweet-list">
			<ul class="list">
				<?php foreach ($this->read_user($user, $count) as $tweet) { ?>
					<li>
						<p>
							<?= $this->fix_links($tweet->text, $tweet->entities) ?>
						</p>
						<time><?= $tweet->created_at ?></time>
					</li>
				<?php } ?>
			</ul>
		</div>

		<?php

		echo $after_widget;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('it_sponsor_widget', $cache, 'widget');

	}

	function single_link($text, $url, $display) {
		return str_replace($url, sprintf("<a href='%s'>%s</a>", $url, $display), $text);
	}

	function fix_links($text, $entities) {
		foreach ($entities->urls as $link) {
			$text = $this->single_link($text, $link->url, $link->display_url);
		}
		foreach ($entities->media as $link) {
			$text = $this->single_link($text, $link->url, $link->display_url);
		}
		foreach ($entities->hashtags as $tag) {
			$t = "#" . $tag->text;
			$text = str_replace($t, sprintf("<a href='http://twitter.com/search?q=%s&src=hash'>%s</a>", "%23" . $tag->text, $t), $text);
		}
		foreach ($entities->user_mentions as $user) {
			$u = "@" . $user->screen_name;
			$text = str_replace($u, sprintf("<a href='http://twitter.com/%s' title='%s'>%s</a>", $user->screen_name, $user->name, $u), $text);
		}
		return $text;
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
			<label for="<?= $this->get_field_id('title');?>"><?php _e("Användare"); ?>:</label>
			<input id="<?= $this->get_field_id('title');?>" name="<?= $this->get_field_name('title');?>" value="<?= $instance['title']; ?>" class="widefat" />
		</p>
		<p>
			<label for="<?= $this->get_field_id('count');?>"><?php _e("Antal tweets"); ?>:</label>
			<input id="<?= $this->get_field_id('count');?>" name="<?= $this->get_field_name('count');?>" type="number" min="0" value="<?= $instance['count']; ?>" />
		</p>
		<input id="<?= $this->get_field_id('type');?>" type="hidden" name="<?= $this->get_field_name('type');?>" value="user" />

		<?php
	}

	function read_user($username, $count) {
		$trans_name = $this->widget_id;
		$cache_time = 10;
		$twitter_data = null;
		if (false === ($twitter_data = get_transient($trans_name))) {
			require_once "twitteroauth/twitteroauth.php";
			$connection = new TwitterOAuth(
				"bGQjHe4fWTsODxdYC9AcDw", // Consumer key
				"VzfwDzLMTsMgMjuVUyf9vSY7oJ4xqb9jYcgtzeGvUc", // Consumer secret
				"367123533-OVAdLEhTtHKdtlTFcCGIOhiCwCaB0E5xI62Sn8SG", // Access token
				"mRzX38QKdf3ujNIONWTqXexSRsIeaUH97Pm6YCByk" // Access token secret
				);
			$twitter_data = $connection->get(
				'statuses/user_timeline',
				array(
					'screen_name' => $username,
					'count' => $count,
					'exclude_replies' => true,
					'trim_user' => true,
					'include_rts' => false
				)
			);
			if ($connection->http_code != 200) {
				$twitter_data = get_transient($trans_name);
			}
			set_transient($trans_name, $twitter_data, 60 * $cache_time);
		}
		return $twitter_data;
	}
}
?>