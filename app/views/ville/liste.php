<h2>Liste des villes</h2>

<a href="/villes/ajout">Ajouter une ville</a>

<table border="1">
<tr>
    <th>Nom</th>
    <th>Région</th>
</tr>

<?php foreach($villes as $v) { ?>
<tr>
    <td><?= $v['nom_ville'] ?></td>
    <td><?= $v['region'] ?></td>
</tr>
<?php } ?>
</table>
