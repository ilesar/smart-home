<?php

namespace App\Grocery\Page\Konzum;

use Exception;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;
use Symfony\Component\Panther\Client;

abstract class Page
{
    /**
     * @var Client
     */
    private $client;

    protected static $name = null;

    protected static $url = null;

    protected static $waitForSelectorOnInit = null;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->open();
    }

    public function open()
    {
        if (null === static::$url) {
            throw new Exception(sprintf('Url not set for page (%s)', get_class($this)));
        }

        $this->client->request('GET', static::$url);

        echo static::$url.PHP_EOL;

        if (static::$waitForSelectorOnInit) {
            $this->client->waitFor(static::$waitForSelectorOnInit);
        }
    }

    public function clickElement(string $selector, int $secondsToWait = 0)
    {
        $element = $this->client->findElement(WebDriverBy::cssSelector($selector));
        $element->click();

        echo 'Click element'.PHP_EOL;

        if ($secondsToWait > 0) {
            $this->client->wait($secondsToWait);
        }
    }

    public function scrollToElement(string $selector)
    {
        echo 'scroll to element'.PHP_EOL;
        $this->client->executeScript($this->getScrollScriptForSelector($selector));
    }

    public function takeScreenshot(string $fileName = 'webdriver_screenshot'): void
    {
        $this->client->takeScreenshot(sprintf('%s.png', $fileName));
    }

    private function getScrollScriptForSelector(string $selector)
    {
        return vsprintf('var element = document.querySelector("%s");if(element){element.scrollIntoView();window.scrollBy(0, -200);}', [
            $selector,
        ]);
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param string $selector
     * @return WebDriverElement
     */
    public function getElementBySelector(string $selector): WebDriverElement
    {
        return $this->getClient()->findElement(WebDriverBy::cssSelector($selector));
    }

    /**
     * @param string $selector
     * @return WebDriverElement[]
     */
    public function getElementsBySelector(string $selector): array
    {
        return $this->getClient()->findElements(WebDriverBy::cssSelector($selector));
    }
}
