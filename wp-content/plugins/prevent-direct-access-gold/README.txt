=== Prevent Direct Access Gold - Protect WordPress Files ===
Contributors: gaupoit, rexhoang, wpdafiles, buildwps
Donate link: https://preventdirectaccess.com/pricing/?utm_source=wordpress&utm_medium=plugin&utm_campaign=donation
Tags: protect uploads, file protection, media files, downloads, secure downloads
Requires at least: 4.7
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tested up to: 5.7
Stable tag: 5.4

A simple way to prevent search engines and the public from indexing and accessing your files without complex user authentication.

== Description ==

Prevent Direct Access (PDA) provides a simple solution to protect your WordPress files as well as prevent Google, other search engines and unwanted users from indexing and stealing your hard-to-produce ebooks, documents, and videos.


== Changelog ==

= 3.3.2: May 31, 2021 =

- [Improvement] Provide options for choosing product types when inputting a new license

- [Bug Fix] “Force Downloads” feature doesn’t support media files

- [Improvement] Support to create private links programmatically

- [Improvement] Remove Error log when installing plugin

= 3.3.1: Mar 16, 2021 =

- [New Feature] File encryption

= 3.3.0: Dec 04, 2020 =

- [Improvement] Compatible with WPML

- [Improvement] Compatible with Polylang

- [Bug Fix] Minor bug with numerous PDF thumbnails

- [Bug Fix] Show error message when enabling raw URLs

- [Bug Fix] Error when moving large files

= 3.2.0: Jun 29, 2020 =

- [Improvement] Require Lite version

- [Improvement] Integrates with PPWP Pro – Support raw URLs

- [Improvement] Show warning message when deactivating plugin

- [Bug Fix] PDF files’ thumbnails disappear after protection

- [Bug Fix] Detect wrong .htaccess files when WP is installed in subdirectory

= 3.1.6: May 15, 2020 =

- [Improvement] Integrate with nMedia User File Uploader plugin

- [Bug Fix] get_post_meta doesn’t work when enabling “Auto-protect New File Uploads” option

= 3.1.5: Mar 24, 2020 =

- [Improvement] Integrate with WPML plugin

- [Improvement] Show warning message if file does not exist

- [Bug Fix] PPWP Integration doesn’t work with Feed Them Gallery

- [Bug Fix] Show 403 error message when enabling “image hotlinking”

- [Bug Fix] Original image size become accessible after editing

- [Bug Fix] Offloaded files can not be unprotected/protected

= 3.1.4: Feb 21, 2020 =

- [Improvement] Handle big image protection with WordPress 5.3

- [Bug Fix] Search and replace feature doesn’t work with Cover block

= 3.1.3: Feb 14, 2020 =

- [New feature] Grant access to protected files if users had visited certain pages – AR extension

- [New feature] Integrate with PPWP Pro to protect files embedded in content

- [Bug Fix] Show WordPress database error when accessing private link

- [Bug Fix] Media files having no author are accessible

= 3.1.2.8: Feb 24, 2020 =

- [Bug Fix] Remove unnecessary scripts when loading content

= 3.1.2.7: Dec 23, 2019 =

- [Improvement] Update function to check plugin update to version 4.8

= 3.1.2.6: Nov 22, 2019 =

- [Bug Fix] Checking license expiry date works improperly

- [Bug Fix] Update htaccess rules for sites that WordPress is installed in subdirectory

- [Improvement] Add Refresh button under License tab

- [Improvement] Display Expiry date under License tab

- [Improvement] Support to protect Korean, Chinese, Japan filename

- [Improvement] Improve UI to compatible with WP 5.3

- [Improvement] Allow File’s author to access his/her file by default

- [Improvement] Show notification for unavailable automatic update

= 3.1.2.5: Nov 13, 2019 =

- [Bug Fix] Fix warning message when users update WordPress to 5.3

- [Bug Fix] Fix add_sub_menu notice

- [Bug Fix] Update file URL under Grid View of WordPress 5.3

= 3.1.1.2: May 30, 2019 =

- [BugFix] File title is auto-updated wrongly after protected/unprotected

- [BugFix] S&R stops working if the protected files are inserted after the non-protected ones

