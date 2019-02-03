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

    echo("[!] Getting self direct messages...\n");
    $cursor = null;
    do
    {
        $messages = $instagram->direct->getInbox($cursor);
        foreach($messages->getInbox()->getThreads() as $message)
        {
            $items = $message->getItems();
            foreach($items as $item)
            {
                $delete = $instagram->direct->deleteItem($message->getThreadId(), $item->getItemId());
                if($delete->getStatus() == "ok")
                {
                    echo "[+] ".date("d-m-Y H:i:s")." on ".$message->getThreadTitle()." message was deleted.\n";
                    sleep($self_direct_messages_deleter['interval']);
                }
                else
                {
                    echo "[!] ".date("d-m-Y H:i:s")." on have a error, please wait for next job in {$self_direct_messages_deleter['have_err']} seconds.\n";
                    sleep($self_direct_messages_deleter['have_err']);
                }
            }
        }
        $cursor = $messages->getInbox()->getOldestCursor();
    }
    while($cursor !== null);
}
catch(Exception $e)
{
    echo("[!] ".$e->getMessage()."\n");
}