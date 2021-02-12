<!doctype html>

<html lang="de">
<head>
    <meta charset="utf-8">

    <title>Reiserechner</title>
    <meta name="description" content="Reiserechner">
    <meta name="author" content="Uwe">

    <script type="text/javascript">

        let gesamtkosten = 0;
        let anteile = 0
        let anteilpreis = 0;
        let wochentagePerson = []

        function recalculate() {
            getKosten();
            checkCheckboxes();
            getAnteilpreis();
            getKostenProPerson();
        }

        function getKosten() {
            const fixkosten = parseInt(document.getElementById("fixkosten").value) || 0;
            const variableKosten = parseInt(document.getElementById("variableKosten").value) || 0;
            gesamtkosten = fixkosten + variableKosten
            document.getElementById('gesamtkosten').innerHTML = gesamtkosten.toString();
        }

        function checkCheckboxes() {

            let numberOfCheckedItems = 0;

            wochentagePerson = [];

            for (let p = 1; p <= 8; p++) {
                const checkboxes = document.getElementsByName("wochentage" + p.toString());
                wochentagePerson[p] = 0
                for (let i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].checked) {
                        wochentagePerson[p]++;
                        numberOfCheckedItems++;
                    }
                }
                document.getElementById('summeTagePerson' + p.toString()).innerHTML = wochentagePerson[p].toString();
            }

            anteile = numberOfCheckedItems;

            document.getElementById('anteile').innerHTML = numberOfCheckedItems.toString();
        }

        function getAnteilpreis() {

            if (anteile > 0) {
                anteilpreis = gesamtkosten / anteile;
            } else {
                anteilpreis = 0;
            }

            document.getElementById('anteilpreis').innerHTML = anteilpreis.toString();
        }

        function getKostenProPerson() {
            for (let p = 1; p <= 8; p++) {

                let genutzteAnteile = wochentagePerson[p];
                let personenKosten = anteilpreis * genutzteAnteile;

                document.getElementById('preisPerson' + p.toString()).innerHTML = personenKosten.toFixed(2).toString();
            }
        }
    </script>

</head>

<body onLoad="recalculate()">

<?php

/*
 * Wochentage im Tabellenkopf
 */
$thPersonen = "\n";
$days = array("SA", "SO", "MO", "DI", "MI", "DO", "FR");
foreach ($days as $v) {
    $thPersonen .= "<th>$v</th>\n";
}

/*
 * Für jede Person eine Zeile
 */
$personenzahl = 8;

$tbody = "\n";
for ($p = 1; $p <= $personenzahl; $p++) {
    $spalte = "";
    $spalte .= "<tr>\n";

    $spalte .= "<td>";
    $spalte .= '<input type="text" id="namePerson' . $p . '" name="personenName" value="Person ' . $p . '">';
    $spalte .= "</td>\n";

    foreach ($days as $v) {
        $zeile = "";
        $zeile .= "<td>";
        $zeile .= '<input type="checkbox" name="wochentage' . $p . '" onclick="return recalculate();">';
        $zeile .= "</td>\n";
        $spalte .= $zeile;
    }

    $spalte .= '<td id="summeTagePerson' . $p . '">0</td>' . "\n";

    $spalte .= '<td id="preisPerson' . $p . '">0</td>' . "\n";

    $spalte .= "</tr>\n\n";

    $tbody .= $spalte;
}
?>

<fieldset>
    <legend>Gesamtkosten</legend>
    <form>
        <p>
            <label for="fixkosten">Fixkosten:</label>
            <input type="text" id="fixkosten" name="fixkosten" oninput="recalculate()">
        </p>
        <p>
            <label for="variableKosten">variable Kosten:</label>
            <input type="text" id="variableKosten" name="variableKosten" oninput="recalculate()">
        </p>
    </form>
    <p>Gesamtkosten: <span id="gesamtkosten">0</span></p>
</fieldset>

<fieldset>
    <legend>Wochentage</legend>
    <table>
        <thead>
        <tr>
            <th></th>
            <?= $thPersonen ?>
            <th>Tage</th>
            <th>Kosten</th>
        </tr>
        </thead>

        <tbody>
        <?= $tbody ?>
        </tbody>
    </table>
</fieldset>

<fieldset>
    <legend>Debug</legend>
    Anteile: <p id="anteile">0</p>
    Anteilpreis: <p id="anteilpreis">0</p>
</fieldset>

</body>
</html>