- [BugFix] S&R makes external links stop working

= 3.1.1.1: May 28, 2019 =

- [Hotfix] PHP function getallheaders undefined in some NGINX and PHP-FPM servers

- [BugFix] Un-comment codes to automatically update PDA Membership Integration and PDA Access Restriction outdated versions

= 3.1.1: May 27, 2019 =

- [Improvement] PDA Gold: improve file protection popup loading

- [Improvement] Improve “Migrate to ver 3.0” notification when installing plugin

- [BugFix] Selected icon disappears under Add Media popup

- [Conflict] Roles disappear in current develop branch because of the conflict with Enhance Media Library plugin

- [BugFix] Can’t see images in thumbnails and attachment pages when enabling “Keep raw URLs”

- [BugFix] Search & Replace displays only full size with Gutenberg editor

- [Improvement] block access to _pda folder when Raw URL option is enabled doesn’t work on multisite

- [BugFix] “Remove Data Upon Uninstall” seem doesn’t work on multisite

- [BugFix] All shared options should only appear on main site on multisite

- [BugFix] Fix FAP under popup displays wrongly as “No one” on multisite

- [BugFix] Block indexing by X-Robots-Tag HTTP header doesn’t work with mp3, mp4

- [BugFix] Fix cookie header issue on GoDaddy servers

- [BugFix] Can’t save if “Protect new file uploads by these user roles only” is enabled

- [Improvement] Display (no title) page under Search & Replace and Customize No Access Page

- [Improvement] Should display General tab after checking .htaccess file successfully

= 3.1.0: May 06, 2019 =

- [Feature] Resolve the coupling between PDA Gold and main extensions – Architecture

- [Feature] Integrate with BuddyPress & bbPress extension

- [Feature] Block access to _pda folder when Raw URL option is enabled

= 3.0.25.5: Apr 21, 2019 =

- [Conflict] Prevent Direct Access section on “Add media” cannot work in Front End editors

- [Improvement] PDA Gold: Retain the original filename when downloading via Raw URLs

= 3.0.25.4: Apr 02, 2019 =

- [Improvement] PDA Gold: Improve function search for “No access page”

- [Improvement] PDA Gold: Improve validation of Custom link under “No Access” Page

- [Improvement] PDA Gold: Redirect to reviews page when clicking on “Let’s do it” button

- [BugFix] PDA Gold: Fix weird UI bug in Add Media modal

- [BugFix] IP Block: Blacklist these IP addresses not work when IP is format 127.*.*.*

= 3.1.25.3: Mar 22, 2019 =

- [Feature] PDA Gold: Integrate file protection with PayPal & Mailchimp

= 3.0.25.2: Mar 19, 2019 =

- [Hotfix] Improve deletion of expired private links

= 3.0.25.1: Mar 13, 2019 =

- [HotFix] Add patch to WordPress check option

= 3.0.25: Mar 12, 2019 =

- [Feature] Redirect users back to protected files after login

- [Feature] Allow users to input a custom URL for No Access page

- [Improvement] Improve Protection Control feature

- [Improvement] Update UI Settings Page

- [Bug] Get Lucky button doesn’t work

- [Bug] Private Link Prefix exist character \ or / then private link not work

- [Bug] Should auto-update file URL once file is protected/unprotected

- [Bug] Migration issues with FAP (Need to release PDA Gold v2 version 2.9.3.5)

- [Bug] Can save invalid link under “No Access Page” option

= 3.0.24.10: Mar 04, 2019 =

- [HotFix] Fix wp_option logic when saving boolean values

= 3.0.24.9: Mar 01, 2019 =

- [Improvement] Add hook for PDA Videos to prevent download manager software

= 3.0.24.8: Feb 27, 2019 =

- [Improvement] Use wp_option to check data migration

= 3.0.24.7: Feb 25, 2019 =

- [Hotfix] Logic checking for data migration from version 2.x.x

= 3.0.24.6: Jan 22, 2019 =

- [Improvement] Using custom function to get mime type

= 3.0.24.5: Jan 16, 2019 =

- [Feature] Added a public service in PDA Gold to fetch the protected file

