<?php
$lang = [

/**
 * Language File: English
 *
 * Table of Contents:
 * ------------------
 * - [GENERAL] global / navigation / alerts / error.php
 * - [SETTINGS] settings.php, website_settings.php, database_settings.php, db.php
 * - [LOGIN] admin/index.php
 * - [DASHBOARD] dashboard.php, delete_scent.php
 * - [USERS] users.php, edit_user.php
 * - [NOTES] notes.php, delete_note.php, edit_note.php
 * - [DEFECTS] defects.php, defekt.php, submit_defekt.php
 * - [FRONTEND] index.php
 */


// ===========================================================================================================================================================
// [GENERAL] global / navigation / alerts / error.php
// ===========================================================================================================================================================

// Universal Buttons
'btn_add' => 'Add',
'btn_save' => 'Save',
'btn_edit' => 'Edit',
'btn_send' => 'Send',
'btn_delete' => 'Delete',
'btn_cancel' => 'Cancel',
'btn_close' => 'Close',
'btn_generate' => 'Generate',
'btn_yes' => 'Yes',
'btn_no' => 'No',
'btn_confirm' => 'Confirm',
'btn_upload' => 'Upload',
'btn_download' => 'Download',
'btn_import' => 'Import',
'btn_export' => 'Export',

// Universal Pagination
'pagination_showing' => 'Showing',
'pagination_to' => 'to',
'pagination_of' => 'of',
'pagination_entries' => 'entries',
'pagination_prev' => 'Previous',
'pagination_next' => 'Next',
'pagination_none' => 'No entries found.',

// Navigation
'nav_dashboard' => 'Dashboard',
'nav_users' => 'Users',
'nav_notes' => 'Notes',
'nav_defects' => 'Defects',
'nav_settings' => 'Settings',
'nav_logout' => 'Logout',

// Placeholders / General
'placeholder_search' => 'Search...',
'placeholder_select' => 'Please select',
'placeholder_enter_value' => 'Enter value',
'placeholder_optional' => 'Optional',
'placeholder_required' => 'Required',

// error.php
'error_title' => 'Error',
'error_unknown' => 'An unknown error occurred.',
'error_back_dashboard' => 'Back to Dashboard',


// ===============================================================================================
// [SETTINGS] settings.php, website_settings.php, database_settings.php, db.php
// ===============================================================================================

'settings_page_title' => 'Settings',
'settings_section_business' => 'Business Settings',
'settings_section_experience' => 'Experience',
'settings_tab_website' => 'Website',
'settings_tab_database' => 'Database',
'settings_tab_security' => 'Security Settings',
'settings_tab_notification' => 'Notification',
'settings_tab_system' => 'System Tools & Maintenance',
'settings_tab_feedback' => 'Give Feedback',
'settings_button_cancel' => 'Cancel',
'settings_button_save' => 'Save',
'settings_load_error' => 'Error loading',
'settings_load_error_details' => 'Please check the file or reload the page.',
'settings_no_permission' => 'You do not have permission to view this page.',
'db_test_testing' => 'Testing connection...',
'db_test_error' => 'Connection attempt failed.',
'lang_file_missing' => '⚠️ Language file could not be loaded.',

'settings_error_title_required' => '❌ Site title cannot be empty.',
'settings_error_invalid_logo' => '❌ Invalid file format for logo.',
'settings_error_logo_upload' => '❌ Error uploading the logo.',
'settings_error_save_failed' => '❌ Failed to save settings.',
'settings_success_saved' => '✅ Website settings have been saved.',

// website_settings.php
'website_settings_heading' => 'Website Settings',
'site_title_label' => 'Site Title',
'meta_description_label' => 'Meta Description',
'site_logo_label' => 'Favicon / Logo',
'current_logo_hint' => 'Current logo:',
'language_label' => 'Language',
'german' => 'German',
'english' => 'English',
'french' => 'French',

// database_settings.php
'db_heading' => 'Database Settings',
'db_backup_title' => 'Create Backup',
'db_backup_description' => 'Create a manual backup of your current database.',
'db_backup_download' => 'Download Backup',
'db_import_title' => 'Import Backup',
'db_import_description' => 'Upload a .sql file to restore a backup.',
'db_import_button' => 'Import Backup',
'db_connection_title' => 'Connection Data (optional)',
'db_connection_description' => 'Only change this if you know what you are doing.',
'db_host_label' => 'Database Host',
'db_name_label' => 'Database Name',
'db_user_label' => 'User',
'db_pass_label' => 'Password',
'db_test_button' => 'Test Connection',

// db.php
'db_config_missing'    => 'No database configuration found. Please create db_config.json.',
'db_config_invalid'    => 'Invalid configuration. Please check db_config.json.',
'db_connection_failed' => 'Database connection failed.',


// ===============================================================================================
// [LOGIN] admin/index.php
// ===============================================================================================

'lang_code' => 'en',

'login_title'       => 'Admin Login',
'login_username'    => 'Username',
'login_password'    => 'Password',
'login_btn'         => 'Login',

'login_error_required' => 'Please fill in all fields.',
'login_error_invalid'  => 'Invalid username or password.',
'login_error_csrf'     => 'Invalid security token.',


// ===============================================================================================
// [DASHBOARD] dashboard.php, delete_scent.php
// ===============================================================================================

'dashboard_title' => 'Dashboard',
'dashboard_pretitle' => 'Overview',
'dashboard_overview' => 'Overview',
'dashboard_button_new_scent' => 'New Scent',
'dashboard_total_label' => 'Total',
'dashboard_men_label' => 'Men',
'dashboard_women_label' => 'Women',
'dashboard_unisex_label' => 'Unisex',

// Table
'dashboard_table_title' => 'Scents',
'dashboard_table_entries' => 'Scents',
'dashboard_table_search' => 'Search:',
'dashboard_table_column_no' => 'No.',
'dashboard_table_column_code' => 'Code',
'dashboard_table_column_inspired' => 'Inspired by',
'dashboard_table_column_gender' => 'Gender',
'dashboard_table_column_direction' => 'Fragrance Direction',
'dashboard_table_column_qrcode' => 'QR Code',
'dashboard_table_column_actions' => '',

'dashboard_table_pagination_info' => 'Showing <span>{start}</span> to <span>{end}</span> of <span>{total}</span> entries',
'dashboard_table_pagination_none' => 'No entries found.',

'dashboard_generate_qrcode' => 'Generate QR Code',

// Modal Add
'dashboard_modal_add_title' => 'Add New Scent',
'dashboard_modal_add_code' => 'Code (optional)',
'dashboard_modal_add_inspired_by' => 'Inspired by',
'dashboard_modal_add_gender' => 'Gender',
'dashboard_modal_add_description' => 'Description / Fragrance direction',
'dashboard_modal_add_pyramid_switch' => 'Use fragrance pyramid?',
'dashboard_modal_scent_pyramid' => 'Fragrance Pyramid',
'dashboard_modal_add_top_notes' => 'Top notes',
'dashboard_modal_add_heart_notes' => 'Heart notes',
'dashboard_modal_add_base_notes' => 'Base notes',
'dashboard_modal_add_general_notes_title' => 'General notes (without pyramid)',
'dashboard_modal_add_general_notes_label' => 'Fragrance notes',
'dashboard_modal_add_attributes_title' => 'Attributes (% values)',
'dashboard_modal_add_type' => 'Fragrance type',
'dashboard_modal_add_type_add' => '+ Add',
'dashboard_modal_add_style' => 'Style',
'dashboard_modal_add_season' => 'Season',
'dashboard_modal_add_occasion' => 'Occasion',

// Modal Edit
'dashboard_modal_edit_title' => 'Edit Scent',
'dashboard_modal_edit_button_save' => 'Save Scent',

// Modal Delete
'dashboard_modal_delete_title' => 'Are you sure?',
'dashboard_modal_delete_text' => 'Do you really want to delete the scent<br>%s<br>?',

// Labels (Style / Season / Occasion)
'label_men' => 'Men',
'label_women' => 'Women',
'label_classic' => 'Classic',
'label_modern' => 'Modern',

'label_spring' => 'Spring',
'label_summer' => 'Summer',
'label_autumn' => 'Autumn',
'label_winter' => 'Winter',

'label_daily' => 'Daily',
'label_leisure' => 'Leisure',
'label_evening' => 'Evening',
'label_work' => 'Work',
'label_going_out' => 'Going out',
'label_sport' => 'Sport',

// delete_scent.php
'scent_delete_no_permission' => 'You do not have permission to delete this scent.',
'scent_delete_success'       => 'The scent <strong>%s – %s</strong> was successfully deleted.',
'scent_delete_not_found'     => 'The selected scent could not be found.',


// ===============================================================================================
// [BENUTZER] users.php, edit_user.php
// ===============================================================================================

'users_title' => 'Users',
'users_total_count' => '1–{count} of {count} users',

'users_add_button' => 'Add User',
'users_add_title' => 'Add New User',
'users_edit_title' => 'Edit User',
'users_delete_confirm' => 'Really delete user?',
'users_no_results' => 'No users found.',

// Form Fields
'users_label_role' => 'Role',
'users_label_firstname' => 'First name',
'users_label_lastname' => 'Last name',
'users_label_username' => 'Username',
'users_label_email' => 'Email',
'users_label_password' => 'Password',
'users_label_new_password' => 'New password (optional)',

// Roles
'users_role_user' => 'User',
'users_role_editor' => 'Editor',
'users_role_admin' => 'Admin',

// edit_user.php
'user_edit_no_permission'   => 'You do not have permission to edit users.',
'user_edit_username_exists' => 'Username already exists.',
'user_edit_success'         => 'User was successfully updated.',
'user_edit_missing_fields'  => 'Please fill out all fields.',


// ===============================================================================================
// [FRAGRANCE NOTES] notes.php, edit_note.php, delete_note.php
// ===============================================================================================

// notes.php
'notes_page_title' => 'Manage Fragrance Notes',
'notes_table_title' => 'Fragrance Notes',
'notes_table_column_number' => '#',
'notes_table_column_name' => 'Name',
'notes_table_column_actions' => 'Actions',
'notes_search_label' => 'Search:',
'notes_table_pagination_info' => 'Showing <span>{start}</span> to <span>{end}</span> of <span>{total}</span> entries',
'notes_table_pagination_none' => 'No entries found.',
'notes_no_results' => 'No fragrance notes found.',

'notes_add_button' => 'Add New Note',
'notes_add_title' => 'Add Fragrance Note',
'notes_add_label_name' => 'Name',
'notes_add_placeholder_name' => 'e.g. Vanilla',
'notes_add_label_image' => 'Image (optional)',
'notes_add_hint_image' => 'Recommended format: webp',

'notes_edit_title' => 'Edit Fragrance Note',
'notes_edit_label_current_image' => 'Current Image:',
'notes_edit_label_replace_image' => 'Upload New Image (optional)',
'notes_edit_hint_replace_image' => 'Only .webp, max. 2 MB',

'notes_delete_confirm_title' => 'Are you sure?',
'notes_delete_confirm_text' => 'Do you really want to delete the note<br><strong>{name}</strong>?',

// delete_note.php
'note_delete_success' => 'The note "%s" was successfully deleted.',
'note_delete_no_permission' => 'You do not have permission for this action.',

// edit_note.php
'note_edit_no_permission' => 'You do not have permission for this action.',
'note_edit_missing_name'  => 'Please enter a name for the note.',
'note_edit_name_exists'   => 'The name "%s" is already taken.',
'note_edit_success'       => 'The note "%s" was successfully updated.',


// ===============================================================================================
// [DEFECTS] defects.php, defekt.php, submit_defekt.php
// ===============================================================================================

// defects.php
'defects_page_title' => 'Manage Reports',
'defects_table_title' => 'Error Reports',

// Filter
'defects_label_filter' => 'Filter by problem:',
'defects_filter_all' => 'All',
'defects_filter_spray' => 'Spray mechanism broken',
'defects_filter_leak' => 'Bottle leaked',
'defects_filter_wrong_scent' => 'Wrong scent received',
'defects_filter_other' => 'Other',

// Table columns
'defects_column_number' => '#',
'defects_column_date' => 'Date',
'defects_column_scent' => 'Scent',
'defects_column_problem' => 'Problem',
'defects_column_contact' => 'Name / E-mail',
'defects_column_message' => 'Message',
'defects_column_image' => 'Image',

// Image display
'defects_image_placeholder' => '—',

// Pagination / empty states
'defects_pagination_info' => 'Showing <span>{start}</span> to <span>{end}</span> of <span>{total}</span> entries',
'defects_pagination_none' => 'No entries found.',
'defects_no_results' => 'No reports found.',


// defekt.php
'defect_title' => 'Report Defective Spray',
'defect_name' => 'Your Name',
'defect_email' => 'E-mail Address',
'defect_scent' => 'Scent Code or Name',
'defect_scent_placeholder' => 'e.g. M-021 or Sauvage',
'defect_problem' => 'Problem',
'defect_option_spray' => 'Spray mechanism broken',
'defect_option_leak' => 'Bottle leaked',
'defect_option_wrong' => 'Wrong scent received',
'defect_option_other' => 'Other',
'defect_note' => 'Additional note (optional)',
'defect_upload' => 'Upload photo (optional)',
'defect_submit' => 'Submit report',

// Confirmation & error
'defect_error_required' => 'Please fill out all required fields.',
'defect_success_title' => 'Thank you!',
'defect_success_message' => 'Your report was successfully submitted. We will take care of it as soon as possible.',
'defect_back_home' => 'Back to home page',
'defect_missing_fields' => 'Please fill out all required fields.',


// ===============================================================================================
// [HOMEPAGE] index.php
// ===============================================================================================

'index_title' => 'Fragrances',
'index_total' => 'Total',
'index_men' => 'Men',
'index_women' => 'Women',
'index_unisex' => 'Unisex',

// Buttons
'index_btn_whatsapp' => 'Chat on WhatsApp',
'index_btn_instagram' => 'Chat on Instagram',
'index_btn_reset' => 'Reset',

// Search / Filter
'index_search_placeholder' => 'Search by code, name, gender…',

// Gender Filter
'index_filter_gender' => 'All Genders',
'index_gender_men' => 'Men',
'index_gender_women' => 'Women',
'index_gender_unisex' => 'Unisex',

// Season Filter
'index_filter_season' => 'All Seasons',
'index_season_spring' => 'Spring',
'index_season_summer' => 'Summer',
'index_season_autumn' => 'Autumn',
'index_season_winter' => 'Winter',

// Occasion Filter
'index_filter_occasion' => 'All Occasions',
'index_occasion_out' => 'Going out',
'index_occasion_evening' => 'Evening',
'index_occasion_free' => 'Leisure',
'index_occasion_daily' => 'Daily',
'index_occasion_work' => 'Work',

// Table headers
'index_table_code' => 'Code',
'index_table_inspired_by' => 'Inspired by',
'index_table_gender' => 'Gender',
'index_table_loading' => 'Loading data...',
'index_table_no_results' => 'No matching fragrances found.',
'index_table_error' => 'Error loading data',

// Modal
'index_modal_type' => 'Fragrance type',
'index_modal_pyramid_title' => 'Fragrance Pyramid',
'index_modal_notes_title' => 'Fragrance Notes',
'index_modal_charts_title' => 'Charts',

// Modal Chart Categories
'index_modal_chart_type' => 'Type',
'index_modal_chart_style' => 'Style',
'index_modal_chart_season' => 'Season',
'index_modal_chart_occasion' => 'Occasion',

// Pyramid Labels
'index_modal_pyramid_top' => 'Top notes',
'index_modal_pyramid_heart' => 'Heart notes',
'index_modal_pyramid_base' => 'Base notes',

// Footer
'index_footer_problem' => 'Report a problem',
'index_footer_sponsor' => 'Sponsor',
'index_footer_imprint' => 'Imprint',
'index_footer_rights' => 'All rights reserved.',


