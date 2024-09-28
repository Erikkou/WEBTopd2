<?php

$title = 'Transactions';

include_once ('../includes/header.php');
?>

<div>
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
        </tbody>
        <tfoot>
        <tr>
            <th colspan="3">Totale Inkomsten:</th>
            <td><!-- HIER CODE --></td>
        </tr>
        <tr>
            <th colspan="3">Totale Uitgaven:</th>
            <td><!-- HIER CODE --></td>
        </tr>
        <tr>
            <th colspan="3">Netto totaal:</th>
            <td><!-- HIER CODE --></td>
        </tr>
        </tfoot>
    </table>
</div>

<?php

include_once ('../includes/footer.php');
