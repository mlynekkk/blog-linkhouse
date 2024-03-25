<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }
    
    /**
     * get all articles
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $lang = $request->query('lang', 'pl');
        $articles = $this->articleService->fetchArticles($lang);
        return response()->json($articles);
    }
        
    /**
     * get article by guid
     *
     * @param  Request $request
     * @param  string $guid
     * @return JsonResponse
     */
    public function getArticle(Request $request, string $guid): JsonResponse
    {
        $lang = $request->query('lang', 'pl');
        $article = $this->articleService->getArticle($lang, $guid);
        return response()->json($article);
    }
}
