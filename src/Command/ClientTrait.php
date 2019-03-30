<?php


namespace RedmineLogger\Command;


use Redmine\Client;

trait ClientTrait
{
    /** @var Client */
    private static $client;

    protected static function getClient(): Client
    {
        if (!self::$client) {
            $apiKey = getenv('REDMINE_API_ACCESS_KEY');
            $url = getenv('REDMINE_URL');

            if (!$apiKey || !$url) {
                echo 'check required env vars';
                exit;
            }

            self::$client = new Client($url, $apiKey);
        }

        return self::$client;
    }
}
