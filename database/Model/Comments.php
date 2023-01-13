<?php

namespace database\Model;

use database\Http\Model;

/**
 * We can inherit the Model class properties and methods. In this way, we have more flexibility and re-usability in our code.
 * We can add more entities as many as we want.
 */
class Comments extends Model
{
    protected static $table = "comment";

    protected static $primaryKey = "id";

    public function news()
    {
        return $this->belongsTo(News::class, "id");
    }
}