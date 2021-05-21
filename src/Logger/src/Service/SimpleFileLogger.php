<?php


namespace Logger\Service;


class SimpleFileLogger
{
    public static function log($logName,$data)
    {
        $date = new \DateTime();
        $dataWithDate=$date->format("Ymd H:i:s -").$data."\r\n";
        file_put_contents(__DIR__."/../../../../data/".$logName,$dataWithDate,FILE_APPEND);
    }
}