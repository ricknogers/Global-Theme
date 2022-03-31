<?php

function better_wpautop($pee){
    return wpautop($pee,false);
}
remove_filter( 'the_content', 'wpautop' );
add_filter( 'the_content', 'better_wpautop' , 99);
add_filter( 'the_content', 'shortcode_unautop',100 );
