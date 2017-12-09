<?php

class Currency
{
	// ВАЛЮТА
    const CURRENCY_USD = 0;
    const CURRENCY_EUR = 1;
    const CURRENCY_RUB = 2;
    const CURRENCY_BTC = 3;
 


    public static function getCurrencies($currency = -1)
    {
        $aliases = array(
            self::CURRENCY_USD => '$ USD',
            self::CURRENCY_EUR => '€ EUR',
            self::CURRENCY_RUB => '₽ RUB',
            self::CURRENCY_BTC => 'Ƀ BTC',
        );

        if ($currency > -1)
            return $aliases[$currency];

        return $aliases;
    }

     public static function getTournamentAllowedCurrencies($currency = -1)
    {
        $allowed = array(
                self::CURRENCY_BTC,
            );



        $result = array();
        foreach(self::getCurrencies() as $id_crnt => $crnt)
            if(in_array($id_crnt, $allowed))
                $result[$id_crnt] = $crnt;

        // var_dump($result);die();

        if ($currency > -1)
            return $result[$currency];

        return $result;
    }

}