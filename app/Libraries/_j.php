<?php
namespace App\Libraries;
### Common custom functions ###

class _j {

  /**
  * Replace all strings in an array.
  * @param $keyvals An array of keys to replace with values.
  * @param $str The base string.
  * @return String
  */
  public static function replaceAll($keyvals, $str){
    //echo "replace all\n";
    $ret = $str;
    foreach($keyvals as $key=>$val){
      $ret = str_replace($key, $val, $ret);
    }
    return $ret;
  }

  /**
  * Find any or all of the passed strings.
  * @param $find The strings to find. Can be an array or a comma-delimited string.
  * @param $str The string being searched.
  * @param $all If true, function only returns true if all strings are found.
  * @return Boolean
  */
  public static function find($find, $str, $all = false){
    //echo "find $str : ";
    if(is_string($find)){
      $find = explode(',', trim($find));
    }
    $found = false;
    foreach($find as $val){
      //echo substr($str, 0, 5) . "\n";
      if(strpos($str, $val) === false){
        $found = false;
        if($all === true){break;}
      }else{
        $found = true;
        if($all === false){break;}
      }
    }
    //echo "\n";
    return $found;
  }

}
