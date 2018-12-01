<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 24/11/2018
 * Time: 05:10
 */

namespace App\Http\Transformers;


use App\User;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    function transform(User $user){
        return [
            'id'  =>  $user->id,
            'full_name'  =>  ucfirst($user->name),
            'email'  =>  $user->email,
            'bio'  =>  $user->bio,
            'created_at'  =>  Carbon::parse($user->created_at)->toDateTimeString(),
        ];
    }

}