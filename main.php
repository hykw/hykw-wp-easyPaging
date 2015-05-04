<?php
  /*
    Plugin Name: HYKW easy Paging
    Plugin URI: https://github.com/hykw/hykw-wp-easyPaging
    Description: ページング用データを返すプラグイン
    Author: Hitoshi Hayakawa
    version: 1.0.0
  */

class hykwEasyPagingClass
{
  const PAGE_CURRENT = 'page_current';
  const PAGE_MAX = 'page_max';
  const PAGE_PREV = 'page_prev';
  const PAGE_NEXT = 'page_next';

  private $wp_query;

  function __construct($wp_query_userdefined)
  {
    # WP_Query()を呼んでない場合、デフォルトの $wp_query の方を使う
    if ($wp_query_userdefined == FALSE) {
      global $wp_query;
      $this->wp_query = $wp_query;
    } else {
      $this->wp_query = $wp_query_userdefined;
    }
  }


  /**
   * get_page_array 指定(or default)の$wp_queryからデータを作って返す
   */
  function get_page_array()
  {
    $ret = array();
    $wp_query = $this->wp_query;

    # RET: 現在のページ番号
    $page_current = get_query_var('paged') ? get_query_var('paged') : 1;
    $ret[self::PAGE_CURRENT] = $page_current;

    # RET: 総ページ数
    $page_max = intval($wp_query->max_num_pages);
    $ret[self::PAGE_MAX] = $page_max;

    # RET: 前ページのページ番号
    if ($page_current == 1)
      $page_prev = FALSE;
    else
      $page_prev = $page_current - 1;
    $ret[self::PAGE_PREV] = $page_prev;

    # RET: 次ページのページ番号
    if ($page_current >= $page_max)
      $page_next = FALSE;
    else
      $page_next = $page_current + 1;
    $ret[self::PAGE_NEXT] = $page_next;

    return $ret;
  }
}


class hykwEasyPagingClass_paged extends hykwEasyPagingClass
{
  const PAGE_FIRST = 'page_first';
  const PAGE_LAST = 'page_last';

  private $show_page_nums;

  function __construct($show_page_nums, $wp_query_userdefined)
  {
    parent::__construct($wp_query_userdefined);
    $this->show_page_nums = $show_page_nums;
  }

  function get_page_array()
  {
    $ret_parent = parent::get_page_array();

    $page_current = $ret_parent[self::PAGE_CURRENT];
    $page_max = $ret_parent[self::PAGE_MAX];

    # ページ表示数 >= 最大ページ数の場合、特に処理は必要ない
    if ($this->show_page_nums >= $ret_parent[self::PAGE_MAX]) {
      $ret = array();

      # 404 の場合、first/last 共に0
      if ($page_max == 0) {
        $ret[self::PAGE_FIRST] = 0;
        $ret[self::PAGE_LAST] = 0;
      } else {
        $ret[self::PAGE_FIRST] = 1;
        $ret[self::PAGE_LAST] = $page_max;
      }

      return $ret_parent + $ret;
    } else {
      $show_page_nums = $this->show_page_nums;

      /*
      現在ページを中心に表示する上限／下限の幅
        e.g.  2 3 [4] 5 6,  の場合、+-2 なので、値は2
       */
      $page_width = intval(floor($show_page_nums / 2));

      # ページ番号のスクロール無しで表示する範囲
      $idx_noScroll_left = $page_width + ($show_page_nums % 2);   # 1〜この値まではスクロール無し
      $idx_noScroll_right = ($page_max - $show_page_nums)+$page_width+1;  # この値〜総ページ数まではスクロール無し

      # ページ番号スクロール時の、左右のページ番号
      $idx_scroll_left = $page_current - $page_width;
      $idx_scroll_right = $page_current + $page_width;

      # RET: ページング開始番号 / ページング終了番号
      $ret = array();
      if ($page_current <= $idx_noScroll_left) {
        # まだスクロールしない
        $ret[self::PAGE_FIRST] = 1;
        $ret[self::PAGE_LAST] = $show_page_nums;
      } elseif ($idx_noScroll_right <= $page_current) {
        # スクロール終わり
        $ret[self::PAGE_FIRST] = $idx_noScroll_right - $page_width;
        $ret[self::PAGE_LAST] = $page_max;
      } else {
        # 途中表示(例： 4 5 [6] 7 8)
        $ret[self::PAGE_FIRST] = $idx_scroll_left;
        $ret[self::PAGE_LAST] = $idx_scroll_right;
      }

      return $ret_parent + $ret;
    }

    return array();  // just in case
  }
}


/**
 * hykwEasyPaging ページング表示用のデータを返す
 * 
 * @param array $wp_query globalの$wp_queryのかわりに使う$wp_query
 * @return array
 */
function hykwEasyPaging($wp_query = FALSE)
{
  $objEP = new hykwEasyPagingClass($wp_query);
  $ret = $objEP->get_page_array();

  return $ret;
}


/**
 * hykwEasyPaging_paged ページング表示用データを返す(途中のページ番号付き）
 * 
 * @param integer $show_page_nums ページ番号の表示数
 * @param array $wp_query globalの$wp_queryのかわりに使う$wp_query
 * @return array
 */
function hykwEasyPaging_paged($show_page_nums = 5, $wp_query = FALSE)
{
  $objEP = new hykwEasyPagingClass_paged($show_page_nums, $wp_query);
  $ret = $objEP->get_page_array();

  return $ret;
}

