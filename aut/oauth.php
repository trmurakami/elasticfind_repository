<?php

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

require ''.__DIR__.'/../inc/config.php'; 

if (!empty(filter_input(INPUT_GET, 'erro'))) {
    ?>
    <HTML>
        <BODY>
            Erro: <?php echo filter_input(INPUT_GET, 'erro'); ?> <br>
            <a href="<?php echo $url_base; ?>/aut/oauth.php">tentar novamente</a> <br>
        </BODY>
    </HTML>
    <?php
    exit;
}

if (!empty(filter_input(INPUT_GET, 'reset'))) {
    unset($_SESSION['token']);
}

if (!isset($_SESSION['state'])) {
    $_SESSION['state'] = 0;
}

if ($_SESSION['state']==1 && empty(filter_input(INPUT_GET, 'oauth_token'))) {
    $_SESSION['state'] = 0;
}

try {

    $oauth = new OAuth($conskey, $conssec, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
    $oauth->enableDebug();

    echo "Sessão antes<br/>";
    print_r($_SESSION);
    echo "<br/>";

    echo "Oauth<br/>";
    print_r($oauth);
    echo "<br/>";

    if (empty(filter_input(INPUT_GET, 'oauth_token')) && empty($_SESSION['state'])) {

        $request_token_info = $oauth->getRequestToken($req_url, '6', 'POST');
        $_SESSION['secret'] = $request_token_info['oauth_token_secret'];
        $_SESSION['state'] = 1;

        $targeturl = $authurl.'?oauth_token='.$request_token_info['oauth_token'].'&callback_id='.$callback_id;

        header('Location: '.$targeturl);

        exit;

    } elseif ($_SESSION['state']==1) {

        $oauth->setToken(filter_input(INPUT_GET, 'oauth_token'), $_SESSION['secret']);
        $access_token_info = $oauth->getAccessToken($acc_url, null, null, 'POST');

        $_SESSION['state'] = 2;
        $_SESSION['token'] = $access_token_info['oauth_token'];
        $_SESSION['secret'] = $access_token_info['oauth_token_secret'];

        $oauth->setToken($_SESSION['token'], $_SESSION['secret']);
        $oauth->fetch($api_url, null, 'POST');
        
        $_SESSION['oauthuserdata'] = json_decode($oauth->getLastResponse());
        
        if (empty($_SESSION['oauthuserdata']->loginUsuario)) {
            $_SESSION['state'] = 0;
            header('Location: '.$url_base.'/aut/oauth.php?erro=usuario%20inválido');
        
            // echo '<pre>';
            // print_r($oauth_result);
            // echo '</pre><br><br><pre>';
            // print_r($alfretdata);
            // echo '</pre>';

        } else {
            header('Location: ../');
        }  
    } else { 
        $_SESSION['state'] = 0;
        print_r($_SESSION);
        //header('Location: '.$url_base.'/aut/oauth.php?erro=erro%20de%20login');
    }
    
} catch(OAuthException $E) {

    print_r($E);

}
