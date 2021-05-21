<?php

declare(strict_types=1);

namespace BitAwsS3;
use BitAwsS3\Factory\S3UploaderServiceFactory;
use BitAwsS3\Service\S3Uploader;


/**
 * The configuration provider for the BgcNewsWrapper module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
/*            'invokables' => [
                S3Uploader::class => S3Uploader::class
            ],*/
            'factories'  => [
                S3Uploader::class => S3UploaderServiceFactory::class
            ],
        ];
    }


}
