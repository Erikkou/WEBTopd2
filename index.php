<?php

declare(strict_types = 1);

$title = "index";
$root = __DIR__ . DIRECTORY_SEPARATOR;

define('APP_PATH', $root . 'app' . DIRECTORY_SEPARATOR);
define('FILES_PATH', $root . 'transaction_files' . DIRECTORY_SEPARATOR);
define('VIEWS_PATH', $root . 'views' . DIRECTORY_SEPARATOR);

/* HIER CODE (zie de instructies op Blackboard) */

require_once (APP_PATH . 'App.php');
include_once('includes/header.php');

$files = scandir(FILES_PATH);

echo "<h2><b>Eenvoudige Pagina voor het lezen van data-bestanden</b></h2>";
echo "<p>Je ziet hier een lijst met alle beschikbare csv bestanden. Klik op een bestand om de data te benaderen.</p>";

echo show_filelist($files);


//fgetcsv();
/*
Bij deze opdracht maken we een eenvoudige pagina die data-bestanden in kan lezen en de daarin opgenomen data als html-pagina weer kan geven.

In het bestand index.php wordt een aantal constanten gedefinieerd:
• FILES_PATH: de directory waar de data-bestanden in zijn opgeslagen.
• VIEWS_PATH: de directory waar de views zijn opgeslagen.

Maak het gegeven bestand index.php af, zodat er een lijstje komt van alle bestanden in de directory FILES_PATH: alle elementen in deze lijst zijn linkjes.
Wanneer de bezoeker op één van deze links klikt, krijgt hij een html-tabel met de data uit het bestand waar hij op heeft geklikt erin.

De data is als volgt opgesteld:
	• Eerste kolom is de datum van de transactie
	• Tweede kolom is de optionele checksum
	• De derde kolom is transactiebeschrijving
	• De vierde kolom is het bedrag (negatief getal geeft aan dat het een uitgave is, positief getal geeft aan dat het een inkomen is)

Geef ook het totale inkomen, de totale kosten en het netto totaal (totale inkomsten - totale kosten) op de html-pagina weer.
Zorg ervoor dat datum als bijvoorbeeld "4 januari 2021" weergegeven wordt.
Toon inkomensbedragen in groene kleur & toon uitgavenbedragen in rood
Maak gebruik van Bootstrap om er een mooi geheel van te maken.
*/

include_once('includes/footer.php');