<?php
namespace App\Libraries\Topix;
use App\Libraries\_j;
use Ddeboer\Imap\Server;
use Ddeboer\Imap\SearchExpression;
use Ddeboer\Imap\Search\Email\To;
use Ddeboer\Imap\Search\Email\FromAddress;
use Ddeboer\Imap\Search\Text\Body;

class TopixTopic {

  //private $data = [];

  private $email_id = null;
  private $title = null;
  private $body = null;
  private $categories = null;
  private $tags = null;
  private $date = null;

  public function __construct(){
    $arguments = func_get_args();
    if(!(count($arguments) === 0)){
      $this->__set($arguments);
    }
  }

  public function __set($key, $val=null){
    if(is_array($key)){
      foreach($key as $k=>$kv){
        if(is_numeric($k)){
          $kk = array_keys(get_object_vars($this))[$k];
          $this->$kk = $kv;
        }elseif(property_exists($this, $k)){
          $this->$k = $kv;
        }
      }
    }else{
      if(property_exists($this, $key)){
        $this->$key = $val;
      }
    }
  }

  public function __get($key=null){
    if(is_null($key)){
      return get_object_vars($this);
    }else{
      if(property_exists($this, $key)){
        return $this->$key;
      }else{
        return null;
      }
    }
  }

  public function getAll(){
    return $this->__get(null);
  }

  /**
   * Create a TopixTopic from an email message.
   *
   */
  public function createFromEmail($message){
    $email_id = $message->getId();
    $title = $this->getTitle($message);
    $body = $message->getBodyText();
    $categories = $this->findCategoriesEmail($body);
    $tags = $this->findTagsEmail($body);
    $date = $message->getDate();
    $topic = [
      'email_id' => $email_id,
      'title' => $title,
      'body' => $body,
      'categories' => $categories,
      'tags' => $tags,
      'date' => $date
    ];
    $this->__set($topic);
  }

  /**
   * Get the title of the email.
   * Ideally, this would be the email subject, but we will also
   * provide a method to set the Title as part of TopiXML.
   * If no title can be found, generate one based on the data and time.
   *
   */
  public function getTitle($message){
    $title = $message->getSubject();
    if($title === ''){
      $body = $this->getBodyArray($message);
      foreach($body as $line){
        if(strpos('subject:', $line) === 0){
          $title = $line;
          break;
        }
      }
      if($title === ''){
        $title = 'Unnamed Topic - ' . date('y/m/d h:ia', time());
      }
    }
    return $title;
  }

  /**
   * Get an array of all the lines in an email's body.
   *
   */
  public function getBodyArray($body){
    $bodyArray = explode("\n", $body);
    return $bodyArray;
  }

  /**
   * Find tags in a Topix email body.
   *
   */
  public function findTagsEmail(&$body){
    $bodyArray = $this->getBodyArray($body);
    $rep = [
      'tags:' => '',
      'tag:' => '',
      'Tags:' => '',
      'Tag:' => '',
      ', ' => ',',
      "\n" => '',
      "\r" => ''
    ];
    $tags = false;
    foreach($bodyArray as $line){
      //echo $line."p "._j::find(['tags:', 'tag:'], $line)." p\n";
      if(_j::find(['tags:', 'tag:', 'Tags:', 'Tag:'], $line) === true){
        //echo "$line\n";
        $cleanLine = _j::replaceAll($rep, $line);
        //echo $cleanLine . "\n";
        $tags = explode(',', $cleanLine);
        $body = str_replace($line, '', $body);
        //echo "Boogers " . $line . "\n";
        break;
      }
    }
    return $tags;
  }

  /**
   * Find categories in an email body.
   *
   */
  public function findCategoriesEmail(&$body){
    $bodyArray = $this->getBodyArray($body);
    $rep = [
      'categories:' => '',
      'category:' => '',
      'Categories:' => '',
      'Category:' => '',
      ', ' => ',',
      "\n" => '',
      "\r" => ''
    ];
    $categories = false;
    foreach($bodyArray as $line){
      if(_j::find(['categories:', 'category:', 'Categories:', 'Category:'], $line)){
        //echo "Artichoke " . $line . "\n";
        $cleanLine = _j::replaceAll($rep, $line);
        $categories = explode(',', $cleanLine);
        $body = str_replace($line, '', $body);
        //echo "Shark nuts " . $line . "\n";
        break;
      }
    }
    return $categories;
  }

  /**
   * For database emails, check to make sure a new topic is unique.
   * We do this by comparing the unique email identifier of the
   * topic with the ones stored in the database.
   *
   */
   public function isUniqueTopic($topic, $db){
     if(Topic::find($topic['email_id']) === null){
       return true;
     }
     return false;
   }

}
