<?php

namespace Symplefony;

use DateTime;

class ConversionTools
{
    public static function dateToSqlFormat( DateTime $date ): string
    {
        return $date->format( 'Y-M-D h:m:s' );
    }
}