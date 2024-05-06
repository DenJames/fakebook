<?php

function formatDiffForHumans(\Carbon\Carbon $date) {
    $now = \Carbon\Carbon::now();
    $diff = $date->diff($now);

    if ($diff->y >= 1) {
        return "{$diff->y} y";
    }

    if ($diff->m >= 1) {
        return "{$diff->m} mo";
    }

    if ($diff->days >= 7) {
        $weeks = floor($diff->days / 7);
        return "{$weeks} w";
    }

    if ($diff->days >= 1) {
        return "{$diff->days} d";
    }

    if ($diff->h >= 1) {
        return "{$diff->h} h";
    }

    if ($diff->i >= 1) {
        return "{$diff->i} m";
    }

    return "{$diff->s} s";
}
