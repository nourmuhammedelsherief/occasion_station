<?php

use App\Models\ProviderRate;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Facades\FCM;



function activity($activity)
{
    switch ($activity){
        case 'sale' :
            return 'بيع';
        case 'rent' :
            return 'تأجير';
    }
}
function status($status)
{
    switch ($status){
        case 'opened' :
            return 'مفتوح';
        case 'sent' :
            return 'فارغ';
        case 'on_cart' :
            return 'في السلة';
        case 'new_paid' :
            return 'جديد مدفوع';
        case 'new_no_paid' :
            return 'جديد غير مدفوع';
        case 'works_on' :
            return 'جاري  العمل علية';
        case 'completed' :
            return 'مكتمل';
        case 'canceled' :
            return 'ملغي';
    }
}
//use FCM;

/** Start Rajahi Integration*/
function payment()
{
    $basURL = "https://securepayments.alrajhibank.com.sa/pg/payment/hosted.htm";
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
    );
    // List numbers 1 to 20
    $pages = range(1,10000000);
// Shuffle numbers
    shuffle($pages);
// Get a page
    $page = array_shift($pages);
    $obj = array(array(
        "amt" => "1.0",
        "action" => "1", // 1 - Purchase , 4 - Pre-Authorization
        "password" => 'Q8$nh4n5s@6DT#D',
        "id" => 'H7F9me4DpW69fcM',
        "currencyCode" => "682",
        "trackId" => "71811137288",
        "responseURL" => 'https://www.google.com',
        "errorURL" => "< Your End Point That The Result Will Be Send To >",
    ));
    $order = json_encode($obj);
    $code = encryption($order , '20206552205620206552205620206552');
    $decode = decryption( $code, '20206552205620206552205620206552');
    $tranData = array(array(
        //Mandatory Parameters
        "id" => 'H7F9me4DpW69fcM',
        "trandata" => $code,
        "responseURL" => 'https://www.google.com',
        "errorURL" => "< Your End Point That The Result Will Be Send To >"
    ));
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($tranData),
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        $result = json_decode($response, true);
        $payment_id = substr($result[0]['result'],0, 18);
        $url = 'https://securepayments.alrajhibank.com.sa/pg/paymentpage.htm?PaymentID=' . $payment_id;
        dd($url);
        return redirect()->to($url);
    }
}
//5105105105105100

function encryption ($str, $key)
{
    $blocksize = openssl_cipher_iv_length("AES-256-CBC");
    $pad = $blocksize - (strlen($str) % $blocksize);
    $str = $str . str_repeat(chr($pad), $pad);
    $encrypted = openssl_encrypt($str, "AES-256-CBC", $key, OPENSSL_ZERO_PADDING, "PGKEYENCDECIVSPC");
    $encrypted = base64_decode($encrypted);
    $encrypted = unpack('C*', ($encrypted));
    $chars = array_map("chr", $encrypted);
    $bin = join($chars);
    $encrypted = bin2hex($bin);
    $encrypted = urlencode($encrypted);
    return $encrypted;
}

function decryption ($code, $key)
{
    $string = hex2bin(trim($code));
    $code = unpack('C*', $string);
    $chars = array_map("chr", $code);
    $code = join($chars);
    $code = base64_encode($code);
    $decrypted = openssl_decrypt($code, "AES-256-CBC", $key, OPENSSL_ZERO_PADDING, "PGKEYENCDECIVSPC");
    $pad = ord($decrypted[strlen($decrypted) - 1]);
    if ($pad > strlen($decrypted)) {
        return false;
    }
    if (strspn($decrypted, chr($pad), strlen($decrypted) - $pad) != $pad) {
        return false;
    }
    return urldecode(substr($decrypted, 0, -1 * $pad));
}


/** End Rajahi Integration*/



function explodeByComma($str)
{
    return explode(",", $str);
}

function explodeByDash($str)
{
    return explode("-", $str);
}

