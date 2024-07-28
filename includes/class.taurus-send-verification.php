<?php

if (!defined('ABSPATH')) {
    exit;
}

class Taurus_Send_Verify_Verification
{
    function __construct()
    {
        add_action('user_register', array($this, 'send_verification'));
        add_action('init', array($this, 'email_verification'));
        add_filter('wp_authenticate_user', array($this, 'prevent_unverified_login'), 30, 3);
    }

    //Send verification email
    public function send_verification($user_id)
    {
        //get user details from ID
        $user_info = get_userdata($user_id);
        $user_email = $user_info->user_email;
        $verification_code = md5(time() . $user_email);

        // add a verification code table in DB and add the verification code
        update_user_meta($user_id, 'verification_code', $verification_code);

        //Create verification link
        $verification_link = add_query_arg(array(
            'user_id' => $user_id,
            'verification_code' => $verification_code
        ), site_url('/'));

        //Email subject and message to be sent to user
        $subject = get_option('taurus_email_subject');
        $message = "Please verify your email address by clicking verification link. <a href=" . $verification_link . ">Click Here to verify.</a>. If this link does not work, please copy the below URL to a  new page. <br/>" . $verification_link;
        $headers = array('Content-Type: text/html; charset=UTF-8');

        //Send mail
        wp_mail($user_email, $subject, $message, $headers);
    }

    //Verify user verification link
    public function email_verification()
    {
        $email_user_id = "";
        $email_verification_code = "";
        if (isset($_GET['user_id']) && isset($_GET['verification_code'])) {
            $email_user_id = intval($_GET['user_id']);
            $email_verification_code = sanitize_text_field($_GET['verification_code']);


            $stored_verification = get_user_meta($email_user_id, 'verification_code', true);

            //compare db code with link code
            if ($stored_verification === $email_verification_code) {
                delete_user_meta($email_user_id, 'verification_code');
                update_user_meta($email_user_id, 'verified', true);

                header("refresh:5;url=" . wp_login_url());
                echo "Your email has been verified. You will be redirected to the Login Page in 5seconds.";
            } else {
                echo "Invalid Verification code. Please click the email link again";
            }
            exit;
        }
    }

    //Restrict login access for non-verified user
    public function prevent_unverified_login($user, $password)
    {

        if (is_a($user, 'WP_User')) {
            $is_verified = get_user_meta($user->ID, 'verified', true);

            if (!$is_verified) {
                $message = get_option('taurus_error_message');
                return new WP_Error('not_verified', __($message));
            }

            return $user;
        }
    }
}
