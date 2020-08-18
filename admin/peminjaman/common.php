<?php

function generate_in_clause($items)
{
    if (count($items) == 0) return "";
    $q = "(";
    foreach ($items as $idx => $item) {
        $q .= $item;
        if ($idx + 1 < count($items)) $q .= ", ";
    }
    $q .= ")";
    return $q;
}
