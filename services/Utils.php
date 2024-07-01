<?php

/**
 * Classe utilitaire : cette classe ne contient que des méthodes statiques qui peuvent être appelées
 * directement sans avoir besoin d'instancier un objet Utils.
 * Exemple : Utils::redirect('home'); 
 */
class Utils
{
    /**
     * Convertit une date vers le format de type "Samedi 15 juillet 2023" en francais.
     * @param DateTime $date : la date à convertir.
     * @return string : la date convertie.
     */
    public static function convertDateToFrenchFormat(DateTime $date): string
    {
        // Attention, s'il y a un soucis lié à IntlDateFormatter c'est qu'il faut
        // activer l'extention intl_date_formater (ou intl) au niveau du serveur apache. 
        // Ca peut se faire depuis php.ini ou parfois directement depuis votre utilitaire (wamp/mamp/xamp)
        $dateFormatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::FULL);
        $dateFormatter->setPattern('EEEE d MMMM Y');
        return $dateFormatter->format($date);
    }

    /**
     * Redirige vers une URL.
     * @param string $action : l'action que l'on veut faire (correspond aux actions dans le routeur).
     * @param array $params : Facultatif, les paramètres de l'action sous la forme ['param1' => 'valeur1', 'param2' => 'valeur2']
     * @return void
     */
    public static function redirect(string $action, array $params = []): void
    {
        $url = "index.php?action=$action";
        foreach ($params as $paramName => $paramValue) {
            $url .= "&$paramName=$paramValue";
        }
        header("Location: $url");
        exit();
    }

    /**
     * Cette méthode retourne le code js a insérer en attribut d'un bouton.
     * pour ouvrir une popup "confirm", et n'effectuer l'action que si l'utilisateur
     * a bien cliqué sur "ok".
     * @param string $message : le message à afficher dans la popup.
     * @return string : le code js à insérer dans le bouton.
     */
    public static function askConfirmation(string $message): string
    {
        return "onclick=\"return confirm('$message');\"";
    }

    /**
     * Cette méthode protège une chaine de caractères contre les attaques XSS.
     * De plus, elle transforme les retours à la ligne en balises <p> pour un affichage plus agréable. 
     * @param string $string : la chaine à protéger.
     * @return string : la chaine protégée.
     */
    public static function format(string $string): string
    {
        // Etape 1, on protège le texte avec htmlspecialchars.
        $finalString = htmlspecialchars($string, ENT_QUOTES);

        // Etape 2, le texte va être découpé par rapport aux retours à la ligne, 
        $lines = explode("\n", $finalString);

        // On reconstruit en mettant chaque ligne dans un paragraphe (et en sautant les lignes vides).
        $finalString = "";
        foreach ($lines as $line) {
            if (trim($line) != "") {
                $finalString .= "<p>$line</p>";
            }
        }

        return $finalString;
    }

    /**
     * Cette méthode permet de récupérer une variable de la superglobale $_REQUEST.
     * Si cette variable n'est pas définie, on retourne la valeur null (par défaut)
     * ou celle qui est passée en paramètre si elle existe.
     * @param string $variableName : le nom de la variable à récupérer.
     * @param mixed $defaultValue : la valeur par défaut si la variable n'est pas définie.
     * @return mixed : la valeur de la variable ou la valeur par défaut.
     */
    public static function request(string $variableName, mixed $defaultValue = null): mixed
    {
        return $_REQUEST[$variableName] ?? $defaultValue;
    }

    /*
     * Cette méthode permet de récupérer le code d'un SVG présent dans le dossier assets afin de
     * l'intégré directement dans le code HTML. Permettant de le rendre dynamique au hover
     */
    public static function convertToHtml(string $svgFilePath): string
    {
        return file_get_contents($svgFilePath);
    }

    /*
     * Permet de généré les liens HTML prenant en compte l'ordre de tri du tableau des articles
     * Permettant de donné une orientation à la flêche de tri notamment.
     */
    public static function createLinksArray(): array
    {
        $sortPossibilities = ['views', 'published', 'title', 'commentaries'];
        $link = 'index.php?action=monitor&';
        $actualSortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : "";
        $ascOrDesc = "asc";
        $links = [];

        foreach ($sortPossibilities as $possibility) {
            $arrowType = "arrowUp";
            $isActive = false;
            if ($possibility === $actualSortBy) {
                $isActive = true;
                if (isset($_GET['asc'])) {
                    $ascOrDesc = "desc";
                }
                if (isset($_GET['desc']))
                    $arrowType = "arrowDown";
            }
            array_push($links, ["link" => $link . 'sortBy=' . $possibility . '&' . $ascOrDesc . '', 'arrow' => $arrowType, "active" => $isActive ? "active" : ""]);
        }
        
        return $links;
    }
    public static function sortByTitle(array $articles, bool $asc = true): array
    {
        usort($articles, function ($a, $b) use ($asc) {
            if ($asc)
                return $a->getTitle() <=> $b->getTitle();
            else
                return $b->getTitle() <=> $a->getTitle();
        });

        return $articles;
    }
    public static function sortByViews(array $articles, bool $asc = true): array
    {
        usort($articles, function ($a, $b) use ($asc) {
            if ($asc)
                return $a->getViews() <=> $b->getViews();
            else
                return $b->getViews() <=> $a->getViews();
        });

        return $articles;
    }
    public static function sortByPublished(array $articles, bool $asc = true): array
    {
        usort($articles, function ($a, $b) use ($asc) {
            if ($asc)
                return $a->getDateCreation() <=> $b->getDateCreation();
            else
                return $b->getDateCreation() <=> $a->getDateCreation();
        });

        return $articles;
    }
    public static function sortByCommentaries(array $articles, bool $asc = true): array
    {
        usort($articles, function ($a, $b) use ($asc) {
            if ($asc)
                return count($a->getComments()) <=> count($b->getComments());
            else
                return count($b->getComments()) <=> count($a->getComments());
        });

        return $articles;
    }
}