<?php 
if (php_sapi_name() != 'cli') return;
require_once("Base.php");

/**
 * メタデータ「Content」に関するテスト
 */
class ContentTest extends ImageCurDirTestBase {

  /**
   * イメージ指定がないときに、ページイメージが設定されないことを確認する
   */
  public function testOnSPL_NoImage() {
    $pd = $this->getPageData();
    $content = $pd["content"];
    $this->test->onSinglePageLoaded($pd);
    $this->assertEquals($content, $pd["content"]);
  }

  /**
   * 以下条件のイメージ指定がある場合、ページイメージが設定されることを確認する
   *
   * イメージ指定：ファイル名
   */
  public function testOnSPL_ImageFullName() {
    $pd = $this->getPageData("pageimage.png");
    $bu = $this->getBaseUrl();
    $this->test->onSinglePageLoaded($pd);
    $this->assertContains("(${bu}sub/pageimage.png)", $pd['content']);
  }

  /**
   * 以下条件のイメージ指定がある場合、ページイメージが設定されることを確認する
   *
   * イメージ指定：拡張子のみ
   */
  public function testOnSPL_ImageExtensionsOnly() {
    $pd = $this->getPageData(".png");
    $bu = $this->getBaseUrl();
    $this->test->onSinglePageLoaded($pd);
    $this->assertContains("(${bu}sub/page.png)", $pd['content']);
  }

  /**
   * 以下条件のイメージ指定がある場合、ページイメージが設定されることを確認する
   *
   * イメージ指定：URL
   */
  public function testOnSPL_ImageInvalidText() {
    $u = "http://onpu-tamago.net/themes/onpunew/images/banner_l.png";
    $pd = $this->getPageData($u);
    $this->test->onSinglePageLoaded($pd);
    $this->assertContains("($u)", $pd['content']);
  }

  /**
   * 以下条件のイメージ指定がある場合、ページイメージが設定されることを確認する
   *
   * ページURL：/で終わるURL
   * イメージ指定：拡張子のみ
   */
  public function testOnSPL_URLEndedSlash() {
    $pd = $this->getPageData(".png", "sub/");
    $bu = $this->getBaseUrl();
    $this->test->onSinglePageLoaded($pd);
    $this->assertContains("(${bu}sub/index.png)", $pd['content']);
  }

  /**
   * 以下条件のイメージ指定がある場合、ページイメージが設定されることを確認する
   *
   * ページURL：空
   * イメージ指定：拡張子のみ
   */
  public function testOnSPL_URLIsEmpty() {
    $pd = $this->getPageData(".png", "");
    $bu = $this->getBaseUrl();
    $this->test->onSinglePageLoaded($pd);
    $this->assertContains("(${bu}index.png)", $pd['content']);
  }
  /**
   * 以下条件のイメージ指定がコンテント内に二つある場合、ページイメージが設定されることを確認する
   *
   * イメージ指定：ファイル名
   */
  public function testOnSPL_ImageTwice() {
    $pd = $this->getPageData("pageimage.png");
    $pd["content"] .= "\n![Test Image](test.png)";
    $bu = $this->getBaseUrl();
    $this->test->onSinglePageLoaded($pd);
    $this->assertContains("(${bu}sub/pageimage.png)", $pd['content']);
    $this->assertContains("(${bu}sub/test.png)", $pd['content']);
  }
  
  private function getPageData($imagename = null, $url = "sub/page") {
    $pd = array("url" => $this->pico->getBaseUrl() . $url, "meta" => array());
    if($imagename) {
      $pd["content"] = <<<TEXT
This is a Test
![Test Image]($imagename)
This is a Test
TEXT;
    }else{
      $pd["content"] = "";
    }
    return $pd;
  }

}

?>