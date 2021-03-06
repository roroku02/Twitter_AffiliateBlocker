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

    unset($_SESSION['block_user']);
    //ユーザ情報を取得
    $user_profile_info = $connection -> get('account/verify_credentials');
    $user_name = $user_profile_info -> {'screen_name'};
    $user_id = $user_profile_info -> {'id'};

    //フォロワーを取得
    $follower_list = $connection -> get('followers/list',array('user_id' => $user_id,'count' => 200));
    foreach($follower_list -> {"users"} as $f){
        $f_user{'name'}[] = $f -> {"name"};
        $f_user{'screen_name'}[] = $f -> {"screen_name"};
        $f_user{'description'}[] = $f -> {'description'};
    }

    //抽出ワードリスト
    $block_words = ["稼ぐ","起業","投資","月収","年収","収入","勝手にお金","副業",
                    "ハイローオーストラリア","バイオプ","ネットビジネス","稼いだ","アフィリエイトビジネス"];
    
    //ユーザ抽出
    if(isset($f_user['name'])){
        for($user_count = 0;$user_count < count($f_user['name']);$user_count++){
            foreach($block_words as $block_word){
                if(strpos($f_user['description'][$user_count],$block_word)){
                    $block_user{'name'}[] = $f_user['name'][$user_count];
                    $block_user{'screen_name'}[] = $f_user{'screen_name'}[$user_count];
                    $block_user{'description'}[] = $f_user{'description'}[$user_count];
                }
            }
        }
    }

    //重複削除
    if(isset($block_user)){
        for($i = 1;$i < count($block_user['name']);$i++){
            if($block_user['name'][$i-1] == $block_user['name'][$i]){
                unset($block_user['name'][$i-1]);
                unset($block_user['screen_name'][$i-1]);
                unset($block_user['description'][$i-1]);
            }
        }
        $block_user['name'] = array_values($block_user['name']);
        $block_user['screen_name'] = array_values($block_user['screen_name']);
        $block_user['description'] = array_values($block_user['description']);
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
    <div class="main_container">
    <?php
        if(isset($block_user{'name'}[0])){
            echo "<p>あなたのアカウントのフォロワーから以下の通りアフィリエイトアカウントとみられるユーザを発見しました</p>";

            //テーブル生成
            echo '<div class="scroll-table">';
            echo '<table class="t_user"><tr><th id = "name">ユーザ名</th><th id ="id">ユーザID</th><th id = "des">プロフィール</th></tr>';
            for($i = 0;$i < count($block_user['name']);$i++){
                echo "<tr><td>".$block_user['name'][$i]."</td><td>@".$block_user['screen_name'][$i]."</td><td>".$block_user['description'][$i]."</td></tr>";
            }
            echo '</table>';
            echo '</div>';
            echo '<br /><br />';

            //ブロックボタン
            echo '<p>以下のボタンを押すと一括でブロックします（API制限に注意してください）</p>';
            $_SESSION['block_user'] = $block_user['screen_name'];
            ?>
            <form action="block.php" method="post">
                <input type="hidden" name="flag" value="1">
                <input type="submit" id="block_button" value="一括ブロック">
            </form>
            <?php
        }else{
            //発見ユーザ無し
            echo "<div class='no-user'><p>あなたのアカウントのフォロワーからアフィリエイトアカウントとみられるユーザは発見できませんでした</p></div>";
        }
    ?>
    </div>
</body>
</html>
