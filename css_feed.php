<?php



$id=$_GET['id'];
query_posts( "p=$id" );

if ( have_posts() ) while ( have_posts() ) : the_post();
$css= get_post_meta($post->ID, 'css', true);
			echo $css;

 endwhile; ?>


