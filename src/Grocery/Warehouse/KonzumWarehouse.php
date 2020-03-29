<?php

namespace App\Grocery\Warehouse;

use App\Entity\GroceryItem;
use App\Grocery\Interfaces\WarehouseInterface;
use App\Grocery\Page\Konzum\CategoryPage;
use App\Grocery\Page\Konzum\HomePage;
use App\Grocery\Page\Konzum\SubcategoryPage;
use App\Repository\GroceryItemRepository;

class KonzumWarehouse extends BaseWarehouse implements WarehouseInterface
{
    public function refreshStock(): void
    {
        $homePage = new HomePage($this->getClient());
        $homePage->scrollToElement(HomePage::CATEGORY_EXPAND_BUTTON_SELECTOR);
        $homePage->clickElement(HomePage::CATEGORY_EXPAND_BUTTON_SELECTOR, 2);
        $homePage->takeScreenshot();

        $categoryObjects = $homePage->getCategoryObjects();

        $this->parseCategories($categoryObjects);
    }

    private function parseCategories(array $categoryObjects)
    {
        $categoryObjects = array_slice($categoryObjects, 0, 1);
        foreach ($categoryObjects as $categoryObject) {
            $categoryPage = new CategoryPage($this->getClient(), $categoryObject);
            $subcategoryObjects = $categoryPage->getSubcategoryObjects();
            $this->parseSubcategories($subcategoryObjects);
        }
    }

    private function parseSubcategories(array $subcategoryObjects)
    {
        $subcategoryObjects = array_slice($subcategoryObjects, 0, 1);
        foreach ($subcategoryObjects as $subcategoryObject) {
            $subcategoryPage = new SubcategoryPage($this->getClient(), $subcategoryObject);

            if ($subcategoryPage->hasSubCategories()) {
                $this->parseSubcategories($subcategoryPage->getSubcategoryObjects());

                return;
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
            }

            $groceryItem->setPrice($productObject->getPrice());

            $entityManager->persist($groceryItem);
        }

        $entityManager->flush();
    }
}
