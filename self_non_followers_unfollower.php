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

    echo("[!] Getting self following...\n");
    $next_max_id = null;
    $following_count = 0;
    $following_pool = [];
    do
    {
        $following_users = $instagram->people->getSelfFollowing($signature, null, $next_max_id);
        foreach($following_users->getUsers() as $following_user)
        {
            array_push($following_pool, $following_user->getPk());
        }
        $next_max_id = $following_users->getNextMaxId();
    }
    while($next_max_id !== null);

    echo("[!] Getting self followers...\n");
    $next_max_id = null;
    $followers_count = 0;
    $followers_pool = [];
    do
    {
        $followers_users = $instagram->people->getSelfFollowers($signature, null, $next_max_id);
        foreach($followers_users->getUsers() as $followers_user)
        {
            array_push($followers_pool, $followers_user->getPk());
        }
        $next_max_id = $followers_users->getNextMaxId();
    }
    while($next_max_id !== null);

    echo("[!] Analyzing self following and self followers list...\n");
    $users = array_values(array_diff($following_pool, $followers_pool));
    echo("[!] ".count($users)." user is not follow back you...\n");
    foreach($users as $id => $pk)
    {
        $unfollow = $instagram->people->unfollow($pk);
        if($unfollow->getStatus() == "ok")
        {
            echo "[+] ".date("d-m-Y H:i:s")." on ".$instagram->people->getInfoById($pk)->getUser()->getUsername()." user was unfollowed.\n";
            sleep($self_non_followers_unfollower['interval']);
            unset($users[$id]);
        }
        else
        {
            echo "[!] ".date("d-m-Y H:i:s")." on have a error, please wait for next job in {$self_non_followers_unfollower['have_err']} seconds.\n";
            sleep($self_non_followers_unfollower['have_err']);
        }
    }
}
catch(Exception $e)
{
    echo("[!] ".$e->getMessage()."\n");
}