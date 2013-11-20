# More anatomy of a Magmi Datapump

## Lets play with categories

Its quite easy to play with categories - Lets just start by creating a product

````
$data = new RequiredData();
$data
    ->setSku('sku')
    ->setDescription('long description')
    ->setShortDescription('short description')
    ->setName('name')
    ->setWeight(100)
    ->setPrice(100)
    ->setTax('Taxable Goods')
    ->setQty(100);

$product = new Simple($data);
````

Now we have our product, all objects that extends [````DataAbstract````](https://github.com/lsv/magmi-datapump/blob/master/src/Datapump/Product/Data/DataAbstract.php) can be injected into products.

````
$category = new Category;
$category->set('category-name');

$product->injectData($category);
````

Lets play with more levels with our categories

````
$category = new Category;
$category->set('category-name / level2 / level3');
// Now the product will be in level3 category at third level

// IF / is bad for category demiliter we can change it
$category->set('category-name # level2 # level3', true, true, true, '#');
````

The 3 true's are

* should the category be active
* should the category be anchored (for layered navigation)
* should the menu be included in the menu

## Lets play with images

@todo image documentation