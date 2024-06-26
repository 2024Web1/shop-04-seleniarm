<?php
// 現在アクセスしているユーザーのユーザーID($userId)とユーザー名($userName)を特定する																	
// セッションからもクッキーからも取得できなかった場合は、仮のユーザーIDを発行する																	
// この場合、ユーザーIDは「8桁の乱数」、ユーザー名は「ゲスト」とする																	

// セッションが開始されていなければ、開始する																	
if (!isset($_SESSION)) {
    session_start();
}

// セッション情報としてユーザーID、名前が保持されているなら、それを取得する(三項演算子を使用している）																	
$userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : '';  // 最後の「''」はシングルクォーテーション２つ。		
$userName = isset($_SESSION['userName']) ? $_SESSION['userName'] : '';

// セッション情報にユーザーID、名前が保持されていない場合で																	
if (empty($userId) || empty($userName)) {
    // クッキーにユーザーID、名前が保持されているなら、それを取得する																	
    if (isset($_COOKIE['userId']) && isset($_COOKIE['userName'])) {
        $userId = $_COOKIE['userId'];
        $userName = $_COOKIE['userName'];
        // クッキーにもユーザーID、名前が保持されていないなら、8桁の乱数を発生させて仮のIDとしユーザー名はゲストとする																	
        // クッキーの有効期限は2週間とする																	
    } else {
        $userId = (string)mt_rand(10000000, 99999999);
        $userName = 'ゲスト';
        setcookie("userId", $userId, time() + 60 * 60 * 24 * 14, '/');
        setcookie("userName", $userName, time() + 60 * 60 * 24 * 14, '/');
    }

    // 以上で決定したユーザーID、名前をセッション情報として保持する																	
    $_SESSION['userId'] = $userId;  // セッション変数を「userId」としてユーザーIDの値を保持する								
    $_SESSION['userName'] = $userName;  // セッション変数を「userName」としてユーザー名の値を保持する								
}

// ヘッダー・フッターで使用するリンクのFQDN(Fully Qualified Domain Name)作成の準備															
$http_host = '//' . $_SERVER['SERVER_NAME'];  // 「(http: or https:)//○○○○○○○○」を取得 ・・・①
$shop_id = mb_strstr($_SERVER['REQUEST_URI'], 'src', true);  // 「shop-○○-GitHubのユーザー名」を取得
$shop_path = $shop_id . 'src/'; // srcフォルダ直下までのパスを格納(例:http://localhost/shop-01-GitHubのユーザー名/src/) 

// ヘッダー・フッターで使用するリンクのURLを用意する															
$index_php = $http_host . $shop_path . 'index.php';  // index.phpのURL
$cart_list_php = $http_host . $shop_path . 'cart/cart_list.php';  // カートのURL
$order_history_php = $http_host . $shop_path . 'order/order_history.php';  // 注文履歴のURL
$login_php = $http_host . $shop_path . 'user/login.php';  // ログインのURL
$logout_php = $http_host . $shop_path . 'user/logout.php';  // ログアウトのURL
$signup_php = $http_host . $shop_path . 'user/signup.php';  // ユーザー情報のURL

// CSSファイルのURLを用意する															
$shop_css = $http_host . $shop_path . 'css/shop.css';
