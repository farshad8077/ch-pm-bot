<?php
/**
 * Created by @OnyxTM.
 * @CH_PM @CH_PM_BOT
 * User: Morteza Bagher Telegram id : @mench
 * Date: 11/12/2016
 * Time: 09:19 PM
 */



include "config.php";


define('API_KEY','455923151:AAGJdyp-NWBjdWwf-qaXE_FFcEGvj2V6Zoo');
$admin = "462092087";
$channeluse = "-1001138985158";

$update = json_decode(file_get_contents('php://input'));
$txt = $update->message->text;
$chat_id = $update->message->chat->id;
$message_id = $update->message->message_id;
$channel_forward = $update->channel_post->forward_from;
$channel_text = $update->channel_post->text;
$from = $update->message->from->id;
$chatid = $update->callback_query->message->chat->id;
$data = $update->callback_query->data;
$msgid = $update->callback_query->message->message_id;



function bridge($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($datas));
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}

function apiRequest($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($datas));
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}

function token($token,$method,$datas=[]){
    $url = "https://api.telegram.org/bot".$token."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($datas));
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}

$user = file_get_contents('pmembers.txt');
$members = explode("\n", $user);
if (!in_array($chat_id, $members)) {
    $add_user = file_get_contents('pmembers.txt');
    $add_user .= $chat_id . "\n";
    file_put_contents('pmembers.txt', $add_user);
}

