余計な出力は何もせず、ページング表示用のデータを返す WordPress プラグイン
----------
自前の表示ロジックなどにより出力する場合などのため、余計な出力やタグなどを埋めこまず、単にページング表示用のデータを連想配列で返すプラグインです。

# 使い方

```php
# WP_Query()を呼んでいない場合(通常の場合)
$ret = hykwEasyPaging();

# WP_Query()により、任意の検索条件でデータを引っぱっている場合
$searchArg = array(
  'post_type' => array(
    'post',
  ),
  'post_status' => array(
    'publish',
  ),
  'paged' => get_query_var('paged'),
  'order' => 'DESC',
  'orderby' => 'date',
);

$wpq = new WP_Query($searchArg);
$ret = hykwEasyPaging($wpq);

```

リターン値として、下記のようなデータが返ってきます。

```php
array(
  'page_prev'     # 前ページのページ番号（前ページが無い場合はFALSE)
  'page_current'  # 現在のページ番号
  'page_next'     # 次ページのページ番号（次ページが無い場合はFALSE)
  'page_max'      # 総ページ数(= 最終ページ番号)
)

※ 404 の場合、page_maxには0が入っています

# 例）
# 現在のページ番号が1で、総ページが10ページある場合
array(
  'page_prev' => FALSE,
  'page_current' => 1,
  'page_next' => 2,
  'page_max' => 10,
);

```
