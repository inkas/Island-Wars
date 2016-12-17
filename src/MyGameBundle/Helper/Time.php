<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 17.12.2016 г.
 * Time: 5:00
 */

namespace MyGameBundle\Helper;


class Time
{
    public static function getFormattedTime(int $seconds)
    {
        return gmdate("H\\h i\\m s\\s", $seconds);
    }
}