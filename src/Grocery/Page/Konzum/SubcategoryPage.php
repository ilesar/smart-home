<?php

namespace App\Grocery\Page\Konzum;

use App\Grocery\Service\WebInventoryService;
use App\Grocery\ValueObject\KonzumCategory;
use App\Grocery\ValueObject\KonzumProduct;
use App\Grocery\ValueObject\KonzumSubcategory;
use Facebook\WebDriver\WebDriverBy;
use Symfony\Component\Panther\Client;

class SubcategoryPage extends Page
{
    public const SUBCATEGORIES_SELECTOR = '#content-start > section > div > div > div.col-12.col-md-12.col-lg-10 h1.f-weight-bold.color-blue-dark > a';
    public const PRODUCTS_SELECTOR = '#content-start > section > div > div > div.col-12.col-md-12.col-lg-10 > div.product-list.product-list--md-5.js-product-layout-container.product-list--grid > article';
    public const NEXT_PAGE_BUTTON_SELECTOR = '#content-start > section > div > div > div.col-12.col-md-12.col-lg-10 > div.product-pagination > div > div:nth-child(2) > ul > li:last-child > a';

    /**
     * @var Client
     */
    private $client;

    public function __construct(WebInventoryService $webInventoryService, KonzumSubcategory $konzumSubcategory)
    {
        static::$url = $konzumSubcategory->getLink().'?sort%5B%5D=&per_page=100';
        static::$name = $konzumSubcategory->getName();
        parent::__construct($webInventoryService);
    }

    /**
     * @return KonzumCategory[]
     */
    public function getSubcategoryObjects(): array
    {
        $subcategoryHtmlObjects = $this->getElementsBySelector(self::SUBCATEGORIES_SELECTOR);
        $subcategoryObjects = [];

        foreach ($subcategoryHtmlObjects as $subcategoryHtmlObject) {
            $name = $subcategoryHtmlObject->getText();
            $link = $subcategoryHtmlObject->getAttribute('href');
            $subcategoryObjects[] = new KonzumSubcategory($name, $link);
        }

        return $subcategoryObjects;
    }

    public function hasSubCategories(): bool
    {
        $subcategoryHtmlObjects = $this->getElementsBySelector(self::SUBCATEGORIES_SELECTOR);

        if (count($subcategoryHtmlObjects) > 0) {
            return true;
        }

        return false;
    }

    /**
     * @return KonzumProduct[]
     */
    public function getProductObjects(): array
    {
        $productHtmlObjects = $this->getElementsBySelector(self::PRODUCTS_SELECTOR);
        $productObjects = [];

        foreach ($productHtmlObjects as $productHtmlObject) {
            $name = $productHtmlObject->findElement(WebDriverBy::cssSelector('.product-default__title'))->getText();

            $priceKuna = $productHtmlObject->findElement(WebDriverBy::cssSelector('div > div.product-default__footer > div.product-default__prices.js-product-price-data > div.price > .price--kn'))->getText();
            $priceLipa = $productHtmlObject->findElement(WebDriverBy::cssSelector('div > div.product-default__footer > div.product-default__prices.js-product-price-data > div.price .price--li'))->getText();
            $price = (int) $priceKuna + ((int) $priceLipa) / 100;

            $imageLink = $productHtmlObject->findElement(WebDriverBy::cssSelector('.product-default__image img'))->getAttribute('src');

            $productObjects[] = new KonzumProduct($name, $price, $imageLink);
        }

        return $productObjects;
    }

    public function getNextPageLink()
    {
        return $this->getElementBySelector(self::NEXT_PAGE_BUTTON_SELECTOR)->getAttribute('href');
    }
}
