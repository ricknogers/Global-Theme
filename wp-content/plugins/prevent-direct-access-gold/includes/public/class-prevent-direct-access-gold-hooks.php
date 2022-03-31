<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 9/27/18
 * Time: 16:09
 *
 * @package pda_hooks
 */

if ( ! class_exists( 'PDA_Hooks') ) {
	/**
	 * Helper class to define the hooks existing in the Prevent Direct Access Gold plugin.
	 *
	 * Class PDA_Hooks
	 */
	class PDA_Hooks {
		/**
		 * Action before sending file to client.
		 *
		 * Code example: https://gist.github.com/bwps/5c5d75072ad7d76aa102bf193d31477c
		 *
		 * Params:
		 *  + array $_SERVER Server and execution environment information (http://php.net/manual/en/reserved.variables.server.php)
		 *
		 *  + array $link_data Containing more information about the clicked link
		 *
		 *  $data_original_link = array(
		 *
				'user_id' => (integer) The current user's id accessing the link
		 *
				'link_id' => (integer) The attachment id
		 *
				'link_type' => (string)  private_link or original_link
		 *
				'mime_type' => (string) The mime type
		 *
		        'file' => (string)  The absolute file path
		 *
			);
		 */
		const PDA_HOOK_BEFORE_SENDING_FILE = 'PDA_HOOK_BEFORE_SENDING_FILE';

		/**
		 * Action after protect attachment file.
		 *
		 * Code example: https://gist.github.com/bwps/0fef96af80529112119b25247dccef7e
		 *
		 * Params:
		 *  + int $post_id The attachment file's ID
		 */
		const PDA_HOOK_AFTER_PROTECT_FILE = 'pda_after_protected';

		/**
		 * Action after un-protect attachment file.
		 *
		 * Code example: https://gist.github.com/bwps/d27bc01f86198d954129f0cb7724f762
		 *
		 * Params:
		 *  + int $post_id The attachment file's ID
		 */
		const PDA_HOOK_AFTER_UN_PROTECT_FILE = 'pda_after_un_protected';

		/**
		 * Action after protect attachment file when file uploads
		 *
		 * Code example: https://gist.github.com/bwps/41a292743181ef59d087deb9b1cd5e55
		 *
		 * Params:
		 *  + int $post_id The attachment file's ID
		 */
		const PDA_HOOK_AFTER_PROTECT_FILE_WHEN_UPLOAD = 'pda_after_protect_file_when_upload';

		/**
		 * Action before protecting file
		 *
		 * Code example: https://gist.github.com/gaupoit/503bd20303e2a81aec670064465947f1
		 *
		 * Params:
		 *  + int $post_id The attachment file's ID
		 */
		const PDA_HOOK_BEFORE_PROTECT_FILE = 'pda_before_protect_file';

	}
}

