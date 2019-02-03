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

    echo("[!] Getting self posted medias...\n");
    $next_max_id = null;
    do
    {
        $feeds = $instagram->timeline->getSelfUserFeed($next_max_id);
        foreach($feeds->getItems() as $feed)
        {
            $delete = $instagram->media->delete($feed->getId());
            if($delete->getStatus() == "ok")
            {
                echo "[+] ".date("d-m-Y H:i:s")." on ".$feed->getId()." post was deleted.\n";
                sleep($self_posted_media_deleter['interval']);
            }
            else
            {
                echo "[!] ".date("d-m-Y H:i:s")." on have a error, please wait for next job in {$self_posted_media_deleter['have_err']} seconds.\n";
                sleep($self_posted_media_deleter['have_err']);
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