<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 5/18/18
 * Time: 14:37
 */

if (!class_exists('PDA_v3_DB')) {
    class PDA_v3_DB
    {
        private $db_version = '1.0';

        public function __construct()
        {
            global $wpdb;
            $this->db_version = get_option(PDA_v3_Constants::$db_version) === false ? '1.0' : get_option(PDA_v3_Constants::$db_version);
            $this->table_name = $wpdb->prefix . 'prevent_direct_access';
        }

        public function run()
        {
            $this->init();
            $this->version1_1();
            $this->version1_2();
            $this->version1_3();
            $this->version1_4();
            $this->version1_5();
            $this->version1_6();
            $this->version1_7();
            $this->version1_8();
            $this->version1_9();
        }

        public function uninstall()
        {
            global $wpdb;
            $table_name = $wpdb->prefix . 'prevent_direct_access';
            $wpdb->query("DROP TABLE IF EXISTS $table_name");
        }

        private function init()
        {
            global $wpdb;
            if ($wpdb->get_var("SHOW TABLES LIKE '$this->table_name'") != $this->table_name) {
                $charset_collate = $wpdb->get_charset_collate();

                $sql = "CREATE TABLE $this->table_name (
	    	ID mediumint(9) NOT NULL AUTO_INCREMENT,
	    	post_id mediumint(9) NOT NULL,
	    	time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	    	url varchar(55) DEFAULT '' NOT NULL,
	    	is_prevented tinyint(1) DEFAULT 1,
	    	UNIQUE KEY id (id)
	    ) $charset_collate;";

                require_once ABSPATH . 'wp-admin/includes/upgrade.php';
                dbDelta($sql);
                $this->db_version = '1.0';
                update_option( PDA_v3_Constants::$db_version, $this->db_version, 'no' );
            }
        }

        private function version1_1()
        {
            global $wpdb;
            if ($this->db_version === '1.0') {
                $charset_collate = $wpdb->get_charset_collate();

                $sql = "CREATE TABLE $this->table_name (
		    	hits_count mediumint(9) NOT NULL
		    ) $charset_collate;";

                require_once ABSPATH . 'wp-admin/includes/upgrade.php';
                dbDelta($sql);
                $this->db_version = '1.1';
                update_option( PDA_v3_Constants::$db_version, $this->db_version, 'no' );
            }
        }

        private function version1_2()
        {
            global $wpdb;
            if ($this->db_version === '1.1') {
                $charset_collate = $wpdb->get_charset_collate();

                $sql = "CREATE TABLE $this->table_name (
		    	limit_downloads mediumint(9)
		    ) $charset_collate;";

                require_once ABSPATH . 'wp-admin/includes/upgrade.php';
                dbDelta($sql);
                $this->db_version = '1.2';
                update_option( PDA_v3_Constants::$db_version, $this->db_version, 'no' );
            }
        }

        private function version1_3()
        {
            global $wpdb;
            if ($this->db_version === '1.2') {
                $charset_collate = $wpdb->get_charset_collate();
                $sql = "CREATE TABLE $this->table_name (
		    	expired_date BIGINT DEFAULT NULL
		    ) $charset_collate;";

                require_once ABSPATH . 'wp-admin/includes/upgrade.php';
                dbDelta($sql);
                $this->db_version = '1.3';
                update_option( PDA_v3_Constants::$db_version, $this->db_version , 'no' );
            }
        }

        private function version1_4()
        {
            global $wpdb;
            if ($this->db_version === '1.3') {
                $charset_collate = $wpdb->get_charset_collate();

                $sql = "CREATE TABLE $this->table_name (
		    	is_default tinyint(0) DEFAULT 0
		    ) $charset_collate;";

                require_once ABSPATH . 'wp-admin/includes/upgrade.php';
                dbDelta($sql);
                $this->db_version = '1.4';
                update_option( PDA_v3_Constants::$db_version, $this->db_version , 'no' );
            }
        }

        private function version1_5()
        {
            global $wpdb;
            if ($this->db_version === '1.4') {
                $charset_collate = $wpdb->get_charset_collate();
                $sql = "CREATE TABLE $this->table_name (
		    	ip_block varchar(200) DEFAULT '' NULL
		    ) $charset_collate;";

                require_once ABSPATH . 'wp-admin/includes/upgrade.php';
                dbDelta($sql);
                $this->db_version = '1.5';
                update_option( PDA_v3_Constants::$db_version, $this->db_version , 'no' );
            }
        }

        private function version1_6()
        {
            global $wpdb;
            if ($this->db_version === '1.5') {
                $charset_collate = $wpdb->get_charset_collate();
                $sql = "CREATE TABLE $this->table_name (
		    	type varchar(55) DEFAULT '',
		    	roles varchar(1000) DEFAULT '',
		    ) $charset_collate;";

                require_once ABSPATH . 'wp-admin/includes/upgrade.php';
                dbDelta($sql);
                $this->db_version = '1.6';
                update_option( PDA_v3_Constants::$db_version, $this->db_version , 'no' );
            }
        }

        private function version1_7() {
        	global $wpdb;
        	if ( $this->db_version === '1.6' ) {
        		$table_name = $this->table_name;
		        $sql = "ALTER TABLE $table_name CHANGE ID ID bigint(20) unsigned NOT NULL AUTO_INCREMENT";
		        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		        $wpdb->query($sql);
		        $this->db_version = '1.7';
		        update_option( PDA_v3_Constants::$db_version, $this->db_version , 'no' );
	        }
        }

	    private function version1_8() {
		    if ( $this->db_version !== '1.7' ) {
			    return;
		    }

		    $encrypt_key = get_option( 'pda_encryption_key', false );
		    if ( false === $encrypt_key ) {
			    $key     = strtoupper( Pda_v3_Gold_Helper::gen_random_str( 16 ) );
			    $updated = update_option( 'pda_encryption_key', $key );

			    $home_path = get_home_path();
			    if ( $updated && is_writable( $home_path ) ) {
				    $bk_file = path_join( $home_path, 'pda_enc.bk' );
				    if ( ! file_exists( $bk_file ) || ( file_exists( $bk_file ) && is_writable( $bk_file ) ) ) {
					    $fp_out = fopen( $bk_file, 'w' );
					    fwrite( $fp_out, $key );
					    fclose( $fp_out );
				    } else {
					    error_log( 'Can not create backup for encryption key' );
				    }
			    }
		    }

		    $this->db_version = '1.8';
		    update_option( PDA_v3_Constants::$db_version, $this->db_version, 'no' );
	    }

	    /**
	     * Force to remove "Check license expired" Cronjob.
	     */
	    private function version1_9() {
		    if ( $this->db_version !== '1.8' ) {
			    return;
		    }

		    $cron = new PDA_Cronjob_Handler();
		    $cron->unschedule_ls_cron_job();
		    $this->db_version = '1.9';
		    update_option( PDA_v3_Constants::$db_version, $this->db_version, 'no' );
	    }
    }
}
