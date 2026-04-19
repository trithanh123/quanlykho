<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
 protected $guarded = []; // Hoặc khai báo $fillable tùy style của bạn

    public function details()
    {
        return $this->hasMany(IssueDetail::class, 'issue_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    protected $fillable = [
    'issue_code', 
    'user_id', 
    'issue_date', 
    'note',
    'tai_xe_id', // Bắt buộc phải có dòng này để Laravel cho phép lưu dữ liệu
    'status'
];
}
