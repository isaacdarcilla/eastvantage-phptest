<?php

namespace database\Model;

use database\Http\Model;

/**
 * We can inherit the Model class properties and methods. In this way, we have more flexibility and re-usability in our code.
 * We can add more entities as many as we want.
 */
class News extends Model
{
    protected static $table = 'news';

    public function comments()
    {
        $this->hasMany(Comments::class, 'news_id');
    }
}