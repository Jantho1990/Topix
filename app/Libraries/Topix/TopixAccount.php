<?php
namespace App\Libraries\Topix;

use Ddeboer\Imap\Server;
use Ddeboer\Imap\SearchExpression;
use Ddeboer\Imap\Search\Email\To;
use Ddeboer\Imap\Search\Email\FromAddress;
use Ddeboer\Imap\Search\Text\Body;

use App\Libraries\Topix\TopixTopic;
use App\TopixTopicEloquent;

// TopixAccount: the mailbox which you are sending Topix emails to.

class TopixAccount {
  private $domain = null;
  private $username = null;
  private $password = null;
  private $topixEmailAddresses = null;

  private $connection = null;
  private $emails = null;
  private $topics = null;

  private $enable_log = false;

  public function __construct(){
    $arguments = func_get_args();
    $this->emails = [];
    $this->topics = [];
    if(!(count($arguments) === 0)){
      $this->__set($arguments);
      $this->getAuthenticatedConnection($this->domain, $this->username, $this->password);
    }
  }

  /**
   * Enable or disable progress reporting.
   * @param $bool True/False
   * @return void
   */
  public function toggleLogs($bool){
    $this->enable_log = (bool)$bool;
  }

  public function __set($key, $val=null){
    //var_dump($key);
    //var_dump(get_object_vars($this));
    if(is_array($key)){
      foreach($key as $k=>$kv){
        //echo $kv."\n";
        $kk = array_keys(get_object_vars($this))[$k];
        $this->$kk = $kv;
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

  /**
   * Connect to the Topix account's email server.
   *
   */
  public function getAuthenticatedConnection($domain, $username, $password){
    $server = new Server($domain);
    $this->connection = $server->authenticate($username, $password);
  }

  /**
   * Get all emails from a Topix account.
   *
   */
  public function getEmails(){
    $this->parseTopixEmailAddresses();
    $this->emails = [];
    // We should only need to access the inbox.
    $mailbox = $this->connection->getMailbox('INBOX');
    // Should we just grab all the emails and store them here?
    // Or should we do our filtration first to find Topix emails?
    // Latter is more complex, former will likely result in wasted space.

    // Find emails from each topixEmailAddress and add it to the emails
    // array.
    foreach($this->topixEmailAddresses as $topixEmailAddress){
      $search = new SearchExpression();
      $search->addCondition(new To($topixEmailAddress));
      $messages = $mailbox->getMessages($search);
      if($messages->count() > 0){
        foreach($messages as $message){
          array_push($this->emails, $message);
        }
      }
    }
  }

  /**
   * Parse the topixEmailAddresses value so getEmails can use it.
   *
   */
  public function parseTopixEmailAddresses(){
    // If there aren't any emails set, throw a fit.
    if(is_null($this->topixEmailAddresses)){
      throw new \ErrorException('No emails have been set.');
    }

    // Convert to an array if necessary.
    if(is_string($this->topixEmailAddresses)){
      $this->topixEmailAddresses = explode(',', trim($topixEmailAddresses));
    }
    // Validate email addresses.
    foreach($this->topixEmailAddresses as $topixEmailAddress){
      if(!filter_var($topixEmailAddress, FILTER_VALIDATE_EMAIL)){
        throw new \ErrorException('Not a valid email address.');
      }
    }
  }

  /**
   * Convert emails to TopixTopics.
   *
   */
  public function convertEmailsToTopics(){
    $log_ct = 0;
    foreach($this->emails as $email){
      array_push($this->topics, $this->convertEmailToTopic($email));
      if($this->enable_log === true){
        echo $log_ct++ . " emails converted. \n";
      }
    }
  }

  /**
   * Convert a single email to a TopixTopic.
   *
   */
  public function convertEmailToTopic($email){
    $topic = new TopixTopic();
    $topic->createFromEmail($email);
    return $topic;
  }

  /**
   * Store topic into the database using Eloquent.
   *
   */
  public function storeTopicEloquent($topic, $topixTopicModel = null){
    //var_dump($topic);
    $model = $topixTopicModel !== null ? $topixTopicModel : new TopixTopicEloquent;
    foreach($topic->getAll() as $t=>$topic_data){
      if(!is_string($topic_data)){
        $topic_data = json_encode($topic_data);
      }
      $model[$t] = $topic_data;
    }
    return $model->save();
  }

  /**
   * Store all topics into a database using Eloquent.
   *
   */
  public function storeTopicsEloquent(){
    $log_ct = 0;
    foreach($this->topics as $topic){
      $this->storeTopicEloquent($topic);
      if($this->enable_log === true){
        echo $log_ct++ . " topics stored to DB. \n";
      }
    }
  }

}
