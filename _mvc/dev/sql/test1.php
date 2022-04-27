<?php

/**
*   temp
*/

declare(strict_types=1);

use Concerto\FiscalYear;


$arr1 = [
    [
        'id' => 2135,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'John.Doe@example.com',
    ],
    [
        'id' => 3245,
        'first_name' => 'Sally',
        'last_name' => 'Smith',
        'email' => 'Sally.Smith@example.com',
    ],
    [
        'id' => 5555,
        'first_name' => 'Jane',
        'last_name' => 'Jones',
        'email' => 'Jane.Jones@example.com',
    ],
    [
        'id' => 5623,
        'first_name' => 'Pater',
        'last_name' => 'Doe',
        'email' => 'Pater.Doe@example.com',
    ],
];
 
$arr2 = [
    [
        'no' => 5342,
        'first_name' => 'Jane',
        'last_name' => 'ccc',
    ],
    [
        'no' => 5623,
        'first_name' => 'Pater',
        'last_name' => 'Doe',
    ],
    [
        'no' => 2135,
        'first_name' => 'aaa',
        'last_name' => 'Doe',
    ],
    [
        'no' => 3245,
        'first_name' => 'ccc',
        'last_name' => 'Smith',
    ],
    //
    [
        'no' => 2135,
        'first_name' => 'John',
        'last_name' => 'Doe',
    ],
    [
        'no' => 5555,
        'first_name' => 'Jane',
        'last_name' => 'Jones',
    ],
    [
        'no' => 5623,
        'first_name' => 'Peter',
        'last_name' => 'Doe',
    ],
    [
        'no' => 6666,
        'first_name' => 'Pater',
        'last_name' => 'Doe',
    ],
    [
        'no' => 5555,
        'first_name' => 'Jane',
        'last_name' => 'eee',
    ],
];

/*
SELECT B.*,
    A.email
FROM arr1 A
JOIN arr2 B
    ON B.no = A.id
        AND B.first_name = A.first_name
WHERE A.first_name LIKE '%a%'
ORDER BY B.no DESC, A.email
*/

echo "////////////////////////////////////////////////\n";
echo "INDEX1\n";

//JOIN index1
//key_valで持ったほうが比較しやすい
//array_columnでは重複した値は

$arr1_ids = array_column($arr1, 'id');

var_dump($arr1_ids);echo "\n";

$arr2_nos = array_column($arr2, 'no');

var_dump($arr2_nos);echo "\n";

$join11_index = array_intersect($arr1_ids, $arr2_nos);

var_dump($join11_index);echo "\n";

$join12_index = array_intersect($arr2_nos, $arr1_ids);

var_dump($join12_index);echo "\n";

echo "////////////////////////////////////////////////\n";
echo "INDEX2\n";

//JOIN index2
//key_valで持ったほうが比較しやすい

$arr1_first_names = array_column($arr1, 'first_name');

var_dump($arr1_first_names);echo "\n";

$arr2_first_names = array_column($arr2, 'first_name');

var_dump($arr2_first_names);echo "\n";

$join21_index = array_intersect($arr1_first_names, $arr2_first_names);

var_dump($join21_index);echo "\n";

$join22_index = array_intersect($arr2_first_names, $arr1_first_names);

var_dump($join22_index);echo "\n";

echo "////////////////////////////////////////////////\n";
echo "ALL INDEX\n";

//ALL JOIN INDEX

$arr1_join_index = array_keys(
    array_intersect_key($join11_index, $join21_index)
);

var_dump($arr1_join_index);echo "\n";

$arr2_join_index = array_keys(
    array_intersect_key($join12_index, $join22_index)
);

var_dump($arr2_join_index);echo "\n";


echo "////////////////////////////////////////////////\n";
echo "JOIN DATA\n";

//JOIN filter

$arr1_join_data = array_filter(
    $arr1,
    function($key) use ($arr1_join_index) {
        return in_array($key, $arr1_join_index);
    },
    ARRAY_FILTER_USE_KEY,
);

var_dump($arr1_join_data);echo "\n";

$arr2_join_data = array_filter(
    $arr2,
    function($key) use ($arr2_join_index) {
        return in_array($key, $arr2_join_index);
    },
    ARRAY_FILTER_USE_KEY,
);

var_dump($arr2_join_data);echo "\n";

////////////////////////////////////////////////

//WHERE

echo "////////////////////////////////////////////////\n";
echo "WHERE\n";

$arr1_where_data = array_filter(
    $arr1_join_data,
    function($row, $key) {
        return mb_ereg_match('.*a', $row['first_name']);
    },
    ARRAY_FILTER_USE_BOTH,
);

var_dump($arr1_where_data);echo "\n";

$arr2_where_data = array_filter(
    $arr2_join_data,
    function($row, $key) {
        return mb_ereg_match('.*a', $row['first_name']);
    },
    ARRAY_FILTER_USE_BOTH,
);

var_dump($arr2_where_data);echo "\n";

////////////////////////////////////////////////

//SELECT

echo "////////////////////////////////////////////////\n";
echo "SELECT\n";

$select_no = array_column($arr2_where_data, 'no');
$select_first_name = array_column($arr2_where_data, 'first_name');
$select_last_name = array_column($arr2_where_data, 'last_name');
$select_email = array_column($arr1_where_data, 'email');

$select_data = array_map(
    function(...$cells) {
        return $cells;
    },
    $select_no,
    $select_first_name,
    $select_last_name,
    $select_email,
);

var_dump($select_data);echo "\n";

////////////////////////////////////////////////

//ORDER

echo "////////////////////////////////////////////////\n";
echo "ORDER\n";

$order_data = array_multisort(
    $select_no,
    SORT_DESC,
    SORT_NATURAL,
    $select_email,
    SORT_ASC,
    SORT_NATURAL,
    $select_data,
);

var_dump($select_data);echo "\n";