function imgPath($folderName)
{

    //عشان ال sub domain  بس هيشها مؤقتا
//    return '/uploads/' . $folderName . '/';
    return '/public/uploads/' . $folderName . '/';
}

function settings()
{

    return \App\Models\Setting::where('id', 1)->first();
}

function validateRules($errors, $rules)
{

    $error_arr = [];

    foreach ($rules as $key => $value) {

        if ($errors->get($key)) {

            array_push($error_arr, array('key' => $key, 'value' => $errors->first($key)));
        }
    }

    return $error_arr;
}

//function randNumber($userId, $length) {
//
//    $seed = str_split('0123456789');
//
//    shuffle($seed);
//
//    $rand = '';
//
//    foreach (array_rand($seed, $length) as $k) $rand .= $seed[$k];
//
////    return $userId * $userId . $rand;
//    return $userId . $rand;
//}

function randNumber($length)
{

    $seed = str_split('0123456789');

    shuffle($seed);

    $rand = '';

    foreach (array_rand($seed, $length) as $k) $rand .= $seed[$k];

    return $rand;
}

function generateApiToken($userId, $length)
{

    $seed = str_split('abcdefghijklmnopqrstuvwxyz' . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . '0123456789');

    shuffle($seed);

    $rand = '';

    foreach (array_rand($seed, $length) as $k) $rand .= $seed[$k];

    return $userId * $userId . $rand;
}

function UploadBase64Image($base64Str, $prefix, $folderName)
{

    $image = base64_decode($base64Str);
    $image_name = $prefix . '_' . time() . '.png';
    $path = public_path('uploads') . DIRECTORY_SEPARATOR . $folderName . DIRECTORY_SEPARATOR . $image_name;

    $saved = file_put_contents($path, $image);

    return $saved ? $image_name : NULL;
}

function UploadImage($inputRequest, $prefix, $folderNam)
{

    $image = time() . '' . rand(11111, 99999) . '.' . $inputRequest->getClientOriginalExtension();
    $destinationPath = public_path('/' . $folderNam);
    $img = Image::make($inputRequest->getRealPath());
    $img->resize(400, 400, function ($constraint) {
        $constraint->aspectRatio();
    })->save($destinationPath . '/' . $image);

    return $image ? $image : false;

}

function UploadImageEdit($inputRequest, $prefix, $folderNam, $oldImage)
{
    if($oldImage != 'default.png')
    {
        @unlink(public_path('/' . $folderNam . '/' . $oldImage));
    }
    $image = time() . '' . rand(11111, 99999) . '.' . $inputRequest->getClientOriginalExtension();
    $destinationPath = public_path('/' . $folderNam);
    $img = Image::make($inputRequest->getRealPath());
    $img->resize(400, 400, function ($constraint) {
        $constraint->aspectRatio();
    })->save($destinationPath . '/' . $image);


    return $image ? $image : false;
}
function notificationShortcutTypes()
{

    return [
        '1' => 'create_join',
        '2' => 'cancel_join',
        '3' => 'accept_join',
        '4' => 'reject_join',
        '5' => 'admin',
        '6' => 'target'
    ];
}

function getNotificationType($typeNum)
{

    $types = notificationShortcutTypes();

    foreach ($types as $key => $value) {
        if ($typeNum == $key) {
            return $value;
        }
    }
}

function genders()
{

    return [
        'M' => 'ذكر',
        'F' => 'أنثى'
    ];
}

function getGenderString($char)
{

    $genders = genders();
    foreach ($genders as $key => $value) {
        if ($char == $key) {
            return $value;
        }
    }
}

function targetGenders()
{

    return [
        'M' => ['M'],
        'F' => ['F'],
        'B' => ['M', 'F']
    ];
}

function getTargetGenderArr($char)
{

    $genders = targetGenders();
    foreach ($genders as $key => $value) {
        if ($char == $key) {
            return $value;
        }
    }
}

function ageIntervals()
{
    return ['10-20', '20-30', '30-40', '40-50', '50-60', '60-70'];
}

