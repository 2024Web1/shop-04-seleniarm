<?php
// スーパークラスであるDbDataを利用するため
require_once __DIR__ . '/dbdata.php';

class Cart extends DbData
{
  // 商品をカートに入れる ・・ テーブルcartに登録する
  public function addItem($userId, $ident, $quantity)  // ユーザーIDも引数として受け取る
  {
    // すでにカート内にその商品がはいっているかどうかをチェックする
    $sql = "select * from cart where userId = ? and ident = ?";  // ユーザーIDも条件に追加する
    $stmt = $this->query($sql, [$userId, $ident]);
    $cart_item = $stmt->fetch();
    if ($cart_item) {
      // カート内にすでに入っているので、今回の注文数を追加する
      $new_quantity = $quantity + $cart_item['quantity'];
      if ($new_quantity > 10) $new_quantity = 10;
      $sql = "update cart set quantity = ? where userId = ? and ident = ?";  // ユーザーIDも条件に追加する
      $result = $this->exec($sql, [$new_quantity, $userId, $ident]);
    } else {
      // カート内にはまだ入っていないので登録する
      $sql = "insert into cart values(?, ?, ?)";
      $result = $this->exec($sql, [$userId, $ident, $quantity]);
    }
  }

  // カート内のすべてのデータを取り出す
  public function getItems($userId)
  {
    $sql = "select items.ident, items.name, items.maker, items.price, cart.quantity, items.image, items.genre from cart join items on cart.ident = items.ident where cart.userId = ? ";
    $stmt = $this->query($sql, [$userId]);
    $items = $stmt->fetchAll();
    return $items;
  }

  // カート内の商品を削除する
  public function deleteItem($userId, $ident)
  {
    $sql = "delete from cart where userId = ? and ident = ?";
    $result = $this->exec($sql, [$userId, $ident]);
  }

  // カート内の商品の個数を変更する
  public function changeQuantity($userId, $ident, $quantity)
  {
    $sql = "update cart set quantity = ? where userId = ? and ident = ?";
    $result = $this->exec($sql, [$quantity, $userId, $ident]);
  }

  // カート内のすべての商品を削除する
  public function clearCart($userId)
  {
    $sql = "delete from cart where userId = ?";
    $result = $this->exec($sql, [$userId]);
  }

  public function changeUserId($tempId, $userId)
  {
    $sql = "select * from cart where userId = ?";  // ユーザーIDでデータを抽出するSQL文を定義する	
    $stmt = $this->query($sql, [$tempId]);         // DbDataクラスのquery( )メソッドを呼び出す	
    $cart_items = $stmt->fetchAll();               // fetchAll( )メソッドで抽出したデータを取り出す	
    foreach ($cart_items as $item) {
      $this->addItem($userId, $item['ident'], $item['quantity']);  // 正式なユーザーIdでaddItem( )メソッドを呼び出す	
      $this->deleteItem($tempId, $item['ident']);                 // 仮のユーザーIdでdeleteItem( )メソッドを呼び出す	
    }
  }
}