$token = json_decode(file_get_contents("https://api.telegram.org/bot$txt/getMe"));
if ($token->ok == true) {
    $id = $token->result->id;
    $username = $token->result->username;
    if (!is_dir("bot/$id")) {
        $mysqli->query("INSERT INTO pmresan (token, username, id) VALUES ('$txt', '$username', $id)");

        mkdir("bot/$id");
        file_put_contents("bot/$id/banlist.txt", "");
        file_put_contents("bot/$id/booleans.txt", "false");
        file_put_contents("bot/$id/msgs.txt", "خوش آمدی پیامتان را ارسال نمایید تا بدست مدیر برسانم.😉
-!-@-#-$
پیام شما با موفقیت ارسال شد✅");
        file_put_contents("bot/$id/pmembers.txt", "");
        file_put_contents("bot/$id/token.txt", $txt);
        file_put_contents("bot/$id/admin.txt", $chat_id);

        $bot = file_get_contents("bot.txt");
        file_put_contents("bot/$id/index.php", $bot);
//        file_get_contents("http://api.telegram.org/bot$txt/setwebhook?url=");

        // file_get_contents("http://api.telegram.org/bot$txt/sendMessage?chzat_id=$chat_id&text=ربات شما با موفقیت به سرور های ما متصل شد");
        token($txt, "setWebHook", [
            'url' => "https://binaam.000webhostapp.com/bot/pmresan/bot/$id/index.php"
        ]);

        token($txt, "sendMessage", [
            'chat_id' => $chat_id,
            'text' => "ربات شما با موفقیت به سرور های @CH_PMRESAN_BOT متصل شد
            @ch_jockdoni"
        ]);
        apiRequest("sendMessage", [
            'chat_id' => $chat_id,
            'text' => "ربات شما با موفقیت ساخته شد
@$username",
            'reply_markup' => json_encode(['inline_keyboard' => [
                [['text' => 'ورود به ربات', 'url' => "http://t.me/$username"]]
            ]])
        ]);
    } else {
        file_put_contents("bot/$id/token.txt", $txt);
        file_put_contents("bot/$id/admin.txt", $chat_id);
        $bot = file_get_contents("bot.txt");
        file_put_contents("bot/$id/index.php", $bot);
        apiRequest("sendMessage", [
            'chat_id' => $chat_id,
            'text' => "ربات شما با موفقیت بروز شد
@$username",
            'reply_markup' => json_encode(['inline_keyboard' => [
                [['text' => 'ورود به ربات', 'url' => "http://t.me/$username"]]
            ]])
        ]);
        token($txt, "setWebHook", [
            'url' => "https://binaam.000webhostapp.com/bot/pmresan/bot/$id/index.php"
        ]);
    }
} else if (preg_match('/^\/([Ss]tate)/', $txt) && $from == $admin) {
    $user = file_get_contents('pmembers.txt');
    $member_id = explode("\n", $user);
    $member_count = count($member_id) - 1;
    bridge('sendMessage', [
        'chat_id' => $chat_id,
        'text' => "👥 تعداد کاربران جدید ربات شما : $member_count",
        'parse_mode' => 'HTML'
    ]);
} else if (preg_match('/^\/([Uu]ploadtext)/', $txt)) {
    $tttext = str_replace("/uploadtext","",$txt);
    function rp($Number){
        $Rand = substr(str_shuffle("0123456789"), 0, $Number);
        return $Rand;
    }
    $ids = rp(8);

//        $mysqli->query("INSERT INTO uploadtext (id, text) VALUES ($id, '$tttext')");

    $mysqli->query("INSERT INTO uploadtext (id, text) VALUES ($ids,'$tttext')");
    bridge("sendMessage",[
        'chat_id'=>$chat_id,
        'text'=>"@ch_pm_bot ".$ids,
        'parse_mode'=>'HTML',
        'reply_markup'=>json_encode(['inline_keyboard'=>[
            [['text'=>'ارسال برای دیگران',"switch_inline_query"=>"$ids"]]
        ]])
    ]);
} else if (preg_match('/^\/([Ss]endtoall)/', $txt) && $from == $admin) {
    $strh = str_replace("/sendtoall", "", $txt);
    $ttxtt = file_get_contents('pmembers.txt');
    $membersidd = explode("\n", $ttxtt);
    for ($y = 0; $y < count($membersidd); $y++) {
        bridge("sendMessage", [
            'chat_id' => $membersidd[$y],
            "text" => $strh,
            "parse_mode" => "HTML"
        ]);
    }
    $memcout = count($membersidd) - 1;
    bridge("sendMessage", [
        'chat_id' => $admin,
        "text" => "پیام شما به $memcout نفر ارسال شد.",
        "parse_mode" => "HTML"
    ]);
} else if (preg_match('/^\/([Tt]akhalof)/', $txt)) {
    $strddh = str_replace("/Ttakalof", "", $txt);

    bridge("sendMessage", [
        'chat_id' => $admin,
        "text" => "$strddh",
        "parse_mode" => "HTML"
    ]);

    bridge("sendMessage", [
        'chat_id' => $chat_id,
        "text" => "✅ ثبت شد.
 ✅ به زودی به درخواست شما رسیدگی میشود",
        "parse_mode" => "HTML"
    ]);

} else if (preg_match('/^\/([Ff]ortoall)/', $txt) && $from == $admin) {
    $ttxtt = file_get_contents('pmembers.txt');
    $membersidd = explode("\n", $ttxtt);

    for ($y = 0; $y < count($membersidd); $y++) {
        bridge("forwardmessage", [
            'chat_id' => $membersidd[$y],
            'from_chat_id' => $chat_id,
            'message_id' => $update->message->reply_to_message->message_id
        ]);
    }

    $memcout = count($membersidd) - 1;
    bridge("sendMessage", [
        'chat_id' => $admin,
        "text" => "پیام شما به $memcout نفر ارسال شد.",
        "parse_mode" => "HTML"
    ]);
} elseif ($txt == "/start") {
    apiRequest("sendMessage", [
        'chat_id' => $chat_id,
        'text' => "ســلام 😉
✴️ به ربات پیام رسان ساز تلگرام خوش اومدی

با استفاده از این سرویس شما میتوانید رباتی جهت ارائه پشتیبانی به کاربران ربات ،کانال،گروه یا وبسایت خود ایجاد کنید🆕

برای ساخت ربات توکن دریافتی از @botfather را برای بنده ارسال کنید.

فقط بخش توکن که به شکل 
123456789:CXJzmvxckzxCDIJc 
میباشد🅿️

امکانات ربات🈳
ارسال پیام به همه 1⃣
دریافت آمار ربات 1⃣ 
حذف اعضا و بن شده ها1⃣
اضافه کردن عضو به لیست سیاه و برعکس1⃣

برای ثبت کردن تخلف یوزر نیم ربات را پس از دستور /takhalof  وارد کنید
مانند:
/takhalof @menchpmbot
و یا آیدی ربات را وارد کنید برای دریافت آیدی ربات به ربات دستور /id را ارسال کنید😉",
        'reply_markup' => json_encode(['inline_keyboard' => [
            [['text' => 'کانال اطلاع رسانی', 'url' => "https://t.me/joinchat/AAAAAD7GSGnI_QyAB3RtwQ"]]
        ]])
    ]);
} else {
        apiRequest("sendMessage", [
        'chat_id' => $chat_id,
        'text' => "متاسفانه چیزی پیدا نکردم☹️",
        'reply_markup' => json_encode(['inline_keyboard' => [
            [['text' => 'کانال اطلاع رسانی', 'url' => "https://t.me/joinchat/AAAAAD7GSGnI_QyAB3RtwQ"]]
        ]])
    ]);
}


$res = $mysqli->query("SELECT * FROM uploadtext");

while ($row = $res->fetch_assoc()){
    $idq = $row["id"];
    $textq = $row["text"];


    $idqq = $update->inline_query->id;
    $textqq = $update->inline_query->query;

    if ($textqq == $idq) {
        bridge('answerInlineQuery', [
            'inline_query_id' => $update->inline_query->id,
            'results' => json_encode([[
                'type' => 'article',
                'id' => base64_encode(rand(5,555)),
                'title' => 'دارای دکمه اشتراک',
                'input_message_content' => ['parse_mode' => 'HTML', 'message_text' => "$textq"],
                'reply_markup' => [
                    'inline_keyboard' => [
                        [
                            ['text' => "اشتراک", 'switch_inline_query' => "$idq"]
                        ]
                    ]
                ]

            ],[
                'type' => 'article',
                'id' => base64_encode(rand(5,555)),
                'title' => 'بدون دکمه اشتراک',
                'input_message_content' => ['parse_mode' => 'HTML', 'message_text' => "$textq"],
            ],[
                'type' => 'article',
                'id' => base64_encode(rand(5,555)),
                'title' => 'اشتراک کد',
                'input_message_content' => ['parse_mode' => 'HTML', 'message_text' => "@ch_pm_bot $idq"],
                'reply_markup' => [
                    'inline_keyboard' => [
                        [
                            ['text' => "اشتراک", 'switch_inline_query' => "$idq"]
                        ]
                    ]
                ]
            ]])
        ]);
    } else {
        bridge('answerInlineQuery', [
            'inline_query_id' => $update->inline_query->id,
            'results' => json_encode([[
                'type' => 'article',
                'id' => base64_encode(rand(5,555)),
                'title' => 'چیزی یافت نشد.',
                'input_message_content' => ['parse_mode' => 'HTML', 'message_text' => "چیزی یافت نشد.
                @CH_PM_BOT
                @CH_PM"]
            ]])
        ]);
    }
}
