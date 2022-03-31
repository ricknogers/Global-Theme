<?php

if ( ! class_exists( 'PDA_Stats_Constants' ) ) {
	class PDA_Stats_Constants {
		const PDA_ANONYMOUS = 'anonymous';

		const PDA_PPW_TABLE = 'pda_password_protect_statistics';

		const PPW_DOMAIN = 'password-protect-wordpress';

		const PDA_DOMAIN = 'prevent-direct-access-gold';

		const PDA_PRIVATE_LINK = 'private_link';

		const YMESE_MESSAGES = [
			'PDA_PPWP_NEVER_ACTIVATE' => 'Please install and activate <a target="_blank" rel="noopener noreferrer" href="https://preventdirectaccess.com/pricing/">Prevent Direct Access Gold</a> or <a target="_blank" rel="noopener noreferrer" href="https://passwordprotectwp.com/pricing/">Password Protect WordPress Pro</a> plugin',
			'PDA_ADDON_STOLEN' => 'You didn\'t purchase this add-on with your <a target="_blank" rel="noopener noreferrer" href="https://preventdirectaccess.com/pricing/">Prevent Direct Access Gold</a> or <a target="_blank" rel="noopener noreferrer" href="https://passwordprotectwp.com/pricing/">Password Protect WordPress Pro</a>. Please <a target="_blank" rel="noopener noreferrer" href="https://preventdirectaccess.com/extensions/">do it now</a> or drop us an email at hello@PreventDirectAccess.com if you have any questions!'
		];

		const DB_VERSION = 'jal_db_version_stats';

		const SUPPORTED_BROWSERS = array( //phpcs:ignore
			array(
				'key'   => 'Firefox',
				'bname' => 'Mozilla Firefox',
				'ub'    => 'Firefox',
			),
			array(
				'key'   => 'OPR',
				'bname' => 'Opera',
				'ub'    => 'OPR',
			),
			array(
				'key'   => 'YaBrowser',
				'bname' => 'Yandex Browser',
				'ub'    => 'YaBrowser',
			),
			array(
				'key'   => 'Edge',
				'bname' => 'Microsoft Edge',
				'ub'    => 'Edge',
			),
			array(
				'key'   => 'SamsungBrowser',
				'bname' => 'Samsung Browser',
				'ub'    => 'SamsungBrowser',
			),
			array(
				'key'   => 'Chrome',
				'bname' => 'Google Chrome',
				'ub'    => 'Chrome',
			),
			array(
				'key'   => 'Safari',
				'bname' => 'Apple Safari',
				'ub'    => 'Safari',
			),
			array(
				'key'   => 'Netscape',
				'bname' => 'Netscape',
				'ub'    => 'Netscape',
			),
		);

		const PPWP_TABLE_VERSION = 'jal_db_version_pda_ppw_stats';

		const PPWP_ENTIRE_SITE = 'sitewide';

		const PPWP_PCP = 'pcp';

		const PPWP_AL = 'ppwp_al';

		const PPWP_NA = 'N/A';

		const LINK_TYPE = array( // phpcs:ignore
			'ORIGINAL' => 'Protected Link',
			'PRIVATE'  => 'Private Download Link',
		);
	}
}
