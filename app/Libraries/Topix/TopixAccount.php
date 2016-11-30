<?php
namespace App\Libraries\Topix;

use Ddeboer\Imap\Server;
use Ddeboer\Imap\SearchExpression;
use Ddeboer\Imap\Search\Email\To;
use Ddeboer\Imap\Search\Email\FromAddress;
use Ddeboer\Imap\Search\Text\Body;

// TopixAccount: the mailbox which you are sending Topix emails to.

class TopixAccount {
  private $domain = null;
  private $username = null;
  private $password = null;
  private $topixEmailAddresses = null;

  private $connection = null;
  private $emails = null;

  public function __construct(){
    $arguments = func_get_args();
    if(!(count($arguments) === 0)){
      $this->__set($arguments);
      $this->getAuthenticatedConnection($this->domain, $this->username, $this->password);
    }
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
    // Convert to an array if necessary.
    if(is_string($this->topixEmailAddresses)){
      $this->topixEmailAddresses = explode(',', trim($topixEmailAddresses));
    }
    // Validate email addresses.
    foreach($this->topixEmailAddresses as $topixEmailAddress){
      if(!filter_var($topixEmailAddress, FILTER_VALIDATE_EMAIL)){
        throw new ErrorException('Not a valid email address.');
      }
    }
  }

}
