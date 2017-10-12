<?php

namespace OCup;

class Cup
{
    public $rules;
    public $dataSets = array();
    public $resultSet = array();

    public function __construct()
    {
        $this->rules = new Rules();

        if (count($_FILES['files']['tmp_name']) < $this->rules->eventsCount) {
            echo 'Не хаватает файлов до заданного количества этапов';
            die();
        }

        $filesCount = count($_FILES['files']['tmp_name']);
        for ($i=0; $i < $filesCount; $i++) {
            $source = $_FILES['files']['tmp_name'][$i];
            if (!empty($source)) {
                $name = $_FILES['files']['name'][$i];
                $this->dataSets[] = new Dataset($source, $name);
            }
        }
    }

    public function calc()
    {
        // Extract data from data sets
        foreach ($this->dataSets as $set) {
            foreach ($set->data as $groupName => $groupData) {
                // Sort set array from 1st place to lowest
                usort($groupData, function (Competitor $a, Competitor $b) {
                    return $a->time <=> $b->time;
                });

                foreach ($groupData as $competitor) {
                    // Calc every competitor
                    $competitorResult = $this->rules->calc($competitor, $groupData);
                    $this->resultSet[$groupName][$competitor->fullName()][$set->name] = $competitorResult;
                }
            }
        }

        // Summarize result
        foreach ($this->resultSet as $groupName => $groupData) {
            foreach ($groupData as $name => $results) {
                // Sort competitor results from best to worst
                arsort($results);

                $this->resultSet[$groupName][$name]['Total'] = array_sum(array_slice($results, 0, $this->rules->eventsCount));
            }
            // Sort results by Total points
            uasort($this->resultSet[$groupName], function($a, $b) {
                return $b['Total'] <=> $a['Total'];
            });
        }

        // Output
        //echo 'Results: ' . var_export($this->resultSet,true) . "\r\n";
        $cupName = 'result_' . date("Y-m-d") . ".csv";
        if( isset($_POST['CupName']) && !empty($_POST['CupName'])) {
            $cupName = trim($_POST['CupName']) . '_' . $cupName;
        }

        Output::download_send_headers($cupName);
        echo Output::array2csv($this->resultSet);
        die();
    }
}