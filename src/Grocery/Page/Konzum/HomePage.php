<?php

namespace App\Grocery\Page\Konzum;

use App\Grocery\ValueObject\KonzumCategory;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Symfony\Component\Panther\Client;

class HomePage extends Page
{

    private const URL = 'https://www.konzum.hr/kreni-u-kupnju';

    public const CATEGORY_EXPAND_BUTTON_SELECTOR = '#content-start > div:nth-child(6) > section:nth-child(11) > button';
    public const CATEGORIES_SELECTOR = '#content-start > div:nth-child(6) > section.data-toggle--is-opened a';

    protected static $url = self::URL;

    protected static $waitForSelectorOnInit = self::CATEGORY_EXPAND_BUTTON_SELECTOR;

    /**
     * @return KonzumCategory[]
     */
    public function getCategoryObjects(): array
    {
        $categoryHtmlObjects = $this->getClient()->findElements(WebDriverBy::cssSelector(self::CATEGORIES_SELECTOR));
        $categoryObjects = [];

        foreach ($categoryHtmlObjects as $categoryHtmlObject) {
            $name = $categoryHtmlObject->getText();
            $link = $categoryHtmlObject->getAttribute('href');
            $categoryObjects[] = new KonzumCategory($name, $link);
        }

        return $categoryObjects;
    }
}
