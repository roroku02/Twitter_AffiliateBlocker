<?php
    session_start();
    require_once('./twitteroauth/autoload.php');
    
    ini_set('display_errors', "On");
    ini_set('error_reporting', E_ALL);

    use Abraham\TwitterOAuth\TwitterOAuth;

    $ConsumerKey = "0J9607H2YzUFGZmChwg1svR0z";
    $ConsumerSecret = "rHoVNb62FwdePqaDKHNnAMIERnjDaPxz6ZTTpdKUUl2s7NNmiU";
    $AccessToken = $_SESSION['access_token'];

    $connection = new TwitterOAuth($ConsumerKey,$ConsumerSecret,$AccessToken['oauth_token'],$AccessToken['oauth_token_secret']);


$block_users[] = $_POST["block_user"];
print_r($block_users);
/*foreach($block_users as $block_user){
    $block_res[] = $connection -> post("blocks/create", array("screen_name" => $block_user));
}*/

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ブロック完了</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
</head>
<body>
    <div class="result">
        <p>ブロックは正常に完了しました</p>
        <p>ブロックしたユーザ▼</p>
        <ul>
            <?php //foreach $block_res as $user {
                //echo '<li>'.$user["name"]."</li>";
            //}
            ?>
        </ul>
    </div>
</body>
</html>