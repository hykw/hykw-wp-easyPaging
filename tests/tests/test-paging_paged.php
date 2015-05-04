<?php

class UT_hykwEasyPaging_paged_paged extends hykwEasyUT {

  ### 表示数 = 最大ページ数
  # 1ページしか無い
  public function test_only1page()
  {
    $this->go_to('/archives/date/2009/05');

    $result = hykwEasyPaging_paged();
    $expects = array(
      hykwEasyPagingClass_paged::PAGE_CURRENT => 1,
      hykwEasyPagingClass_paged::PAGE_MAX => 1,
      hykwEasyPagingClass_paged::PAGE_PREV => FALSE,
      hykwEasyPagingClass_paged::PAGE_NEXT => FALSE,

      hykwEasyPagingClass_paged::PAGE_FIRST => 1,
      hykwEasyPagingClass_paged::PAGE_LAST => 1,
    );
    $this->assertEquals($expects, $result);
  }

  # 複数ページあるけど、全部表示される
  public function test_search()
  {
    # 1ページ目
    $this->go_to('/?s=test');

    $result = hykwEasyPaging_paged();
    $expects = array(
      hykwEasyPagingClass_paged::PAGE_CURRENT => 1,
      hykwEasyPagingClass_paged::PAGE_MAX => 2,
      hykwEasyPagingClass_paged::PAGE_PREV => FALSE,
      hykwEasyPagingClass_paged::PAGE_NEXT => 2,

      hykwEasyPagingClass_paged::PAGE_FIRST => 1,
      hykwEasyPagingClass_paged::PAGE_LAST => 2,
    );
    $this->assertEquals($expects, $result);

    # 2ページ目
    $this->go_to('/page/2?s=test');

    $result = hykwEasyPaging_paged();
    $expects = array(
      hykwEasyPagingClass_paged::PAGE_CURRENT => 2,
      hykwEasyPagingClass_paged::PAGE_MAX => 2,
      hykwEasyPagingClass_paged::PAGE_PREV => 1,
      hykwEasyPagingClass_paged::PAGE_NEXT => FALSE,

      hykwEasyPagingClass_paged::PAGE_FIRST => 1,
      hykwEasyPagingClass_paged::PAGE_LAST => 2,
    );
    $this->assertEquals($expects, $result);
  }

  # 404
  public function test_404()
  {
    $this->go_to('/aaaaaaaaaaaaaaaaa');

    $result = hykwEasyPaging_paged();
    $expects = array(
      hykwEasyPagingClass_paged::PAGE_CURRENT => 1,
      hykwEasyPagingClass_paged::PAGE_MAX => 0,
      hykwEasyPagingClass_paged::PAGE_PREV => FALSE,
      hykwEasyPagingClass_paged::PAGE_NEXT => FALSE,

      hykwEasyPagingClass_paged::PAGE_FIRST => 0,
      hykwEasyPagingClass_paged::PAGE_LAST => 0,
    );
    $this->assertEquals($expects, $result);
  }


  ### 複数ページ
  # 1～3ページ目:  [1] 2 3 4 5 ～ 1 2 [3] 4 5
  public function test_top_firstPage()
  {
    # １ページ目
    $this->go_to('/');

    $result = hykwEasyPaging_paged(5);
    $expects = array(
      hykwEasyPagingClass_paged::PAGE_CURRENT => 1,
      hykwEasyPagingClass_paged::PAGE_MAX => 9,
      hykwEasyPagingClass_paged::PAGE_PREV => FALSE,
      hykwEasyPagingClass_paged::PAGE_NEXT => 2,

      hykwEasyPagingClass_paged::PAGE_FIRST => 1,
      hykwEasyPagingClass_paged::PAGE_LAST => 5,

    );
    $this->assertEquals($expects, $result);

    # ２ページ目
    $this->go_to('/page/2');
    $result = hykwEasyPaging_paged(5);
    $expects[hykwEasyPagingClass_paged::PAGE_CURRENT] = 2;
    $expects[hykwEasyPagingClass_paged::PAGE_PREV] = 1;
    $expects[hykwEasyPagingClass_paged::PAGE_NEXT] = 3;
    $this->assertEquals($expects, $result);

    # ３ページ目
    $this->go_to('/page/3');
    $result = hykwEasyPaging_paged(5);
    $expects[hykwEasyPagingClass_paged::PAGE_CURRENT] = 3;
    $expects[hykwEasyPagingClass_paged::PAGE_PREV] = 2;
    $expects[hykwEasyPagingClass_paged::PAGE_NEXT] = 4;
    $this->assertEquals($expects, $result);

  }


