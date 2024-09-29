<?php

declare(strict_types = 1);

// HIER CODE

function show_filelist(array $files): string{
    $list = '<ul>';

    foreach($files as $file){
        if ($file != '..' && $file != '.'){
            $list .= '<li>' . '<a href="views/transactions.php?file=' . $file . '">' . $file . '</a>' . '</li>';
        }
    }
    $list .= '</ul>';
    return $list;
}

function format_data($file) {
    $file_data = fopen($file, 'r');
    $multi_array = [];
    $delimiters = [',', ';', "\t", '|'];
    fgetcsv($file_data);
    $position = 0;

    while ($lines = fgetcsv($file_data)) {
        //print_r($lines);
        $string_line = '';
        $single_array = [];
        foreach($lines as $line) {
            $string_line .= $line;
            $string_line = iconv('ISO-8859-1', 'UTF-8//IGNORE', $string_line);
            $string_line = str_replace(['"'], '', $string_line);

        }
        for ($i = 0; $i < strlen($string_line); $i++) {
            if (in_array($string_line[$i], $delimiters)) {
                $slice = substr($string_line, $position, $i - $position);
                if (!empty($slice)) {
                    $single_array[] = $slice;
                }
                $position = $i + 1;
            }
        }
        //print_r($single_array);
        if (count($single_array) < 4 or fnmatch('Transactie*', $single_array[1])) {
            array_splice($single_array, 1, 0, '');
        }
        if (count($single_array) > 4) {
            $last_element = array_pop($single_array);
            $second_last_element = array_pop($single_array);

            $single_array[3] = $second_last_element . $last_element;
        }
        $date = DateTime::createFromFormat('d/m/Y', $single_array[0]);
        $single_array[0] = $date->format('j F Y');
        $position = 0;
        $multi_array[] = $single_array;
    }

    return $multi_array;
}

function show_data($file) {
    $clean_data = format_data($file);
    //print_r($clean_data);
    foreach($clean_data as $line) {
        //print_r($line);

        echo '<tr>';
        echo '<td>' . $line[0] . '</td>';
        echo '<td>' . $line[1] . '</td>';
        echo '<td>' . $line[2] . '</td>';
        echo '<td>' . $line[3] . '</td>';
        echo '</tr>';
    }
}

function calculate_income($formatted_data) {
    $income = 0.0;
    foreach ($formatted_data as $line) {
        if (!str_contains($line[3], '-'))  {

            $cleaned_value = preg_replace('/[^\d.,-]/', '', $line[3]);

            $income += (float) $cleaned_value;
        }
    }
    return $income;
}

function calculate_costs($formatted_data) {
    $cost = 0.0;
    foreach ($formatted_data as $line) {
        if (str_contains($line[3], '-'))  {
            //echo (float) $line[3];
            $cleaned_value = preg_replace('/[^\d.,]/', '', $line[3]);
            $cost += (float) $cleaned_value;
        }
    }
    //echo number_format($cost, 2) . "\n";
    return $cost;
}

function total_profit($income, $cost) {
    return $income - $cost;
}