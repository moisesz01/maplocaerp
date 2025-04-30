<?php

namespace App\Services;

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
use Illuminate\Support\Facades\Storage;


class AzureBlobService
{
    protected $blobClient;
    protected $containerName;
    protected $accountName;

    protected $disk;
    protected $defaultExpiry;


    public function __construct()
    {
        \Log::debug('Azure Credentials Check', [
            'name' => config('azure.name'),
            'key' => config('azure.key') ? '***REDACTED***' : 'MISSING',
            'container' => config('azure.container')
        ]);
        
        $this->disk = 'azure';
        $this->defaultExpiry = now()->addHours(24);
       




        $this->accountName = config('azure.name');
        $this->containerName = trim(config('azure.container'), '/');
        $accountKey = config('azure.key');
        

        $connectionString = sprintf(
            'DefaultEndpointsProtocol=https;AccountName=%s;AccountKey=%s;EndpointSuffix=core.windows.net',
            $this->accountName,
            $accountKey
        );

        $this->blobClient = BlobRestProxy::createBlobService($connectionString);
    }

    /**
     * Sube un archivo y devuelve su URL temporal
     */
    public function uploadFile($file, $filename, $expiryMinutes = 60)
    {
        $content = fopen($file->getRealPath(), "r");

        try {
            $options = new CreateBlockBlobOptions();
            $options->setContentType($file->getClientMimeType());

            $this->blobClient->createBlockBlob(
                $this->containerName,
                $filename,
                $content,
                $options
            );

            return $this->generateTemporaryUrl($filename, $expiryMinutes);

        } catch (ServiceException $e) {
            throw new \Exception('Error al subir el archivo: ' . $e->getMessage());
        } finally {
            if (is_resource($content)) {
                fclose($content);
            }
        }
    }

    public function generateTemporaryUrl($blobName, $expiryMinutes)
    {
        $expiry = gmdate('Y-m-d\TH:i:s\Z', strtotime("+{$expiryMinutes} minutes"));
        $start = gmdate('Y-m-d\TH:i:s\Z', strtotime('-5 minutes'));


        $signedPermission = 'r';
        $signedResource = 'b'; 
        $signedVersion = '2020-08-04';
        $canonicalizedResource = sprintf(
            "/blob/%s/%s/%s",
            $this->accountName,
            $this->containerName,
            $blobName
        );

        // El orden es CRUCIAL aquí - debe coincidir exactamente con lo que espera Azure
        $stringToSign = implode("\n", [
            $signedPermission,            // sp
            $start,                       // st
            $expiry,                      // se
            $canonicalizedResource,       // canonicalizedresource
            '',                           // identifier
            '',                           // sip
            '',                           // spr
            $signedVersion,               // sv
            $signedResource,              // sr - ¡Este faltaba!
            '',                           // snapshot time
            '',
            '',
            '',
            '',
            ''            // rscc, rscd, rsce, rscl, rsct
        ]);

        $decodedAccountKey = base64_decode(config('azure.key'));

        $signature = base64_encode(
            hash_hmac('sha256', $stringToSign, $decodedAccountKey, true)
        );

        $queryParams = [
            'sv' => $signedVersion,
            'st' => $start,
            'se' => $expiry,
            'sr' => $signedResource,
            'sp' => $signedPermission,
            'sig' => $signature,
        ];

        return sprintf(
            "https://%s.blob.core.windows.net/%s/%s?%s",
            $this->accountName,
            $this->containerName,
            str_replace('%2F', '/', rawurlencode($blobName)), // Corrección aquí
            http_build_query($queryParams)
        );
    }



    // public function getTemporaryUrl(string $filePath, ?int $expiryMinutes = null): string
    // {
    //     $expiry = $expiryMinutes ? now()->addMinutes($expiryMinutes) : $this->defaultExpiry;

    //     return Storage::disk('azure')->temporaryUrl($filePath, $expiry);
    // }
    public function getTemporaryUrl(string $filePath, ?int $expiryMinutes = null): string
    {
        $minutes = $expiryMinutes ?? $this->defaultExpiry->diffInMinutes(now());
        \Log::info('Azure Storage Account: ' . env('AZURE_STORAGE_NAME'));
        \Log::info('Azure Storage Key: ' . !empty(env('AZURE_STORAGE_KEY')));
        return $this->generateTemporaryUrl($filePath, $minutes);
    }




}