- [Feature] PDA Memberships: Separate custom membership from the default FAP dropdown

- [Feature] PDA Gold – IP Block: protect all files on a selected folder

= 3.0.24.4: Dec 26, 2018 =

- [BugFix] Add debug log for $_SERVER object

= 3.0.24.3: Dec 26, 2018 =

- [BugFix] Add debug log when streaming the video


= 3.0.24.2: Dec 21, 2018 =

- [BugFix] Super Administrator cannot apply control protection

= 3.0.24.1: Dec 21, 2018 =

- [BugFix] Fix cannot set default user control protect’s value

= 3.0.24: Dec 19, 2018 =

- [Feature] Settings option to specify who can change the file protection

- [Improvement] When the media file is HTML type, the protected or private links should show in the browser. Now it’s automatically downloaded by browsers.

- [Improvement] allow “Choose custom roles & memberships” FAP on “Add Media”

- [Improvement] Change the Protection file component when the file is backing up

- [BugFix] Auto-update unprotected URLs in content – not full file URLs

- [BugFix] The total number of protected and unprotected files isn’t equal the number of all files

- [Improvement] Enhance 404 redirection

- [BugFix] iFAP displays as Custom Memberships even though No role is chosen

= 3.0.23.1: Dec 14, 2018 =

- [BugFix] Unprotect files by bulk action does not update the post meta

= 3.0.23: Dec 07, 2018 =

- [Feature] Auto-activate license on sub-sites

- [Feature] Protect file uploads by user roles does not apply for Super Admin at normal sites

- [Improvement] Choose “Admin users” as the default option of FAP in multisite mode

= 3.0.22: Nov 30, 2018 =

- [Improvement] Better license handling

- [Feature] Add “Protect” button on file actions

- [Feature] Change download links to S3 Signed URLs once synced/offloaded

- [Improvement] Choose “Admin users” is the default setting option of FAP

- [Improvement] Show warning notification when PHP’s version is lower than 5.5

- [BugFix] PDA Gold: FAP seem doesn’t work in multisite mode

- [Improvement] PDA logo in WordPress menu

= 3.0.21.5: Nov 22, 2018 =

- [BugFix] Cannot protect/un-protect from media grid view

= 3.0.24.4: Nov 22, 2018 =

- [BugFix] Do not cache for streaming video

= 3.0.24.3: Nov 21, 2018 =

- [Improvement] Clear ob flush cache

= 3.0.21.2: Nov 16, 2018 =

- [HotFix] Update the background process’s name to remove duplication

= 3.0.21.1: Nov 08, 2018 =

- [BugFix] Replace nonce attribute by wp_nonce_field function to fix the bug cannot get the nonce value in chrome (using nonce attribute)

= 3.0.21: Nov 06, 2018 =**version 3.0.21: November 6th, 2018**

- [Feature] Auto protect files upload by certain user roles

= 3.0.20.1: Oct 29, 2018 =

- [BugFix] Cannot get the backup protection when re-activate the plugin

= 3.0.20: Oct 26, 2018 =

-[Improvement] Add force download option

- [Improvement] Revamp setting UI and file status on media file

- [BugFix] Cannot show some pages or posts

= 3.0.19.11: Oct 19, 2018 =

- [Improvement] Protected files should have the red shadow borders with other Custom Post Types

- [Improvement] Add hook before running the file status.

- [Improvement] Handle errors – exception and show the error message to client with PDA S3 Integration

= 3.0.19.10: Oct 17, 2018 =

- [Hotfix] Integrate with old pda-s3 plugin

= 3.0.19.9: Oct 16, 2018 =

- [Improvement] Add hook before and after protect file

- [Improvement] When un-protect file, DO NOT unsync file

- [BugFix] Protected files don’t have the red shadow borders

- [BugFix] Private links don’t work when Protect Videos extension is enabled

- [Improvement] Showing purchased add-ons on license tab

- [Improvement]Hide File Access Permission (status button and tab) if the file is deleted from Server

= 3.0.19.8: Oct 15, 2018 =

- [Improvement] Generate pot file

= 3.0.19.7: Oct 9, 2018 =

- [BugFix]: Private links for mp3 files cannot play
