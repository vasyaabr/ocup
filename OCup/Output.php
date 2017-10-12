<?php

namespace OCup;

class Output
{
    public static function array2csv(array $array)
    {
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
        $headerReady = false;
        foreach ($array as $groupName => $groupData) {
            foreach ($groupData as $name => $results) {
                if (!$headerReady) {
                    $columns = array('Group' => 'Группа') + array('Name' => 'ФИО') + array_keys($results);
                    fputcsv($df, $columns);
                    $headerReady = true;
                }
                $data = array('Group' => $groupName) + array('Name' => $name) + $results;
                fputcsv($df, $data);
            }
        }
        fclose($df);
        return ob_get_clean();
    }

    public static function download_send_headers($filename) {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }
}