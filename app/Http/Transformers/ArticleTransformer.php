<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 24/11/2018
 * Time: 05:10
 */

namespace App\Http\Transformers;


use App\Article;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ArticleTransformer extends TransformerAbstract
{

    function transform(Article $article){
        return [
            'title'  =>  $article->title,
            'body'  =>  $article->body,
            'created_at'  =>  Carbon::parse($article->created_at)->toDateTimeString(),
        ];
    }

}