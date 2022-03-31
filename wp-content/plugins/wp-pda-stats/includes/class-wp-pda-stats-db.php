<?php

class Wp_Pda_Stats_Db {

	/**
	 * Password table version
	 *
	 * @var string
	 */
	protected $ppwp_table_version;

	/**
	 * WordPress Database Access Abstraction Object
	 *
	 * @var object
	 */
	protected $wpdb;

	/**
	 * Password table name
	 *
	 * @var string
	 */
	protected $ppwp_table_name;

	public function __construct() {
		global $wpdb;
		$this->wpdb               = $wpdb;
		$this->ppwp_table_version = $this->ppwp_get_table_version();
		$this->ppwp_table_name    = $wpdb->prefix . PDA_Stats_Constants::PDA_PPW_TABLE;
	}

	/**
	 * Create table for PDA Plugin
	 */
    public static function create_table() {

        global $wpdb;
        $jal_db_version = '1.0';
        $table_name = $wpdb->prefix . 'pda_hotlinking';
        if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
            //table is not created. you may create the table here.
            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE $table_name (
	    	ID mediumint(9) NOT NULL AUTO_INCREMENT,
	    	post_id mediumint(9) NOT NULL,
	    	domain varchar(100) DEFAULT '' NOT NULL,
	    	UNIQUE KEY id (id)
	    ) $charset_collate;";

            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta( $sql );
            add_option( PDA_Stats_Constants::DB_VERSION, $jal_db_version );
        }

        // Only run in version 1.0
	    self::add_column_to_table();
	    self::update_column_length();

