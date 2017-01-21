<?php
error_reporting(E_ALL);
ini_set('default_charset', 'UTF-8');
require __DIR__.'/../../../bootstrap/autoload.php';
require_once __DIR__.'/../../../bootstrap/app.php';
use App\Libraries\Topix\TopixAccount as TopixAccount;
use App\TopixTopicEloquent;
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
$topixAccount = new TopixAccount(
  env('TEST_EMAIL_DOMAIN'),
  env('TEST_EMAIL_USERNAME'),
  env('TEST_EMAIL_PASSWORD'),
  ['joshua@paceintl.com', 'jantho1990@gmail.com']
);
$topixAccount->toggleLogs(true);
$topixAccount->getEmails();
$topixAccount->convertEmailsToTopics();
$topixAccount->storeTopicsEloquent();
$kernel->terminate($request, $response);
?>
