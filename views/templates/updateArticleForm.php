<?php
/** 
 * Template du formulaire d'update/creation d'un article. 
 */

//Vérification si un id de commentaire est passé en paramètre. Si c'est le cas suppression de ce dernier :
if (isset($_GET["commentId"])) {
    $commentController = new CommentController();
    $commentController->deleteComment($_GET["commentId"], $article->getId());
}
?>

<form action="index.php" method="post" class="foldedCorner">
    <h2><?= $article->getId() == -1 ? "Création d'un article" : "Modification de l'article " ?></h2>
    <div class="formGrid">
        <label for="title">Titre</label>
        <input type="text" name="title" id="title" value="<?= $article->getTitle() ?>" required>
        <label for="content">Contenu</label>
        <textarea name="content" id="content" cols="30" rows="10" required><?= $article->getContent() ?></textarea>
        <input type="hidden" name="action" value="updateArticle">
        <input type="hidden" name="id" value="<?= $article->getId() ?>">
        <button class="submit"><?= $article->getId() == -1 ? "Ajouter" : "Modifier" ?></button>
    </div>
</form>

<h2>Modération des commentaires présents.</h2>
<section class="admin_comments">

    <?php
    foreach ($article->getComments() as $comment) { ?>
        <article class="comments">
            <header>
                <h3>
                    <?= $comment->getPseudo() ?> a écrit le :
                    <?= Utils::convertDateToFrenchFormat($comment->getDateCreation()) ?>
                    <a href="index.php?action=showUpdateArticleForm&id=<?= $article->getId() ?>&commentId=<?= $comment->getId() ?>"
                        <?= Utils::askConfirmation("Êtes-vous sûr de vouloir supprimer ce commentaire ?") ?>>
                        <?= Utils::convertToHtml("./assets/trash.svg"); ?>
                    </a>
                </h3>

            </header>
            <p><?= $comment->getContent() ?></p>
        </article>
        <?php
    }


    ?>
</section>


<script>
</script>