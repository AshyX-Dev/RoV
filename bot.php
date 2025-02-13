<?
date_default_timezone_set("Asia/Tehran");

use danog\MadelineProto\Settings;
use danog\Loop\GenericLoop as loop;
use danog\MadelineProto\FileCallback as FCallback;
use danog\MadelineProto\EventHandler;
use danog\MadelineProto\RPCErrorException as err;


if (is_file('vendor/autoload.php')) {
    include 'vendor/autoload.php';
} else {
    if (!is_file('madeline.php')) {
        copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
    }
    include 'madeline.php';
}



?>
