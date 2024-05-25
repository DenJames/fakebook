<?php


function formatDiffForHumans(\Carbon\Carbon $date)
{
    $now = \Carbon\Carbon::now();
    $diff = $date->diff($now);

    if ($diff->y >= 1) {
        return "{$diff->y} y";
    }

    if ($diff->m >= 1) {
        return "{$diff->m} mo";
    }

    if ($diff->d >= 7) {
        $weeks = floor($diff->d / 7);

        return "{$weeks} w";
    }

    if ($diff->d >= 1) {
        return "{$diff->d} d";
    }

    if ($diff->h >= 1) {
        return "{$diff->h} h";
    }

    if ($diff->i >= 1) {
        return "{$diff->i} m";
    }

    return "{$diff->s} s";
}

function dynamicResponse(string $toRoute, mixed $routeParams = [], string|array $message = '', string $responseStatus = 'success', int $statusCode = 200)
{
    return request()?->wantsJson()
        ? response()->json([$responseStatus => $message], $statusCode)
        : redirect()->route($toRoute, $routeParams)->with($responseStatus, $message);
}
