<?php
/**
 * Pico ImageCurDir Plugin
 *
 * @author TakamiChie
 * @link http://onpu-tamago.net/
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
class ImageCurDir{

  private $base_url;
  
  public function config_loaded(&$settings) {
    $this->base_url = $settings['base_url'];
    $this->content_dir = $settings['content_dir'];
  }

  public function before_read_file_meta(&$headers)
  {
  	$headers['image'] = 'Image';
  }
	
	public function get_page_data(&$data, $page_meta)
	{
    $file_url = substr($data["url"], strlen($this->base_url));
    if($file_url[strlen($file_url) - 1] == "/") $file_url .= 'index';
    if (strlen($page_meta['image']) > 0 && preg_match('/^(.*\/)[\w\.-]+$/', $file_url, $m)) {
      if($page_meta['image'][0] == '.'){
        $data['image'] = $this->base_url . "/" . $this->content_dir . "$m[0]$page_meta[image]";
      }else if(!preg_match('/^(https?|ftp)/', $page_meta['image'])){
        $data['image'] = $this->base_url . "/" . $this->content_dir . "$m[1]$page_meta[image]";
      }else{
        $data['image'] = $page_meta['image'];
      }
    } else {
      $data['image'] = NULL;
    }
	}
}

?>