<?php
class Taurus_Verify_Register_Settings
{
    function __construct()
    {
        add_action('admin_menu', array($this, 'taurus_verify_settings'));
        add_action('admin_init', array($this, 'taurus_verify_register_settings'));
        add_action('admin_notices', array($this, 'taurus_notifications'));
    }

    //Admin error/update notifications
    public function taurus_notifications()
    {
        settings_errors();
    }


    //Add admin menu item
    public function taurus_verify_settings()
    {
        add_menu_page('Taurus User Verification', 'Taurus Verify', 'manage_options', 'taurus-verify-settings', array($this, 'taurus_settings_form_html'));
    }

    //Register fields for settings
    public function taurus_verify_register_settings()
    {
        register_setting('taurus_verify_group', 'taurus_error_message');
        register_setting('taurus_verify_group', 'taurus_email_subject');
        register_setting('taurus_verify_group', 'taurus_email_message');

        //add section
        add_settings_section('taurus_verify_section1', 'Taurus User Verification Settings', null, 'taurus_verify_group');

        //add settings
        add_settings_field('taurus_error_message', 'Unverified Error Message', array($this, 'taurus_error_message_callback'), 'taurus_verify_group', 'taurus_verify_section1');
        add_settings_field('taurus_email_subject', 'Email Subject', array($this, 'taurus_email_subject'), 'taurus_verify_group', 'taurus_verify_section1');
        add_settings_field('taurus_email_message', 'Email Message', array($this, 'taurus_email_message'), 'taurus_verify_group', 'taurus_verify_section1');
    }

    //Display error message field form
    public function taurus_error_message_callback()
    {
        $taurus_error_message = get_option('taurus_error_message'); ?>
        <input type='text' name='taurus_error_message' id='taurus_error_message' value='<?php echo isset($taurus_error_message) ? $taurus_error_message : '' ?>' />
    <?php
    }

    //Display email subject field form
    public function taurus_email_subject()
    {
        $taurus_email_subject = get_option('taurus_email_subject'); ?>
        <textarea name='taurus_email_subject' id='taurus_email_subject' rows='2'><?php echo isset($taurus_email_subject) ? $taurus_email_subject : '' ?></textarea>
    <?php
    }

    //Display email message field form
    public function taurus_email_message()
    {
        $taurus_email_message = get_option('taurus_email_message'); ?>
        <textarea id='taurus_email_message' name='taurus_email_message' rows="2"><?php echo isset($taurus_email_message) ? $taurus_email_message : '' ?></textarea>
<?php
    }



    //Callback functions

    //CBF for taurus_verify_settings
    public function taurus_settings_form_html()
    {
        require_once(TAURUS_VERIFY_PATH . '/admin/views/class.taurus-verify-show-form.php');
    }
}