function getAgeIntervalMinMax($interval)
{

    $ageIntervals = ageIntervals();
    foreach ($ageIntervals as $value) {

        // return ['$interval' => gettype($interval), '$value' => gettype($value)];

        if ($interval == $value) {
// return 'eman';
            return explodeByDash($value);
        }
        // else
        // return 'hi';
    }
}

function siteImagesTypes()
{

    return [
        'slider' => '1',
        'app' => '2'
    ];
}

function getSiteImagesTypes($type)
{

    $siteImagesTypes = siteImagesTypes();
    foreach ($siteImagesTypes as $key => $value) {
        if ($type == $key) {
            return $value;
        }
    }
    return false;
}

function usersTypes()
{

    return [
        'admin' => '1',
        'user' => '2',
        'company' => '3'
    ];
}

function getIntUserType($type)
{

    $users = usersTypes();
    foreach ($users as $key => $value) {
        if ($type == $key) {
            return $value;
        }
    }
    return false;
}

function endsWith($string, $finding)
{
    $length = strlen($finding);
    if ($length == 0) {
        return true;
    }
    return (substr($string, -$length) === $finding);
}


//function sendNotification($notificationTitle, $notificationBody, $deviceToken)
//{
//
//    $optionBuilder = new OptionsBuilder();
//    $optionBuilder->setTimeToLive(60 * 20);
//
//    $notificationBuilder = new PayloadNotificationBuilder($notificationTitle);
//    $notificationBuilder->setBody($notificationBody)
//        ->setSound('default');
//
//    $dataBuilder = new PayloadDataBuilder();
//    $dataBuilder->addData(['a_data' => 'my_data']);
//
//    $option = $optionBuilder->build();
//    $notification = $notificationBuilder->build();
//    $data = $dataBuilder->build();
//
//    $token = $deviceToken;
//
//    $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
//
//    $downstreamResponse->numberSuccess();
//    $downstreamResponse->numberFailure();
//    $downstreamResponse->numberModification();
//
////return Array - you must remove all this tokens in your database
//    $downstreamResponse->tokensToDelete();
//
////return Array (key : oldToken, value : new token - you must change the token in your database )
//    $downstreamResponse->tokensToModify();
//
////return Array - you should try to resend the message to the tokens in the array
//    $downstreamResponse->tokensToRetry();
//
//// return Array (key:token, value:errror) - in production you should remove from your database the tokens
//}

function sendMultiNotification($notificationTitle, $notificationBody, $devicesTokens)
{

    $optionBuilder = new OptionsBuilder();
    $optionBuilder->setTimeToLive(60 * 20);

    $notificationBuilder = new PayloadNotificationBuilder($notificationTitle);
    $notificationBuilder->setBody($notificationBody)
        ->setSound('default');

    $dataBuilder = new PayloadDataBuilder();
    $dataBuilder->addData(['a_data' => 'my_data']);

    $option = $optionBuilder->build();
    $notification = $notificationBuilder->build();
    $data = $dataBuilder->build();

// You must change it to get your tokens
    $tokens = $devicesTokens;

    $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

    $downstreamResponse->numberSuccess();
    $downstreamResponse->numberFailure();
    $downstreamResponse->numberModification();

//return Array - you must remove all this tokens in your database
    $downstreamResponse->tokensToDelete();

//return Array (key : oldToken, value : new token - you must change the token in your database )
    $downstreamResponse->tokensToModify();

//return Array - you should try to resend the message to the tokens in the array
    $downstreamResponse->tokensToRetry();

// return Array (key:token, value:errror) - in production you should remove from your database the tokens present in this array
    $downstreamResponse->tokensWithError();

    return ['success' => $downstreamResponse->numberSuccess(), 'fail' => $downstreamResponse->numberFailure()];
}

