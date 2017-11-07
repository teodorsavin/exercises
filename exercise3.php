<?php

class CategoryTree
{
    private $rootCategories;
    private $categories;

    public function __construct()
    {
        $this->rootCategories = [];
        $this->categories = [];
    }

    /**
     * @param string      $category
     * @param null|string $parent
     *
     * @return null
     */
    public function addCategory(string $category, ?string $parent)
    {
        $this->validateCategories($category, $parent);

        if ($parent === null) {
            $this->rootCategories[$category] = new Category($category);
            $this->categories[] = $category;
        } else {
            if (!empty($this->rootCategories[$parent])) {
                $this->addCategoryToParent($this->rootCategories[$parent], $category);
            } else {
                foreach($this->rootCategories as $rootCategory) {
                    $parentCategory = $rootCategory->searchChild($parent);
                    if (!empty($parentCategory)) {
                        $this->addCategoryToParent($parentCategory, $category);
                    }
                }
            }
        }

        return NULL;
    }

    /**
     * @param string      $category
     * @param null|string $parent
     */
    public function validateCategories(string $category, ?string $parent)
    {
        if (in_array($category, $this->categories)) {
            throw new InvalidArgumentException("The category ". $category ." already exists!");
        }

        if ($parent !== null && !in_array($parent, $this->categories)) {
            throw new InvalidArgumentException("The category ". $parent ." does not exist!");
        }
    }

    /**
     * @param Category $parentCategory
     * @param string   $category
     */
    public function addCategoryToParent(Category $parentCategory, string $category)
    {
        $parentCategory->addChild($category);
        $this->categories[] = $category;
    }

    /**
     * TO BE DELETED
     */
    public function displayCategories()
    {
        echo '<pre>';
        var_dump($this->categories);
        die();
    }

    /**
     * @param string $category
     *
     * @return array
     */
    public function getChildren(string $category): array
    {
        if (!empty($this->rootCategories[$category])) {
            $categoryChildren =  $this->rootCategories[$category]->getChildren();
        } else {
            foreach($this->rootCategories as $rootCategory) {
                $parentCategory = $rootCategory->searchChild($category);
                if (!empty($parentCategory)) {
                    if (!$parentCategory->isParent()) {
                        new InvalidArgumentException(" The category ". $category ." does not have children!");
                    }
                    $categoryChildren = $parentCategory->getChildren();
                    break;
                }
            }
        }

        foreach ($categoryChildren as $child) {
            $arrChildren[] = $child->getName();
        }

        return $arrChildren;
    }
}

class Category
{
    private $parent;
    private $children;
    private $name;

    /**
     * Category constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->parent = false;
        $this->children = [];
        $this->name = $name;
    }

    /**
     * @param string $name
     */
    public function addChild(string $name)
    {
        $this->parent = true;
        $this->children[$name] = new Category($name);
    }

    /**
     * @return bool
     */
    public function isParent(): bool
    {
        return $this->parent;
    }

    /**
     * @param string $name
     *
     * @return Category|null
     */
    public function searchChild(string $name): ?Category
    {
        if (isset($this->children[$name]))
        {
            return $this->children[$name];
        } else {
            foreach ($this->children as $child) {
                if ($child->isParent()) {
                    $child = $child->searchChild($name);
                    if (!empty($child)) {
                        return $child;
                    }
                }
            }
        }
        return null;
    }

    /**
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}

$c = new CategoryTree;
$c->addCategory('A', null);
$c->addCategory('B', 'A');
$c->addCategory('C', 'A');
$c->addCategory('D', 'B');
$c->addCategory('E', 'B');
$c->addCategory('F', 'B');
$c->addCategory('G', 'D');
$c->addCategory('H', 'G');
$c->addCategory('I', 'H');
$c->addCategory('J', 'H');
$c->addCategory('K', 'J');
$c->addCategory('L', 'K');

echo implode(',', $c->getChildren('A'));
echo "<br />";
echo implode(',', $c->getChildren('L'));
