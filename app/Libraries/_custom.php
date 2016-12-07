<?php
namespace _custom;
use \app\Libraries\_j;
### Custom functions for this specific application. ###

class _custom {

  /**
  * Search the email body for tags and return them in an array.
  * @param $body The email body.
  * @return Array
  */
  public function getTags($body){
    $lines = explode("\n", $body);
    $rep = [
      'tags:' => '',
      ', ' => ',',
      "\n" => '',
      "\r" => ''
    ];
    foreach($lines as $line){
      if(strpos($line,'tags') !== false){
        $line = _j::replaceAll($rep, $line);
        if(strpos($line, ' ') === 0){$line = substr($line, 1);}
        $tags = explode(',', $line);
        return $tags;
      }
    }
    return false;
  }

}

?>
