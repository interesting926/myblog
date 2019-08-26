<?php

namespace App\Services;

use Michelf\MarkdownExtra;
use Michelf\SmartyPants;

class Markdowner
{
    public function toHtml($text)
    {
        $text = $this->preTransformText($text);
        $text = MarkdownExtra::defaultransForm($text);
        $text = SmartyPants::defaultTransFrom($text);
        $text = $this->postTransFromText($text);
        return $text;
    }

    protected function pretransFromText($text)
    {
        return $text;
    }

    protected function postTransformText($text)
    {
        return $text;
    }


}
