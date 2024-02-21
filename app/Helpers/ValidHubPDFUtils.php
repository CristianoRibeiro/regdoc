<?php

namespace App\Helpers;

use GuzzleHttp\Client;

use Illuminate\Support\Facades\Storage;

use Exception;

class ValidHubPDFUtils
{
    public static function checkSigned($filepath)
    {
        $url = config('pdfutils.url').'/pdf/check-signed';

        $pdf_content = Storage::get($filepath);

        $GuzzleClient = new Client(['headers' => ['Accept' => 'application/json' , 'Content-Type' => 'application/json']]);
        $response = $GuzzleClient->request('POST', $url, [
            'form_params' => [
                'pdf_content' => base64_encode($pdf_content)
            ]
        ]);

        if ($response->getStatusCode() !== 200)
            throw new Exception("O servidor retornou erro com o status {$response->getStatusCode()} e descrição: {$response->getBody()->getContents()}.");

        $response = json_decode($response->getBody()->getContents());

        return $response->signed;
    }

    public static function convertPDFA($filepath)
    {
        $url = config('pdfutils.url').'/pdf/convert-pdfa';

        $pdf_content = Storage::get($filepath);

        $GuzzleClient = new Client(['headers' => ['Accept' => 'application/json' , 'Content-Type' => 'application/json']]);
        $response = $GuzzleClient->request('POST', $url, [
            'form_params' => [
                'pdf_content' => base64_encode($pdf_content)
            ]
        ]);

        if ($response->getStatusCode() !== 200)
            throw new Exception("O servidor retornou erro com o status {$response->getStatusCode()} e descrição: {$response->getBody()->getContents()}.");

        $response = json_decode($response->getBody()->getContents());

        return base64_decode($response->pdf_content);
    }

    public static function convertPDF($filepath, $extension, $convert_pdfa = false)
    {
        $url = config('pdfutils.url').'/pdf/convert-pdf';

        $file_content = Storage::get($filepath);

        $GuzzleClient = new Client(['headers' => ['Accept' => 'application/json' , 'Content-Type' => 'application/json']]);
        $response = $GuzzleClient->request('POST', $url, [
            'form_params' => [
                'file_content' => base64_encode($file_content),
                'extension' => $extension,
                'convert_pdfa' => $convert_pdfa,
            ]
        ]);

        if ($response->getStatusCode() !== 200)
            throw new Exception("O servidor retornou erro com o status {$response->getStatusCode()} e descrição: {$response->getBody()->getContents()}.");

        $response = json_decode($response->getBody()->getContents());

        return base64_decode($response->pdf_content);
    }
}
