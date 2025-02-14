<?
date_default_timezone_set("Asia/Tehran");

if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}

include 'madeline.php';
include 'manager.php';

$MadelineProto = new \danog\MadelineProto\API('session.madeline');
$MadelineProto->start();

$me = $MadelineProto->getSelf();
$MadelineProto->logger($me);

if (!$me['bot']){
    $MadelineProto->messages->sendMessage(peer: "@gnome_shell", message: "Self is online and active");
}

$MadelineProto->echo("KKKK");