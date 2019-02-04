<?php
require __DIR__."/vendor/autoload.php";
require __DIR__."/config.php";

$instagram = new \InstagramAPI\Instagram(false, false);
$signature = \InstagramAPI\Signatures::generateUUID();

try
{
    echo("[?] Try for login as {$account['username']}...\n");
    $instagram->login($account['username'], $account['password']);
    echo("[!] Login successfully!\n");
    sleep(2);

    echo("[!] Getting story feeds...\n");
    $next_user = false;
    do
    {
        $feed = $instagram->story->getReelsTrayFeed();
        if($feed->getTray()[1]->isItems() == 1)
        {
            $seen = $instagram->story->markMediaSeen($feed->getTray()[1]->getItems());
            if($seen->getStatus() == "ok")
            {
                echo "[+] ".date("d-m-Y H:i:s")." on ".$feed->getTray()[1]->getUser()->getUsername()."'s story was seened.\n";
                sleep($story_feeds_seener['interval']);
                $next_user = true;
            }
            else
            {
                echo "[!] ".date("d-m-Y H:i:s")." on have a error, please wait for next job in {$story_feeds_seener['have_err']} seconds.\n";
                sleep($story_feeds_seener['have_err']);
            }
        }
        else
        {
            $next_user = false;
        }
	}
	while($next_user !== false);
}
catch(Exception $e)
{
    echo("[!] ".$e->getMessage()."\n");
}