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

    function user_block($block_list,$connection){
        foreach($block_list as $block_user){
            //$block_res[] = $connection -> post('blocks/create',array('screen_name' => $block_user));
        }    
    }

    //ユーザ情報を取得
    $user_profile_info = $connection -> get('account/verify_credentials');
    $user_name = $user_profile_info -> {'screen_name'};
    $user_id = $user_profile_info -> {'id'};

    //フォロワーを取得
    $follower_list = $connection -> get('followers/list',array('user_id' => $user_id,'count' => 100));
    foreach($follower_list -> {"users"} as $f){
        $f_user{'name'}[] = $f -> {"name"};
        $f_user{'screen_name'}[] = $f -> {"screen_name"};
        $f_user{'description'}[] = $f -> {'description'};
    }

    //抽出ワードリスト
    $block_words = ["稼ぐ","起業","投資","月収","年収","収入","勝手にお金"];
    
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
            echo '<table class="t_user"><tr><th id = "name">ユーザ名</th><th id ="id">ユーザID</th><th id = "des">プロフィール</th></tr>';
            for($i = 0;$i < count($block_user['name']);$i++){
                echo "<tr><td>".$block_user['name'][$i]."</td><td>".$block_user['screen_name'][$i]."</td><td>".$block_user['description'][$i]."</td></tr>";
            }
            echo '</table>';
            echo '<br /><br />';
            echo '<p>以下のボタンを押すと一括でブロックします（API制限に注意してください）</p>';
            ?>
            <input type="button" name="block_button" id="block_button" value="一括ブロック" onclick="document.getElementsByClassName('result').innerHTML = '<?php user_block($block_user['screen_name'],$connection); ?>'">
            <?php
        }else{
            echo "<p>あなたのアカウントのフォロワーからアフィリエイトアカウントとみられるユーザは発見できませんでした<p>";
        }
    ?>
    <div class="result">
        <h1>ブロックしました</h1>
    </div>
</body>
</html>