function sendNotification($firebaseToken, $title, $body, $photo = null)
{
    $SERVER_API_KEY = 'AAAAMPW1SSg:APA91bHaD3j132C9NNKBrmHD4OMGOv_6GpWdOSHCpPHtWIXnhpA7WQo_ldHCeV2Nk9UBcaR-Jj4R4xvlng2AxF3ioFpjyg2q1UCI9wNZjbZmAFgVNPqe-q3Aucs9KWao_6sFjMrUkOdW';
    // payload data, it will vary according to requirement
    $data = [
        "registration_ids" => $firebaseToken,
        "notification" => [
            "title" => $title,
            "body" => $body,
            "photo" => $photo,
        ]
    ];
    $dataString = json_encode($data);

    $headers = [
        'Authorization: key=' . $SERVER_API_KEY,
        'Content-Type: application/json',
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

function saveNotification($userId, $title, $message , $type, $order_id = null , $device_token = null)
{
    $created = \App\Models\UserNotification::create([
        'user_id' => $userId,
        'title' => $title,
        'type' => $type,
        'message' => $message,
        'order_id' => $order_id,
        'device_token' => $device_token,
    ]);
    return $created;
}

####### Check Payment Status ######
function MyFatoorahStatus($api, $PaymentId)
{
    // dd($PaymentId);
    $token = $api;
    $basURL = "https://api-sa.myfatoorah.com";
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "$basURL/v2/GetPaymentStatus",
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\"Key\": \"$PaymentId\",\"KeyType\": \"PaymentId\"}",
        CURLOPT_HTTPHEADER => array("Authorization: Bearer $token", "Content-Type: application/json"),
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        return $response;
    }
}

// ===============================  MyFatoorah public  function  =========================
function MyFatoorah($api, $userData)
{
    // dd($userData);
    $token = $api;
    $basURL = "https://api-sa.myfatoorah.com";
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "$basURL/v2/ExecutePayment",
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $userData,
        CURLOPT_HTTPHEADER => array("Authorization: Bearer $token", "Content-Type: application/json"),
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        return $response;
    }
}

/**
 * calculate the distance between tow places on the earth
 *
 * @param latitude $latitudeFrom
 * @param longitude $longitudeFrom
 * @param latitude $latitudeTo
 * @param longitude $longitudeTo
 * @return double distance in KM
 */
