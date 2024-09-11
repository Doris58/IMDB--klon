<?php
    require_once __DIR__ . '/_header.php';
    require_once __DIR__ . '/pretragaFilmova.php'; 
?>

<table>
    <tr>
        <th> Naziv </th>
        <th> Redatelj </th>
        <th> Godina </th>
        <th> Ocjena </th>
        <th> Moj komentar </th>
        <th> Moja ocjena </th>
    </tr>
    <?php
        foreach($popisFilmova as $film)
        {
            echo '<tr>';
            echo '<td><a href="index.php?rt=ProfilFilma&id=' . $film->id . '">' . $film->naziv . '</td>';
            echo '<td>' . $film->redatelj . '</td>';
            echo '<td>' . $film->godina . '</td>';
            echo '<td>' . $film->ocjena . '</td>';
            echo '<td>' . $film->moj_komentar . '</td>';
            echo '<td>' . $film->moja_ocjena . '</td>';
            echo '</tr>';
        } //
    ?>
</table>

<?php
    require_once __DIR__ . '/_footer.php'; 
?>