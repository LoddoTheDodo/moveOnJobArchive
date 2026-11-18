<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    public function archivedJob() {
        return $this->belongsToMany(ArchivedJobModel::class, 'JobCategories', 'UrlId', 'categoryId');
    }
}
