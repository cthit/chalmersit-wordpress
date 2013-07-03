<?php

class Twitter_Widget extends WP_widget {

	private $auth_key = null;
	
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
		<div class="tweet-list">
			<ul class="list">
				<?php /*foreach ($this->read_user($user) as $tweet) { ?>
					<li>
						<p>
							<?= $this->fix_links($tweet->text, $tweet->entities) ?>
						</p>
						<time><?= $tweet->created_at ?></time>
					</li>
				<?php }*/ ?>
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

	function read_user($username) {
		$data = array("screen_name" => $username, "trim_user" => true, "include_rts" => false, "exclude_replies" => true);
		$headers = array("Authorization: Bearer " . $this->get_auth_key());
		$result = $this->send_request("https://api.twitter.com/1.1/statuses/user_timeline.json", $headers, $data);
		//print_r(json_decode($result));
		return json_decode($result);
	}

	function get_auth_key() {
		if ($this->auth_key == null) {
			$key = urlencode("bGQjHe4fWTsODxdYC9AcDw");
			$secret = urlencode("VzfwDzLMTsMgMjuVUyf9vSY7oJ4xqb9jYcgtzeGvUc");
			$whole_code = base64_encode(sprintf("%s:%s", $key, $secret));
			$data = array('grant_type' => 'client_credentials');
			$headers = array("Authorization: Basic " . $whole_code);
			$result = json_decode($this->send_request("https://api.twitter.com/oauth2/token", $headers, $data, "POST"));
			$this->auth_key = $result->access_token;
		}
		return $this->auth_key;
	}

	function send_request($url, $headers, $data, $method="GET") {
		$headers = array_merge($headers, array("User-Agent: Chalmers.it", "Content-Type: application/x-www-form-urlencoded;charset=UTF-8"));
		$options = array(
			'http' => array(
				'header' => join("\r\n", $headers),
				'method' => $method
			)
		);
		if ($method == "POST") {
			$options['http']['content'] = http_build_query($data);
		} else if ($method == "GET") {
			$url .= "?" . http_build_query($data);
		}
		$context = stream_context_create($options);
		return file_get_contents($url, false, $context);
	}

}
?>