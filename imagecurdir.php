<?php
/**
 * Pico ImageCurDir Plugin
 *
 * @author TakamiChie
 * @link http://onpu-tamago.net/
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
class ImageCurDir extends AbstractPicoPlugin{

  private $base_url;
  
  public function onConfigLoaded(array &$config) {
    $this->base_url = $config['base_url'];
    $this->content_dir = $config['content_dir'];
  }

  public function onMetaHeaders(array &$headers)
  {
  	$headers['image'] = 'Image';
  }
	
	public function onSinglePageLoaded(array &$pageData)
  {
    $file_url = substr($pageData["url"], strlen($this->base_url));
    if($file_url[strlen($file_url) - 1] == "/") $file_url .= 'index';
    $img = $pageData['meta']['image'];
    // rootdirを除去する(1.0対応)
    $rootdir = $this->getPico()->getRootDir();
    $dir = substr($this->content_dir, strlen($rootdir));
    if (strlen($img) > 0 && preg_match('/^(.*\/)[\w\.-]+$/', $file_url, $m)) {
      if($img[0] == '.'){
        $img = $this->base_url . "/$dir$m[0]$img";
      }else if(!preg_match('/^(https?|ftp)/', $img)){
        $img = $this->base_url . "/$dir$m[1]$img";
      }
    } else {
      $img = NULL;
    }
    $pageData['image'] = $img;
	}
}

?>