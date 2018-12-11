<?php
    session_start();
    require_once('./twitteroauth/autoload.php');
    use Abraham\TwitterOAuth\TwitterOAuth;
 
    $ConsumerKey = "0J9607H2YzUFGZmChwg1svR0z";
    $ConsumerSecret = "rHoVNb62FwdePqaDKHNnAMIERnjDaPxz6ZTTpdKUUl2s7NNmiU";
    $Callback_URL = "https://roroku.shop/Twitter_AffiliateBlocker/callback.php";

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
<body class="login_body">
    <section class ="login">
        <div class="login_container">
            <h1>Affiliate Blocker</h1>
            <p>Twitter IDでログインしてください</p>
            <a href="<?php echo $Url; ?>">Login</a>
        </div>
    </section>
    <section class="log">
        <div class="container">
            <h2>更新履歴</h2>
            <div class="scroll-box">
                <ul>
                    <li>2018-12-10:ページ公開</li>
                    <li>2018-12-11:ログインページをリニューアル</li>
                </ul>
            </div>
        </div>
    </section>
    <section class="contact">
        <div class="container">
            <h2>お問い合わせ</h2>
            <a href="https://twitter.com/roroku02" alt="Twitter|roroku02" class="contact-twitter">
                <h3>Twitter - @roroku02</h3>
            </a>
        </div>
    </section>
</body>
</html>