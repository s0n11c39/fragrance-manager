<?php
$lang = [

/**
 * Sprachdatei: Deutsch
 *
 * Inhaltsverzeichnis:
 * -------------------
 * - [ALLGEMEIN] global / navigation / alerts / error.php
 * - [EINSTELLUNGEN] settings.php, website_settings.php, database_settings.php, db.php
 * - [LOGINBEREICH] admin/index.php
 * - [DASHBOARD] dashboard.php, delete_scent.php
 * - [BENUTZER] users.php, edit_user.php
 * - [DUFTNOTEN] notes.php, edit_note.php, delete_note.php
 * - [DEFEKTE] defects.php, defekt.php, submit_defekt.php
 * - [STARTSEITE] index.php
 */



    // ===============================================================================================
    // [ALLGEMEIN] global / navigation / alerts / error.php
    // ===============================================================================================

    // Universal Buttons
    'btn_add'       => 'Hinzufügen',
    'btn_save'      => 'Speichern',
    'btn_edit'      => 'Bearbeiten',
    'btn_send'      => 'Senden',
    'btn_delete'    => 'Löschen',
    'btn_cancel'    => 'Abbrechen',
    'btn_close'     => 'Schließen',
    'btn_generate'  => 'Generieren',
    'btn_yes'       => 'Ja',
    'btn_no'        => 'Nein',
    'btn_confirm'   => 'Bestätigen',
    'btn_upload'    => 'Hochladen',
    'btn_download'  => 'Herunterladen',
    'btn_import'    => 'Importieren',
    'btn_export'    => 'Exportieren',

    // Universal Pagination
    'pagination_showing'   => 'Zeige',
    'pagination_to'        => 'bis',
    'pagination_of'        => 'von',
    'pagination_entries'   => 'Einträgen',
    'pagination_prev'      => 'Zurück',
    'pagination_next'      => 'Weiter',
    'pagination_none'      => 'Keine Einträge gefunden.',

    // Navigation
    'nav_dashboard' => 'Dashboard',
    'nav_users'     => 'Benutzer',
    'nav_notes'     => 'Duftnoten',
    'nav_defects'   => 'Defekte',
    'nav_settings'  => 'Einstellungen',
    'nav_logout'    => 'Abmelden',

    // Platzhalter / allgemein
    'placeholder_search'        => 'Suchen...',
    'placeholder_select'        => 'Bitte auswählen',
    'placeholder_enter_value'   => 'Wert eingeben',
    'placeholder_optional'      => 'Optional',
    'placeholder_required'      => 'Pflichtfeld',

    // error.php
    'error_title'           => 'Fehler',
    'error_unknown'         => 'Ein unbekannter Fehler ist aufgetreten.',
    'error_back_dashboard'  => 'Zurück zum Dashboard',


    // ===============================================================================================
    // [EINSTELLUNGEN] settings.php, website_settings.php, database_settings.php, db.php
    // ===============================================================================================

    // settings.php
    'settings_page_title'         => 'Einstellungen',
    'settings_section_business'   => 'Business Einstellungen',
    'settings_section_experience' => 'Erlebnis',
    'settings_tab_website'        => 'Website',
    'settings_tab_database'       => 'Datenbank',
    'settings_tab_security'       => 'Sicherheitseinstellungen',
    'settings_tab_notification'   => 'Benachrichtigung',
    'settings_tab_system'         => 'System-Tools & Wartung',
    'settings_tab_feedback'       => 'Feedback geben',
    'settings_button_cancel'      => 'Abbrechen',
    'settings_button_save'        => 'Speichern',
    'settings_load_error'         => 'Fehler beim Laden von',
    'settings_load_error_details' => 'Bitte überprüfe die Datei oder lade die Seite neu.',
    'settings_no_permission'      => 'Du hast keine Berechtigung, diese Seite zu sehen.',
    'settings_error_title_required' => '❌ Der Seitentitel darf nicht leer sein.',
    'settings_error_invalid_logo'  => '❌ Ungültiges Dateiformat für das Logo.',
    'settings_error_logo_upload'   => '❌ Fehler beim Hochladen des Logos.',
    'settings_error_save_failed'   => '❌ Fehler beim Speichern der Einstellungen.',
    'settings_success_saved'       => '✅ Website-Einstellungen wurden gespeichert.',
    'lang_file_missing'            => '⚠️ Sprachdatei konnte nicht geladen werden.',
    'db_test_testing'              => 'Verbindung wird getestet...',
    'db_test_error'                => 'Fehler beim Verbindungsversuch.',

    // website_settings.php
    'website_settings_heading' => 'Website Einstellungen',
    'site_title_label'         => 'Seitentitel',
    'meta_description_label'  => 'Meta-Beschreibung',
    'site_logo_label'         => 'Favicon / Logo',
    'current_logo_hint'       => 'Aktuelles Logo:',
    'language_label'          => 'Sprache',
    'german'                  => 'Deutsch',
    'english'                 => 'Englisch',
    'french'                  => 'Französisch',

    // database_settings.php
    'db_heading'              => 'Datenbank-Einstellungen',
    'db_backup_title'         => 'Backup erstellen',
    'db_backup_description'   => 'Erstelle ein manuelles Backup deiner aktuellen Datenbank.',
    'db_backup_download'      => 'Backup herunterladen',
    'db_import_title'         => 'Backup importieren',
    'db_import_description'   => 'Lade eine .sql-Datei hoch, um ein Backup wiederherzustellen.',
    'db_import_button'        => 'Backup importieren',
    'db_connection_title'     => 'Verbindungsdaten (optional)',
    'db_connection_description' => 'Nur ändern, wenn du weißt, was du tust.',
    'db_host_label'           => 'Datenbank-Host',
    'db_name_label'           => 'Datenbank-Name',
    'db_user_label'           => 'Benutzer',
    'db_pass_label'           => 'Passwort',
    'db_test_button'          => 'Verbindung testen',

    // db.php
    'db_config_missing'    => 'Keine Datenbank-Konfiguration gefunden. Bitte db_config.json anlegen.',
    'db_config_invalid'    => 'Ungültige Konfiguration. Bitte db_config.json prüfen.',
    'db_connection_failed' => 'Verbindung zur Datenbank fehlgeschlagen.',



    // ===============================================================================================
    // [LOGINBEREICH] admin/index.php
    // ===============================================================================================
    'lang_code' => 'de',

    'login_title'       => 'Admin Login',
    'login_username'    => 'Benutzername',
    'login_password'    => 'Passwort',
    'login_btn'         => 'Login',

    'login_error_required' => 'Bitte alle Felder ausfüllen.',
    'login_error_invalid'  => 'Benutzername oder Passwort ist falsch.',
    'login_error_csrf'     => 'Ungültiger Sicherheits-Token.',



    // ===============================================================================================
    // [DASHBOARD] dashboard.php, delete_scent.php
    // ===============================================================================================

    'dashboard_title' => 'Dashboard',
    'dashboard_pretitle' => 'Übersicht',
    'dashboard_overview' => 'Übersicht',
    'dashboard_button_new_scent' => 'Neuer Duft',
    'dashboard_total_label' => 'Gesamtanzahl',
    'dashboard_men_label' => 'Herren',
    'dashboard_women_label' => 'Damen',
    'dashboard_unisex_label' => 'Unisex',

    // Tabelle
    'dashboard_table_title' => 'Düfte',
    'dashboard_table_entries' => 'Düfte',
    'dashboard_table_search' => 'Suchen:',
    'dashboard_table_column_no' => 'Nr.',
    'dashboard_table_column_code' => 'Code',
    'dashboard_table_column_inspired' => 'Inspiriert von',
    'dashboard_table_column_gender' => 'Geschlecht',
    'dashboard_table_column_direction' => 'Duftrichtung',
    'dashboard_table_column_qrcode' => 'QR Code',
    'dashboard_table_column_actions' => '',

    'dashboard_table_pagination_info' => 'Zeige <span>{start}</span> bis <span>{end}</span> von <span>{total}</span> Einträgen',
    'dashboard_table_pagination_none' => 'Keine Einträge gefunden.',

    'dashboard_generate_qrcode' => 'QR-Code generieren',

    // Modal Hinzufügen
    'dashboard_modal_add_title' => 'Neuen Duft hinzufügen',
    'dashboard_modal_add_code' => 'Code (optional)',
    'dashboard_modal_add_inspired_by' => 'Inspiriert von',
    'dashboard_modal_add_gender' => 'Geschlecht',
    'dashboard_modal_add_description' => 'Beschreibung / Duftrichtung',
    'dashboard_modal_add_pyramid_switch' => 'Duftpyramide verwenden?',
    'dashboard_modal_scent_pyramid' => 'Duftpyramide',
    'dashboard_modal_add_top_notes' => 'Kopfnoten',
    'dashboard_modal_add_heart_notes' => 'Herznoten',
    'dashboard_modal_add_base_notes' => 'Basisnoten',
    'dashboard_modal_add_general_notes_title' => 'Allgemeine Noten (ohne Pyramide)',
    'dashboard_modal_add_general_notes_label' => 'Duftnoten',
    'dashboard_modal_add_attributes_title' => 'Eigenschaften (%-Angaben)',
    'dashboard_modal_add_type' => 'Dufttyp',
    'dashboard_modal_add_type_add' => '+ hinzufügen',
    'dashboard_modal_add_style' => 'Stil',
    'dashboard_modal_add_season' => 'Jahreszeit',
    'dashboard_modal_add_occasion' => 'Anlass',

    // Modal Bearbeiten
    'dashboard_modal_edit_title' => 'Duft bearbeiten',
    'dashboard_modal_edit_button_save' => 'Duft speichern',

    // Modal Löschen
    'dashboard_modal_delete_title' => 'Bist du sicher?',
    'dashboard_modal_delete_text' => 'Möchtest du den Duft<br>%s<br>wirklich löschen?',

    // Stil / Jahreszeit / Anlass Labels (für Input-Felder)
    'label_men' => 'Herren',
    'label_women' => 'Damen',
    'label_classic' => 'Klassisch',
    'label_modern' => 'Modern',

    'label_spring' => 'Frühling',
    'label_summer' => 'Sommer',
    'label_autumn' => 'Herbst',
    'label_winter' => 'Winter',

    'label_daily' => 'Täglich',
    'label_leisure' => 'Freizeit',
    'label_evening' => 'Abend',
    'label_work' => 'Arbeit',
    'label_going_out' => 'Ausgehen',
    'label_sport' => 'Sport',

    // delete_scent.php
    'scent_delete_no_permission' => 'Du hast keine Berechtigung, diesen Duft zu löschen.',
    'scent_delete_success'       => 'Der Duft <strong>%s – %s</strong> wurde erfolgreich gelöscht.',
    'scent_delete_not_found'     => 'Der ausgewählte Duft konnte nicht gefunden werden.',



    // ===============================================================================================
    // [BENUTZER] users.php, edit_user.php
    // ===============================================================================================

    'users_title' => 'Benutzer',
    'users_total_count' => '1–{count} von {count} Benutzern',

    'users_add_button' => 'Benutzer hinzufügen',
    'users_add_title' => 'Neuen Benutzer hinzufügen',
    'users_edit_title' => 'Benutzer bearbeiten',
    'users_delete_confirm' => 'Benutzer wirklich löschen?',
    'users_no_results' => 'Keine Benutzer gefunden.',

    // Formularfelder
    'users_label_role' => 'Rolle',
    'users_label_firstname' => 'Vorname',
    'users_label_lastname' => 'Nachname',
    'users_label_username' => 'Benutzername',
    'users_label_email' => 'E-Mail',
    'users_label_password' => 'Passwort',
    'users_label_new_password' => 'Neues Passwort (optional)',

    // Rollen
    'users_role_user' => 'User',
    'users_role_editor' => 'Editor',
    'users_role_admin' => 'Admin',

    // edit_user.php
    'user_edit_no_permission'   => 'Du hast keine Berechtigung, Benutzer zu bearbeiten.',
    'user_edit_username_exists' => 'Benutzername existiert bereits.',
    'user_edit_success'         => 'Benutzer wurde erfolgreich aktualisiert.',
    'user_edit_missing_fields'  => 'Bitte alle Felder ausfüllen.',




    // ===============================================================================================
    // [DUFTNOTEN] notes.php, edit_note.php, delete_note.php
    // ===============================================================================================

    // notes.php
    'notes_page_title' => 'Duftnoten verwalten',
    'notes_table_title' => 'Duftnoten',
    'notes_table_column_number' => '#',
    'notes_table_column_name' => 'Name',
    'notes_table_column_actions' => 'Aktionen',
    'notes_search_label' => 'Suchen:',
    'notes_table_pagination_info' => 'Zeige <span>{start}</span> bis <span>{end}</span> von <span>{total}</span> Einträgen',
    'notes_table_pagination_none' => 'Keine Einträge gefunden.',
    'notes_no_results' => 'Keine Duftnoten gefunden.',


    // add note (modalbox -> notes.php (-> edit_note.php))
    'notes_add_button' => 'Neue Duftnote',
    'notes_add_title' => 'Neue Duftnote hinzufügen',
    'notes_add_label_name' => 'Name',
    'notes_add_placeholder_name' => 'z. B. Vanille',
    'notes_add_label_image' => 'Bild (optional)',
    'notes_add_hint_image' => 'Empfohlenes Format: webp',


    // delete_notes.php
    'notes_delete_confirm_title' => 'Bist du sicher?',
    'notes_delete_confirm_text' => 'Möchtest du die Duftnote<br><strong>{name}</strong><br>wirklich löschen?',
    
    'note_delete_success' => 'Die Duftnote "%s" wurde erfolgreich gelöscht.',
    'note_delete_no_permission' => 'Du hast keine Berechtigung für diese Aktion.',

    // edit_notes.php
    'notes_edit_title' => 'Duftnote bearbeiten',
    'notes_edit_label_current_image' => 'Aktuelles Bild:',
    'notes_edit_label_replace_image' => 'Neues Bild hochladen (optional)',
    'notes_edit_hint_replace_image' => 'Nur .webp, max. 2 MB',

    'note_edit_no_permission' => 'Du hast keine Berechtigung für diese Aktion.',
    'note_edit_missing_name'  => 'Bitte gib einen Namen für die Duftnote an.',
    'note_edit_name_exists'   => 'Der Name "%s" ist bereits vergeben.',
    'note_edit_success'       => 'Die Duftnote "%s" wurde erfolgreich bearbeitet.',




    // ===============================================================================================
    // [DEFEKTE] defects.php, defekt.php, submit_defekt.php
    // ===============================================================================================

    // defects.php
    'defects_page_title' => 'Meldungen verwalten',
    'defects_table_title' => 'Fehlermeldungen',

    // Filter
    'defects_label_filter' => 'Problem filtern:',
    'defects_filter_all' => 'Alle',
    'defects_filter_spray' => 'Pumpspray defekt',
    'defects_filter_leak' => 'Flasche ausgelaufen',
    'defects_filter_wrong_scent' => 'Falscher Duft erhalten',
    'defects_filter_other' => 'Sonstiges',

    // Tabellentitel
    'defects_column_number' => '#',
    'defects_column_date' => 'Datum',
    'defects_column_scent' => 'Duft',
    'defects_column_problem' => 'Problem',
    'defects_column_contact' => 'Name / E-Mail',
    'defects_column_message' => 'Nachricht',
    'defects_column_image' => 'Bild',

    // Bildanzeige
    'defects_image_placeholder' => '—',

    // Pagination / keine Einträge
    'defects_pagination_info' => 'Zeige <span>{start}</span> bis <span>{end}</span> von <span>{total}</span> Einträgen',
    'defects_pagination_none' => 'Keine Einträge gefunden.',
    'defects_no_results' => 'Keine Meldungen gefunden.',


    // defekt.php
    'defect_title' => 'Defektes Pumpspray melden',
    'defect_name' => 'Dein Name',
    'defect_email' => 'E-Mail-Adresse',
    'defect_scent' => 'Duftcode oder Name',
    'defect_scent_placeholder' => 'z. B. M-021 oder Sauvage',
    'defect_problem' => 'Problem',
    'defect_option_spray' => 'Pumpspray defekt',
    'defect_option_leak' => 'Flasche ausgelaufen',
    'defect_option_wrong' => 'Falscher Duft erhalten',
    'defect_option_other' => 'Sonstiges',
    'defect_note' => 'Weitere Anmerkung (optional)',
    'defect_upload' => 'Foto hochladen (optional)',
    'defect_submit' => 'Meldung absenden',

    // Bestätigung & Fehler
    'defect_error_required' => 'Bitte fülle alle Pflichtfelder aus.',
    'defect_success_title' => 'Vielen Dank!',
    'defect_success_message' => 'Deine Meldung wurde erfolgreich übermittelt. Wir kümmern uns schnellstmöglich darum.',
    'defect_back_home' => 'Zurück zur Startseite',
    'defect_missing_fields'  => 'Bitte fülle alle Pflichtfelder aus.',



    // ===============================================================================================
    // [STARTSEITE] index.php
    // ===============================================================================================

    'index_title' => 'Düfte',
    'index_total' => 'Gesamtanzahl',
    'index_men' => 'Herren',
    'index_women' => 'Damen',
    'index_unisex' => 'Unisex',

    // Buttons
    'index_btn_whatsapp' => 'Chat auf WhatsApp',
    'index_btn_instagram' => 'Chat auf Instagram',
    'index_btn_reset' => 'Zurücksetzen',

    // Filter / Suche
    'index_search_placeholder' => 'Suche nach Duft, Code, Geschlecht …',

    // Geschlecht Filter
    'index_filter_gender' => 'Alle Geschlechter',
    'index_gender_men' => 'Herren',
    'index_gender_women' => 'Damen',
    'index_gender_unisex' => 'Unisex',

    // Jahreszeit Filter
    'index_filter_season' => 'Alle Jahreszeiten',
    'index_season_spring' => 'Frühling',
    'index_season_summer' => 'Sommer',
    'index_season_autumn' => 'Herbst',
    'index_season_winter' => 'Winter',

    // Anlass Filter
    'index_filter_occasion' => 'Alle Anlässe',
    'index_occasion_out' => 'Ausgehen',
    'index_occasion_evening' => 'Abend',
    'index_occasion_free' => 'Freizeit',
    'index_occasion_daily' => 'Täglich',
    'index_occasion_work' => 'Arbeit',

    // Tabellenüberschriften
    'index_table_code' => 'Code',
    'index_table_inspired_by' => 'Inspiriert von',
    'index_table_gender' => 'Geschlecht',
    'index_table_loading' => 'Lade Daten...',
    'index_table_no_results' => 'Keine passenden Düfte gefunden.',
    'index_table_error' => 'Fehler beim Laden der Daten',

    // Modal
    'index_modal_type' => 'Duftrichtung',
    'index_modal_pyramid_title' => 'Duftpyramide',
    'index_modal_notes_title' => 'Duftnoten',
    'index_modal_charts_title' => 'Diagramme',

    // Modalbox Kategorien
    'index_modal_chart_type' => 'Dufttyp',
    'index_modal_chart_style' => 'Stil',
    'index_modal_chart_season' => 'Jahreszeit',
    'index_modal_chart_occasion' => 'Anlass',

    // Duftpyramide-Titel
    'index_modal_pyramid_top' => 'Kopfnote',
    'index_modal_pyramid_heart' => 'Herznote',
    'index_modal_pyramid_base' => 'Basisnote',

    // Footer
    'index_footer_problem' => 'Problem melden',
    'index_footer_sponsor' => 'Sponsor',
    'index_footer_imprint' => 'Impressum',
    'index_footer_rights' => 'Alle Rechte vorbehalten.',

];