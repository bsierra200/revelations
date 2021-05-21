<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 10/05/18
 * Time: 04:25 PM
 */

namespace BuhoLegalSearch\Factory;


use BuhoLegalSearch\Service\BuhoLegalClient;
use BitAwsS3\Service\S3Uploader;
use Logger\Service\BlErrorLogger;
use Psr\Container\ContainerInterface;

class BuhoLegalClientFactory
{

    /**
     * @param ContainerInterface $container
     * @return BuhoLegalActionHandler
     */
    public function __invoke(ContainerInterface $container) : BuhoLegalClient
    {
        $s3Uploader = $container->get(S3Uploader::class);
        $blError = $container->get(BlErrorLogger::class);

       $buhoLegalClient = new BuhoLegalClient($s3Uploader, $blError);
        return $buhoLegalClient;
    }

}