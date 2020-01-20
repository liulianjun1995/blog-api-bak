<?php

namespace App\Enums;

class ArticleConstants
{
    public const ARTICLE_STATUS = [
        1 => 'published',
        2 => 'draft'
    ];

    public static function getArticleStatusLabel($status)
    {
        return self::ARTICLE_STATUS[$status] ?? '';
    }
}
