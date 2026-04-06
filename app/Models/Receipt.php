<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = ['user_id', 'note'];

    // Thiết lập mối quan hệ: 1 phiếu nhập thuộc về 1 người dùng (Thủ kho)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Thiết lập mối quan hệ: 1 phiếu nhập có nhiều chi tiết hàng hóa
    public function details()
    {
        return $this->hasMany(ReceiptDetail::class);
    }
}
