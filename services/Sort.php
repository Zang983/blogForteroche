<?php

/**
 * Classe utilitaire permettant d'effectuer des opérations de tri.
 */
class Sort
{
    /**
     * @param array $articles : un tableau d'objets Article.
     * @param string $property : le nom de la propriété sur laquelle trier, il faut un getter contenant le nom de cette propriété.
     * @param bool $asc : true pour un tri ascendant, false pour un tri descendant.
     * @return array : le tableau d'articles trié.
     * Trie un tableau d'articles en fonction d'une propriété.
     */
     
    public static function sortArticles(array $articles, string $property, bool $asc = true): array
    {
        usort($articles, function ($a, $b) use ($property, $asc) {
            $getter = 'get' . ucfirst($property);
            if (!method_exists($a, $getter))
                throw new Exception("La propriété $property n'existe pas dans la classe Article");

            $valueA = $a->$getter();
            $valueB = $b->$getter();

            if ($asc)
                return $valueA <=> $valueB;
            else
                return $valueB <=> $valueA;
        });

        return $articles;
    }

}