function distanceBetweenTowPlaces($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
{
    $long1 = deg2rad($longitudeFrom);
    $long2 = deg2rad($longitudeTo);
    $lat1 = deg2rad($latitudeFrom);
    $lat2 = deg2rad($latitudeTo);
    //Haversine Formula
    $dlong = $long2 - $long1;
    $dlati = $lat2 - $lat1;
    $val = pow(sin($dlati / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($dlong / 2), 2);
    $res = 2 * asin(sqrt($val));
    $radius = 6367.756;
    return ($res * $radius);
}


/**
 *  Taqnyat sms to send message
 */
function taqnyatSms($msgBody, $reciver)
{
    $setting = \App\Models\Setting::find(1);
    $bearer = '573858c8e33d0751c5d10f1f60b6e2e8';
    $sender = 'Occasion-s';
    $taqnyt = new TaqnyatSms($bearer);

    $body = $msgBody;
    $recipients = $reciver;
    $message = $taqnyt->sendMsg($body, $recipients, $sender);
    // dd($message);
    return $message;
}

function Yamamah($phone , $body)
{
    $jsonObj = array(
        "Username" => "0530242211",
        "Password" => "0530242211",
        "Tagname" => "RAHAL",
        "RecepientNumber" => $phone,
        "VariableList" => "",
        "ReplacementList" => "",
        "Message" => $body,
        "SendDateTime" => 0,
        "EnableDR" => False
    );
    sendSMS($jsonObj);

}
function createColdtOrder($order=null)
{
    $basURL = "https://prodapi.shipox.com/api/v2/customer/order";

    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer '. \App\Models\Setting::find(1)->coltd_token,
    );
//    $order = array(
//        'sender_data' => array(
//            'address_type' => "business",
//            'name' => "Test Sender",
//            'email' => "test@gmail.com",
//            'apartment'=> "",
//            'building' => "",
//            'street' => "Istanbul Street, near from Fetchr warehouse no7 AlSuly, Almashael",
//            'landmark'=> "",
//            'city' => array(
//                'code' => "riyadh"
//            ),
//            'country' => array(
//                'id' => 191
//            ),
//            'phone'=> "54 344 3354",
//        ),
//        'recipient_data' => array(
//            'address_type'=> "business",
//            'name'=> "فراج محمد العماني",
//            'email'=> "recipient@example.com",
//            'apartment'=>"",
//            'building'=> "",
//            'street'=> "الرياض, اشبيليا, الرصافة, السعودية ",
//            'landmark'=> "",
//            'city' => array(
//                'id' => 26148057
//            ),
//            'country' => array(
//                'id' => 191
//            ),
//            'phone' => "532921078",
//        ),
//        'dimensions' => array(
//            "weight"=> 1,
//            "width"=> 10,
//            "length"=> 10,
//            "height"=> 10,
//            "unit"=> "METRIC",
//            "domestic"=> false
//        ),
//        'package_type' => array(
//            "courier_type" => "COLD_COD"
//        ),
//        'charge_items' => array(
//            array(
//                "charge_type" => "cod",
//                "charge" => 188,
//                "payer" => "recipient"
//            ),
////            array(
////                "charge_type" => "service_custom",
////                "charge" => 0,
////                "payer" => "recipient"
////            )
//        ),
//        "recipient_not_available" => "do_not_deliver",
//        "payment_type" => "credit_balance",
//        "payer" => "recipient",
//        "parcel_value" => 145,
//        "fragile"=> true,
//        "note" => "258",
//        "piece_count" => "",
//        "force_create"=> true,
//        "reference_id"=> "15759",
//    );
//    $headers = json_encode($headers);
    $order = json_encode($order);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $order,
        CURLOPT_HTTPHEADER => $headers,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        $response = array_values(json_decode($response, true));
        if($response[2] == 'success')
        {
            return $response;
        }else{
            echo "حدث خطأ ما في أرسال الطلب الي شركه الشحن";
        }
    }
}
function oauthToken()
{
    $basURL = "https://prodapi.shipox.com/api/v1/customer/authenticate";

    $body = array(
        "username" => "occasion.stations@gmail.com",
        "password" => "Occasions123",
        "remember_me" => true
    );
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
    );
    $body = json_encode($body);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => $headers,

    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        $response = array_values(json_decode($response, true));
        \App\Models\Setting::find(1)->update([
            'coltd_token' => $response[0]['id_token']
        ]);
        return $response[0]['id_token'];
    }
}

function tamara()
{
    $basURL = "https://api-sandbox.tamara.co/checkout/payment-options-pre-check";

    $body = array(
        "country" => "SA",
        "order_value" => array(
            "amount" => "300.00",
            "currency" => "SAR"
        ),
        "phone_number" => "966503334444",
        "is_vip" => true
    );
    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhY2NvdW50SWQiOiIyZmIyOWVjZC0yNWExLTQ5NTgtOTc5Yi0zZThkNTVlNzQwMmIiLCJ0eXBlIjoibWVyY2hhbnQiLCJzYWx0IjoiOWVlZDA1YmM0YTkxMDUzOWYyMjQ3NzU4NjkwNzZmMzMiLCJyb2xlcyI6WyJST0xFX01FUkNIQU5UIl0sImlhdCI6MTY5OTQ0OTMwMiwiaXNzIjoiVGFtYXJhIn0.dKtVudFsEcbOC1tOLRtGekgWB1VwFtnUaTDb5UfPGNaXFO91hcN7SW0nk98qaz3ybOE8IMYTIXSG2zJB7wWxhMdPDVKczre0wQzdngP24Ufzu5siZ-AQLuUvEB8Xi1v16T25hukVo-sMBmE2sIpReEl5XxNkJw5UHpCsGDhi5WuIFGruv7hFlCR9ZPNc7smMbNM0KfvBBJpmItIUlU_ZqVHh5loD07XY6lFd4l6XrhVP-AjQ1uK0TJ_3cKNFXPUPaPmmHFQkSroS6YsxHrCaoxoeOzdJtS8PDM3pALg_oT7-g_xutJlDeSX5cqszAUfwJpESr5ts0OpUvDza2h4JZg'
    );
    $body = json_encode($body);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => $headers,

    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        $response = array_values(json_decode($response, true));
        return $response[2];
    }
}

