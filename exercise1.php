<?php

class Boxes
{
    public static $largeBoxSize = 5;
    public static $smallBoxSize = 1;

    public static function minimalNumberOfBoxes(int $products, int $availableLargeBoxes, int $availableSmallBoxes)
    {
        if ($products > 0) {
            $noLargeBoxes = (int) ($products / self::$largeBoxSize);
            if ($noLargeBoxes > $availableLargeBoxes) {
                $noLargeBoxes = $availableLargeBoxes;
            }

            $noSmallBoxes = $products - $noLargeBoxes * self::$largeBoxSize;

            if($noSmallBoxes <= $availableSmallBoxes) {
                return $noLargeBoxes + $noSmallBoxes;
            }
        }
        return -1;
    }
}

echo Boxes::minimalNumberOfBoxes(16, 2, 10);