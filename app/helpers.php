<?php

function pagination($data, $page, $total, $limit)
{
    return [
        'totalData' => $total,
        'currentPage' => $page,
        'totalPage' => ceil($total / $limit),
        'data' => $data
    ];
}
