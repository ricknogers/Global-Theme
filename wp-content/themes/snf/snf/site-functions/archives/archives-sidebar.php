<?php
/**
 * Custom post type date archives
 */

/**
 * Custom post type specific rewrite rules
 * @return wp_rewrite             Rewrite rules handled by Wordpress
 */
function cpt_rewrite_rules($wp_rewrite) {
    $rules = cpt_generate_date_archives('global-communication', $wp_rewrite);
    $wp_rewrite->rules = $rules + $wp_rewrite->rules;
    return $wp_rewrite;
}
add_action('generate_rewrite_rules', 'cpt_rewrite_rules');

/**
 * Generate date archive rewrite rules for a given custom post type
 * @param  string $cpt        slug of the custom post type
 * @return rules              returns a set of rewrite rules for Wordpress to handle
 */
function cpt_generate_date_archives($cpt, $wp_rewrite) {
    $rules = array();

    $post_type = get_post_type_object($cpt);
    $slug_archive = $post_type->has_archive;
    if ($slug_archive === false) return $rules;
    if ($slug_archive === true) {
        $slug_archive = $post_type->name;
    }

    $dates = array(
        array(
            'rule' => "([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})",
            'vars' => array('year', 'monthnum', 'day')),
        array(
            'rule' => "([0-9]{4})/([0-9]{1,2})",
            'vars' => array('year', 'monthnum')),
        array(
            'rule' => "([0-9]{4})",
            'vars' => array('year'))
    );

    foreach ($dates as $data) {
        $query = 'index.php?post_type='.$cpt;
        $rule = $slug_archive.'/'.$data['rule'];

        $i = 1;
        foreach ($data['vars'] as $var) {
            $query.= '&'.$var.'='.$wp_rewrite->preg_index($i);
            $i++;
        }

        $rules[$rule."/?$"] = $query;
        $rules[$rule."/feed/(feed|rdf|rss|rss2|atom)/?$"] = $query."&feed=".$wp_rewrite->preg_index($i);
        $rules[$rule."/(feed|rdf|rss|rss2|atom)/?$"] = $query."&feed=".$wp_rewrite->preg_index($i);
        $rules[$rule."/page/([0-9]{1,})/?$"] = $query."&paged=".$wp_rewrite->preg_index($i);
    }
    return $rules;
}
/**
 * Get a montlhy archive list for a custom post type
 * @param  string  $cpt  Slug of the custom post type
 * @param  boolean $echo Whether to echo the output
 * @return array         Return the output as an array to be parsed on the template level
 */
function get_cpt_archives( $cpt, $echo = false )
{
    global $wpdb;
    $sql = $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_type = %s AND post_status = 'publish' GROUP BY YEAR($wpdb->posts.post_date), MONTH($wpdb->posts.post_date) ORDER BY $wpdb->posts.post_date DESC", $cpt);
    $results = $wpdb->get_results($sql);

    if ( $results )
    {
        $archive = array();
        foreach ($results as $r)
        {
            $year = date('Y', strtotime( $r->post_date ) );
            $month = date('F', strtotime( $r->post_date ) );
            $month_num = date('m', strtotime( $r->post_date ) );
            $link = get_bloginfo('siteurl') . '/' . $cpt . '/' . $year . '/' . $month_num;
            $this_archive = array( 'month' => $month, 'year' => $year, 'link' => $link );
            array_push( $archive, $this_archive );
        }

        if( !$echo )
            return $archive;
        foreach( $archive as $a )
        {
            echo '<li class="list-group-item"><a href="' . $a['link'] . '">' . $a['month'] . ' ' . $a['year'] . '</a></li>';
        }
    }
    return false;
}