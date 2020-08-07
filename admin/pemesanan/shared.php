<?php

function get_status_bg_color($status)
{
    switch ($status) {
        case "usulan":
            return "bg-blue-700";
            break;
        case "diterima":
            return "bg-blue-500";
            break;
        case "dalam proses pemesanan":
            return "bg-green-500";
            break;
        case "ditunda":
            return "bg-purple-500";
            break;
        case "ditolak":
            return "bg-red-500";
            break;
        default:
            return "bg-red";
    }
}
