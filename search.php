<?php
	/* Search template */
    $users_per_page = 21;
    // Searchable columns in the database (detailed_users view):
    $searchable_col = array('cid', 'email', 'firstname', 'lastname',
                            'nickname', 'it_year', 'it_phone');

    // We need to repeat each term the same number of times
    // as the amount of searchable terms because of wpdb->prepare(...)
    function repeat_terms($terms, $c) {
        $ret = array();
        foreach($terms as $term) {
            $ret = array_merge($ret, array_fill(0, $c, trim($term)));
        }

        return $ret;
    }

    // $logic is either AND or OR.
    function build_where($terms, $searchable_col, $logic = "AND") {
        $sql;
        // Build the where clause. e.g
        // ( cid = "bepa" or email = "bepa" ... ) $logic 
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
             $sql .= ") ". $logic." \n";
        }

        $sql = substr($sql, 0, (strlen($logic)+2)*-1); // Remove last AND.
        return $sql;
    }

    // Build sql for the user search (Without groups)
    // if $only != null the query searches only against users 
    // in this list. Expects $only to be the ouput from group_users(...)
    function build_user_sql($terms, $only = null, $page=0) {
        global $users_per_page;
        global $searchable_col;
        $sql = "SELECT * FROM detailed_users WHERE\n";
        $sql .= build_where($terms, $searchable_col);

        // Limit to the users in $only
        if($only != null && count($only) > 0) {
            if(count($terms > 0)) {
                $sql .= " AND ";
            }
            $sql .= "(";
            foreach($only as $user) {
                $sql .= "\nid = " . $user->id . " OR ";            
            }
            $sql = substr($sql, 0, -4); // Remove last or 
            $sql .= ")";
        }
        // Ordering
        $sql .= "\nORDER BY it_year DESC";

        // Useful for pagination
        $sql .= "\nLIMIT " . ($page * $users_per_page) . ", " . ($page+1)*$users_per_page;
        return $sql;
    }

    // Return the groups the user is a member of,
    // e.g: "digIT, snIT"
    function user_groups($uid) {
        global $wpdb;
        $sql = "SELECT * FROM group_membership WHERE\n";       
        $sql .= "ID = '%d'";
        $sql = $wpdb->prepare($sql, $uid);
        $groups = $wpdb->get_results($sql);

        $nice = "";
        foreach($groups as $group) {
            $nice .= $group->group_name . ", ";
        }
        return substr($nice, 0, -2); //Remove last comma
    }

    // Return all users in given group(s).
    function group_users($terms) {
        global $wpdb;
        // TODO: fix so that p.r.i.t. is searchable by prit
        // TODO: remove wildcard support for group terms.
        $cols = array('group_name');
        $sql = "SELECT * FROM group_membership WHERE\n";       
        $sql .= build_where($terms, $cols, "OR");

        $sql = $wpdb->prepare($sql, repeat_terms($terms, count($cols)));
        $gu = $wpdb->get_results($sql);

        return $gu;
    }

    // Returns all possible groups
    function get_avail_groups() {
        global $wpdb;
        $sql = "SELECT REPLACE(LOWER(group_name), '.', '.') AS group_name FROM it_groups_rs
            WHERE group_name NOT LIKE '[%'";
        return $wpdb->get_results($sql, ARRAY_N);
    }

    // Remove any terms that are groups
    function trim_group_terms(&$terms) {
        $ag = get_avail_groups();
        $flat_ag = array();
        // Flatten the multidimensional array.
        foreach(new RecursiveIteratorIterator(new RecursiveArrayIterator($ag)) as $k=>$v){
            $flat_ag[] = $v;
        }
        return array_diff($terms, $flat_ag);
    }

    // Remove unneccessary white-space and convert regex wildcards
    // to SQL wildcards
    $q = str_replace("*", "%",trim($_GET['s']));
    $terms = explode(" ", $q); 

    $groupusers = group_users($terms);
    // Remove the matched group-term(s).
    $terms = trim_group_terms($terms);

    if(empty($tems)) {
        $terms[] = "%";
    }
    $sql = $wpdb->prepare(build_user_sql($terms, $groupusers), repeat_terms($terms, count($searchable_col)));
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

						<?php if($user->in_group == "true") : ?>
						<dt>Medlem i</dt>
						<dd><?php echo user_groups($user->id, $wpdb);?></dd>
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
