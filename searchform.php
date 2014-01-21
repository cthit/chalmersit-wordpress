<form role="search" action="/" method="get">
	<input type="search" placeholder="Sök på Chalmers.it" results="0" name="s"
	<?php if(is_search()) : ?>value="<?php the_search_query();?>" <?php endif;?> />
</form>
