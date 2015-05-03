<?php

class UT_hykwEasyPaging extends hykwEasyUT {

  ### TOP
  public function test_top()
  {
    # 1ページ目
    $this->go_to('/');

    $result = hykwEasyPaging();
    $expects = array(
      'page_current' => 1,
      'page_max' => 9,
      'page_prev' => FALSE,
      'page_next' => 2,
    );
    $this->assertEquals($expects, $result);

    # 8ページ目
    $this->go_to('/page/8');

    $result = hykwEasyPaging();
    $expects = array(
      'page_current' => 8,
      'page_max' => 9,
      'page_prev' => 7,
      'page_next' => 9,
    );
    $this->assertEquals($expects, $result);

    # 9ページ目
    $this->go_to('/page/9');

    $result = hykwEasyPaging();
    $expects = array(
      'page_current' => 9,
      'page_max' => 9,
      'page_prev' => 8,
      'page_next' => FALSE,
    );
    $this->assertEquals($expects, $result);
  }


  ### カテゴリ以下
  public function test_category()
  {
    # 1ページ目
    $this->go_to('/archives/category/%E6%9C%AA%E5%88%86%E9%A1%9E');

    $result = hykwEasyPaging();
    $expects = array(
      'page_current' => 1,
      'page_max' => 3,
      'page_prev' => FALSE,
      'page_next' => 2,
    );
    $this->assertEquals($expects, $result);

    # 2ページ目
    $this->go_to('/archives/category/%E6%9C%AA%E5%88%86%E9%A1%9E/page/2');

    $result = hykwEasyPaging();
    $expects = array(
      'page_current' => 2,
      'page_max' => 3,
      'page_prev' => 1,
      'page_next' => 3,
    );
    $this->assertEquals($expects, $result);

    # 3ページ目
    $this->go_to('/archives/category/%E6%9C%AA%E5%88%86%E9%A1%9E/page/3');

    $result = hykwEasyPaging();
    $expects = array(
      'page_current' => 3,
      'page_max' => 3,
      'page_prev' => 2,
      'page_next' => FALSE,
    );
    $this->assertEquals($expects, $result);
  }


  ### 1ページしか無いパターン
  public function test_only1page()
  {
    $this->go_to('/archives/date/2009/05');

    $result = hykwEasyPaging();
    $expects = array(
      'page_current' => 1,
      'page_max' => 1,
      'page_prev' => FALSE,
      'page_next' => FALSE,
    );
    $this->assertEquals($expects, $result);
  }


  ### 検索結果
  public function test_search()
  {
    # 1ページ目
    $this->go_to('/?s=test');

    $result = hykwEasyPaging();
    $expects = array(
      'page_current' => 1,
      'page_max' => 2,
      'page_prev' => FALSE,
      'page_next' => 2,
    );
    $this->assertEquals($expects, $result);

    # 2ページ目
    $this->go_to('/page/2?s=test');

    $result = hykwEasyPaging();
    $expects = array(
      'page_current' => 2,
      'page_max' => 2,
      'page_prev' => 1,
      'page_next' => FALSE,
    );
    $this->assertEquals($expects, $result);
  }


  ### 404
  public function test_404()
  {
    $this->go_to('/aaaaaaaaaaaaaaaaa');

    $result = hykwEasyPaging();
    $expects = array(
      'page_current' => 1,
      'page_max' => 0,
      'page_prev' => FALSE,
      'page_next' => FALSE,
    );
    $this->assertEquals($expects, $result);
  }


  ### 任意のWP_Query()のパターン
  public function test_customized_WP_Query()
  {
    $this->go_to('/');

    # 複数ページ
    $searchArg = array(
      'post_type' => array(
        'post',
      ),
      'post_status' => array(
        'publish',
      ),
    );

    $wpq = new WP_Query($searchArg);
    $result = hykwEasyPaging($wpq);
    wp_reset_postdata();

    $expects = array(
      'page_current' => 1,
      'page_max' => 9,
      'page_prev' => FALSE,
      'page_next' => 2,
    );
    $this->assertEquals($expects, $result);


    # 1ページのみ
    $searchArg = array(
      'post_type' => array(
        'post',
      ),
      'post_status' => array(
        'draft',
      ),
    );

    $wpq = new WP_Query($searchArg);
    $result = hykwEasyPaging($wpq);
    wp_reset_postdata();

    $expects = array(
      'page_current' => 1,
      'page_max' => 1,
      'page_prev' => FALSE,
      'page_next' => FALSE,
    );
    $this->assertEquals($expects, $result);
  }

}

