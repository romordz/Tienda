<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class CloudinaryUploader
{
    public function __construct()
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => getenv('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => getenv('CLOUDINARY_API_KEY'),
                'api_secret' => getenv('CLOUDINARY_API_SECRET'),
            ]
        ]);
    }

    public function subirImagen($tmpPath)
    {
        $uploadApi = new UploadApi();
        $result = $uploadApi->upload($tmpPath, [
            'resource_type' => 'image',
            'folder' => 'tienda/productos'
        ]);
        return $result['secure_url'];
    }
}