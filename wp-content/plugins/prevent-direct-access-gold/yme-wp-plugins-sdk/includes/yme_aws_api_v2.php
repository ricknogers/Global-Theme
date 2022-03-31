<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 3/7/18
 * Time: 14:08
 */

if ( !class_exists('Yme_AWS_Api_v2') ) {

    class Yme_AWS_Api_v2 extends Yme_AWS_Api {

        function updateCountAndUserAgents( $id, $domain, $count ) {
            $configs = require('class/class_yme_configs.php');
            $endpoint = $configs->lu_api;
            $body = array(
                'id' => $id,
                'domain' => $domain,
                'countActivated' => $count
            );

            $args = array(
                'body' => $body,
                'timeout' => 100,
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(
                    'x-api-key' => $configs->lu_key
                )
            );

            $response = wp_remote_post( $endpoint, $args );
            return json_decode(wp_remote_retrieve_body($response));
        }

        function getAvailableDomain( $id ){
            $configs = require('class/class_yme_configs.php');
            $endpoint = $configs->ad_api;
            $body = array(
                'id' => $id,
            );

            $args = array(
                'body' => $body,
                'timeout' => 100,
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(
                    'x-api-key' => $configs->lu_key
                )
            );

            $response = wp_remote_post( $endpoint, $args );
            return json_decode(wp_remote_retrieve_body($response));
        }
    }

}
