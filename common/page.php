<?php

require('../../vendor/autoload.php');

use Rakit\Validation\Validator;

function processs_common_input($input, $valid_columns)
{
    $valid_ipp          = [5, 10, 15];
    $valid_asc          = ["asc", "desc"];
    $first_valid_column = $valid_columns[0];

    $validator = new Validator(VALIDATION_MESSAGES);
    $validation = $validator->make($input, [
        "sort_by"   => ["default:$first_valid_column", $validator("in", $valid_columns)],
        "asc"       => ["default:asc", $validator("in", $valid_asc)],
        "keyword"   => "default:|min:1",
        "ipp"       => ["default:5", $validator("in", $valid_ipp)],
        "page"      => "default:1"
    ]);
    $validation->validate();

    $valid_data = $validation->getValidData();

    $sort_by    = $valid_data["sort_by"];
    $asc        = $valid_data["asc"];
    $keyword    = $valid_data["keyword"];
    $ipp        = $valid_data["ipp"];
    $page       = $valid_data["page"];

    $is_search_mode = strlen($keyword) >= 1;

    return [
        "sort_by"           => $sort_by,
        "asc"               => $asc,
        "keyword"           => $keyword,
        "ipp"               => $ipp,
        "page"              => $page,
        "is_search_mode"    => $is_search_mode
    ];
}


function get_offset_limit($page, $ipp)
{
    $offset = $page * $ipp - $ipp;
    $limit  = $ipp - 0;
    return ["offset" => $offset, "limit" => $limit];
}

function get_page_count($total_items, $ipp)
{
    return ceil($total_items / $ipp);
}
