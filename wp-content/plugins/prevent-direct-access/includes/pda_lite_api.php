<?php
/**
 * Created by PhpStorm.
 * User: beosdev
 * Date: 05/11/2018
 * Time: 10:27
 */

if ( ! class_exists('PDA_Lite_API') ) {

    class PDA_Lite_API
    {
        private $repo;

        public function __construct()
        {
            $this->repo = new Repository();
        }

        private function prefix_role_name($name)
        {
            $hostname = is_ssl() ? home_url('/', 'https') : home_url('/');
            return $hostname . 'private/' . $name;
        }

        public function register_rest_routes()
        {
            register_rest_route(PDA_Lite_Constants::PREFIX_API_NAME, '/files/(?P<id>\d+)', array(
                'methods' => 'POST',
                'callback' => array($this, 'protect_files'),
                'permission_callback' => function () {
                    return current_user_can( 'upload_files' );
                },
            ));

            register_rest_route(PDA_Lite_Constants::PREFIX_API_NAME, '/private-urls/(?P<id>\d+)', array(
                'methods' => 'GET',
                'callback' => array($this, 'list_private_links'),
                'permission_callback' => function () {
                    return current_user_can( 'upload_files' );
                },
            ));

            register_rest_route(PDA_Lite_Constants::PREFIX_API_NAME, '/files/(?P<id>\d+)', array(
                'methods' => 'GET',
                'callback' => array($this, 'is_protected'),
                'permission_callback' => function () {
                    return current_user_can( 'upload_files' );
                },
            ));

            register_rest_route(PDA_Lite_Constants::PREFIX_API_NAME, '/un-protect-files/(?P<id>\d+)', array(
                'methods' => 'POST',
                'callback' => array($this, 'un_protect_files'),
                'permission_callback' => function () {
                    return current_user_can( 'upload_files' );
                },
            ));
        }

        function list_private_links($data)
        {
            $links = $this->repo->get_private_links_by_post_id_and_type_is_null($data['id']);
            return array_map(function ($link) {
                $link['time'] = strtotime($link['time']);
                $link['full_url'] = $this->prefix_role_name($link['url']);
                return $link;
            }, $links);
        }

        function is_protected($data)
        {
            $edit_url = wp_get_attachment_url($data['id']);
            $title = get_the_title($data['id']) === '' ? '(no title)' : get_the_title($data['id']);
            return array(
                'is_protected' => $this->repo->is_protected_file($data['id']),
                'post' => array(
                    'title' => $title,
                    'edit_url' => $edit_url,
                    's3_link' => false,
                    'is_file_deleted' => false
                ),
                'role_setting' => array(
                    "file_access_permission" => Pda_Helper::get_fap_setting(),
                    "whitelist_roles" => "",
                ),
            );
        }

        function protect_files($data)
        {
            $pda_admin = new Pda_Admin();

            $file_result = $pda_admin->insert_prevent_direct_access($data['id'], 1);
            $pda_admin->handle_move_file($data['id']);
            return $file_result;
        }

        function un_protect_files($data)
        {
            $pda_admin = new Pda_Admin();

            $file_result = $pda_admin->insert_prevent_direct_access($data['id'], 0);
            $pda_admin->handle_move_file($data['id']);
            return $file_result;
        }

    }

}
