<?php

namespace OCup;


class Dataset
{
    public $name;
    public $data = array();

    public function __construct(string $source, string $name)
    {
        // Get data from file
        $this->name = $name;
        $results = $this->extractFromCSV($source);
        foreach ($results as $record) {
            $competitor = new Competitor($record);
            $this->data[$competitor->group][] = $competitor;
        }
    }

    public function extractFromCSV(string $source) : array
    {
        $result = array();
        $file = fopen($source, 'r');

        if (!$file) {
            echo 'Не удалось открыть файл протокола';
            die();
        }

        while (($line = fgetcsv($file,0,';')) !== FALSE) {
            // Not valid SFR string
            if (count($line) < 14) {
                continue;
            }

            // Guess column with result time, if no - it's no finish, then skip
            if (!self::isSFRTime($line[13])) {
                continue;
            }

            $result[] = array(self::SFRdecode($line[2]), self::SFRdecode($line[3]), self::SFRdecode($line[1]), $line[13]);
        }
        fclose($file);
        return $result;
    }

    public static function isSFRTime($str)
    {
        return preg_match("/\d{1}:\d{2}:\d{2}/", $str);
    }

    public static function SFRdecode($str)
    {
        return mb_convert_encoding($str, 'UTF-8','Windows-1251');
    }

}