<?php

/************************************************************ Database Functions ******************************************************************************************************************************************************************/

function create_messenger_user($name, $sender_id, $last_message, $profile_pic_url, $locale, $timezone, $gender, $sign_up_timestamp,
    $last_message_timestamp, $db) {
    //Check to see if the user is in the Database. If not add them to the db.
    $query = "SELECT name FROM messenger_users WHERE facebook_id= '" . $sender_id . "'";
    $result = pg_query($db, $query);

    if (pg_num_rows($result) > 0) {
        $name = pg_fetch_result($result, 0, 0);
    } else {
        $query = "INSERT INTO messenger_users (id, name, facebook_id, profile_pic_url, locale, timezone, gender, last_message, sign_up_timestamp,
			last_message_timestamp) VALUES (DEFAULT, '" . $name . "','" . $sender_id . "', '" . $profile_pic_url . "', '" . $locale . "', '" . $timezone . "'
			, '" . $gender . "', '" . $last_message . "', '" . $sign_up_timestamp . "', '" . $sign_up_timestamp . "')";
        $result = pg_query($db, $query);
    }

}

function create_messenger_message_log($message, $sender_id, $log_timestamp, $description, $type, $db)
{
    $query = "INSERT INTO messenger_message_log (id, message, facebook_id, log_timestamp, description, type)
	VALUES (DEFAULT, '" . $message . "', '" . $sender_id . "', '" . $log_timestamp . "', '" . $description . "', '" . $type . "')";
    $result = pg_query($db, $query);
}

function create_messenger_error_log($message, $error_code, $error_subcode, $error_type, $error_timestamp, $fbtrace_id, $db)
{
    $query = "INSERT INTO messenger_error_log (id, message, error_code, error_subcode, error_type, error_timestamp, fbtrace_id)
	VALUES (DEFAULT, '" . $message . "', " . $error_code . ", " . $error_subcode . ", '" . $error_type . "', '" . $error_timestamp . "', '" . $fbtrace_id . "')";
    $result = pg_query($db, $query);
}

/************************************************************* End of Database Functions *****************************************************************************************************************************************************************/
