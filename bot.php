<?
date_default_timezone_set("Asia/Tehran");

if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}

include 'madeline.php';
include 'manager.php';
include 'configs.php';

use danog\MadelineProto\API;
use danog\MadelineProto\Broadcast\Progress;
use danog\MadelineProto\Broadcast\Status;
use danog\MadelineProto\EventHandler\Attributes\Handler;
use danog\MadelineProto\EventHandler\Filter\FilterCommand;
use danog\MadelineProto\EventHandler\Filter\FilterRegex;
use danog\MadelineProto\EventHandler\Filter\FilterText;
use danog\MadelineProto\EventHandler\Filter\FilterTextCaseInsensitive;
use danog\MadelineProto\EventHandler\Message;
use danog\MadelineProto\EventHandler\Message\ChannelMessage;
use danog\MadelineProto\EventHandler\Message\PrivateMessage;
use danog\MadelineProto\EventHandler\Message\Service\DialogPhotoChanged;
use danog\MadelineProto\EventHandler\Plugin\RestartPlugin;
use danog\MadelineProto\EventHandler\SimpleFilter\FromAdmin;
use danog\MadelineProto\EventHandler\SimpleFilter\HasAudio;
use danog\MadelineProto\EventHandler\SimpleFilter\Incoming;
use danog\MadelineProto\EventHandler\SimpleFilter\IsReply;
use danog\MadelineProto\Logger;
use danog\MadelineProto\ParseMode;
use danog\MadelineProto\RemoteUrl;
use danog\MadelineProto\Settings;
use danog\MadelineProto\Settings\Database\Mysql;
use danog\MadelineProto\Settings\Database\Postgres;
use danog\MadelineProto\Settings\Database\Redis;
use danog\MadelineProto\SimpleEventHandler;
use danog\MadelineProto\VoIP;

use function Amp\Socket\SocketAddress\fromString;

function createFont(string $input): string {
    $input = strtolower($input);
    $translationTable = [
        'q' => 'Q', 'w' => 'ᴡ', 'e' => 'ᴇ', 'r' => 'ʀ', 't' => 'ᴛ',
        'y' => 'ʏ', 'u' => 'ᴜ', 'i' => 'ɪ', 'o' => 'ᴏ', 'p' => 'ᴘ',
        'a' => 'ᴀ', 's' => 's', 'd' => 'ᴅ', 'f' => 'ꜰ', 'g' => 'ɢ',
        'h' => 'ʜ', 'j' => 'ᴊ', 'k' => 'ᴋ', 'l' => 'ʟ', 'z' => 'ᴢ',
        'x' => 'x', 'c' => 'ᴄ', 'v' => 'ᴠ', 'b' => 'ʙ', 'n' => 'ɴ',
        'm' => 'ᴍ', '-' => '-', '0' => '𝟎', '1' => '𝟏', '2' => '𝟐',
        '3' => '𝟑', '4' => '𝟒', '5' => '𝟓', '6' => '𝟔', '7' => '𝟕',
        '8' => '𝟖', '9' => '𝟗'
    ];
    
    return strtr($input, $translationTable);
}

$MadelineProto = new \danog\MadelineProto\API('session.madeline');
$MadelineProto->start();

$me = $MadelineProto->getSelf();
$MadelineProto->logger($me);

// if (!$me['bot']){
//     $MadelineProto->messages->sendMessage(peer: "@gnome_shell", message: "Self is online and active");
// }

class RoVHandler extends SimpleEventHandler{
    public const ADMIN = username;
    
    public function getReportPeers()
    {
        return [self::ADMIN];
    }

    public function onStart(): void {
        $this->sendMessageToAdmins(createFont("self is online and active "));
    }

    #[Handler]
    public function handleMessage(Incoming&Message $message): void {
        foreach ($message->getMethods() as $method){
            echo $method->getName() . "\n";
        }
        exit(1);
    }

}