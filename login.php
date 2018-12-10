<?php
    session_start();
    require_once('./twitteroauth/autoload.php');
    use Abraham\TwitterOAuth\TwitterOAuth;
 
    $ConsumerKey = "0J9607H2YzUFGZmChwg1svR0z";
    $ConsumerSecret = "rHoVNb62FwdePqaDKHNnAMIERnjDaPxz6ZTTpdKUUl2s7NNmiU";
    $Callback_URL = "http://www.roroku.shop/Twitter_AffiliateBlocker/callback.php";

    $connection = new TwitterOAuth($ConsumerKey,$ConsumerSecret);

    $request_token = $connection->oauth('oauth/request_token', array('oauth_callback=>$CallbackURL'));

    $_SESSION['oauth_token'] = $request_token['oauth_token'];
    $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

    $Url = $connection->url('oauth/authorize',array('oauth_token' => $request_token['oauth_token']));
    
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="main.css" />
</head>
<body>
    <div class ="login">
        <h1>Welcome</h1>
        <p>ログイン画面に移動してください</p>
        <a href="<?php echo $Url; ?>">Login</a>
    </div>
</body>
</html>