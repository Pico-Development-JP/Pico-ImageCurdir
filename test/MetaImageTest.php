<?php 
if (php_sapi_name() != 'cli') return;
require_once("Base.php");

/**
 * メタデータ「Image」に関するテスト
 */
class MetaImageTest extends ImageCurDirTestBase {

  /**
   * イメージ指定がないときに、ページイメージが設定されないことを確認する
   */
  public function testOnSPL_NoImage() {
    $pd = $this->getPageData();
    $this->test->onSinglePageLoaded($pd);
    $this->assertFalse(isset($pd['image']));
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
    $this->assertEquals("${bu}sub/pageimage.png", $pd['image']);
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
    $this->assertEquals("${bu}sub/page.png", $pd['image']);
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
    $this->assertEquals($u, $pd['image']);
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
    $this->assertEquals("${bu}sub/index.png", $pd['image']);
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
    $this->assertEquals("${bu}index.png", $pd['image']);
  }
  
  private function getPageData($imagename = null, $url = "sub/page") {
    $pd = array("url" => $this->pico->getBaseUrl() . $url, "meta" => array());
    if($imagename) {
      $pd["meta"]["image"] = $imagename;
    }
    return $pd;
  }

}

?>