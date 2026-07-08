<?php

namespace App\Core\Infrastructure\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class StripStyleTagsExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('strip_style_tags', [self::class, 'strip'], ['is_safe' => ['html']]),
        ];
    }

    public static function strip(string $html): string
    {
        $document = new \DOMDocument();
        $internalErrors = libxml_use_internal_errors(true);
        $document->loadHTML(mb_encode_numericentity($html, [0x80, 0x10FFFF, 0, 0x1FFFFF], 'UTF-8'));
        libxml_use_internal_errors($internalErrors);

        $xpath = new \DOMXPath($document);
        foreach (iterator_to_array($xpath->query('//style')) as $styleNode) {
            $styleNode->parentNode?->removeChild($styleNode);
        }

        return $document->saveHTML() ?: $html;
    }
}