        self::create_table_statistics();
    }

	/**
	 * Create table for Password Protect Wordpress Pro Plugin.
	 */
	public function create_table_for_ppwp() {
		$this->create_table_statistics_for_password();
		$this->ppwp_add_new_column( '1.0', '1.1', 'post_type varchar(50)' );
		$this->ppwp_add_new_column( '1.1', '1.2', 'redirect_url varchar(255)' );
		$this->ppwp_add_new_column( '1.2', '1.3', 'post_slug varchar(255)' );
		$this->ppwp_add_new_column( '1.3', '1.4', 'meta_data LONGTEXT' );
	}

	public static function add_column_to_table() {
		if ( '1.0' !== get_option( PDA_Stats_Constants::DB_VERSION ) ) {
			return ;
		}

		global $wpdb;
		$table = $wpdb->prefix . 'prevent_direct_access';

		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table'" ) !== $table ) {
			return;
		}

		if ( ! $wpdb->get_col( "SHOW COLUMNS FROM $table LIKE 'country'" ) ) {
			$wpdb->query( sprintf( "ALTER TABLE %s ADD country VARCHAR(255) DEFAULT '' NULL", $table ) );
		}
		if ( ! $wpdb->get_col( "SHOW COLUMNS FROM $table LIKE 'browser'" ) ) {
			$wpdb->query( sprintf( "ALTER TABLE %s ADD browser VARCHAR(255) DEFAULT '' NULL", $table ) );
		}
	}

    public static function update_column_length() {
    	if ( '1.0' !== get_option( PDA_Stats_Constants::DB_VERSION ) ) {
    		return ;
	    }

	    global $wpdb;
	    $table = $wpdb->prefix . 'prevent_direct_access';

	    if ( $wpdb->get_var( "SHOW TABLES LIKE '$table'" ) !== $table ) {
		    return;
	    }

	    if ( $wpdb->get_col( "SHOW COLUMNS FROM $table LIKE 'country'" ) ) {
		    $wpdb->query( sprintf( "ALTER TABLE %s CHANGE country country TEXT DEFAULT '' NULL", $table ) );
	    }

	    if ( $wpdb->get_col( "SHOW COLUMNS FROM $table LIKE 'browser'" ) ) {
		    $wpdb->query( sprintf( "ALTER TABLE %s CHANGE browser browser TEXT DEFAULT '' NULL", $table ) );
	    }

	    update_option( PDA_Stats_Constants::DB_VERSION, '1.1' );
    }

    public static function insert_tables( $post_id, $referer ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'pda_hotlinking';
        $data       = Wp_Pda_Stats_Db::get_post_id_db( $post_id );

        if ( empty( $data ) ) {
            $wpdb->insert(
                $table_name,
                array(
                    'post_id' => $post_id,
                    'domain'  => $referer
                )
            );
        } else {
            $domain_db     = $data[0]->domain;
            $arr_domain_db = explode( ';', $domain_db );
            if ( ! in_array( $referer, $arr_domain_db ) ) {
                array_push( $arr_domain_db, $referer );
            }
            $domain = join( ";", $arr_domain_db );
            $wpdb->update(
                $table_name,
                array(
                    'domain' => $domain
                ),
                array(
                    'post_id' => $post_id
                )
            );
        }

    }

    public static function get_post_id_db( $post_id ) {
        global $wpdb;
        $results = $wpdb->get_results( " SELECT * FROM {$wpdb->prefix}pda_hotlinking WHERE post_id = $post_id " );

        return $results;
    }

    public static function create_table_statistics() {
        global $wpdb;
        $jal_db_version_stats = '1.0';

        $table_name = $wpdb->prefix . 'prevent_direct_access_statistics';
        if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
            //table is not created. you may create the table here.
            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE $table_name (
	    	ID mediumint(9) NOT NULL AUTO_INCREMENT,
	    	link_id mediumint(9) NOT NULL,
	    	user_id mediumint(9) DEFAULT -1 NOT NULL,
	    	link_type varchar(50) DEFAULT '' NOT NULL,
	    	count mediumint(9) NOT NULL,
	    	can_view tinyint(0) DEFAULT 0,
	    	UNIQUE KEY id (id)
	    ) $charset_collate;";

            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta( $sql );
            add_option( 'jal_db_version_pda_stats', $jal_db_version_stats );
        }
    }

	public function create_table_statistics_for_password() {
		if ( $this->wpdb->get_var( "SHOW TABLES LIKE '$this->ppwp_table_name'" ) != $this->ppwp_table_name ) {
			//table is not created. you may create the table here.
			$charset_collate = $this->wpdb->get_charset_collate();
			$sql             = "CREATE TABLE $this->ppwp_table_name (
		        id mediumint(9) NOT NULL AUTO_INCREMENT,
		        username varchar(60),
		        post_id bigint(20) NOT NULL,
	            access_date bigint(20) NOT NULL,
	            password varchar(255) NOT NULL,
		        ip_address varchar(50) NOT NULL,
		        country varchar(50),
		        user_agent varchar(255),
	            is_valid tinyint(1) DEFAULT 1,
		        UNIQUE KEY id (id)
		    ) $charset_collate;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
			add_option( PDA_Stats_Constants::PPWP_TABLE_VERSION, $this->ppwp_table_version );
		}
	}

	/**
	 * Add new column for table
	 *
	 * @param string $old_version Old version.
	 * @param string $new_version New version.
	 * @param string $value       Column name and data type.
	 */
	public function ppwp_add_new_column( $old_version, $new_version, $value ) {
		if ( $this->ppwp_table_version === $old_version ) {
			$charset_collate = $this->wpdb->get_charset_collate();
			$sql             = "CREATE TABLE $this->ppwp_table_name ( $value ) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			$this->ppwp_table_version = $new_version;
			$this->ppwp_update_table_version( $this->ppwp_table_version );
		}
	}

	/**
	 * Update the Password table version
	 *
	 * @param string $version new version.
	 */
	public function ppwp_update_table_version( $version ) {
		update_option( PDA_Stats_Constants::PPWP_TABLE_VERSION, $version );
	}

	/**
	 * Get the Password table version
	 */
	private function ppwp_get_table_version() {
		$version = get_option( PDA_Stats_Constants::PPWP_TABLE_VERSION, false );

		return ! $version ? '1.0' : $version;
	}

	public static function drop_table_and_version() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'prevent_direct_access_statistics';
        $wpdb->query( "DROP TABLE IF EXISTS $table_name" );
        delete_option( 'jal_db_version_pda_stats' );
    }

}
