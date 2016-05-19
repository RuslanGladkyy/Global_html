<?php 


remove_filter( 'the_content', 'wpautop' ); 
remove_filter( 'the_excerpt', 'wpautop' ); 
remove_filter('comment_text', 'wpautop'); 



register_nav_menu('menu', 'Меню');



add_theme_support('post-thumbnails');
