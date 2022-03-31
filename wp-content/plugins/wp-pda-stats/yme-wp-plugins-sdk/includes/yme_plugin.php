<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 3/7/18
 * Time: 15:48
 */

if ( !class_exists('Yme_Plugin') ) {

    class Yme_Plugin {

        public static function getLicenseKey($plugin) {
            switch ($plugin) {
                case 'pda':
                    return get_option('pda_license_key');
                    break;
                case 'ppp':
                    return get_option('ppp_license_key');
                    break;
                case 'pda-s3':
                    return get_option('pda-s3-license-key');
                    break;
	            case 'pdav3':
		            return get_option('prevent-direct-access-gold_license_key');
                    break;
                default:
                    return '';
                    break;
            }
        }

        public static function getAppId($plugin) {
            switch ($plugin) {
                case 'wpp':
                    return '77808414';
                    break;
                case 'pda':
                    return '583147';
                    break;
                case 'ppp':
                    return '77779715';
                    break;
                case 'pda-s3':
                    return '77822218';
                    break;
							case 'pda-pdf':
										return '77869370';
										break;
                default:
                    return '';
                    break;
            }
        }
    }

}
