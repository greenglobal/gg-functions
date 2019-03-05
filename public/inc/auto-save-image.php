<?php 
class Auto_Save_Images{
  function __construct(){     
    add_filter( 'content_save_pre',array($this,'post_save_images') ); 
  }
  function post_save_images( $content ){
    if((isset($_POST['save']) || isset($_POST['publish'])) && ($_POST['save'] || $_POST['publish'] )){
      set_time_limit(240);
      global $post;
      $post_id=$post->ID;
      $preg=preg_match_all('/<img.*?src="(.*?)"/',stripslashes($content),$matches);
      if($preg){
        foreach($matches[1] as $image_url){
          if(empty($image_url)) continue;
          $pos=strpos($image_url,$_SERVER['HTTP_HOST']);
          if($pos===false){
            $res=$this->save_images($image_url,$post_id);
            $content=str_replace($image_url,$res,$content);
            // var_dump($content);die;
          }
        }
      }
    }
    remove_filter( 'content_save_pre', array( $this, 'post_save_images' ) );
    return $content;
  }
  function save_images($image_url,$post_id){
    global $post;
    $post = get_post($post_id);
    $posttitle = $post->post_title;
    $postname = sanitize_title($posttitle);
    $im_name = "$postname-$post_id.jpg";
    if (!empty($image_url)) {
      $file_array = array();
      preg_match('/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $image_url, $matches);
      $file_array['name'] = basename($matches[0]);
      $file_array['tmp_name'] = download_url($image_url);
      if (is_wp_error($file_array['tmp_name'])) {
          return $file_array['tmp_name'];
      }
      $id = media_handle_sideload($file_array, $post_id, $im_name);
      if (is_wp_error($id)) {
          @unlink($file_array['tmp_name']);
          return $id;
      }
      $src = wp_get_attachment_url($id);
      return $src;
    }
  }
}
new Auto_Save_Images();