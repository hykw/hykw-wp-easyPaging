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
    $ret['page_current'] = $page_current;

    # RET: 総ページ数
    $page_max = intval($wp_query->max_num_pages);
    $ret['page_max'] = $page_max;

    # RET: 前ページのページ番号
    if ($page_current == 1)
      $page_prev = FALSE;
    else
      $page_prev = $page_current - 1;
    $ret['page_prev'] = $page_prev;

    # RET: 次ページのページ番号
    if ($page_current >= $page_max)
      $page_next = FALSE;
    else
      $page_next = $page_current + 1;
    $ret['page_next'] = $page_next;

    return $ret;
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

