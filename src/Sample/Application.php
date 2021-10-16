<?php


namespace Beyond\SmartHttp\Sample;


use Beyond\SmartHttp\Sample\Demo\Demo;
use Beyond\SmartHttp\Sample\Demo\DemoProvider;

class Application extends \Beyond\SmartHttp\Application
{
    /**
     * @var string[]
     */
    protected $defaultProviders = [
        DemoProvider::class,
    ];

    /**
     * @return Demo
     */
    public function demo()
    {
        return $this->offsetGet('demo');
    }
}