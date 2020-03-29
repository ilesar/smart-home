<?php

namespace App\Grocery\Warehouse;

use App\Entity\GroceryItem;
use App\Grocery\Interfaces\WarehouseInterface;
use App\Grocery\Page\Konzum\CategoryPage;
use App\Grocery\Page\Konzum\HomePage;
use App\Grocery\Page\Konzum\SubcategoryPage;
use App\Grocery\ValueObject\KonzumSubcategory;
use App\Repository\GroceryItemRepository;

class KonzumWarehouse extends BaseWarehouse implements WarehouseInterface
{
    public function refreshStock(): void
    {
        $homePage = new HomePage($this->getInventoryService());
        $homePage->scrollToElement(HomePage::CATEGORY_EXPAND_BUTTON_SELECTOR);
        $homePage->clickElement(HomePage::CATEGORY_EXPAND_BUTTON_SELECTOR, 2);
        $homePage->takeScreenshot();

        $categoryObjects = $homePage->getCategoryObjects();

        $this->parseCategories($categoryObjects);
    }

    private function parseCategories(array $categoryObjects)
    {
        $cut = 4;
        $categoryObjects = array_slice($categoryObjects, $cut, count($categoryObjects) - $cut);
        foreach ($categoryObjects as $categoryObject) {
            $categoryPage = new CategoryPage($this->getInventoryService(), $categoryObject);
            $subcategoryObjects = $categoryPage->getSubcategoryObjects();
            $this->parseSubcategories($subcategoryObjects);
        }
    }

    private function parseSubcategories(array $subcategoryObjects)
    {
//        $subcategoryObjects = array_slice($subcategoryObjects, 0, 1);
        foreach ($subcategoryObjects as $subcategoryObject) {
            $subcategoryPage = new SubcategoryPage($this->getInventoryService(), $subcategoryObject);

            if ($subcategoryPage->hasSubCategories()) {
                $this->parseSubcategories($subcategoryPage->getSubcategoryObjects());

                continue;
            }

            $this->parseProductList($subcategoryPage);
        }
    }

    private function parseProductList(SubcategoryPage $productPage)
    {
        // TODO: parse product with pagination
        $productPage->takeScreenshot();
        $productObjects = $productPage->getProductObjects();

        $entityManager = $this->getEntityManager();
        /** @var GroceryItemRepository $groceryRepository */
        $groceryRepository = $entityManager->getRepository(GroceryItem::class);

        foreach ($productObjects as $productObject) {
            $groceryItem = $groceryRepository->findOneBy([
                'name' => $productObject->getName(),
            ]);

            if (null === $groceryItem) {
                $groceryItem = new GroceryItem();
                $groceryItem->setName($productObject->getName());
                $groceryItem->setSource($productObject->getImageLink());
            }

            $groceryItem->setPrice($productObject->getPrice());

            $entityManager->persist($groceryItem);
        }

        echo count($productObjects).' PRODUCTS'.PHP_EOL;

        $entityManager->flush();

        if (100 === count($productObjects)) {
            $nextPageLink = $productPage->getNextPageLink();
            $nextPageSubcategory = new KonzumSubcategory('otherpage', $nextPageLink);
            $this->parseProductList(new SubcategoryPage($this->getInventoryService(), $nextPageSubcategory));
        }
    }
}
