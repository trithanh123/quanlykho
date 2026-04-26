<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        
        <div class="mb-4 no-print">
            <a href="{{ route('receipts.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center">
                <span class="mr-2">&larr;</span> Quay lại danh sách
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
            <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-white uppercase m-0">
                    Chi tiết phiếu nhập: #PNK-{{ str_pad($receipt->id, 5, '0', STR_PAD_LEFT) }}
                </h2>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold border border-green-200">
                    ĐÃ NHẬP KHO
                </span>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <p class="text-sm text-gray-500 uppercase font-bold mb-1">Người lập phiếu:</p>
                        <p class="text-lg font-bold text-gray-800">{{ $receipt->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 uppercase font-bold mb-1">Ngày nhập kho:</p>
                        <p class="text-lg font-bold text-gray-800">
                            {{ $receipt->created_at->format('d/m/Y H:i:s') }}
                        </p>
                    </div>
                    <div class="md:col-span-2 border-t border-gray-200 pt-4">
                        <p class="text-sm text-gray-500 uppercase font-bold mb-1">Ghi chú:</p>
                        <p class="italic text-gray-700 bg-gray-50 p-3 rounded border border-gray-100">
                            {{ $receipt->note ? $receipt->note : 'Không có ghi chú kèm theo.' }}
                        </p>
                    </div>
                </div>

                <div class="mb-10">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b-2 border-gray-200 pb-2 flex items-center">
                        <span class="mr-2">📋</span> DANH SÁCH SẢN PHẨM NHẬP
                    </h3>
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="w-full text-left text-sm text-gray-700 border-collapse">
                            <thead class="bg-gray-100 text-gray-600 uppercase text-xs border-b border-gray-300">
                                <tr>
                                    <th class="px-4 py-3 font-bold">STT</th>
                                    <th class="px-4 py-3 font-bold">Mã SKU</th>
                                    <th class="px-4 py-3 font-bold">Tên sản phẩm</th>
                                    <th class="px-4 py-3 font-bold text-right">Số lượng</th>
                                    <th class="px-4 py-3 font-bold text-right">Giá nhập</th>
                                    <th class="px-4 py-3 font-bold text-right">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalAmount = 0; @endphp
                                @forelse($receipt->details as $index => $detail)
                                    @php 
                                        $subTotal = $detail->quantity * ($detail->price ?? 0);
                                        $totalAmount += $subTotal;
                                    @endphp
                                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-4">{{ $index + 1 }}</td>
                                        <td class="px-4 py-4 font-mono text-indigo-600 font-medium">{{ $detail->product->sku ?? 'N/A' }}</td>
                                        <td class="px-4 py-4 font-bold text-gray-800">{{ $detail->product->name ?? 'Sản phẩm đã bị xóa' }}</td>
                                        <td class="px-4 py-4 text-right">{{ number_format($detail->quantity) }}</td>
                                        <td class="px-4 py-4 text-right">{{ number_format($detail->price) }}đ</td>
                                        <td class="px-4 py-4 text-right font-bold text-red-600">{{ number_format($subTotal) }}đ</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">Không có dữ liệu chi tiết.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-gray-50 font-bold border-t-2 border-gray-300">
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-right text-gray-700 uppercase">Tổng giá trị lô hàng:</td>
                                    <td class="px-4 py-4 text-right text-red-600 text-lg">{{ number_format($totalAmount) }}đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>       

                <div class="flex justify-end gap-4 no-print border-t border-gray-200 pt-6">
                    <button onclick="window.print()" class="bg-gray-100 hover:bg-gray-200 text-gray-800 border border-gray-300 px-6 py-2 rounded shadow-sm transition-colors font-bold flex items-center">
                        <span class="mr-2">🖨️</span> In phiếu nhập
                    </button>
                    <a href="{{ route('receipts.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-2 rounded font-bold shadow-sm transition-colors">
                        Xong
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        header, nav, .no-print, footer { display: none !important; }
        body { background-color: white !important; }
        .bg-white { box-shadow: none !important; border: none !important; }
        table, th, td { border: 1px solid #ccc !important; }
    }
</style>
</x-app-layout>