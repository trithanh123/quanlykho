<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssueDetail extends Model
{
protected $guarded = [];

    public function issue()
    {
        return $this->belongsTo(Issue::class, 'issue_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
