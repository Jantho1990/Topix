<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Libraries\Topix\TopixAccount as TopixAccount;

use App\TopixTopicEloquent;

class TopixAccountTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateEmptyTopixAccount()
    {
        $topixAccount = new TopixAccount();
        $this->assertInstanceOf(TopixAccount::class, $topixAccount);
        //$topixAccount->getEmails();
    }

    /**
     * @expectedException ErrorException
     * Make sure that an error is thrown if we try to get emails
     * without setting at least one email account.
     *
     * @return void
     */
    public function testErrorIfGettingEmailsWithoutSettingEmailAccount(){
      $topixAccount = new TopixAccount();
      $topixAccount->getEmails();
    }

    /**
     * @expectedException ErrorException
     * Make sure that an error is thrown if we try to get emails
     * with an invalid email address.
     *
     * @return void
     */
    public function testErrorIfGettingEmailsWithInvalidEmailAccount(){
      $topixAccount = new TopixAccount(
        env('TEST_EMAIL_DOMAIN'),
        env('TEST_EMAIL_USERNAME'),
        env('TEST_EMAIL_PASSWORD'),
        ['unreal']
      );
      $topixAccount->getEmails();
    }

    /**
     * Another test.
     * @return void
     */
    public function testCreateTopixAccountWithDynamicConstruct(){
      $topixAccount = new TopixAccount(
        env('TEST_EMAIL_DOMAIN'),
        env('TEST_EMAIL_USERNAME'),
        env('TEST_EMAIL_PASSWORD'),
        ['joshua@paceintl.com', 'jantho1990@gmail.com']
      );
      $this->assertInstanceOf(TopixAccount::class, $topixAccount);
      $this->assertFalse($topixAccount->connection === null);
      $topixAccount->getEmails();
      $this->assertFalse($topixAccount->emails === []);
    }

    /**
     * Create a topic.
     *
     */
    public function testCreateTopicFromEmail(){
      $topixAccount = new TopixAccount(
        env('TEST_EMAIL_DOMAIN'),
        env('TEST_EMAIL_USERNAME'),
        env('TEST_EMAIL_PASSWORD'),
        ['joshua@paceintl.com', 'jantho1990@gmail.com']
      );
      $topixAccount->getEmails();
      $topic = $topixAccount->convertEmailToTopic($topixAccount->emails[1]);
      $this->assertFalse(is_null($topic->email_id));
    }

    /**
     * Store a topic into a database using Eloquent.
     *
     */
    public function testStoreTopicinDBViaEloquent(){
      $topixAccount = new TopixAccount(
        env('TEST_EMAIL_DOMAIN'),
        env('TEST_EMAIL_USERNAME'),
        env('TEST_EMAIL_PASSWORD'),
        ['joshua@paceintl.com', 'jantho1990@gmail.com']
      );
      $topixAccount->getEmails();
      $topic = $topixAccount->convertEmailToTopic($topixAccount->emails[6]);
      $topixTopicModel = new TopixTopicEloquent;
      $topixAccount->storeTopicEloquent($topic, $topixTopicModel);
      $this->assertFalse(is_null($topixTopicModel->id));
    }

}
