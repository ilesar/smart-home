<?php

namespace App\Grocery\Page\Konzum;

use App\Grocery\Service\WebInventoryService;
use Exception;
use Facebook\WebDriver\Exception\TimeoutException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;
use Symfony\Component\Panther\Client;

abstract class Page
{
    protected static $name = null;

    protected static $url = null;

    protected static $waitForSelectorOnInit = null;
    /**
     * @var WebInventoryService
     */
    private $webService;

    public function __construct(WebInventoryService $webService)
    {
        sleep(5);
        $this->webService = $webService;
        $this->open();
    }

    public function open()
    {
        if (null === static::$url) {
            throw new Exception(sprintf('Url not set for page (%s)', get_class($this)));
        }

        echo static::$url.PHP_EOL;

        try {
            $this->getClient()->request('GET', static::$url);

            if (static::$waitForSelectorOnInit) {
                $this->getClient()->waitFor(static::$waitForSelectorOnInit);
            }
        } catch (TimeoutException $exception) {
            $this->webService->blacklistCurrentClient();
            $this->refreshClient();
            $this->open();
            return;
        }


    }

    public function clickElement(string $selector, int $secondsToWait = 0)
    {
        $element = $this->getClient()->findElement(WebDriverBy::cssSelector($selector));
        $element->click();

        echo 'Click element'.PHP_EOL;

        if ($secondsToWait > 0) {
            $this->getClient()->wait($secondsToWait);
        }
    }

    public function scrollToElement(string $selector)
    {
        echo 'scroll to element'.PHP_EOL;
        $this->getClient()->executeScript($this->getScrollScriptForSelector($selector));
    }

    public function takeScreenshot(string $fileName = 'webdriver_screenshot'): void
    {
        $this->getClient()->takeScreenshot(sprintf('%s.png', $fileName));
    }

    private function getScrollScriptForSelector(string $selector)
    {
        return vsprintf('var element = document.querySelector("%s");if(element){element.scrollIntoView();window.scrollBy(0, -200);}', [
            $selector,
        ]);
    }

    public function getClient(): Client
    {
        return $this->webService->getClient();
    }

    public function refreshClient(): void
    {
        echo 'REFRESHING CLIENT'.PHP_EOL;
        $this->webService->open();
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
