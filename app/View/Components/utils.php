<?php
function truncateText($text, $length = 100, $suffix = '...')
{
    $text = strip_tags($text);
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    $truncated = mb_substr($text, 0, $length);
    $lastSpace = mb_strrpos($truncated, ' ');
    if ($lastSpace !== false) {
        $truncated = mb_substr($truncated, 0, $lastSpace);
    }
    return $truncated . $suffix;
}

function timeAgo($datetime, $full = false)
{
    // Mengubah string menjadi objek DateTime
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    // Menentukan unit waktu
    $units = [
        'years' => $diff->y,
        'months' => $diff->m,
        'weeks' => floor($diff->d / 7),
        'days' => $diff->d,
        'hours' => $diff->h,
        'minutes' => $diff->i,
        'seconds' => $diff->s,
    ];

    // Mencari unit waktu yang pertama kali tidak nol
    foreach ($units as $unit => $value) {
        if ($value > 0) {
            $timeString = $value . ' ' . $unit . ($value > 1 ? ' ago' : ' ago');
            return $timeString;
        }
    }

    return 'recently'; // Jika tidak ada perbedaan waktu
}