<?php
/**
 * Pico ImageCurDir Plugin
 *
 * @author TakamiChie
 * @link http://onpu-tamago.net/
 * @license http://opensource.org/licenses/MIT
 * @version 1.1
 */
class ImageCurDir extends AbstractPicoPlugin{

  protected $enabled = false;

  public function onMetaHeaders(array &$headers)
  {
  	$headers['image'] = 'Image';
  }
	
	public function onSinglePageLoaded(array &$pageData)
  {
    $base_url = $this->getBaseUrl();
    $content_dir = $this->getConfig('content_dir');
    
    $file_url = substr($pageData["url"], strlen($base_url));
    if($file_url[strlen($file_url) - 1] == "/") $file_url .= 'index';
    $img = $pageData['meta']['image'];
    // rootdirを除去する(1.0対応)
    $rootdir = $this->getPico()->getRootDir();
    $dir = substr($content_dir, strlen($rootdir));
    if (strlen($img) > 0 && preg_match('/^(.*\/)[\w\.-]+$/', $file_url, $m)) {
      if($img[0] == '.'){
        $img = $base_url . "$dir/$m[0]$img";
      }else if(!preg_match('/^(https?|ftp)/', $img)){
        $img = $base_url . "$dir/$m[1]$img";
      }
    } else {
      $img = NULL;
    }
    $pageData['image'] = $img;
	}
}

?>