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
    // コンテンツの実URLを導出
    $base_url = $this->getBaseUrl();
    $content_dir = $this->getConfig('content_dir');
    
    $file_url = substr($pageData["url"], strlen($base_url));
    $file_url_len = strlen($file_url);
    if(!$file_url_len || $file_url[$file_url_len - 1] == "/")
    {
      $file_url .= 'index';
    }
    $rootdir = $this->getPico()->getRootDir();
    $dir = substr($content_dir, strlen($rootdir));
    $path = pathinfo($file_url);
    $path['dirname'] = $path['dirname'] == "." ? "" : 
      $path['dirname'] . "/";

    // イメージパスを展開する内部メソッド
    $pathSettlement = function($img) use ($base_url, $dir, $path) {
      if (strlen($img) > 0) {
        if($img[0] == '.'){
          $img = $base_url . "$dir${path['dirname']}${path['filename']}$img";
        }else if(!preg_match('/^(https?|ftp)/', $img)){
          $img = $base_url . "$dir${path['dirname']}$img";
        }
      } else {
        $img = NULL;
      }
      return $img;
    };

    // メタデータ「image」のパス変換
    if(isset($pageData['meta']['image'])) {
      $pageData['image'] = $pathSettlement($pageData['meta']['image']);
    }

    // メタデータ「content」のパス変換
    if(isset($pageData['content'])) {
      var_dump($pageData['content']);
      $pageData['content'] = preg_replace_callback("|!\[([^]]*)\]\(([^\)]*)\)|", 
      function($m) use ($pathSettlement){
        $imgpath = $pathSettlement($m[2]);
        return "![$m[1]]($imgpath)";
      }, $pageData['content']);
    }
	}
}

?>