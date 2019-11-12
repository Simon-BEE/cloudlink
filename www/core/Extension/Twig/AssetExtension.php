<?php

namespace Core\Extension\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AssetExtension extends AbstractExtension
{
    /**
     * Liens entre la méthode principal et le nom de la fonction dans Twig
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset', [$this, "asset"])
        ];
    }

    /**
     * Génère une url pour acceder au dossier assets
     */
    public function asset(string $path): string
    {
        return $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"]."/assets/".$path;
    }
}