function tamara_checkOut($cart_id , $user , $amount , $instalment)
{
    $basURL = "https://api-sandbox.tamara.co/checkout";
    $cart = \App\Models\Cart::find($cart_id);
    $body = array(
        "total_amount" => array(
            "amount" => $amount,
            "currency" => "SAR"
        ),
        "shipping_amount" => array(
            "amount" => 0,
            "currency" => "SAR"
        ),
        "tax_amount" => array(
            "amount" => 0,
            "currency" => "SAR"
        ),
        "order_reference_id" => $cart_id,
        "order_number" => $cart_id,
        "discount" => array(
            "amount" => array(
                "amount" => 0,
                "currency" => "SAR"
            )
        ,
            "name" => "Christmas 2020"
        ),
        "items" => array(
            array(
                "name" => $user->name,
                "type" => "Digital",
                "reference_id" => $cart->id,
                "sku" => $cart->id,
                "quantity" => 1,
                "discount_amount" => array(
                    "amount" => 0,
                    "currency" => "SAR"
                ),
                "tax_amount" => array(
                    "amount" => $cart->tax_value,
                    "currency" => "SAR"
                ),
                "unit_price" => array(
                    "amount" => $cart->items_price,
                    "currency" => "SAR"
                ),
                "total_amount" => array(
                    "amount" => $amount,
                    "currency" => "SAR"
                )
            )
        ),
        "consumer" => array(
            "email" => $user->email == null ? 'info@email.com' : $user->email,
            "first_name" => $user->name,
            "last_name" => $user->name,
            "phone_number" => $user->phone_number,
        ),
        "country_code" => "SA",
        "description" => $cart->delivery_address,
        "merchant_url" => array(
            "cancel" => "https://dashboard.takia-app.com/api/v1/complete_order",
            "failure" => "https://dashboard.takia-app.com/api/v1/complete_order",
            "success" => "https://dashboard.takia-app.com/api/v1/complete_order",
            "notification" => "https://dashboard.takia-app.com/api/v1/complete_order"
        ),
        "payment_type" => intval($instalment) == 0  ? "PAY_NOW" : "PAY_BY_INSTALMENTS",
        "instalments" => $instalment == null ? 0 : intval($instalment),
        "billing_address" => array(
            "city" => $user->city ? $user->city->name : 'loren',
            "country_code" => "SA",
            "first_name" => $user->name,
            "last_name" => $user->name,
            "line1" => $cart->delivery_address,
            "line2" => "string",
            "phone_number" => $user->phone_number,
            "region" => $user->city ? $user->city->name : 'loren',
        ),
        "shipping_address" => array(
            "city" => $user->city ? $user->city->name : 'loren',
            "country_code" => "SA",
            "first_name" => $user->name,
            "last_name" => $user->name,
            "line1" => $cart->delivery_address,
            "line2" => "string",
            "phone_number" => $user->phone_number,
            "region" => $user->city ? $user->city->name : 'loren',
        ),
        "platform" => $cart->delivery_address,
        "is_mobile" => true,
        "locale" => "en_US",
        "risk_assessment" => array(
            "customer_age" => 22,
            "customer_dob" => "31-01-2000",
            "customer_gender" => "Male",
            "customer_nationality" => "SA",
            "is_premium_customer" => true,
            "is_existing_customer" => true,
            "is_guest_user" => true,
            "account_creation_date" => $user->created_at ? $user->created_at->format('d-m-Y') : "31-01-2024",
            "platform_account_creation_date" => "string",
            "date_of_first_transaction" => $user->created_at ? $user->created_at->format('d-m-Y') : "31-01-2024",
            "is_card_on_file" => true,
            "is_COD_customer" => true,
            "has_delivered_order" => true,
            "is_phone_verified" => true,
            "is_fraudulent_customer" => true,
            "total_ltv" => 501.5,
            "total_order_count" => 12,
            "order_amount_last3months" => 301.5,
            "order_count_last3months" => 2,
            "last_order_date" => "31-01-2021",
            "last_order_amount" => 301.5,
            "reward_program_enrolled" => true,
            "reward_program_points" => 300,
            "phone_verified" => false
        ),
        "additional_data" => array(
            "delivery_method" => "home delivery",
            "pickup_store" => "Store A",
            "store_code" => "Store code A",
            "vendor_amount" => 0,
            "merchant_settlement_amount" => 0,
            "vendor_reference_code" => "AZ1234"
        )
    );

    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhY2NvdW50SWQiOiIyZmIyOWVjZC0yNWExLTQ5NTgtOTc5Yi0zZThkNTVlNzQwMmIiLCJ0eXBlIjoibWVyY2hhbnQiLCJzYWx0IjoiOWVlZDA1YmM0YTkxMDUzOWYyMjQ3NzU4NjkwNzZmMzMiLCJyb2xlcyI6WyJST0xFX01FUkNIQU5UIl0sImlhdCI6MTY5OTQ0OTMwMiwiaXNzIjoiVGFtYXJhIn0.dKtVudFsEcbOC1tOLRtGekgWB1VwFtnUaTDb5UfPGNaXFO91hcN7SW0nk98qaz3ybOE8IMYTIXSG2zJB7wWxhMdPDVKczre0wQzdngP24Ufzu5siZ-AQLuUvEB8Xi1v16T25hukVo-sMBmE2sIpReEl5XxNkJw5UHpCsGDhi5WuIFGruv7hFlCR9ZPNc7smMbNM0KfvBBJpmItIUlU_ZqVHh5loD07XY6lFd4l6XrhVP-AjQ1uK0TJ_3cKNFXPUPaPmmHFQkSroS6YsxHrCaoxoeOzdJtS8PDM3pALg_oT7-g_xutJlDeSX5cqszAUfwJpESr5ts0OpUvDza2h4JZg'
    );
    $body = json_encode($body);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => $headers,

    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        //Save the order_id and checkout_id in your DBs
        $response = array_values(json_decode($response, true));
        $cart->update([
            'tamara_order_id' => $response[0],
            'tamara_checkout_id' => $response[1],
        ]);
        return $response[2];
    }
}

