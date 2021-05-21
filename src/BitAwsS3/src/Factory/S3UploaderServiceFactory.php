<?php

namespace BitAwsS3\Factory;

use Aws\S3\S3Client;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use BitAwsS3\Service\S3Uploader;

class S3UploaderServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return S3Uploader
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        //Grab the configuration
        $awsConfig = $container->get('config')['aws'];

        //Put the envioromental variables
        putenv("AWS_ACCESS_KEY_ID={$awsConfig['credentials']['key']}");
        putenv("AWS_SECRET_ACCESS_KEY={$awsConfig['credentials']['secret']}");

        $s3Client = new S3Client(['region' => $awsConfig['region'], 'version' => $awsConfig['version']]);

        return new S3Uploader($s3Client);
    }
}