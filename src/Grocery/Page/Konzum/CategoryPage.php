<?php

namespace App\Grocery\Page\Konzum;

use App\Grocery\Service\WebInventoryService;
use App\Grocery\ValueObject\KonzumCategory;
use App\Grocery\ValueObject\KonzumSubcategory;
use Facebook\WebDriver\WebDriverBy;

class CategoryPage extends Page
{
    public const SUBCATEGORIES_SELECTOR = '#content-start > section > div > div > div.col-12.col-md-12.col-lg-10 h1.f-weight-bold.color-blue-dark > a';

    protected static $waitForSelectorOnInit = '#default > footer > div > section.footer.pt-2.pt-sm-3.b-top';

    public function __construct(WebInventoryService $webInventoryService, KonzumCategory $konzumCategory)
    {
        static::$url = $konzumCategory->getLink();
        static::$name = $konzumCategory->getName();
        parent::__construct($webInventoryService);
    }

    /**
     * @return KonzumCategory[]
     */
    public function getSubcategoryObjects(): array
    {
        $subcategoryHtmlObjects = $this->getClient()->findElements(WebDriverBy::cssSelector(self::SUBCATEGORIES_SELECTOR));
        $subcategoryObjects = [];

        foreach ($subcategoryHtmlObjects as $subcategoryHtmlObject) {
            $name = $subcategoryHtmlObject->getText();
            $link = $subcategoryHtmlObject->getAttribute('href');
            $subcategoryObjects[] = new KonzumSubcategory($name, $link);
        }

        return $subcategoryObjects;
    }
}