function order_authorise($order_id)
{
    $basURL = 'https://api-sandbox.tamara.co/orders/'.$order_id.'/authorise';

    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhY2NvdW50SWQiOiIyZmIyOWVjZC0yNWExLTQ5NTgtOTc5Yi0zZThkNTVlNzQwMmIiLCJ0eXBlIjoibWVyY2hhbnQiLCJzYWx0IjoiOWVlZDA1YmM0YTkxMDUzOWYyMjQ3NzU4NjkwNzZmMzMiLCJyb2xlcyI6WyJST0xFX01FUkNIQU5UIl0sImlhdCI6MTY5OTQ0OTMwMiwiaXNzIjoiVGFtYXJhIn0.dKtVudFsEcbOC1tOLRtGekgWB1VwFtnUaTDb5UfPGNaXFO91hcN7SW0nk98qaz3ybOE8IMYTIXSG2zJB7wWxhMdPDVKczre0wQzdngP24Ufzu5siZ-AQLuUvEB8Xi1v16T25hukVo-sMBmE2sIpReEl5XxNkJw5UHpCsGDhi5WuIFGruv7hFlCR9ZPNc7smMbNM0KfvBBJpmItIUlU_ZqVHh5loD07XY6lFd4l6XrhVP-AjQ1uK0TJ_3cKNFXPUPaPmmHFQkSroS6YsxHrCaoxoeOzdJtS8PDM3pALg_oT7-g_xutJlDeSX5cqszAUfwJpESr5ts0OpUvDza2h4JZg'
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => $headers,

    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        //Save the order_id and checkout_id in your DBs
        $response = array_values(json_decode($response, true));
        return 0;
    }
}

