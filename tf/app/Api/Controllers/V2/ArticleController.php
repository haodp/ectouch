<?php

namespace app\api\controller\v2;

use app\api\controller\Controller;
use app\api\model\v2\Article;
use app\api\model\v2\ArticleCategory;

class ArticleController extends Controller
{
    /**
    * POST ecapi.article.list
    */
    public function index()
    {
        $rules = [
            'id'        => 'required|integer',
            'page'      => 'required|integer|min:1',
            'per_page'  => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $model = ArticleCategory::getList($this->validated);

        return $this->json($model);
    }

    /**
    * GET article.{id:[0-9]+}
    */
    public function show($id)
    {
        return Article::getArticle($id);
    }
}
