<html>

<body>
    <div id="exceptions">
        <h1 data-tr="Informacion de los errores:">...</h1>
        <table class="table_list_exceptions">
            <tr>
                <th data-tr="Error">...</th>
                <th data-tr="Descripción">...</th>
                <th data-tr="Dia y Hora">...</th>
            </tr>
            <?php
            if ($rdo->num_rows === 0) {
                echo '<tr>';
                echo '<td align="left"  colspan="3">!No hay errores registrados hasta el momento!</td>';
                echo '</tr>';
            } else {
                foreach ($rdo as $row) {
                    echo "<tr>";
                    echo "<td>" . $row['type_error'] . "</td>";
                    echo "<td>" . $row['spot'] . "</td>";
                    echo "<td>" . $row['current_date_time'] . "</td>";
                    echo '</tr>';
                }
            }
            ?>
        </table>
        <button><a href="index.php?module=ctrl_home&op=list" data-to="Volver"><img src="views\img\volver.png"> Volver</a></button>
    </div>

</body>

</html>