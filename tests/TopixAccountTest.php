<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Libraries\Topix\TopixAccount as TopixAccount;

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
    }

    /**
     * Another test.
     * @return void
     */
    public function testCreateTopixAccountWithDynamicConstruct(){
      $topixAccount = new TopixAccount(env('TEST_EMAIL_DOMAIN'), env('TEST_EMAIL_USERNAME'), env('TEST_EMAIL_PASSWORD'), ['joshua@paceintl.com', 'jantho1990@gmail.com']);
      $this->assertInstanceOf(TopixAccount::class, $topixAccount);
      $this->assertFalse($topixAccount->connection === null);
      $topixAccount->getEmails();
      $this->assertFalse($topixAccount->emails === []);
    }
}
