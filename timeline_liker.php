<?php
require __DIR__."/vendor/autoload.php";

$instagram = new \InstagramAPI\Instagram(false, false);
$signature = \InstagramAPI\Signatures::generateUUID();

///////////////
$username = ''; // your instagram username
$password = ''; // your instagram password
$interval = 20; // per sleep seconds (min: 10 | max: 200);
//////////////

try
{
    echo("[?] Try for login...\n");
    $instagram->login($username, $password);
    echo("[!] Login successfully!\n");
    sleep(2);

    echo("[!] Getting timeline medias...\n");
    $next_max_id = null;
    do
    {
        $feeds = $instagram->timeline->getTimelineFeed($next_max_id);
        foreach($feeds->getFeedItems() as $feed)
        {
            if($feed->isMediaOrAd() == 1)
            {
                if($feed->getMediaOrAd()->isId() && empty($feed->getMediaOrAd()->isHasLiked()))
                {
                    $like = $instagram->media->like($feed->getMediaOrAd()->getId());
                    if($like->getStatus() == "ok")
                    {
                        echo "[+] ".date("d-m-Y H:i:s")." on ".$feed->getMediaOrAd()->getUser()->getUsername()."'s post was liked.\n";
                        sleep($interval);
                    }
                }
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