function tamara_capture($cart_id  , $amount , $user_id , $order_id)
{
    $basURL = "https://api-sandbox.tamara.co/payments/capture";
    $cart = \App\Models\Cart::find($cart_id);
    $user = \App\Models\User::find($user_id);
    $body = array(
        "order_id" => $order_id,
        "total_amount" => array(
            "amount" => $amount,
            "currency" => "SAR"
        ),
        "items" => array(
            array(
                "name" => $user->name,
                "type" => "Digital",
                "reference_id" => $cart->id,
                "sku" => $cart->id,
                "quantity" => 1,
                "discount_amount" => array(
                    "amount" => 0,
                    "currency" => "SAR"
                ),
                "tax_amount" => array(
                    "amount" => $cart->tax_value,
                    "currency" => "SAR"
                ),
                "unit_price" => array(
                    "amount" => $cart->items_price,
                    "currency" => "SAR"
                ),
                "total_amount" => array(
                    "amount" => $amount,
                    "currency" => "SAR"
                )
            )
        ),
        "discount_amount" => array(
            "amount" => 0,
            "currency" => "SAR"
        ),
        // "shipping_amount" => array(
        //     "amount" => 0,
        //     "currency" => "SAR"
        // ),
        // "shipping_info" => array(
        //     "shipped_at" => "2020-03-31T19:19:52.677Z",
        //     "shipping_company" => "DHL",
        //     "tracking_number" => 100,
        //     "tracking_url" => "https://shipping.com/tracking?id=123456"
        // ),
        "tax_amount" => array(
            "amount" => 0,
            "currency" => "SAR"
        )
    );

    $headers = array(
        'Content-type: application/json',
        'Accept: application/json',
        'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhY2NvdW50SWQiOiIyZmIyOWVjZC0yNWExLTQ5NTgtOTc5Yi0zZThkNTVlNzQwMmIiLCJ0eXBlIjoibWVyY2hhbnQiLCJzYWx0IjoiOWVlZDA1YmM0YTkxMDUzOWYyMjQ3NzU4NjkwNzZmMzMiLCJyb2xlcyI6WyJST0xFX01FUkNIQU5UIl0sImlhdCI6MTY5OTQ0OTMwMiwiaXNzIjoiVGFtYXJhIn0.dKtVudFsEcbOC1tOLRtGekgWB1VwFtnUaTDb5UfPGNaXFO91hcN7SW0nk98qaz3ybOE8IMYTIXSG2zJB7wWxhMdPDVKczre0wQzdngP24Ufzu5siZ-AQLuUvEB8Xi1v16T25hukVo-sMBmE2sIpReEl5XxNkJw5UHpCsGDhi5WuIFGruv7hFlCR9ZPNc7smMbNM0KfvBBJpmItIUlU_ZqVHh5loD07XY6lFd4l6XrhVP-AjQ1uK0TJ_3cKNFXPUPaPmmHFQkSroS6YsxHrCaoxoeOzdJtS8PDM3pALg_oT7-g_xutJlDeSX5cqszAUfwJpESr5ts0OpUvDza2h4JZg'
    );
    $body = json_encode($body);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $basURL,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => $headers,

    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        //Save the order_id and checkout_id in your DBs
        $response = array_values(json_decode($response, true));
        return 0;
    }
}


function takia_webhook(Request $request)
{
    file_put_contents('log.txt', $request, FILE_APPEND);
    echo "success payment";
}
function providerRateAvg($id)
{
    return round(ProviderRate::whereProviderId($id)->avg('rate') , 1);
}
