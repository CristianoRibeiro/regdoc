<?php 
namespace App\Helpers;

class SMS
{
    public static function envia_sms($args)
    {
        if (config('sms.ENV') === 'local') return null;
        
        $phone = $args['nu_ddi'].$args['nu_ddd'].(strlen($args['nu_telefone'])>8?$args['nu_telefone']:'9'.$args['nu_telefone']);

        if ($phone != '' && $args['message'] != '') {
            $cURL = curl_init(config('sms.TEXTBELT.URL'));
            $args = [
                'phone' => $phone,
                'message' => $args['message'],
                'key' => config('sms.TEXTBELT.TOKEN')
            ];

            curl_setopt($cURL, CURLOPT_POST, 1);
            curl_setopt($cURL, CURLOPT_POSTFIELDS, http_build_query($args));
            curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($cURL);
            curl_close($cURL);
            return $response;
        } else {
            return null;
        }
    }
}