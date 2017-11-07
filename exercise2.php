<?php
class Products
{
    public static function sortByPriceAscending(string $jsonString)
    {
        $products = json_decode($jsonString, true);

        usort($products, 'Products::sortByValues');

        return json_encode($products);
    }

    public static function sortByValues(array $first,array $second)
    {
        $diff = $first['price'] <=> $second['price'];
        if ($diff == 0) {
            $diff = $first['name'] <=> $second['name'];
        }
        return $diff;
    }
}

//echo Products::sortByPriceAscending('[{"name":"eggs","price":1},{"name":"coffee","price":9.99},{"name":"rice","price":4.04}]');
echo Products::sortByPriceAscending('[{"name":"eggs","price":1},{"name":"coffee","price":9.99},{"name":"rice","price":4.04},{"name":"egg","price":1}]');
