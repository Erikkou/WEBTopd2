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

