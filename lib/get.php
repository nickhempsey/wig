<?php

function GET($name=NULL, $value=false, $option="default") {
  $option=false; // Old version depricated part
  $content=(!empty($_GET[$name]) ? trim($_GET[$name]) : (!empty($value) && !is_array($value) ? trim($value) : false));
  if(is_numeric($content))
      return preg_replace("@([^0-9])@Ui", "", $content);
  else if(is_bool($content))
      return ($content?true:false);
  else if(is_float($content))
      return preg_replace("@([^0-9\,\.\+\-])@Ui", "", $content);
  else if(is_string($content))
  {
      if(filter_var ($content, FILTER_VALIDATE_URL))
          return $content;
      else if(filter_var ($content, FILTER_VALIDATE_EMAIL))
          return $content;
      else if(filter_var ($content, FILTER_VALIDATE_IP))
          return $content;
      else if(filter_var ($content, FILTER_VALIDATE_FLOAT))
          return $content;
      else
          return preg_replace("@([^a-zA-Z0-9\+\-\_\*\@\$\!\;\.\?\#\:\=\%\/\ ]+)@Ui", "", $content);
  }
  else false;
}


function get_content_by_id($id) {
	$content = '';
	if($id) {
		$content_post = get_post($id);
		$content = $content_post->post_content;
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
	}
	return $content;
}


function get_json_data($url) {
    if($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);

        $obj = json_decode($result, true);
        return $obj;
    }
}


function get_googlesheet_data($sheetID) {
    if($sheetID) {
        $sheet            = '1X-Dma_ySPefi3YAIHlW-2T68wsX34RQSxTkkkH-3O8Y';
        $sheetURL   = 'http://gsx2json.com/api?id='.$sheet.'&sheet='.$sheetID;
        $obj = get_json_data($sheetURL);

        return $obj;
    }
}
