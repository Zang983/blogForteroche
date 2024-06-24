<?php
/** 
 * Affichage de la partie monitoring admin : Interface plus complète pour l'édition, suppression des articles et des commentaires.
 */
$links = Utils::createLinksArray(); // Récupération des liens permettant de trier le tableau et du sens de la flêche.
?>

<h2>Monitoring</h2>
<table class="adminArticle monitoring">
    <thead>
        <tr>
            <th>
                <?= Utils::convertToHtml("./assets/eye.svg"); ?>
                <a href='<?= $links[0]["link"] ?>' class="<?= $links[0]["active"]?>" aria-label="Tri par nombre de vues">
                    <?= Utils::convertToHtml("./assets/" . $links[0]['arrow'] . ".svg"); ?>

                </a>
            </th>
            <th>Publié le
                <a href='<?= $links[1]["link"] ?>' class="<?= $links[1]['active']?>" aria-label="Tri par date de publication">
                    <?= Utils::convertToHtml("./assets/" . $links[1]['arrow'] . ".svg"); ?>
                </a>
            </th>
            <th>
                Titre
                <a href='<?= $links[2]["link"] ?>' class="<?= $links[2]["active"]?>" aria-label="Tri par titre">
                    <?= Utils::convertToHtml("./assets/" . $links[2]['arrow'] . ".svg"); ?>
                </a>
            </th>
            <th>Contenu</th>
            <th>Commentaires
                <a href='<?= $links[3]["link"] ?>' class="<?= $links[3]["active"]?>" aria-label="Tri par nombre de commentaires">
                    <?= Utils::convertToHtml("./assets/" . $links[3]['arrow'] . ".svg"); ?>
                </a>
            </th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($articles as $article) {
            echo "<br><br><br><br><br>";
            var_dump($article);
            echo "<br><br><br><br><br>";
            $articleDetails = $article["article"];
            ?>
            <tr>
                <td>
                <?= $articleDetails->getViews() ?>
                </td>
                <td><?= Utils::convertDateToFrenchFormat($articleDetails->getDateCreation()) ?></td>
                <td><?= $articleDetails->getTitle() ?></td>
                <td><?= $articleDetails->getContent(200) ?></td>
                <td class="commentaries"><?= count($article["commentaries"])?></td>
                <td>
                    <div class="actions">
                        <a class="submit" href="index.php?action=showUpdateArticleForm&id=<?= $articleDetails->getId() ?>">
                            <?= Utils::convertToHtml("./assets/edit.svg"); ?>
                        </a>
                        <a class="submit" href="index.php?action=deleteArticle&id=<?= $articleDetails->getId() ?>"
                            <?= Utils::askConfirmation("Êtes-vous sûr de vouloir supprimer cet article ?") ?>>
                            <?= Utils::convertToHtml("./assets/trash.svg"); ?>
                        </a>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>


<!-- <a class="submit" href="index.php?action=showUpdateArticleForm">Ajouter un article</a> -->