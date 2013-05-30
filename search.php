<?php
	/* Search template */
    $users_per_page = 21;
    // Searchable columns in the database (detailed_users view):
    $searchable_col = array('cid', 'email', 'firstname', 'lastname',
                            'nickname', 'it_year', 'it_phone');

    // We need to repeat each term the same number of times
    // as the amount of searchable terms because of wpdb->prepare(...)
    function repeat_terms($terms) {
        global $searchable_col;
        $c = count($searchable_col);
        $ret = array();
        foreach($terms as $term) {
            $ret = array_merge($ret, array_fill(0, $c, $term));
        }

        return $ret;
    }

    // Build sql for the user search (Without groups)
    function build_user_sql($terms, $page=0) {
        global $users_per_page;
        global $searchable_col;
        $sql = "SELECT * FROM detailed_users WHERE\n";

        // Build the where clause. e.g
        // ( cid = "bepa" or email = "bepa" ... ) AND
        // ( cid = "apa"  or email = "apa" ...)
        for($j = 0; $j < count($terms); $j++) {
            for($i = 0; $i < count($searchable_col); $i++) {
                 $key = $searchable_col[$i];
                 if($i == 0) {
                     $sql .= "(";
                 }
                 else if($i > 0) {
                     $sql .= " OR ";
                 }
                 $sql .= $key . " LIKE '%s'";
             }
             $sql .= ") AND \n";
        }
        $sql = substr($sql, 0, -5); // Remove last AND.

        // Useful for pagination
        $sql .= "\nLIMIT " . ($page * $users_per_page) . ", " . ($page+1)*$users_per_page;

        return $sql;
    }

    $q = trim($_GET['s']);
    $terms = explode(" ", $q); 
    $sql = $wpdb->prepare(build_user_sql($terms), repeat_terms($terms));
    $users = $wpdb->get_results($sql);

	get_header();
?>

<section class="main-col eight columns push-two">
	<div class="box row">
		<header>
			<h1>Sökresultat</h1>
			<?php get_search_form();?>
		</header>

		<section class="six columns">

			<h2>Nyheter och sidor</h2>

			<?php if(have_posts()) : ?>
			
			<ol class="simple-list">

			<?php while(have_posts()) : the_post(); ?>

				<li>
					<a href="<?php the_permalink();?>"><?php the_title();?></a>
				</li>

			<?php endwhile;?>
			</ol>

			<?php else : ?>

			<p class="no-content">Inget hittades</p>

			<?php endif;?>
		</section>

		<section class="six columns">

			<h2>Personer</h2>

			<ul class="list user-search-results">
			<?php if(!empty($users)) : ?>
                <?php 
                    foreach($users as $user) : 
                ?>

				<li>
					<?php echo get_avatar($user->id, 64);?>

					<dl>
						<dt>Namn</dt>
                        <dd><a href="/author/<?php echo $user->cid; echo "\">"; echo $user->firstname . " '" . $user->nickname . "' " . $user->lastname; ?></a></dd>

                        <?php if(is_user_logged_in()) : ?>
                        <dt>E-post</dt>
						<dd><a href="mailto:<?php echo $user->email;?>"><?php echo $user->email;?></a></dd>
                        <?php endif; ?>
                        
						<?php if($user->it_phone != -1 && is_user_logged_in()) : ?>
						<dt>Telefon</dt>
						<dd><?php echo $user->it_phone; ?></dd>
						<?php endif;?>

						<?php if($user->it_year != -1) : ?>
						<dt>Antagningsår</dt>
						<dd><?php echo $user->it_year;?></dd>
						<?php endif;?>
					</dl>
				</li>
			<?php endforeach; ?>
            <?php if(count($users) > 20 ) : ?>
			      <p class="no-content">Hittade för många användare. Vänligen förfina din söksträng</p>
            <?php endif; ?>

			<?php else : ?>

			<p class="no-content">Inga personer hittades</p>

			<?php endif;?>
			</ul>
		</section>

	</div>
</section>

<?php get_footer();?>
