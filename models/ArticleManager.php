<?php

/**
 * Classe qui gère les articles.
 */
class ArticleManager extends AbstractEntityManager
{
    /**
     * Récupère tous les articles.
     * @return array : un tableau d'objets Article.
     */
    public function getAllArticles(bool $withCommentaries = false): array
    {
        $sql = "SELECT * FROM article";
        $result = $this->db->query($sql);
        $articles = [];

        while ($article = $result->fetch()) {
            if (!$withCommentaries)
                $articles[] = new Article($article);
            else {
                $commentariesManager = new CommentManager();
                $articles[] = [
                    "article" => new Article($article),
                    "commentaries" => $commentariesManager->getAllCommentsByArticleId($article['id'])
                ];
            }
        }

        if (isset($_GET['sortBy']))
            $articles = $this->sortArticles($_GET['sortBy'], $articles, !isset($_GET['desc']));
        return $articles;
    }

    public function sortArticles(string $sortBy, array $articles, $asc = true): array
    {
        $sortPossibilities = ['views', 'published', 'title', 'commentaries'];
        /*
        Faire la fonction de tri
        */
        if (in_array($sortBy, $sortPossibilities)) {
            switch ($sortBy) {
                case 'views':
                    return Utils::sortByViews($articles, $asc);
                case 'title':
                    return Utils::sortByTitle($articles, $asc);
                case 'commentaries':
                    return Utils::sortByCommentaries($articles, $asc);
                case 'published':
                    return Utils::sortByPublished($articles, $asc);
            }
        }


        return $articles;
    }

    /**
     * Récupère un article par son id.
     * @param int $id : l'id de l'article.
     * @return Article|null : un objet Article ou null si l'article n'existe pas.
     */
    public function getArticleById(int $id): ?Article
    {
        $sql = "SELECT * FROM article WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        $article = $result->fetch();
        if ($article) {
            $sql = "UPDATE article SET views = :incrementedViews WHERE id = :id";
            $this->db->query($sql, ['incrementedViews' => $article['views'] + 1, 'id' => $id]);
            return new Article($article);
        }
        return null;
    }

    /**
     * Ajoute ou modifie un article.
     * On sait si l'article est un nouvel article car son id sera -1.
     * @param Article $article : l'article à ajouter ou modifier.
     * @return void
     */
    public function addOrUpdateArticle(Article $article): void
    {
        if ($article->getId() == -1) {
            $this->addArticle($article);
        } else {
            $this->updateArticle($article);
        }
    }

    /**
     * Ajoute un article.
     * @param Article $article : l'article à ajouter.
     * @return void
     */
    public function addArticle(Article $article): void
    {
        $sql = "INSERT INTO article (id_user, title, content, date_creation) VALUES (:id_user, :title, :content, NOW())";
        $this->db->query($sql, [
            'id_user' => $article->getIdUser(),
            'title' => $article->getTitle(),
            'content' => $article->getContent()
        ]);
    }

    /**
     * Modifie un article.
     * @param Article $article : l'article à modifier.
     * @return void
     */
    public function updateArticle(Article $article): void
    {
        $sql = "UPDATE article SET title = :title, content = :content, date_update = NOW() WHERE id = :id";
        $this->db->query($sql, [
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            'id' => $article->getId()
        ]);
    }

    /**
     * Supprime un article.
     * @param int $id : l'id de l'article à supprimer.
     * @return void
     */
    public function deleteArticle(int $id): void
    {
        $sql = "DELETE FROM article WHERE id = :id";
        $this->db->query($sql, ['id' => $id]);
    }
}