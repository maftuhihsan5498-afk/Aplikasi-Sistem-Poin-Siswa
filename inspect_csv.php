<?php
$url = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vQE3K6fsKmQLDCuYJajLi1P0NGJgOlIjCG20M5HbmpF_HNYcdMxIzMV6WSOHT4pncvpg2DXoJL8lcM4/pub?gid=0&single=true&output=csv';
$csvData = file_get_contents($url);
$rows = array_map('str_getcsv', explode("\n", $csvData));
// Print first 7 rows
for ($i = 0; $i < 7; $i++) {
    if (isset($rows[$i])) {
        print_r($rows[$i]);
    }
}
