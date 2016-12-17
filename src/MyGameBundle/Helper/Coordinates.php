<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 16.12.2016 г.
 * Time: 1:16
 */

namespace MyGameBundle\Helper;


class Coordinates
{
    public static function getDistance($x1, $y1, $x2, $y2)
    {
        return sqrt(pow(($x1 - $x2), 2) + pow(($y1 - $y2), 2));
    }
}