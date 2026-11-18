<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyModel extends Model
{
    public function ArchivedJob() {
        return $this->hasMany(ArchivedJobModel::class, 'companyId', 'robotId');
    }
}
