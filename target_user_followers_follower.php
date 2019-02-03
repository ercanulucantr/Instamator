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

    echo("[!] Getting {$target_user_followers_follower['target_username']}'s followers...\n");
    $next_max_id = null;
    $target_user = $instagram->people->getUserIdForName($target_user_followers_follower['target_username']);
    do
    {
        $users = $instagram->people->getFollowers($target_user, $signature, null, $next_max_id);
        foreach($users->getUsers() as $user)
        {
            $follow = $instagram->people->follow($user->getPk());
            if($follow->getStatus() == "ok")
            {
                echo "[+] ".date("d-m-Y H:i:s")." on ".$user->getUsername()." user was followed.\n";
                sleep($target_user_followers_follower['interval']);
            }
            else
            {
                echo "[!] ".date("d-m-Y H:i:s")." on have a error, please wait for next job in {$target_user_followers_follower['have_err']} seconds.\n";
                sleep($target_user_followers_follower['have_err']);
            }
        }
        $next_max_id = $users->getNextMaxId();
    }
    while($next_max_id !== null);
}
catch(Exception $e)
{
    echo("[!] ".$e->getMessage()."\n");
}