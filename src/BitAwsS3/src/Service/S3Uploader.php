<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 5/07/17
 * Time: 06:10 PM
 */

namespace BitAwsS3\Service;


use Aws\S3\S3Client;

class S3Uploader
{

    /**
     * @var S3Client
     */
    protected $s3Client;

    /**
     * S3Uploader constructor.
     * @param S3Client $s3Client
     */
    public function __construct(S3Client $s3Client)
    {
        $this->setS3Client($s3Client);
    }

    /**
     * Upload a image(from dataUri) to a AWS bucket
     *
     * @param $blacktrustId
     * @param $proccessName
     * @param $dataUri
     * @param $bucketName
     */
    public function uploadImage($blacktrustId,$processName,$dataUri,$bucketName)
    {
        $fileName="FacePictures/".$blacktrustId."/".$processName."/img_".time().".png";
        $imgData = base64_decode(substr($dataUri,strpos($dataUri, ',')+1));
        $result = $this->getS3Client()->putObject([
            'Bucket'     => $bucketName,
            'Key'        => $fileName,
            'Body' => $imgData,
        ]);
    }

    /**
     * Upload a image(from dataUri) to a AWS bucket
     *
     * @param $blacktrustId
     * @param $proccessName
     * @param $dataUri
     * @param $bucketName
     */
    public function uploadImageToRoot($blacktrustId,$imageName,$dataUri,$bucketName)
    {
        $fileName="FacePictures/".$blacktrustId."/{$imageName}.png";
        $imgData = base64_decode(substr($dataUri,strpos($dataUri, ',')+1));
        $result = $this->getS3Client()->putObject([
            'Bucket'     => $bucketName,
            'Key'        => $fileName,
            'Body' => $imgData,
        ]);
    }


    /**
     * @param $processName
     * @param $fileData
     * @param $bucketName
     */
    public function uploadFile($processName,$fileData,$bucketName)
    {
        $fecha = date("Ymd");
        $hoy = time();
        $processName = sha1($processName);
        $fileName = $processName."-".$hoy.".json";

        $carpeta = "{$fecha}/";

        $result = $this->getS3Client()->putObject([
            'Bucket'     => $bucketName,
            'Key'        => $carpeta.$fileName,
            'Body' => $fileData,
        ]);
    }


    /**
     * @param $pictureBase64
     * @param $fileName
     * @param $bucketName
     * @return \Aws\Result
     */
    public function toS3UploadBase64($pictureBase64, $fileName, $bucketName)
    {
        $result = $this->getS3Client()->putObject([
            'Bucket'     => $bucketName,
            'Key'        => $fileName,
            'Body' => $pictureBase64,
            'ACL'    => 'public-read'
        ]);

        return $result;
    }

    /**
     * @return S3Client
     */
    public function getS3Client()
    {
        return $this->s3Client;
    }

    /**
     * @param S3Client $s3Client
     */
    public function setS3Client($s3Client)
    {
        $this->s3Client = $s3Client;
    }



}