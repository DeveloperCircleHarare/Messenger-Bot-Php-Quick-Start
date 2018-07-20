<?php

include './config/conn.php';
include './inc/db_queries.php';
include './inc/graphapi.php';
include './inc/messengerpayloads.php';

error_reporting(E_ALL & ~E_NOTICE);

//Retrieving our Environment Variable Values
$VERIFY_TOKEN = getenv('VERIFY_TOKEN');
$PAGE_ACCESS_TOKEN = getenv('ACCESS_TOKEN');
$PROFILE_PIC_URL = getenv('PROFILE_PIC_URL');
$WEBSITE_URL = getenv('WEBSITE_URL');

error_log("Verify Token: " . getenv('VERIFY_TOKEN'));
error_log("Page Access Token: " . getenv('ACCESS_TOKEN'));
error_log("Profile Pic Url: " . getenv('PROFILE_PIC_URL'));
error_log("Website Url: " . getenv('WEBSITE_URL'));

$challenge = $_REQUEST['hub_challenge'];
$verify_token = $_REQUEST['hub_verify_token'];
$old_message = "";
if ($verify_token === $VERIFY_TOKEN) {
    //If the Verify token matches, return the challenge.
    echo $challenge;
} else {
    $request_contents = file_get_contents('php://input');
    error_log("\nResponse: " . $request_contents . " .\n");
    $input = json_decode($request_contents, true);
    // Get error message
    $error = $input['error']['message'];
    // Get the Senders Page Scoped ID
    $sender = $input['entry'][0]['messaging'][0]['sender']['id'];
    // Get the Recipient Page Scoped ID
    $recipient = $input['entry'][0]['messaging'][0]['recipient']['id'];
    // Get the Message text sent
    $message = $input['entry'][0]['messaging'][0]['message']['text'];
    // Get the Postbacks sent
    $postback = $input['entry'][0]['messaging'][0]['postback']['payload'];
    // Get the Entity response
    //$entity = $input[entry][0]['messaging'][0]['entity']['value'];

    $greetings = array("hi", "hello", "hey", "olla", "bonjour");
    $help = array("help", "clue", "hint", "difficult", "hard", "i dont know");
    $about = array("about", "info", "411", "rules", "rule", "law", "how does this work", "explain", "describe");

    $count = 1;

    $VERIFY_TOKEN = getenv('VERIFY_TOKEN');
    $PAGE_ACCESS_TOKEN = getenv('ACCESS_TOKEN');
    $PROFILE_PIC_URL = getenv('PROFILE_PIC_URL');
    $WEBSITE_URL = getenv('WEBSITE_URL');

    if (!empty($message)) {

        $description = "User typed text.";
        $type = "User";
        $log_timestamp = date('Y-m-d H:i:s', time());
        create_messenger_message_log($message, $sender, $log_timestamp, $description, $type, $db);
        $message = strtolower($message);

        if (0 < count(array_intersect(array_map('strtolower', explode(' ', $message)), $greetings))) {
            $obj = retrieve_user_profile($sender, $PAGE_ACCESS_TOKEN);
            $name = $obj->first_name;
            $welcome_message = "Hi " . $name . "!";
            send_text_message($sender, $welcome_message, $PAGE_ACCESS_TOKEN);
            send_share_button_template_message($sender, "", "", "", $PAGE_ACCESS_TOKEN, $PROFILE_PIC_URL, $WEBSITE_URL);
        } elseif (0 < count(array_intersect(array_map('strtolower', explode(' ', $message)), $help))) {
            $share = "Share this messenger bot with a friend.";
            send_text_message($sender, $share, $PAGE_ACCESS_TOKEN);
            send_share_button_template_message($sender, "", "", "", $PAGE_ACCESS_TOKEN, $PROFILE_PIC_URL, $WEBSITE_URL);
        } elseif (0 < count(array_intersect(array_map('strtolower', explode(' ', $message)), $about))) {
            $message = "About";
            send_text_message($sender, $message, $PAGE_ACCESS_TOKEN);
        } else {
            $message = "No context";
            send_text_message($sender, $message, $PAGE_ACCESS_TOKEN);
        }
    } elseif (!empty($postback)) {
        #logging the message to the message_log table in the database.
        $log_timestamp = date('Y-m-d H:i:s', time());
        $description = "Postback";
        $type = "User";
        create_messenger_message_log($postback, $sender, $log_timestamp, $description, $type, $db);

        if ($postback == 'GET_STARTED') {

            $obj = retrieve_user_profile($sender, $PAGE_ACCESS_TOKEN);
            $first_name = $obj->first_name;
            $last_name = $obj->last_name;
            $image_url = $obj->profile_pic;
            $locale = $obj->locale;
            $timezone = $obj->timezone;
            $gender = $obj->gender;
            $email = $obj->email;
            $name = $first_name . " " . $last_name;
            $sign_up_timestamp = date('Y-m-d H:i:s', time());
            $last_message_timestamp = date('Y-m-d H:i:s', time());
            $last_message = $postback;

            #Adding a user to our messenger_users table in the database.
            create_messenger_user($name, $sender, $last_message, $image_url, $locale, $timezone, $gender, $sign_up_timestamp,
                $last_message_timestamp, $db);

            $welcome_message = "Hi " . $name . "!";
            send_text_message($sender, $welcome_message, $PAGE_ACCESS_TOKEN);
            send_share_button_template_message($sender, "", "", "", $PAGE_ACCESS_TOKEN, $PROFILE_PIC_URL, $WEBSITE_URL);

        } elseif ($postback == 'INFO_PAYLOAD') {

            $info = "Info.";
            send_text_message($sender, $info, $PAGE_ACCESS_TOKEN);

        } elseif ($postback == 'SHARE_PAYLOAD') {

            $share = "Share this messenger bot with a friend.";
            send_text_message($sender, $share, $PAGE_ACCESS_TOKEN);
            send_share_button_template_message($sender, "", "", "", $PAGE_ACCESS_TOKEN, $PROFILE_PIC_URL, $WEBSITE_URL);

        } elseif ($postback == 'HELP_PAYLOAD') {

            $help = "Help";
            send_text_message($sender, $help, $PAGE_ACCESS_TOKEN);

        } elseif ($postback == 'ABOUT_PAYLOAD') {

            $about = "About";
            send_text_message($sender, $about, $PAGE_ACCESS_TOKEN);

        }
    } elseif (!empty($error)) {
        //Example Error Message
        //{"error":{"message":"(#100) The parameter recipient is required","type":"OAuthException","code":100,"fbtrace_id":"EckcOFDoLZQ"}}
        #logging the error to the error_log table in the database.
        $error_type = $input['error']['type'];
        $error_code = $input['error']['code'];
        $error_subcode = $input['error']['error_subcode'] + 0;
        $error_fbtrace_id = $input['error']['fbtrace_id'];
        $error_timestamp = date('Y-m-d H:i:s', time());
        error_log("\nError Message: " . $error . " .\n");
        error_log("\nError Message: Type - " . $error_type . " Code - " . $error_code . " Subcode - " . $error_subcode . " fbtrace_id - " . $error_fbtrace_id . " Timestamp - " . $error_timestamp . ".\n");
        create_messenger_error_log($error, $error_code, $error_subcode, $error_type, $error_timestamp, $error_fbtrace_id, $db);
    }
}
