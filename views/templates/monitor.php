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
            <th class="col col-view">
                <a href='<?= $links[0]["link"] ?>' class="<?= $links[0]["active"] . ' ' . $links[0]['arrow'] ?>"
                    aria-label="Tri par nombre de vues">
                    <?= Utils::convertToHtml("./assets/eye.svg"); ?>
                </a>
            </th>
            <th class="col col-published">
                <a href='<?= $links[1]["link"] ?>' class="<?= $links[1]['active'] . ' ' . $links[1]['arrow'] ?>"
                    aria-label="Tri par date de publication">
                    Publication
                </a>
            </th>
            <th class="col col-title">
                <a href='<?= $links[2]["link"] ?>' class="<?= $links[2]["active"] . ' ' . $links[2]['arrow'] ?>">
                    Titre
                </a>
            </th>
            <th class="col col-content">Extrait de l'article</th>
            <th class="col col-commentaries">
                <a href='<?= $links[3]["link"] ?>' class="<?= $links[3]["active"] . ' ' . $links[3]['arrow'] ?>"
                    aria-label="Tri par nombre de commentaires">
                    <?= Utils::convertToHtml("./assets/comments.svg"); ?>
                </a>
            </th>
            <th class="col col-actions">

            </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($articles as $article) {
            ?>
            <tr>
                <td>
                    <?= $article->getViews() ?>
                </td>
                <td><?= Utils::convertDateToFrenchFormat($article->getDateCreation()) ?></td>
                <td><?= $article->getTitle() ?></td>
                <td><?= $article->getContent(200) ?></td>
                <td class="commentaries"><?= count($article->getComments()) ?></td>
                <td>
                    <div class="actions">
                        <a class="submit" href="index.php?action=showUpdateArticleForm&id=<?= $article->getId() ?>">
                            <?= Utils::convertToHtml("./assets/edit.svg"); ?>
                        </a>
                        <a class="submit" href="index.php?action=deleteArticle&id=<?= $article->getId() ?>"
                            <?= Utils::askConfirmation("Êtes-vous sûr de vouloir supprimer cet article ?") ?>>
                            <?= Utils::convertToHtml("./assets/trash.svg"); ?>
                        </a>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<a class="submit" href="index.php?action=showUpdateArticleForm">Ajouter un article</a>