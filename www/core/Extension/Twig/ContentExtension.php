<?php

namespace Core\Extension\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ContentExtension extends AbstractExtension
{
    /**
     * Connection à la base de données
     */
    public function __construct()
    {
        $this->content = \App\App::getInstance()->getDb();
    }

    /**
     * Liens entre la méthode principal et le nom de la fonction dans Twig
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getContent', [$this, "getContent"])
        ];
    }

    /**
     * Méthode allant chercher le contenu du site à afficher sur le site en bdd
     */
    public function getContent(string $page, string $section): string
    {
        $query = "SELECT content FROM site_content WHERE page = '$page' AND section = '$section'";
        return $this->content->query($query, null, true)->content;
    }
}
