<?php

namespace App\Http\Controllers;

use App\Article;
use App\User;
use App\Http\Transformers\ArticleTransformer;
use Dingo\Api\Auth\Auth;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    use Helpers;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::get();
        return $this->response->item($articles, new ArticleTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => ['required', 'unique:articles'],
            'body' => ['required']
        ];

        $payload  =  $request->only('title', 'body');
        $validator = app('validator')->make($payload, $rules);
        $user_id = \Auth::user() ?  \Auth::user()->id  :  null;

        if ($validator->fails())
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Errors found in the form.', $validator->errors());

        $created  =  Article::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => $user_id
        ]);

        if ($created)
            return $this->response->item($created, new ArticleTransformer())->addMeta('message','Article created successfully!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $articles = Article::where('user_id',$id)->get();
        return $this->response->item($articles, new ArticleTransformer());
        //return $articles;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $article = Article::find($id);
        $article->title = $request->get('title');
        $article->body = $request->get('body');
        $article->save();
        if($article)
            return $this->response->item($article, new ArticleTransformer())->addMeta('Message','Article updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::with('author')->find($id);
        $user_id = \Auth::user()->id;
        if($user_id == $article->user_id) {
            $article->delete();
            return "Data deleted";
        }
        else{
            return "Data not deleted";
        }
    }
}
