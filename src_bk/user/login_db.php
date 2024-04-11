<?php
// 送られてきたゆーざーIDとパスワードを受け取る
$userId = $_POST['userId'];
$password = $_POST['password'];

// Userオブジェクトを生成し、「authUser()メソッド」を呼び出し、認証結果を受け取る
require_once __DIR__ . '/../classes/user.php';
$user = new User();
$result = $user->authUser($userId, $password);

session_start();
//ログインに失敗した場合、エラーメッセージをセッションに保存し、ログイン画面に遷移する
if (empty($result['userId'])) { //userIdが格納されていないなら
    $_SESSION['login_error'] = 'ユーザーID、パスワードを確認してください。';
    header('Location: login.php');
    exit();
}

// データベースから名前を取り出す
$userName = $result['userName'];

// cartテーブルに仮のuserIdで保存された商品があれば正式なログインユーザーのuserIdにへんこうする
$user->changeCartUserId($_SESSION['userId'], $userId);

// ユーザー情報をセッションに保持する
$_SESSION['userId'] = $userId;
$_SESSION['userName'] = $userName;
$_SESSION['kana'] = $result['kana'];
$_SESSION['zip'] = $result['zip'];
$_SESSION['address'] = $result['address'];
$_SESSION['tel'] = $result['tel'];

// ユーザーIDと名前をクッキーに保持する
setcookie("userId", $userId, time() + 60 * 60 * 24 * 14);
setcookie("userName", $userName, time() + 60 * 60 * 24 * 14);

require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../util.php';
?>
<p>こんにちは、<?= h($userName); ?>さん。</p> <!-- エスケープ処理をした上で、ユーザー名を表示する -->
<p>ショッピングをお楽しみください。</p>
<?php
require_once __DIR__ . '/../footer.php';
?>