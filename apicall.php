<?php

session_start();

require_once('twitteroauth/twitteroauth/twitteroauth.php');
require_once('twitteroauth/config.php');
/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) ||
    empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: ./twitteroauth/clearsessions.php');
}
/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(
    CONSUMER_KEY,
    CONSUMER_SECRET,
    $access_token['oauth_token'],
    $access_token['oauth_token_secret']
);

/* If method is set change API call made. Test is called by default. */

$content = '';

if (isset($_GET['method']) && isset($_GET['call'])) {
    $switch = strtolower($_GET['call' ]);
    if (strtolower($_GET['method']) == 'get') {
        switch ($switch) {
            case 'mentions_timeline':
                $content = $connection->get('statuses/mentions_timeline');
                break;

            case 'user_timeline':
                $content = $connection->get('statuses/user_timeline');
                break;

            case 'home_timeline':
                $content = $connection->get('statuses/home_timeline');
                break;

            case 'retweets_of_me':
                $content = $connection->get('statuses/retweets_of_me');
                break;

            case 'retweets':
                if (isset($_GET['id'])) {
                    $content = $connection->get('statuses/retweets/'.$_GET['id']);
                } else {
                    $content = array('error', 'id was empty or '.$_GET['id']);
                }

                break;

            default:
                $content = $connection->get('account/verify_credentials');
                break;
        }
    }
}

echo json_encode($content);
