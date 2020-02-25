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


function get_sheet_data($id) {
    if($id) {
        $sheet      = '14j4vM2EgfikCqFhmzox09Sq5R1pRdetANoF9Xi05rdM';
        $sheetURL   = 'http://gsx2json.com/api?id='.$sheet.'&sheet='.$id;
        $obj        = get_json_data($sheetURL);

        return $obj;
    }
}

function val_sort($array,$key) {

	//Loop through and get the values of our specified key
	foreach($array as $k=>$v) {
		$b[] = strtolower($v[$key]);
	}

	arsort($b);


	foreach($b as $k=>$v) {
		$c[] = $array[$k];
	}

    return $c;
}
