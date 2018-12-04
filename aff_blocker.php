<?php
    session_start();
    require_once('./twitteroauth/autoload.php');
    
    ini_set('display_errors', "On");
    ini_set('error_reporting', E_ALL);

    use Abraham\TwitterOAuth\TwitterOAuth;

    $ConsumerKey = "m8fmmQGoGbRpooxPGwgGg";
    $ConsumerSecret = "Llbr5TBIL0VcxZNS4jcIGXOq3qelCADnthYfjUeUQs";
    $AccessToken = $_SESSION['access_token'];

    $connection = new TwitterOAuth($ConsumerKey,$ConsumerSecret,$AccessToken['oauth_token'],$AccessToken['oauth_token_secret']);

    //ユーザ情報を取得
    $user_profile_info = $connection -> get('account/verify_credentials');
    $user_name = $user_profile_info -> {'screen_name'};
    $user_id = $user_profile_info -> {'id'};

    //フォロワーを取得
    $follower_list = $connection -> get('followers/list',array('user_id' => $user_id,'count' => 10));
    //print_r($follower_list);
    foreach($follower_list -> {"users"} as $f){
        $f_user{'name'}[] = $f -> {"name"};
        $f_user{'screen_name'}[] = $f -> {"screen_name"};
        $f_user{'description'}[] = $f -> {'description'};
    }

    //抽出ワードリスト
    $block_words = ["稼ぐ","企業","投資","月収","年収","収入","勝手にお金"];
    
    //ユーザ抽出
    for($user_count = 0;$user_count < count($f_user['name']);$user_count++){
        foreach($block_words as $block_word){
            if(strpos($f_user['description'][$user_count],$block_word)){
                $block_user{'name'}[] = $f_user['name'][$user_count];
                $block_user{'screen_name'}[] = $f_user{'screen_name'}[$user_count];
                $block_user{'description'}[] = $f_user{'description'}[$user_count];
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Affiliate Blocker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
</head>
<body>
    <?php
        if(isset($block_user{'name'}[0])){
            echo "<p>あなたのアカウントのフォロワーから以下の通りアフィリエイトアカウントとみられるユーザを発見しました</p>";
            echo '<table border="1" class="t_user"><tr><th>ユーザ名</th><th>ユーザID</th><th>プロフィール</th></tr>';
            for($i = 0;$i < count($block_user['name']);$i++){
                echo "<tr><td>".$block_user['name'][$i]."</td><td>".$block_user['screen_name'][$i]."</td><td>".$block_user['description'][$i]."</td></tr>";
            }
            echo '</table>';
        }else{
            echo "<p>あなたのアカウントのフォロワーからアフィリエイトアカウントとみられるユーザは発見できませんでした<p>";
        }
    ?>
</body>
</html>
