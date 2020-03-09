# PHPを使った簡易通販サイト

## 開発環境

PHP/MySQL/phpMyAdmin/Github/Visual Studio Code

## 概要

PHPを学ぶことを目標に、書籍を参考に作成、追加実装しました。  
CSSなど、見た目の設定はほとんどしていません。

## 実装したこと

- スタッフとしてログインができ、スタッフの追加、削除、名前などの編集ができる
- 商品の追加、編集、削除ができる。また商品画像が投稿できる（サイズ制限あり）
- `$_SESSION` でユーザー認証を実装し、URLで直接アクセスした場合『ログインしてください』の警告が出る
- 商品をカートに入れることができる。また数量変更、削除ができる
- ゲストと会員で買い物の処理が変わる。会員の場合は住所の入力を飛ばすことができる
- 注文が確定するとメールがお客様と自分に自動送信される（ローカル環境のため実際にメールを受信するかは未確認）

## 独自で実装したこと

- 性別などで分ける場合 `if` がやや冗長に感じられたため `switch` に変更
- 変数名が `$onamae` など日本語で書かれていたため英語名に変更、またデータベースのカラム名も修正  
  それに伴ってMySqlのアクセスに使う変数 `$name` とダブったためエラーに悩まされたといったことが何度かありました……
- 注文確定後にカートの中身が空になるように追加実装
- MySqlにアクセスする際のエラー処理を推奨される書き方に変更

```php
catch (Exception $e){
  print 'エラー';
  exit();
}
```

↓

```php
catch (PDOException $e){
  print 'エラー<br>' .$e->getMessage().'<br>';
  exit();
}
```

## できなかったこと

- リダイレクトがうまくいかなかった  
  `<a href="index.php">` を `<a href="redirect.php">` に変えると、なぜかどこにも指定してないのにindex.phpに飛ぶ。
  `<a href="index.php">` を `<a href="in.php">` にするとエラーになる。
  別ファイルを指定するとちゃんと飛ぶ……。

- csvファイルで注文データをダウンロードできなかった  
  fopenしてcsvをダウンロードさせようとすると出てくるエラーが解決できなかった。
  調べると「 `chmod` で権限変える」といった方法が出てくるけど、うまくいかなかった。

  `Warning: fopen(./ファイル名.csv): failed to open stream: Permission denied in`
  
## DB

- dat_member（会員登録したお客様）
- dat_order_customer（買い物したお客様）
- dat_order_product（購入された商品）
- mst_product（商品）
- mst_staff（スタッフ）