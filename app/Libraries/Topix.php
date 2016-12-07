<?php
### The main Topix functions. ###
### By Josh Anthony ###
//namespace Topix;
namespace App\Libraries;
//require('_j.php');
use Ddeboer\Imap\Server;
use Ddeboer\Imap\SearchExpression;
use Ddeboer\Imap\Search\Email\To;
use Ddeboer\Imap\Search\Email\FromAddress;
use Ddeboer\Imap\Search\Text\Body;
use App\Topic;
use App\TopicAccount;

class Topix {

  // List of topics.
  private $topics = null;

  // Get all topics.
  public function getTopics(){
    return $this->topics;
  }

  // Constructor
  // Search the Topix email server for emails from Topix accounts,
  // find new topics and gather existing topics.
  public function __construct(){
    $accounts = $this->getTopicAccounts();
    foreach($accounts as $account){
      $connection = $this->getAuthenticatedConnection(
        $account['domain'],
        $account['username'],
        $account['password']
      );
      $mailboxes = $connection->getMailboxes();
      foreach($mailboxes as $mailbox){
        // Get all topic emails from the Topix server and store into
        // an array.
        // Check to see if it is from a Topix account, and check to
        // see if it a unique topic.
        $this->findTopics($mailbox, $account);
      }
    }
  }

  // Search the database for all Topix-connected accounts.
  // Return their imap domain, username, and password in an array.
  public function getTopicAccounts(){
    $accounts = TopicAccount::all();
    $topicAccounts = [];
    foreach($accounts as $a=>$account){
      $topicAccounts[$a] = [];
      $topicAccounts[$a]['domain'] = $account->domain;
      $topicAccounts[$a]['username'] = $account->username;
      $topicAccounts[$a]['password'] = $account->password;
    }
    return $topicAccounts;
  }

  // Create a Server object, authenticate, and return
  // the connection.
  public function getAuthenticatedConnection($domain, $username, $password){
    $server = new Server($domain);
    $connection = $server->authenticate($username, $password);
    return $connection;
  }

  // Find all unique topics in a mailbox.
  public function findTopics($mailbox, $account){
    if($this->topics === null){
      $this->topics =  [];
    }
    $this->topics[$account['username']] = [];
    $search = new SearchExpression();
    $search->addCondition(new To($account['username']));
    $messages = $mailbox->getMessages($search);
    foreach($messages as $message){
      $topic = $this->getTopic($message);
      if($this->isUniqueTopic($topic)){
        array_push($this->topics[$account['username']], $topic);
      }
    }
  }

  // Parse an email to see if it is a topic.

  // Convert topic email into topic format and return the topic.
  public function getTopic($message){
    $email_id = $message->getId();
    $title = $this->getTitle($message);
    $body = $message->getBodyText();
    //$bodyArray = $this->getBodyArray($body);
    $categories = $this->getCategories($body);
    $tags = $this->findTags($body);
    $date = $message->getDate();
    $topic = [
      'email_id' => $email_id,
      'title' => $title,
      'body' => $body,
      'categories' => $categories,
      'tags' => $tags,
      'date' => $date
    ];
    return $topic;
  }

  // Get the title of the email.
  // Ideally this would be the email subject, but we will also
  // provide a method to set the Title as part of TopiXML.
  // If no title can be found, generate one based on the data
  // and time.
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

  // Get an array of all the lines in an email's body.
  public function getBodyArray($body){
    $bodyArray = explode("\n", $body);
    return $bodyArray;
  }

  // Search the topic and see if any categories were set.
  // If it wasn't, then return 'Uncategorized'.
  public function findCategories(&$body){
    $bodyArray = $this->getBodyArray($body);
    $rep = [
      'tags:' => '',
      'tag:' => '',
      ', ' => ',',
      "\n" => '',
      "\r" => ''
    ];
    $tags = false;
    foreach($bodyArray as $lines){
      $cleanLine = _j::replaceAll($rep, $line);
      if(_j::find(['tags:', 'tag:'], $cleanLine)){
        $tags = explode(',', $cleanLine);
        $body = str_replace($line, '', $body);
        break;
      }
    }
    return $tags;
  }

  // Search the topic and find any tags.
  public function findTags(&$body){
    $bodyArray = $this->getBodyArray($body);
    $rep = [
      'categories:' => '',
      'category:' => '',
      ', ' => ',',
      "\n" => '',
      "\r" => ''
    ];
    $categories = false;
    foreach($bodyArray as $lines){
      $line = _j::replaceAll($rep, $line);
      if(_j::find(['categories:', 'category:'], $line)){
        $categories = explode(',', $categories);
        $body = str_replace($line, '', $body);
        break;
      }
    }
    return $categories;
  }

  // Check the database to make sure the current topic doesn't
  // already exist. We do this by comparing the unique email
  // identifier of the topic with the ones stored in the database.
  public function isUniqueTopic($topic){
    if(Topic::find($topic['email_id']) === null){
      return true;
    }
    return false;
  }

  // Destructor
  // Close the connection.
  public function __destruct(){

  }

}
