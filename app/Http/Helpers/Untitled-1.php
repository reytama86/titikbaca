<?php
function penjumlahan ($a,$b)
{
    return $a + $b;
}

$identitas = [
    "item1" => ["nama" => "Adhimas", "umur" => 21,"nilai" => 99,],
];

foreach($identitas as $item => $details){
    echo $item->$details["nama"];
    echo $item->$details["umur"];
    echo $item->$details["nilai"];
}
