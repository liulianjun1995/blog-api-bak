<?php

namespace App\Enums;

class ArticleConstants
{
    public const ARTICLE_STATUS_PUBLISHED = 1;
    public const ARTICLE_STATUS_DRAFT = 2;

    public const ARTICLE_TAG_STATUS_DISABLED = 0;
    public const ARTICLE_TAG_STATUS_ENABLED = 1;

    /**
     * 文章状态
     */
    public const ARTICLE_STATUS = [
        self::ARTICLE_STATUS_PUBLISHED => 'published',
        self::ARTICLE_STATUS_DRAFT => 'draft'
    ];

    /**
     * 文章标签状态
     */
    public const ARTICLE_TAG_STATUS = [
        self::ARTICLE_TAG_STATUS_DISABLED => 'disabled',
        self::ARTICLE_TAG_STATUS_ENABLED => 'enabled'
    ];

    public static function getArticleStatusLabel($status)
    {
        return self::ARTICLE_STATUS[$status] ?? '';
    }
}
