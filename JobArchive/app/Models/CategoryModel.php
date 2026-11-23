<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    protected $table = 'Categories';

    public function archivedJob() {
        return $this->belongsToMany(ArchivedJobModel::class, 'JobCategories', 'UrlId', 'categoryId');
    }
}
