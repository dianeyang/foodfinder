<?php

// source http://www.html-form-guide.com/php-form/php-form-submit.html

$ch = curl_init('https://m.stubhub.com/philadelphia-phillies-tickets/phillies-vs-red-sox-5-29-2013-4169368/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

$topost['form_eventid'] = '4118558';
$topost['form_start'] = '0';
$topost['form_rows'] = '10000';

foreach ($topost as $key => $value) 
{
    $post_items[] = $key . '=' . $value;
}
$post_string = implode ('&', $post_items);

curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
$result = curl_exec($ch);

//print_r(curl_getinfo($ch));
//echo curl_errno($ch) . '-' . curl_error($ch);
                
echo $result;
                
curl_close($ch);



?>