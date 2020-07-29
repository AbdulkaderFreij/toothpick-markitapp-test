<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\User;

class Post extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'body', 'user_id',
    ];
    protected $guarded=[];
    protected $tables = 'posts';

    public function user(){
        return $this->belongsTo('App\User');
    }
}
