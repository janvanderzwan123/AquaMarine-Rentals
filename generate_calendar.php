<?php
$month = date('n');
$year = date('Y');

$numDays = date('t', mktime(0, 0, 0, $month, 1, $year));

$firstDayOfWeek = date('N', mktime(0, 0, 0, $month, 1, $year));

$html = '';

for ($i = 1; $i <= $numDays; $i++) {
    if ($i == 1 || ($i - 1) % 7 == 0) {
        $html .= '<div class="row">';
    }

    if ($i == 1) {
        for ($j = 1; $j < $firstDayOfWeek; $j++) {
            $html .= '<div class="date" style="width: calc(100% / 7);"></div>';
        }
    }

    $html .= '<div style="border: 1px solid black; width: calc(100% / 7);" class="date">' . $i . '</div>';

    if ($i == $numDays || ($i + $firstDayOfWeek - 1) % 7 == 0) {
        $html .= '</div>';
    }
}
echo $html;
?>
