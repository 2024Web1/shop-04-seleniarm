<?php
session_start();

// ログインしていなければログイン画面に遷移させる																	
if ($_SESSION['userName']  ===  "ゲスト") {    // セッション情報のユーザー名が「ゲスト」ならば					
    header('Location: ../user/login.php');    // ログイン画面に遷移する					
    exit();
}
// ユーザー情報をセッションから取得																
$userName = $_SESSION['userName'];        // 名前					
$zip = $_SESSION['zip'];                            // 郵便番号					
$address = $_SESSION['address'];            // 住所					
$tel = $_SESSION['tel'];                            // 電話番号					

// Cartオブジェクトを生成する																
require_once __DIR__ . '/../classes/cart.php';
$cart = new Cart();
// カート内の全ての商品を取り出す 																
$cartItems = $cart->getItems($_SESSION['userId']);    // 現在このサイトを利用しているユーザーIDを引数で指定					

require_once  __DIR__  .  '/../header.php';        // header.phpを読み込む					
require_once  __DIR__  .  '/../util.php';            // util.phpを読み込む					
?>

<p>注文内容をご確認ください。
    <a href="../order/order_now.php"><span class="button_image2">注文を確定する</span></a>
</p>
<table>
    <caption>お届け先</caption>
    <tr>
        <td>お名前</td>
        <td><?= h($userName) ?></td>
    </tr>
    <tr>
        <td>郵便番号</td>
        <td><?= h(mb_substr($zip, 0, 3)) ?>-<?= h(mb_substr($zip, 3)) ?></td>
    </tr>
    <tr>
        <td>住所</td>
        <td><?= h($address) ?></td>
    </tr>
    <tr>
        <td>電話番号</td>
        <td><?= h($tel) ?></td>
    </tr>
    <tr>
        <td colspan="2" class="td_center">
            <a href="../user/signup.php"><span class="button_image">お届け先情報を変更する</span></a>
        </td>
    </tr>
</table>
<table>
    <caption>注文内容</caption>
    <tr>
        <th>&nbsp;</th>
        <th>商品名</th>
        <th>メーカー・著者<br>アーティスト</th>
        <th>価格</th>
        <th>注文数</th>
        <th>金額</th>
    </tr>
    <?php
    $total = 0;                                            // 合計金額用									
    foreach ($cartItems as $item) {        // 取り出したカート内の商品の合計金額を算出する									
        $total += $item['price'] * $item['quantity'];
    ?>
        <tr>
            <td class="td_mini_img"><img class="mini_img" src="../images/<?= $item['image'] ?>"></td>
            <td class="td_item_name"><?= $item['name'] ?></td>
            <td class="td_item_maker"><?= $item['maker'] ?></td>
            <td class="td_right">&yen;<?= number_format($item['price']) ?></td>
            <td class="td_right"><?= $item['quantity'] ?></td>
            <td class="td_right">&yen;<?= number_format($item['price'] * $item['quantity']) ?></td>
        </tr>
    <?php
    }                                            // foreach文の終わり									
    ?>
    <tr>
        <th colspan="5">合計金額</th>
        <td class="td_right">&yen;<?= number_format($total) ?></td>
    </tr>
    <tr>
        <td colspan="6" class="td_center">
            <a href="../cart/cart_list.php"><span class="button_image">注文内容を変更する</span></a>
        </td>
    </tr>
</table>
<?php
require_once  __DIR__  .  '/../footer.php';    // footer.phpを読み込む									
?>