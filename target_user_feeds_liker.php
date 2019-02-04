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

    echo("[!] Getting {$target_user_feeds_liker['target_username']}'s feeds...\n");
    $next_max_id = null;
    $target_user = $instagram->people->getUserIdForName($target_user_feeds_liker['target_username']);
    do
    {
        $feeds = $instagram->timeline->getUserFeed($target_user, $next_max_id);
        foreach($feeds->getItems() as $feed)
        {
            $like = $instagram->media->like($feed->getId());
            if($like->getStatus() == "ok")
            {
                echo "[+] ".date("d-m-Y H:i:s")." on ".$feed->getId()." feed was liked.\n";
                sleep($target_user_feeds_liker['interval']);
            }
            else
            {
                echo "[!] ".date("d-m-Y H:i:s")." on have a error, please wait for next job in {$target_user_feeds_liker['have_err']} seconds.\n";
                sleep($target_user_feeds_liker['have_err']);
            }
        }
        $next_max_id = $feeds->getNextMaxId();
    }
    while($next_max_id !== null);
}
catch(Exception $e)
{
    echo("[!] ".$e->getMessage()."\n");
}