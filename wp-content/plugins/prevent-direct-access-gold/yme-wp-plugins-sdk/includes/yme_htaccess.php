<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 3/1/18
 * Time: 14:36
 */
if (!defined('ABSPATH')) die('You do not have sufficient permissions to access this file.');

if ( !class_exists('Yme_Htaccess') ) {

    class Yme_Htaccess
    {
        static function htaccess_writable()
        {

            $htaccess_file = Yme_Htaccess::get_htaccess_file_path();

            if (!file_exists($htaccess_file)) {
                error_log('.htaccess file not existed ');
                return '.htaccess file not existed';
            }

            error_log('.htaccess is writeable: ' . is_writable($htaccess_file));
            if (is_writable($htaccess_file)) {
                return true;
            }

            @chmod($htaccess_file, 0666);

            if (!is_writable($htaccess_file)) {
                error_log('Please ask host manager to grant write permission for .htaccess file.');
                return 'Please ask host manager to grant write permission for .htaccess file.';
            }

            return true;
        }

        static function get_htaccess_file_path()
        {

            //global $wp_rewrite;
            $htaccess_file = ABSPATH. '.htaccess';

            return $htaccess_file;
        }
    }

}