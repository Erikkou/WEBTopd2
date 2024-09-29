<?php

$title = 'Transactions';
$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
$file = $root . 'transaction_files' . DIRECTORY_SEPARATOR . $_GET['file'];
//echo $file;

require_once ($root . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'App.php');

include_once ($root . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php');
?>

<div>
    <h2>Tabel met data</h2>
    <p>De data uit het gekozen bestand wordt hier weergegeven.</p>
    <table>
        <thead>
        <tr>
            <th>Datum</th>
            <th>Check #</th>
            <th>Beschrijving</th>
            <th>Bedrag</th>
        </tr>
        </thead>
        <tbody>
        <!-- HIER CODE -->
        <?php show_data($file); ?>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="3">Totale Inkomsten:</th>
            <td style="color: green;"> <?php echo '€ ' . number_format(calculate_income(format_data($file)), 2);?></td>
        </tr>
        <tr>
            <th colspan="3">Totale Uitgaven:</th>
            <td style="color: red;"><?php echo '€ ' . number_format(calculate_costs(format_data($file)), 2);?></td>
        </tr>
        <tr>
            <th colspan="3">Netto totaal:</th>
            <td> <?php echo '€ ' . number_format(total_profit(calculate_income(format_data($file)), calculate_costs(format_data($file))), 2);?></td>
        </tr>
        </tfoot>
    </table>
</div>

<?php

include_once ('../includes/footer.php');
