<?php

declare(strict_types = 1);

// HIER CODE

function show_filelist(array $files): string{
    // Deze functie geeft de lijst op de beginpagina met alle bestanden in de directory.
    // Het wordt in een lijst geplaatst en ik geef ook het bestandsnaam mee.
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
    // De data in de csv bestanden is alles behalve standaard. Ik doe met deze functie een poging om het te clean.
    // Dat wordt nu heel hardhandig gedaan. Een elegante oplossing is mij niet gelukt.
    $file_data = fopen($file, 'r');

    // Ik maak een lege array die gevuld wordt met alle single arrays.
    $multi_array = [];

    // Ik bepaal welke delimiters er mogelijk om te controleren waar data staat.
    $delimiters = [',', ';', "\t", '|'];

    // De eerste lijn van de data wordt gelezen en daarmee halen we de kolomnamen weg.
    fgetcsv($file_data);

    // We gaan door de lijnen heen loopen en houden de position bij met deze variabele.
    $position = 0;

    // We lopen door alle rows heen in de array en per lijn zetten we deze in een string.
    // de strings worden gecleaned.
    while ($lines = fgetcsv($file_data)) {
        //print_r($lines);
        $string_line = '';
        $single_array = [];
        for ($line = 0; $line < 1; $line++) {
            $string_line .= $lines[$line];

            // Blijkbaar gaat het bij sommige files wel goed met het vinden van de delimiter
            // Jammerlijk genoeg omdat dit als comma in een cijfer staat waar een punt moet.
            // Ik controleer of er toch een tweede element in de array staat en voeg deze ook toe aan de string.
            if (isset($lines[$line+1])) {
                // Waarom hier een nul erbij? Geen idee, maar het werkt.
                $string_line .= '.' . $lines[$line+1] . '0';
            }
            // De euro tekens werden niet herkend. Het lukte mij op dit moment niet goed om ze weg te halen.
            // Met deze code verdwenen ze dus leek opgelost. Later gaf dit alsnog problemen.
            $string_line = iconv('ISO-8859-1', 'UTF-8//IGNORE', $string_line);
            $string_line = str_replace(['"'], '', $string_line);
        }
        // We loopen door de string heen om te bepalen waar data staat en slicen de rows in kolommen.
        for ($i = 0; $i < strlen($string_line); $i++) {
            if (in_array($string_line[$i], $delimiters) or ($i == (strlen($string_line) - 1))) {
                $slice = substr($string_line, $position, $i - $position);
                if (!empty($slice)) {
                    $single_array[] = $slice;
                }
                //print_r($single_array);
                ////echo $slice;
                $position = $i + 1;
            }
        }
        //print_r($single_array);

        // We controleren of we in ieder geval 4 elementen hebben of dat element twee niet over transactie gaat.
        if (count($single_array) < 4 or fnmatch('Transactie*', $single_array[1])) {
            // Anders dan voegen we gewoon zelf een tweede element toe met een lege string.
            // Niet heel mooi.
            array_splice($single_array, 1, 0, '');
        }

        // Zijn er meer elementen dan vier? Dan is er iets mis. We pakken de laatste twee elementen en combineren deze.
        if (count($single_array) > 4) {
            $last_element = array_pop($single_array);
            $second_last_element = array_pop($single_array);

            $single_array[3] = $second_last_element . $last_element;
        }

        // De datum in de eerste kolom wordt omgezet naar een ander format.
        $date = DateTime::createFromFormat('d/m/Y', $single_array[0]);
        $single_array[0] = $date->format('j F Y');
        $position = 0;

        // We voegen de single array toe aan de multi array.
        $multi_array[] = $single_array;
    }
    fclose($file_data);
    return $multi_array;
}

function show_data($file) {
    // Deze functie regelt het tonen van de data. Dit willen we in de tabel mooi plaatsen.
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
    // Dit is één van de drie functies om de totalen te berekenen.
    $income = 0.0;
    foreach ($formatted_data as $line) {
        if (!str_contains($line[3], '-'))  {
            // Ik had grote problemen met de euro tekens. Ik geef toe dat deze code hieronder wat te ver gaat voor mij.
            // Het werkt echter wel.
            $cleaned_value = preg_replace('/[^\d.-]/', '', $line[3]);

            // De string wordt gecast nu het wordt gezien als numerieke waarde naar een float en opgeteld bij het totaal.
            $income += (float) $cleaned_value;
        }
    }
    return $income;
}

function calculate_costs($formatted_data) {
    // De tweede functie voor het uitrekenen van de totalen. Werkt nagenoeg hetzelfde als de vorige.
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