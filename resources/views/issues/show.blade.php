<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        
        <div class="mb-6">
            <a href="{{ route('issues.index') }}" class="text-gray-400 hover:text-white flex items-center">
                <span class="mr-2">←</span> Quay lại danh sách
            </a>
        </div>

        <div class="bg-[#1e2130] rounded-lg shadow-xl overflow-hidden">
            <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-white uppercase">
                    Chi tiết phiếu xuất: {{ $issue->issue_code }}
                </h2>
                <span class="bg-white text-indigo-600 px-3 py-1 rounded-full text-xs font-bold">
                    HOÀN THÀNH
                </span>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 text-gray-300">
                    <div>
                        <p class="text-sm text-gray-500 uppercase font-semibold mb-1">Người lập phiếu:</p>
                        <p class="text-lg font-medium text-white">{{ $issue->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 uppercase font-semibold mb-1">Ngày xuất kho:</p>
                        <p class="text-lg font-medium text-white">
                            {{ \Carbon\Carbon::parse($issue->issue_date)->format('d/m/Y H:i:s') }}
                        </p>
                    </div>
                    <div class="md:col-span-2 border-t border-gray-700 pt-4">
                        <p class="text-sm text-gray-500 uppercase font-semibold mb-1">Ghi chú:</p>
                        <p class="italic">
                            {{ $issue->note ? $issue->note : 'Không có ghi chú kèm theo.' }}
                        </p>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                        <span class="mr-2">📋</span> DANH SÁCH SẢN PHẨM XUẤT
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-300">
                            <thead class="bg-[#161824] text-gray-400 uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-3">STT</th>
                                    <th class="px-4 py-3">Mã SKU</th>
                                    <th class="px-4 py-3">Tên sản phẩm</th>
                                    <th class="px-4 py-3 text-right">Số lượng</th>
                                    <th class="px-4 py-3 text-right">Đơn giá</th>
                                    <th class="px-4 py-3 text-right">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalAmount = 0; @endphp
                                @foreach($issue->details as $index => $detail)
                                    @php 
                                        $subTotal = $detail->quantity * ($detail->price ?? 0);
                                        $totalAmount += $subTotal;
                                    @endphp
                                    <tr class="border-b border-gray-700 hover:bg-[#252a3d]">
                                        <td class="px-4 py-4">{{ $index + 1 }}</td>
                                        <td class="px-4 py-4 font-mono text-indigo-400">
                                            {{ $detail->product->sku ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-4 font-medium text-white">
                                            {{ $detail->product->name ?? 'Sản phẩm đã bị xóa' }}
                                        </td>
                                        <td class="px-4 py-4 text-right">
                                            {{ number_format($detail->quantity) }}
                                        </td>
                                        <td class="px-4 py-4 text-right">
                                            {{ number_format($detail->price) }}đ
                                        </td>
                                        <td class="px-4 py-4 text-right font-bold text-white">
                                            {{ number_format($subTotal) }}đ
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-[#161824] font-bold">
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-right text-gray-400">TỔNG GIÁ TRỊ XUẤT:</td>
                                    <td class="px-4 py-4 text-right text-green-400 text-lg">
                                        {{ number_format($totalAmount) }}đ
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-4">
                    <button onclick="window.print()" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded flex items-center transition-colors">
                        <span class="mr-2">🖨️</span> In phiếu
                    </button>
                    <a href="{{ route('issues.index') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-2 rounded font-bold transition-colors">
                        Xong
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS để ẩn các nút khi in phiếu */
    @media print {
        header, nav, .mb-6, .mt-8.flex, footer {
            display: none !important;
        }
        body {
            background-color: white !important;
            color: black !important;
        }
        .bg-[#1e2130], .bg-[#161824] {
            background-color: transparent !important;
            color: black !important;
            box-shadow: none !important;
        }
        .text-white, .text-gray-300, .text-indigo-400 {
            color: black !important;
        }
        table, th, td {
            border: 1px solid #ccc !important;
        }
    }
</style>
</x-app-layout>