  # 4～7ページ目:  2 3 [4] 5 6 ～ 5 6 [7] 8 9
  public function test_top_middlePage()
  {
    # 4ページ目
    $this->go_to('/page/4');
    $result = hykwEasyPaging_paged(5);
    $expects = array(
      hykwEasyPagingClass_paged::PAGE_CURRENT => 4,
      hykwEasyPagingClass_paged::PAGE_MAX => 9,
      hykwEasyPagingClass_paged::PAGE_PREV => 3,
      hykwEasyPagingClass_paged::PAGE_NEXT => 5,

      hykwEasyPagingClass_paged::PAGE_FIRST => 2,
      hykwEasyPagingClass_paged::PAGE_LAST => 6,
    );
    $this->assertEquals($expects, $result);


    # 5ページ目
    $this->go_to('/page/5');
    $result = hykwEasyPaging_paged(5);
    $expects = array(
      hykwEasyPagingClass_paged::PAGE_CURRENT => 5,
      hykwEasyPagingClass_paged::PAGE_MAX => 9,
      hykwEasyPagingClass_paged::PAGE_PREV => 4,
      hykwEasyPagingClass_paged::PAGE_NEXT => 6,

      hykwEasyPagingClass_paged::PAGE_FIRST => 3,
      hykwEasyPagingClass_paged::PAGE_LAST => 7,
    );
    $this->assertEquals($expects, $result);

    # 6ページ目
    $this->go_to('/page/6');
    $result = hykwEasyPaging_paged(5);
    $expects = array(
      hykwEasyPagingClass_paged::PAGE_CURRENT => 6,
      hykwEasyPagingClass_paged::PAGE_MAX => 9,
      hykwEasyPagingClass_paged::PAGE_PREV => 5,
      hykwEasyPagingClass_paged::PAGE_NEXT => 7,

      hykwEasyPagingClass_paged::PAGE_FIRST => 4,
      hykwEasyPagingClass_paged::PAGE_LAST => 8,
    );
    $this->assertEquals($expects, $result);

    # 7ページ目
    $this->go_to('/page/7');
    $result = hykwEasyPaging_paged(5);
    $expects = array(
      hykwEasyPagingClass_paged::PAGE_CURRENT => 7,
      hykwEasyPagingClass_paged::PAGE_MAX => 9,
      hykwEasyPagingClass_paged::PAGE_PREV => 6,
      hykwEasyPagingClass_paged::PAGE_NEXT => 8,

      hykwEasyPagingClass_paged::PAGE_FIRST => 5,
      hykwEasyPagingClass_paged::PAGE_LAST => 9,
    );
    $this->assertEquals($expects, $result);
  }


  # 8～9ページ目:  5 6 7 [8] 9 ～ 5 6 7 8 [9]
  public function test_top_lastPage()
  {
    # 8ページ目
    $this->go_to('/page/8');

    $result = hykwEasyPaging_paged(5);
    $expects = array(
      hykwEasyPagingClass_paged::PAGE_CURRENT => 8,
      hykwEasyPagingClass_paged::PAGE_MAX => 9,
      hykwEasyPagingClass_paged::PAGE_PREV => 7,
      hykwEasyPagingClass_paged::PAGE_NEXT => 9,

      hykwEasyPagingClass_paged::PAGE_FIRST => 5,
      hykwEasyPagingClass_paged::PAGE_LAST => 9,
    );
    $this->assertEquals($expects, $result);

    # ２ページ目
    $this->go_to('/page/9');
    $result = hykwEasyPaging_paged(5);
    $expects[hykwEasyPagingClass_paged::PAGE_CURRENT] = 9;
    $expects[hykwEasyPagingClass_paged::PAGE_PREV] = 8;
    $expects[hykwEasyPagingClass_paged::PAGE_NEXT] = FALSE;
    $this->assertEquals($expects, $result);
  }

}

