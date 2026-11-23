<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivedJobModel extends Model
{
    protected $table = 'Jobs';

    public function category() {
        return $this->belongsToMany(CategoryModel::class, 'JobCategories', 'UrlId', 'categoryId');
    }

    public function company() {
        return $this->belongsTo(CompanyModel::class, 'companyId', 'robotId');
    }
}
