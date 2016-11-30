<?php
namespace _j;
### Common custom functions ###

class _j {

  /**
  * Replace all strings in an array.
  * @param $keyvals An array of keys to replace with values.
  * @param $str The base string.
  * @return String
  */
  public function replaceAll($keyvals, $str){
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
  public function find($find, $str, $all = false){
    if(is_string($find)){trim($find = explode(',', $find));}
    $found = false;
    foreach($find as $val){
      if(!strpos($val, $str)){
        $found = false;
        if($all === true){break;}
      }else{
        $found = true;
        if($all === false){break;}
      }
    }
    return $found;
  }

}
