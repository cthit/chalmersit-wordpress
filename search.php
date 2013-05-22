<?php
	/* Search template */

/*
 * This functions builds a query similar to the below dynamically:
 * SELECT *
    FROM it_usermeta t1 
    INNER JOIN it_usermeta t2
    on t1.user_id = t2.user_id
    INNER JOIN it_usermeta t3
    on t1.user_id = t3.user_id
    WHERE 
    ((t1.meta_key = 'nickname' OR t1.meta_key = 'first_name' OR t1.meta_key = 'last_name' OR t1.meta_key = 'it_year') AND t1.meta_value LIKE '%horv%')
    AND
    ((t2.meta_key = 'nickname' OR t2.meta_key = 'first_name' OR t2.meta_key = 'last_name' OR t2.meta_key = 'it_year') AND t2.meta_value LIKE '%%')
    AND
    ((t3.meta_key = 'nickname' OR t3.meta_key = 'first_name' OR t3.meta_key = 'last_name' OR t3.meta_key = 'it_year') AND t3.meta_value LIKE '%%');
 */
    function dyn_build_query($pieces) {
       $dynquery = "SELECT user_id FROM it_usermeta";

       if(count($pieces) == 1) {
            $dynquery .= " WHERE ((meta_key = 'it_year' OR meta_key = 'nickname' OR meta_key = 'first_name' OR meta_key = 'last_name') 
            AND meta_value LIKE '%%%s%%') LIMIT 21";
        return $dynquery;
       }
       else {
            $i=1;
            $dynquery = "SELECT * FROM it_usermeta t1 \n";
            $join = " "; 
            $where;
            foreach($pieces as $piece) {
                if( $i <= count($pieces)-1) {
                    $join .=  "INNER JOIN it_usermeta " . " t" . ($i+1) . " \n";
                    $join .= " on t" . $i . ".user_id = t" . ($i+1) . ".user_id \n";
                }
                $where .= "((t" . $i . ".meta_key = 'nickname' OR t" . $i . ".meta_key = 'first_name' OR t" . $i . ".meta_key = 'last_name' OR t" . $i . ".meta_key = 'it_year') AND t" . $i . ".meta_value LIKE '%%%s%%') \nAND\n ";
                $i ++; 
            }
            
            return $dynquery . $join . "WHERE\n" . substr($where, 0, -5);
       }
    }

    $q = trim($_GET['s']);
    $splitted = explode(" ", $q); 
    $users;
    // Reduce the number of searchable words. 
    if(count($splitted) <= 8) {  
        // This a performance hack since wordpress makes poor queries.
        $users = $wpdb->get_results( $wpdb->prepare( 
            dyn_build_query($splitted)
	        /*"
                SELECT user_id FROM it_usermeta  
                WHERE ((meta_key = 'it_year' OR meta_key = 'nickname' OR meta_key = 'first_name' OR meta_key = 'last_name') 
                AND meta_value LIKE '%%%s%%') LIMIT 21*/
            , $splitted
        )); 
    }

    // Convert the above results to a format used in WP_User_Query.
    $userids = array();
    foreach($users as $user) {
        $userids[] = $user->user_id;
    }
    $userids = array_unique($userids);

    // "Refetch" the users..."
    if(count($userids) == 0) {
        // If the query doesnt return anything... haxx a bit...
        $userids[] = 0;
    } 
	$user_query = new WP_User_Query(array(
        "include" => $userids
    ));
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
			<?php if($user_query->results) : ?>
                <?php 
                    $counter = 0;
                    foreach($user_query->results as $user) : 
                ?>
                <?php 
                    $meta = get_user_meta($user->ID);
                    $counter++;
                ?>

				<li>
					<?php echo get_avatar($user->ID, 64);?>

					<dl>
						<dt>Namn</dt>
                        <dd><a href="/author/<?php echo $user->user_login; echo "\">"; user_fullname($user);?></a></dd>

                        <?php if(is_user_logged_in()) : ?>
                        <dt>E-post</dt>
						<dd><a href="mailto:<?php echo $user->user_email;?>"><?php echo $user->user_email;?></a></dd>
                        <?php endif; ?>
                        
						<?php if($meta['it_phone'][0] && is_user_logged_in()) : ?>
						<dt>Telefon</dt>
						<dd><?php echo $meta['it_phone'][0];?></dd>
						<?php endif;?>

						<?php if($meta['it_year'][0]) : ?>
						<dt>Antagningsår</dt>
						<dd><?php echo $meta['it_year'][0];?></dd>
						<?php endif;?>
					</dl>
				</li>
            <?php if($counter > 20 ) : ?>
			      <p class="no-content">Hittade för många användare. Vänligen förfina din söksträng</p>
            <?php break; endif; ?>
			<?php endforeach; ?>

			<?php else : ?>

			<p class="no-content">Inga personer hittades</p>

			<?php endif;?>
			</ul>
		</section>

	</div>
</section>

<?php get_footer();?>
