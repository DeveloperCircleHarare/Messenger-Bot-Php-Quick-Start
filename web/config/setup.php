<?php

ini_set('session.save_handler', 'memcached');
ini_set('session.save_path', 'PERSISTENT=pool ' . getenv('MEMCACHIER_SERVERS'));
ini_set('memcached.sess_binary', 1);
ini_set('memcached.sess_sasl_username', getenv('MEMCACHIER_USERNAME'));
ini_set('memcached.sess_sasl_password', getenv('MEMCACHIER_PASSWORD'));

error_log("Verify Token: " . getenv('VERIFY_TOKEN'));
error_log("Page Access Token: " . getenv('ACCESS_TOKEN'));
error_log("Profile Pic Url: " . getenv('PROFILE_PIC_URL'));
error_log("Website Url: " . getenv('WEBSITE_URL'));

$payload = '{
                "get_started":{
                    "payload":"GET_STARTED"
                }
            }'
;

// Send/Recieve API
$url = "https://graph.facebook.com/v2.6/me/messenger_profile?access_token=" . getenv('ACCESS_TOKEN');
// Initiate the curl
$ch = curl_init($url);
// Set the curl to POST
curl_setopt($ch, CURLOPT_POST, 1);
// Add the json payload
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
// Set the header type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
// SSL Settings
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Send the request
$result = curl_exec($ch);
curl_close($ch);
error_log("Get Started: " . $result);

$payload = '{
    "persistent_menu":[
        {
        "locale":"default",
        "composer_input_disabled": true,
        "call_to_actions":[
            {
                "title":"Info",
                "type":"postback",
                "payload":"INFO_PAYLOAD"
            },
            {
                "title":"Options",
                "type":"nested",
                "call_to_actions":[
                    {
                        "title":"Share",
                        "type":"postback",
                        "payload":"SHARE_PAYLOAD"
                    },
                    {
                        "title":"Help",
                        "type":"postback",
                        "payload":"HELP_PAYLOAD"
                    },
                    {
                        "title":"About",
                        "type":"postback",
                        "payload":"ABOUT_PAYLOAD"
                    }
                ]
            }
        ]
        }
    ]
}'
;

// Send/Recieve API
$url = "https://graph.facebook.com/v2.6/me/messenger_profile?access_token=" . getenv('ACCESS_TOKEN');
// Initiate the curl
$ch = curl_init($url);
// Set the curl to POST
curl_setopt($ch, CURLOPT_POST, 1);
// Add the json payload
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
// Set the header type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
// SSL Settings
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Send the request
$result = curl_exec($ch);
curl_close($ch);
error_log("Persistent Menu: " . $result);

echo "Setting Up Database.\n" . '</br>';

include "conn.php";

//Setup database tables for dashboard.
$query = "CREATE TABLE IF NOT EXISTS messenger_users (
        id SERIAL PRIMARY KEY,
        name VARCHAR(100) 				NOT NULL,
        facebook_id VARCHAR(100) UNIQUE NOT NULL,
        profile_pic_url VARCHAR(1000) 			,
        locale VARCHAR(50) 						,
        timezone VARCHAR(50) 					,
        gender VARCHAR(50) 						,
		last_message VARCHAR(1000)				,
        sign_up_timestamp TIMESTAMP 	NOT NULL,
        last_message_timestamp TIMESTAMP NOT NULL
    );";

$result = pg_query($db, $query);

$query = "CREATE TABLE IF NOT EXISTS messenger_message_log(
        id SERIAL PRIMARY KEY,
        message VARCHAR(1000)                   ,
        facebook_id VARCHAR(100)                ,
        log_timestamp TIMESTAMP                 ,
        description VARCHAR(100)                ,
        type VARCHAR(100)
    );";
$result = pg_query($db, $query);

$query = "CREATE TABLE IF NOT EXISTS messenger_error_log (
        id SERIAL PRIMARY KEY,
        message                   VARCHAR(1000),
        error_code                 INT NOT NULL,
        error_subcode              INT NOT NULL,
        error_type                 VARCHAR(100),
        error_timestamp               TIMESTAMP,
        fbtrace_id                 VARCHAR(100)
    );";
$result = pg_query($db, $query);

echo "Done.\n" . '</br>';
