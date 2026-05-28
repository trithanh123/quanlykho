<?php

namespace App\Models;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $guarded = [];

    // Thiết lập mối quan hệ: 1 phiếu nhập thuộc về 1 người dùng (Thủ kho)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Thiết lập mối quan hệ: 1 phiếu nhập có nhiều chi tiết hàng hóa
   public function details() {
    return $this->hasMany(ReceiptDetail::class, 'receipt_id');
}
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
