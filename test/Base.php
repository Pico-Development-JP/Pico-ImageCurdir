<?php 
if (php_sapi_name() != 'cli') return;
require_once(__DIR__."/../../../lib/test.php");
require_once(__DIR__."/../imagecurdir.php");

class ImageCurDirTestBase extends PHPUnit_Framework_TestCase {

  public function setUp() {
    $this->pico = $GLOBALS['pico'];
    $this->test = new ImageCurDir($this->pico);
  }
  
  protected function getBaseUrl(){
    $base_url = $this->pico->getBaseUrl();
    $content_dir = $this->pico->getConfig('content_dir');
    $rootdir = $this->pico->getRootDir();
    $dir = substr($content_dir, strlen($rootdir));
    return $base_url . $dir;
  }
  
  /**
   * 警告を出さないためのダミーテスト
   */
  public function test(){
    $this->assertNull(null);
  }
};

?>