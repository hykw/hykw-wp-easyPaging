余計な出力は何もせず、ページング表示用のデータを返す WordPress プラグイン
----------
自前の表示ロジックなどにより出力する場合などのため、余計な出力やタグなどを埋めこまず、単にページング表示用のデータを連想配列で返すプラグインです。

# 使い方
## カレント/最大ページ番号がわかればいいだけの場合

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

### リターン値

```php
array(
  hykwEasyPagingClass_paged::PAGE_PREV     # 前ページのページ番号（前ページが無い場合はFALSE)
  hykwEasyPagingClass_paged::PAGE_CURRENT  # 現在のページ番号
  hykwEasyPagingClass_paged::PAGE_NEXT     # 次ページのページ番号（次ページが無い場合はFALSE)
  hykwEasyPagingClass_paged::PAGE_MAX      # 総ページ数(= 最終ページ番号)
)

※ 404 の場合、page_maxには0が入っています

# 例）
# 現在のページ番号が1で、総ページが10ページある場合
array(
  hykwEasyPagingClass_paged::PAGE_PREV => FALSE,
  hykwEasyPagingClass_paged::PAGE_CURRENT => 1,
  hykwEasyPagingClass_paged::PAGE_NEXT => 2,
  hykwEasyPagingClass_paged::PAGE_MAX => 10,
);
```


## 途中のページ番号も欲しい場合

```
最初 < 1 2 [3] 4 5 > 最後
```

みたいな表示をする時のデータが必要な場合


```php
# $show_page_nums: ページ番号の表示数(無指定の場合=5)
$ret = hykwEasyPaging_paged($show_page_nums, $wpq);

# 例）
$ret = hykwEasyPaging_paged();
$ret = hykwEasyPaging_paged(5);
$ret = hykwEasyPaging_paged(5, $wpq);
```

### リターン値(hykwEasyPaging()のリターン値＋この関数のリターン値が返る）

```php
array(
  hykwEasyPagingClass_paged::PAGE_PREV     # 前ページのページ番号（前ページが無い場合はFALSE)
  hykwEasyPagingClass_paged::PAGE_CURRENT  # 現在のページ番号
  hykwEasyPagingClass_paged::PAGE_NEXT     # 次ページのページ番号（次ページが無い場合はFALSE)
  hykwEasyPagingClass_paged::PAGE_MAX      # 総ページ数(= 最終ページ番号)

  hykwEasyPagingClass_paged::PAGE_FIRST  # ページ表示の最初のページ
  hykwEasyPagingClass_paged::PAGE_LAST    # ページ表示の最後のページ
)

# 例）
array(
  hykwEasyPagingClass_paged::PAGE_PREV => 2,
  hykwEasyPagingClass_paged::PAGE_CURRENT => 3,
  hykwEasyPagingClass_paged::PAGE_NEXT => 4,
  hykwEasyPagingClass_paged::PAGE_MAX => 10,

  hykwEasyPagingClass_paged::PAGE_FIRST => 1,
  hykwEasyPagingClass_paged::PAGE_LAST => 5,
);

```

### ページングのパターン
可能な限り、現在ページが中心に来るようにします(偶数の場合は左)

# 全部で7ページ、ページ番号の表示数=5の場合

```奇数
[1] 2 3 4 5
1 [2] 3 4 5
1 2 [3] 4 5
2 3 [4] 5 6
3 4 [5] 6 7
3 4 5 [6] 7
3 4 5 6 [7]
```

# 全部で7ページ、ページ番号の表示数=4の場合
```偶数
[1] 2 3 4
1 [2] 3 4
2 [3] 4 5
3 [4] 5 6
4 [5] 6 7
4 5 [6] 7
4 5 6 [7]
```

