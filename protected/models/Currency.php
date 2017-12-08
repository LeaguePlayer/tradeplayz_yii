<?php

class Currency
{
	// ВАЛЮТА
    const CURRENCY_USD = 0;
    const CURRENCY_EUR = 1;
    const CURRENCY_RUB = 2;
 


    public static function getCurrencies($currency = -1)
    {
        $aliases = array(
            self::CURRENCY_USD => '$ USD',
            self::CURRENCY_EUR => '€ EUR',
            self::CURRENCY_RUB => '₽ RUB',
        );

        if ($currency > -1)
            return $aliases[$currency];

        return $aliases;
    }

}