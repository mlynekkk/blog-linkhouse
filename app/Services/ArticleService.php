<?php

namespace App\Services;

use App\Models\Article;
use DateTime;
use SimpleXMLElement;

class ArticleService
{
    const LANGUAGE_VERSION = [
        'pl' => 'https://linkhouse.pl/feed/',
        'en' => 'https://linkhouse.net/feed/',
    ];

    /**
     * get all articles
     *
     * @param  string $lang
     * @return array
     */
    public function fetchArticles(string $lang): array
    {
        $items = $this->fetchItems($lang);
        $articles = [];

        foreach ($items as $item) {
            $categories = [];
            foreach ($item->category as $category) {
                $categories[] = (string)$category;
            }

            preg_match('/\d+$/', (string)$item->guid, $matches);
            $guid = $matches[0];

            $article = [
                'guid' => $guid,
                'title' => (string)$item->title,
                'pubDate' => (new DateTime((string)$item->pubDate))->format('d/m/y H:i:s',),
                'category' => implode(", ", $categories),
            ];

            $articles[] = $article;
        }

        return $articles;
    }

    /**
     * get article by guid
     *
     * @param  string $lang
     * @param  string $guid
     * @return Article|null
     */
    public function getArticle(string $lang, string $guid): ?Article
    {
        $items = $this->fetchItems($lang);

        foreach ($items as $item) {
            preg_match('/\d+$/', (string)$item->guid, $matches);
            $articleGuid = $matches[0];
            if ($articleGuid === $guid) {
                $article = new Article();
                $article->guid = $articleGuid;
                $article->title = (string)$item->title;
                $article->link = (string)$item->link;
                $article->description = (string)$item->description;
                $categories = [];
                foreach ($item->category as $category) {
                    $categories[] = (string)$category;
                }
                $article->categories = implode(", ", $categories);

                return $article;
            }
        }
        return null;
    }

    /**
     * fetch items
     *
     * @param  string $lang
     * @return array|SimpleXMLElement
     */
    private function fetchItems(string $lang)
    {
        // fetch contents
        $response = file_get_contents(self::LANGUAGE_VERSION[$lang]);
        if ($response === false) {
            return [];
        }

        // parse xml response
        $xml = simplexml_load_string($response);
        if ($xml === false  || !isset($xml->channel->item)) {
            return [];
        }
        
        return $xml->channel->item;
    }
}
