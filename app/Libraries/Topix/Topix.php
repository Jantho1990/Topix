<?php
namespace App\Libraries\Topix;

use Ddeboer\Imap\Server;
use Ddeboer\Imap\SearchExpression;
use Ddeboer\Imap\Search\Email\To;
use Ddeboer\Imap\Search\Email\FromAddress;
use Ddeboer\Imap\Search\Text\Body;

use App\Libraries\Topix\TopixAccount;
use App\Libraries\Topix\TopixTopic;

class Topix {

  public function getTopixAccountsFromDb(){
    // Get Topix account information from a DB source.
  }

  public function createTopixTopic($message){
    // Creates a TopixTopic object from the contents of $message.
  }

}
