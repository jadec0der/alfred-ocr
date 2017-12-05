<?php

$token_url = "https://aip.baidubce.com/oauth/2.0/token?grant_type=client_credentials&client_id=N7GWF63CRc2RM8iYHih6GBjZ&client_secret=McqgCdBOprgOjdiysA7U18FTouX1iwDz";
$arrContextOptions=["ssl" => ["verify_peer" => false, "verify_peer_name" => false]];

$file_path = "{query}";

$image = file_get_contents($file_path);
$base64_img = base64_encode($image);

$token_response = file_get_contents($token_url, false, stream_context_create($arrContextOptions));
$token = json_decode($token_response, true)["access_token"];
$ocr_url = "https://aip.baidubce.com/rest/2.0/ocr/v1/general?access_token=".$token;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$ocr_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(["image" => $base64_img]));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);

curl_close ($ch);

$words = json_decode($result, true)['words_result'];
foreach ($words as $word) {
    echo $word["words"]."\n";
}
