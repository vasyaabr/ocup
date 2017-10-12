<?php

namespace OCup;


class Rules
{
    const EVENTS_RUS_2017 = 10;

    public $eventsCount;

    public function __construct(int $count = 0)
    {
        if( isset($_POST['setCount']) && !empty($_POST['setCount']) && ctype_digit($_POST['setCount'])) {
            $this->eventsCount = (int)$_POST['setCount'];
        }
        else {
            $this->eventsCount = self::EVENTS_RUS_2017;
        }
    }

    public function calc(Competitor $competitor, array $all) : int
    {
        $bestTime = $all[0]->time;
        return self::strTimeToInt($bestTime) / self::strTimeToInt($competitor->time) * 100;
    }

    public static function strTimeToInt(string $strTime) : int
    {
        list ($hour, $min, $sec) = explode(':', $strTime);
        return $hour * 3600 + $min * 60 + $sec;
    }
}
