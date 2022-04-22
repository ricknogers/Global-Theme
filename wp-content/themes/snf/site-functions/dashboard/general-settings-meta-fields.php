<?php


add_action('admin_init', 'my_general_section', 'wp_enqueue_media');
function my_general_section()
{
    add_settings_section(
        'my_settings_section', // Section ID
        'Group Global Settings', // Country Specific Information
        'snf_global_callback', // Callback
        'general' // What Page?  This makes the section show up on the General Settings Page
    );
    add_settings_field( // Option 1
        'linkedin_url', // Option ID | Linkedin URL
        'Company LinkedIn URL', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'my_settings_section', // Name of our section
        array( // The $args
            'linkedin_url' // Should match Option ID
        )
    );
    add_settings_field( // Option 1
        'facebook_url', // Option ID | Facebook URL
        'Company Facebook URL', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'my_settings_section', // Name of our section
        array( // The $args
            'facebook_url' // Should match Option ID
        )
    );
    add_settings_field( // Option 2
        'company_name', // Option ID | Country URL
        'Company Name', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'my_settings_section', // Name of our section
        array( // The $args
            'company_name' // Should match Option ID
        )
    );

    add_settings_field( // Option 3
        'corporate_tag_line', // Option ID | Country URL
        'Corporate Tag Line', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'my_settings_section', // Name of our section
        array( // The $args
            'corporate_tag_line' // Should match Option ID
        )
    );
    add_settings_field( // Option 3
        'default_contact_email_address', // Option ID | Country URL
        'Default Contact Email Address ', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'my_settings_section', // Name of our section
        array( // The $args
            'default_contact_email_address' // Should match Option ID
        )
    );
    add_settings_field( // Option 3
        'secondary_tag_line', // Option ID | Country URL
        'Secondary Tag Line ', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'my_settings_section', // Name of our section
        array( // The $args
            'secondary_tag_line' // Should match Option ID
        )
    );
	add_settings_field( // Option 3
		'snf_esg_update_link', // Option ID | Country URL
		'SNF ESG Document Link ', // Label
		'my_textbox_callback', // !important - This is where the args go!
		'general', // Page it will be displayed (General Settings)
		'my_settings_section', // Name of our section
		array( // The $args
			'snf_esg_update_link' // Should match Option ID
		)
	);
	add_settings_field( // Option 3
		'snf_esg_link_title', // Option ID | Country URL
		'SNF ESG Title ', // Label
		'my_textbox_callback', // !important - This is where the args go!
		'general', // Page it will be displayed (General Settings)
		'my_settings_section', // Name of our section
		array( // The $args
			'snf_esg_link_title' // Should match Option ID
		)
	);
	add_settings_field( // Option 3
		'snf_code_of_conduct', // Option ID | Country URL
		'Code of Conduct Link', // Label
		'my_textbox_callback', // !important - This is where the args go!
		'general', // Page it will be displayed (General Settings)
		'my_settings_section', // Name of our section
		array( // The $args
			'snf_code_of_conduct' // Should match Option ID
		)
	);
	register_setting('general', 'snf_code_of_conduct', 'esc_attr');
	register_setting('general', 'snf_esg_link_title', 'esc_attr');
	register_setting('general', 'snf_esg_update_link', 'esc_attr');
    register_setting('general', 'default_contact_email_address', 'esc_attr');
    register_setting('general', 'corporate_tag_line', 'esc_attr');
    register_setting('general', 'linkedin_url', 'esc_attr');
    register_setting('general', 'facebook_url', 'esc_attr');
    register_setting('general', 'company_name', 'esc_attr');
    register_setting('general', 'secondary_tag_line', 'esc_attr');
}

function snf_subsididary_callback()
{ // Section Callback
    echo '<p>Include your Company Information:</p>';
}

function my_textbox_callback($args)
{  // LinkedIn Callback
    $option = get_option($args[0]);
    echo '<input type="text" id="' . $args[0] . '" name="' . $args[0] . '" value="' . $option . '" />';
}