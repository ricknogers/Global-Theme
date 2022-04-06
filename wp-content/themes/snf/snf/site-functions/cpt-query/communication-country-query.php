<?php
//add_filter('pre_get_posts','my_filter_the_search',10,1);
//function my_filter_the_search($query){
//
//    //If the query is a search AND taxonomy terms are set, filter by those terms:
//    if($query->is_search() && isset($_GET['my-filter-terms'])){
//        //Get array of slugs of checked terms
//        $terms = (array) $_GET['my-filter-terms'];
//
//        //Tax_query array
//        $tax_query = array(array(
//            'taxonomy' => 'country',
//            'field' => 'slug',
//            'terms' => $terms,
//            'operator' => 'IN',
//        ));
//
//        //Tell the query to filter by tax
//        $query->set('tax_query', $tax_query  );
//    }
//    return $query;
//}