<?php

namespace App\Contracts\Services\Article;

/**
 * Interface ArticleInterface
 * @package App\Contracts\Services\Article
 */
interface ArticleInterface
{
    /**
     * @param $condition
     * @return mixed
     */
    public function getList($condition);

    /**
     * @param $data
     * @return mixed
     */
    public function createArticle($data);

    /**
     * @param $id
     * @return mixed
     */
    public function getDetail($id);

    /**
     * @param $data
     * @return mixed
     */
    public function updateArticle($data);

    /**
     * @param $id
     * @return mixed
     */
    public function deleteArticle($id);

    /**
     * @return mixed
     */
    public function getCatArticles();

    /**
     * @return mixed
     */
    public function getArticleCount();
}
