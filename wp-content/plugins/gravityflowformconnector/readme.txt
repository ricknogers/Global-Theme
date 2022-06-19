=== Gravity Flow Form Connector Extension ===
Contributors: stevehenty
Tags: gravity forms, approvals, workflow
Requires at least: 4.4
Tested up to: 5.4.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create, update and link entries in Gravity Flow.

== Description ==

The Gravity Flow Form Connector Extension is an advanced extension for Gravity Flow.

Gravity Flow is a premium Add-On for [Gravity Forms](https://gravityflow.io/gravityforms)

= Requirements =

1. [Purchase and install Gravity Forms](https://gravityflow.io/gravityforms)
1. [Purchase and install Gravity Flow](https://gravityflow.io)
1. Wordpress 4.7+
1. Gravity Forms 2.1+
1. Gravity Flow 1.7+


= Support =
If you find any that needs fixing, or if you have any ideas for improvements, please get in touch:
https://gravityflow.io/contact/


== Installation ==

1.  Download the zipped file.
1.  Extract and upload the contents of the folder to /wp-contents/plugins/ folder
1.  Go to the Plugin management page of WordPress admin section and enable the 'Gravity Flow Form Connector Extension' plugin.

== Frequently Asked Questions ==

= Which license of Gravity Flow do I need? =
The Gravity Flow Form Connector Extension will work with any license of [Gravity Flow](https://gravityflow.io).


== ChangeLog ==

= 2.3 = | 2022-04-22
- Fixed an issue where Save and Continue links are broken in scenarios involving both Form Submission step and Gravity Forms Partial Entries feed.


= 2.2 = | 2022-04-09
- Added uninstall message on Gravity Forms Uninstall Page to clarify what is removed when Form Connector extension is uninstalled.
- Added extension icon.
- Fixed an issue where Form Submission Step would be stuck to 'Pending' with a Destination Form having Save and Continue enabled.
- API: Updated Form Submission step handling of access tokens that affects step status involving Save & Continue.


= 2.1 =
- Added support for Gravity Forms REST API v2.
- Fixed an issue with Field Mappings on Update Fields Step not fetching Source Form values.
- Fixed an issue with Field Mappings on New Entry, Update an Entry, and Form Submission steps not fetching all values.


= 2.0 =
- Fixed an issue with Field Mappings not displaying for User Input Action under Update an Entry Step
- Fixed an issue with Entry ID missing on Field Mappings for the Update Fields Step.


= 1.9 =
- Fixed Update an Entry step not displaying Key values on Field Mapping with Gravity Forms 2.5.
- Fixed Form Submission step not loading Field Mapping with Gravity Forms 2.5.
- Fixed some missing values from Field Mapping on New Entry, Update an Entry and Update Fields steps with Gravity Forms 2.5.


= 1.8 =
- Added new action gravityflowformconnector_post_new_entry to allow customized action after the New Entry step has been processed. Credit: The team at GravityWiz.
- Removed the entry_id from the choices available for field mapping as the Entry ID cannot be overridden. The ID can still be selected for mapping into field values.
- Fixed an issue where values of hidden and administrative fields could be overridden when an Update an Entry step follows a Form Submission step.
- Fixed an issue where New Entry mapping for Assignee Fields do not store the Assignee correctly on the Target Form.


= 1.7.4 =
- Added the gravityflowformconnector_new_entry_form and gravityflowformconnector_update_field_values_form filters.
- Fixed issue with merge tag evaluation that caused a fatal error involving certain conditional logic setup. Update to Gravity Flow 2.5.11 will also be required.


= 1.7.3 =
- Added gravityflowformconnector_update_entry_form filter which allows the form of entry for update to be customized/hydrated.
- Updated translations. Added Catalan and Arabic.

= 1.7.2 =
- Fixed an issue where the form submission step cannot be released if the payment method set in the target form is Stripe Checkout (Stripe add-on > 3.0).
- Fixed an issue with hidden field values mapped with form submission step not being stored into child entry correctly.
- Fixed an issue with Update An Entry local/remote processing that prevents updates when the form of selected entry does not match step settings target form.

= 1.7.1 =
- Fixed an issue with the form submission step when the target page contains the submit shortcode. Requires Gravity Flow 2.5.4.
- Updated translations.

= 1.7 =
- Updated help text on Update Fields step settings screen.
- Updated translations.
- Fixed the form display and submission processes for the Form Submission step running for both the parent and nested form when used with GP Nested Forms.

= 1.6 =
- Added support for the license key constant GRAVITY_FLOW_FORM_CONNECTOR_LICENSE_KEY.
- Updated Gravity_Flow_Step_Update_Entry::get_entry_lookup_search_criteria() to support processing merge tags. Credit: Jake Jackson.
- Deprecated Gravity_Flow_Step_Update_Entry::gravityflow_entry_lookup_sort_criteria()
- Deprecated Gravity_Flow_Step_Update_Entry::gravityflow_entry_lookup_search_criteria()

= 1.5 =
- Added a new step type, Update Fields, for mapping values from other entries into the current entry including support for lookup conditional logic.
- Fixed a conflict with ForGravity's Live Population Add-On.

= 1.4.2 =
- Added support for the form submission merge tag to be used in the confirmation for email assignees.
- Removed the discussion field from the available options for mapping on the Form submissions step.

= 1.4.1 =
- Fixed an issue with New/Update an Entry steps with mapped list field types not copying data.
- Fixed an issue with New/Update an Entry steps settings for list field types. Individual columns are no longer shown for selection.


= 1.4 =
- Added the Delete Entry step.
- Added the "gravityflowformconnector_{step_type}_use_choice_text" filter allowing the choice text to be returned instead of the choice values.
- Fixed an issue where a checkbox field (selected) mapped to a text field would return the choice text instead of the choice values.
- Fixed a PHP deprecation notice on PHP 7.2 with the Form Submission step.

= 1.3 =
- Added support for the token attribute to the {workflow_form_submission_url} and {workflow_form_submission_link} merge tags.
- Added the Assignee setting to the Update Entry step to allow the assignee to be selected for User Input and Approval actions.
- Fixed a misleading message at the top of the form for email assignees when the link is not valid.
- Fixed an issue with the Form Submission step where the role and email assignees can't complete the step.
- Fixed an issue with the Form Submission step where hidden and administrative fields may not get mapped.
- Updated Members 2.0 integration to use human readable labels for the capabilities. Requires Gravity Flow 1.8.1 or greater.


= 1.2.1 =
- Added support for steps extending Gravity_Flow_Step_Form_Submission
- Fixed an issue with the Parent-Child Forms extension where an invalid link message is displayed when the parent entry is on a step that is not a Form Submission step.

= 1.2 =
- Added the Store New Entry ID setting to the New Entry step settings.
- Added the gravityflowformconnector_update_entry_id filter to allow the target entry ID to be modified.
  Example:
  add_filter( 'gravityflowformconnector_update_entry_id', 'sh_gravityflowformconnector_update_entry_id', 10, 5);
  function sh_gravityflowformconnector_update_entry_id( $target_entry_id, $target_form_id, $entry, $form, $step ) {
      // Custom search for the target entry ID based on the value of field ID 4.
      $search_criteria['status'] = 'active';
      $search_criteria['field_filters'][] = array( 'key' => '2', 'value' => $entry['4'] );
      $entries = GFAPI::get_entries( $target_form_id, $search_criteria );
      // Return the ID of the first entry in the results.
      return $entries[0]['id'];
  }
- Added support for updating the same entry when the target and source forms are the same. Select Entry ID (Self) in the Entry ID field setting.
- Fixed an issue with the approval action of the Update Entry Step for entries created with the New Entry step.
- Fixed an issue with the field mappings which may affect some forms.
- Fixed an issue with the update step when triggered by a schedule or the expiration of a previous step where the approval or user input action does not complete.
- Fixed an issue with the update step where remote approval and user input steps can fail on some servers. Requires Gravity Flow 1.6.2-dev+.


= 1.1 =
- Added translations for Chinese (China) and Dutch (Netherlands).
- Added integration with the Gravity Flow Parent-Child Forms Extension; a parent form can now be selected for the 'Entry ID Field' setting on the 'Update an Entry' step.
- Added the Form Submission step.
- Fixed an issue with the value for choice based Poll, Quiz, and Survey fields in the new or updated entry.

= 1.0.1.2 =
- Added support for mapping the created_by field in the target entry.
- Fixed a fatal error which could occur when using the 'Update an Entry' step type and the 'Entry ID Field' setting was not configured.

= 1.0.1.1 =
- Added support for merge tag processing in the custom values fields of mappings.

= 1.0.1 =
- Added support for custom values in the mapping. Requires Gravity Flow 1.3.0.10.

= 1.0.0 =
All new!
