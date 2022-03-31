<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 5/18/18
 * Time: 11:25
 */

if ( ! class_exists( 'PDA_v3_Constants' ) ) {
	/**
	 * Class PDA_v3_Constants
	 *
	 * @codeCoverageIgnore
	 */
	class PDA_v3_Constants {
		static $hooks = array(
			'HTACCESS' => 'pda_v3_get_rewrite_rules',
			'NGINX'    => 'pda_v3_get_nginx_rewrite_rules',
			'IIS'      => 'pda_v3_get_iis_rewrite_rules'
		);
		static $secret_param = 'pda_v3_pf';
		static $secret_param_test = 'pda_v3_pf_test';
		static $secret_private_link_name = 'pdav3_rexypo';
		static $secret_private_link = 'pdav3_rexypo=ymerexy';
		static $db_version = 'jal_db_version';
		static $default_private_link_prefix = 'private';
		static $pda_test_file_name = 'please_dont_remove_pda_v3_test.txt';
		static $pda_meta_key_user_roles = "pda_user_roles";
		static $pda_meta_key_memberships_integration = "pda_memberships_integration";

		const PLUGIN_VERSION = "3.0.0";

		const LICENSE_OPTIONS = 'pda_is_licensed';

		const LICENSE_KEY = 'pda_license_key';

		const LICENSE_INFO = 'pda_License_info';

		const LICENSE_FORM_NONCE = 'prevent-direct-access-gold_license_form_nonce';

		const LICENSE_ERROR = 'pda_license_error';

		const LICENSE_EXPIRED = 'pda_licensed_expired';

		const APP_ID = 'pda_app_id';

		const LICENSE_NOT_ACTIVATED = 'Please enter your license to activate our powerful Gold features!';

		const LICENSE_ACTIVATED = 'Congrats! You\'re using the Gold version of Prevent Direct Access Gold!';

		const COLUMN_ID = 'pda-v3-column';

		const OPTION_NAME = 'pdav3_options';

		const SITE_OPTION_NAME = 'pdav3_site_options';

		const PROTECTION_META_DATA = '_pda_protection';

		const MIGRATE_DATA = 'pdav3_migrated_data';

		const METABOX_OPTION_NONCE = 'pda_v3_protection_metabox_nonce';

		const SETTING_PAGE_PREFIX = 'pda-gold';

		const STATUS_PAGE_PREFIX = 'pda-status';

		const AFFILIATE_PAGE_PREFIX = 'pda-affiliate';

		const FULLY_ACTIVATED = 'pda_v3_fully_activated';

		const REMOTE_LOG = 'remote_log';

		CONST PDA_PREFIX_URL = 'pda_prefix_url';

		CONST PDA_AUTO_PROTECT_NEW_FILE = 'pda_auto_protect_new_file';

		CONST PDA_GOLD_ENABLE_IMAGE_HOT_LINKING = 'pda_gold_enable_image_hot_linking';

		CONST PDA_GOLD_ENABLE_DERECTORY_LISTING = 'pda_gold_enable_directory_listing';

		CONST PDA_PREVENT_ACCESS_LICENSE = 'pda_prevent_access_license';

		const PDA_GOLD_ENABLE_WEB_CRAWLERS = 'pda_prevent_access_enable_wc';

		const PDA_GOLD_WEB_CRAWLERS = 'pda_prevent_access_selected_wc';

		CONST PDA_PREVENT_ACCESS_VERSION = 'pda_prevent_access_version';

		CONST PDA_GOLD_NO_ACCESS_PAGE = 'pda_gold_no_access_page';

		CONST PDA_NAP_CUSTOM_LINK = 'pda_nap_custom_link';

		CONST PDA_NAP_EXISTING_PAGE_POST = 'pda_nap_existing_page_post';

		CONST WHITElIST_ROLES = 'whitelist_roles';

		CONST WHITElIST_ROLES_AUTO_PROTECT = 'whitelist_roles_auto_protect';

		CONST WHITELIST_USER_GROUPS = 'whitelist_user_groups';

		CONST FILE_ACCESS_PERMISSION = 'file_access_permission';

		CONST REMOVE_LICENSE_AND_ALL_DATA = 'remove_license_and_all_data';

		CONST FORCE_PDA_HTACCESS = 'force_pda_htaccess';

		CONST USE_REDIRECT_URLS = 'use_redirect_urls';

		CONST PDA_AUTO_CREATE_NEW_PRIVATE_LINK = 'pda_auto_create_new_private_link';

		CONST PDA_AUTO_REPLACE_PROTECTED_FILE = 'pda_auto_replace_protected_file';

		CONST PDA_REPLACED_PAGES_POSTS = 'pda_replaced_pages_posts';

		CONST PDA_PRIVATE_LINK = 'private_link';

		CONST PDA_ORIGINAL_LINK = 'original_link';

		CONST PDA_CANNOT_VIEW = false;

		CONST PDA_CAN_VIEW = true;

		CONST PDA_DO_ACTION_FOR_STATS = 'pda_before_return_link';

		CONST PDA_INVITE_AND_EARN = 'Invite & Earn';

		CONST FORCE_DOWNLOAD = 'force_download';

		CONST PDA_AUTO_ACTIVATE_NEW_SITE = 'pda_gold_enable_auto_activate_new_site';

		static function get_file_permissions() {
			$perms = array(
				''             => array(
					'select' => __( 'Default', 'prevent-direct-access-gold' )
				),
				'admin_users'  => array(
					'select' => __( 'Admin users', 'prevent-direct-access-gold' )
				),
				'author'       => array(
					'select' => __( 'The file\'s author', 'prevent-direct-access-gold' )
				),
				'logged_users' => array(
					'select' => __( 'Logged-in users', 'prevent-direct-access-gold' )
				),
				'anyone'       => array(
					'select' => __( 'Anyone', 'prevent-direct-access-gold' )
				)
			);

			return $perms;
		}

		const RETENTION_TIMES_LONG_LIFE = MONTH_IN_SECONDS;
		const RETENTION_TIMES_P_EXPIRED = DAY_IN_SECONDS;
		const LICENSE_GAP_TIME_CHECKING = 8 * DAY_IN_SECONDS;

		const CRON_JOB_QUARTER = 'quarterly';

		const CRON_JOB_YEARLY = 'yearly';
		const CRON_JOB_MONTHLY = 'monthly';
		const UN_LIMITED_LICENSE = '584088';
		const FIFTEEN_SITE_LICENSE = "77844608";
		const FIFTEEN_SITE_LIFETIME_LICENSE = "77917246";
		const DEV_LICENSE = '77814469';

		const PDA_S3_LINK_META = 'pda_s3_link';

		const PDA_GOLD_LOGIN_PAGE_COOKIE = 'pda_gold_att_id';

		//Private link types
		const PDA_PRIVATE_LINK_NORMAL = '';
		const PDA_PRIVATE_LINK_S3_USER = 'p_user_s3';
		const PDA_PRIVATE_LINK_USER = 'p_user';
		const PDA_PRIVATE_LINK_EXPIRED = 'p_expired';
		const PDA_PRIVATE_LINK_PG = 'p_pg';
		const PDA_PRIVATE_LINK_LONG_LIFE = 'p_lg_expired';

		const PDA_IS_BACKUP_AFTER_ACTIVATE_OPTION = 'pda_is_backup_after_activate';
		const PDA_NOTICE_CRONJOB_AFTER_ACTIVATE_OPTION = 'pda_notice_cronjob_after_activate';
		const PDA_MAX_VALUE_MOVE_FILES = 10000000;

//		const FILE_ACCESS_PERM = 'file_access_permission';

		static function get_screen_map_id() {
			$screens = array(
				'media'        => 'upload',
				'pda_settings' => 'toplevel_page_pda-gold',
				'status'       => 'prevent-direct-access-gold_page_pda-status',
				'attachment'   => 'attachment',
				'affiliate'    => 'prevent-direct-access-gold_page_pda-affiliate',
				'upload'       => 'upload',
				'plugins'      => 'plugins',
				'page'         => 'page',
				'post'         => 'post',
			);

			return $screens;
		}

		const LICENSE_EXPIRED_MESSAGE = '<p><strong>Your Prevent Direct Access Gold license has expired!</strong> You must <a href="mailto:hello@preventdirectaccess.com?subject=Renew Prevent Direct Access Gold License">renew your license</a> to retain access to our priority support and important plugin updates. <br>
If you don\'t keep your plugins up-to-date, you risk your website <strong>being hacked</strong> and <strong>expose important files to the public</strong>.</p>
<p>
    <a href="mailto:hello@preventdirectaccess.com?subject=Renew Prevent Direct Access Gold License" class="button" target="_blank">Renew Now</a>
</p>';

		const PDA_LS_CRON_JOB_NAME = 'pda_lcs_cronjob';
		const PDA_DELETE_EXPIRED_PRIVATE_LINK_CRON_JOB_NAME = 'pda_delete_expired_private_links_cronjob';
		const PDA_NUM_BACKUP_FILES_OPTION = 'pda_num_backup_files';

		//UI Strings
		const PDA_V3_FILE_PROTECTED = 'protected';
		const PDA_V3_FILE_UNPROTECTED = 'unprotected';
		const PDA_V3_TITLE_FOR_FILE_PROTECTED = 'This file is protected';
		const PDA_V3_TITLE_FOR_FILE_UNPROTECTED = 'This file is unprotected';
		const PDA_V3_CLASS_FOR_FILE_UNPROTECTED = 'pda-unprotected';
		const PDA_V3_ACTIVATE_ALL_SITES_OPTION_NAME = 'pda_activate_all_sites';
		CONST PDA_GOLD_ROLE_PROTECTION = 'pda_role_protection';
		//Hooks
		const PDA_V3_BEFORE_RENDER_PDA_COLUMN = 'before_render_pda_column';

		const HOOK_PDA_FAP = 'pda_file_access_permission';

		const HOOK_SUPPORTED_WEB_CRAWLERS = 'pda_supported_web_crawlers';

		const PDA_UPDATE_SERVICES = 'pda_update_services';

		const PDA_PREFIX_PROTECTED_FOLDER = '/_pda/';

		const PDA_THE_CONTENT_HOOK = 'pda_the_content';

		const PDA_BEFORE_HANDLE_SR_HOOK = 'pda_before_the_content';

		const PDA_IS_USING_SEARCH_REPLACE = 'pda_is_using_search_replace';

		const SEARCH_AND_REPLACE_LEVEL = array(
			'PDA_GOLD'       => 300,
			'PDA_MAGIC_LINK' => 200,
			'PDA_S3'         => 100,
		);
		const PDA_FAP = array(
			'MEDIA_FILE'      => array(
				'ADMIN_USER'     => 'admin-user',
				'LOGGED_IN_USER' => 'logger-in-user',
				'CUSTOM_ROLES'   => 'custom-roles',
			),
			'DEFAULT_SETTING' => array(
				'ADMIN_USER'     => 'admin_users',
				'LOGGED_IN_USER' => 'logged_users',
				'CUSTOM_ROLES'   => 'custom_roles',
			),
			'AUTHOR'          => 'author',
			'ANYONE'          => 'anyone',
		);

		const PDA_MESSAGE_REMOVE_HTACCESS_FILE_IN_PDA_FOLDER = 'Error: Fail to <a href="https://preventdirectaccess.com/docs/pda-rewrite-rules/#pda-folder" target="_blank" rel="noopener noreferrer">remove the .htaccess from _pda folder</a>. Please click on "Save changes" to fix it';

		const PDA_HTACCESS_RAW_URL_ERROR = 'raw_url_error';

		const PDA_HTACCESS_MESSAGE = 'message';

		const PDA_HTACCESS_APACHE_ERROR = 'apache_error';

		const PDA_HTACCESS_MOD_REWRITE_ERROR = 'mod_rewrite_error';

		const PDA_HTACCESS_FILE_ERROR = 'file_error';

		const PDA_HTACCESS_WRITABLE_ERROR = 'writable_error';

		const PDA_HTACCESS_CONTENT_ERROR = 'content_error';

		const APACHE_SERVER = 'apache';

		const NGINX_SERVER = 'nginx';

		const IIS_SERVER = 'iis';

		const UNDEFINED_SERVER = '';

		const LINK_GUIDE_APACHE_SERVER = 'https://preventdirectaccess.com/docs/pda-rewrite-rules/#pda-folder';

		const LINK_GUIDE_NGINX_SERVER = 'https://preventdirectaccess.com/docs/pda-rewrite-rules/#raw-url-limitation';

		const LINK_GUIDE_IIS_SERVER = 'https://preventdirectaccess.com/docs/pda-rewrite-rules/#raw-url-limitation';

		const LINK_GUIDE_UNDEFINED_SERVER = 'https://preventdirectaccess.com/docs/pda-rewrite-rules/#raw-url-limitation';

		const PLUGIN_RUN_WITH_LIMITATION = 'Our plugin is working with <a target="_blank" rel="noreferrer nofollow" href="https://preventdirectaccess.com/docs/pda-rewrite-rules/#raw-url-limitation">some limitation</a>';

		const PLUGIN_RUN_OK = 'Our plugin is up and running';

		const PLUGIN_CANNOT_RUN = 'Our plugin isn\'t working properly due to incorrect rewrite rules';

		const PLUGIN_RULE_ERROR_APACHE = 'Our plugin isn\'t working properly due to <a href="admin.php?page=pda-gold&tab=general#pda-advance-opts">incorrect Raw URLs setup</a>';

		const R_RULE_OK = 'Our rewrite rules are set up correctly.';

		const R_RULE_ERROR = 'We\'re unable to check the rewrite rules. Please try again.';

		const R_RULE_FAIL = 'Our rewrite rules aren\'t inserted correctly on your server\'s config file.';

		const R_RULE_ERROR_APACHE = 'Our _pda folder is blocked by .htaccess. Please remove the file there.';

		const PDA_PRIVATE_LINK_SHORT_CODE_ERROR_MESSAGE = '[pda_private_link] Invalid attributes or values';

		const TEN_YEAR_IN_MINUTES = 5256000;

		const PDA_OPTION_PLUGIN_CHANGE_VERSION = 'pda_plugin_change_version';

		const PDA_DEFAULT_FAP = 'default';

		const PDA_FREE_PATH = 'prevent-direct-access/prevent-direct-access.php';

	}
}
