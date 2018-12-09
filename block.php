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

if($_POST['flag'] == 1){
    $block_users = $_SESSION["block_user"];
    foreach($block_users as $block_user){
        $block_res[] = $connection -> post("blocks/create", array("screen_name" => $block_user));
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ブロック完了</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
</head>
<body>
    <div class="result">
        <p>ブロックは正常に完了しました</p>
        <p>▼ブロックしたユーザ▼</p>
        <ul>
            <?php
            //テーブル生成
            echo '<table class="t_user"><tr><th id = "name">ユーザ名</th><th id ="id">ユーザID</th><th id = "des">プロフィール</th></tr>';
            for($i = 0;$i < count($block_res);$i++){
                echo "<tr><td>".$block_res[$i]->{'name'}."</td><td>".$block_res[$i]->{'screen_name'}."</td><td>".$block_res[$i]->{'description'}."</td></tr>";
            }
            echo '</table>';
            ?>
        </ul>
    <a href="aff_blocker.php">トップページ</a>
    <br><br>
    <a href="./logout">ログアウト（推奨）</a>
    </div>
</body>
</html>