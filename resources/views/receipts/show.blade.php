<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        
        <div class="mb-4 no-print">
            <a href="{{ route('issues.index') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                <span class="mr-2">&larr;</span> Quay lại danh sách
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
            <div class="bg-blue-600 px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-white uppercase m-0">
                    Chi tiết phiếu xuất: {{ $issue->issue_code }}
                </h2>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold border border-green-200">
                    HOÀN THÀNH
                </span>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <p class="text-sm text-gray-500 uppercase font-bold mb-1">Người lập phiếu:</p>
                        <p class="text-lg font-bold text-gray-800">{{ $issue->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 uppercase font-bold mb-1">Ngày xuất kho:</p>
                        <p class="text-lg font-bold text-gray-800">
                            {{ \Carbon\Carbon::parse($issue->issue_date)->format('d/m/Y H:i:s') }}
                        </p>
                    </div>
                    <div class="md:col-span-2 border-t border-gray-200 pt-4">
                        <p class="text-sm text-gray-500 uppercase font-bold mb-1">Ghi chú:</p>
                        <p class="italic text-gray-700 bg-gray-50 p-3 rounded border border-gray-100">
                            {{ $issue->note ? $issue->note : 'Không có ghi chú kèm theo.' }}
                        </p>
                    </div>
                </div>

                <div class="mb-10">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b-2 border-gray-200 pb-2 flex items-center">
                        <span class="mr-2">📋</span> DANH SÁCH SẢN PHẨM XUẤT
                    </h3>
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="w-full text-left text-sm text-gray-700 border-collapse">
                            <thead class="bg-gray-100 text-gray-600 uppercase text-xs border-b border-gray-300">
                                <tr>
                                    <th class="px-4 py-3 font-bold">STT</th>
                                    <th class="px-4 py-3 font-bold">Mã SKU</th>
                                    <th class="px-4 py-3 font-bold">Tên sản phẩm</th>
                                    <th class="px-4 py-3 font-bold text-right">Số lượng</th>
                                    <th class="px-4 py-3 font-bold text-right">Đơn giá</th>
                                    <th class="px-4 py-3 font-bold text-right">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalAmount = 0; @endphp
                                @foreach($issue->details as $index => $detail)
                                    @php 
                                        $subTotal = $detail->quantity * ($detail->price ?? 0);
                                        $totalAmount += $subTotal;
                                    @endphp
                                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-4">{{ $index + 1 }}</td>
                                        <td class="px-4 py-4 font-mono text-blue-600 font-medium">{{ $detail->product->sku ?? 'N/A' }}</td>
                                        <td class="px-4 py-4 font-bold text-gray-800">{{ $detail->product->name ?? 'Sản phẩm đã bị xóa' }}</td>
                                        <td class="px-4 py-4 text-right">{{ number_format($detail->quantity) }}</td>
                                        <td class="px-4 py-4 text-right">{{ number_format($detail->price) }}đ</td>
                                        <td class="px-4 py-4 text-right font-bold text-red-600">{{ number_format($subTotal) }}đ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 font-bold border-t-2 border-gray-300">
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-right text-gray-700 uppercase">Tổng giá trị xuất:</td>
                                    <td class="px-4 py-4 text-right text-red-600 text-lg">{{ number_format($totalAmount) }}đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>       

                <div class="bg-blue-50 rounded-lg border border-blue-200 p-6 no-print mb-8 shadow-sm">
                    <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                        <span class="mr-2">🚚</span> ĐIỀU PHỐI GIAO NHẬN TÀI XẾ
                    </h3>
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 font-medium flex items-center">
                            <span class="mr-2">✅</span> {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('issues.assign', $issue->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="flex flex-col md:flex-row gap-4 items-end">
                            <div class="flex-1 w-full">
                                <label for="tai_xe_id" class="block text-sm font-bold text-gray-700 mb-2">Chọn tài xế phụ trách đơn hàng này:</label>
                                <select name="tai_xe_id" id="tai_xe_id" required 
                                        class="w-full bg-white border border-gray-300 text-gray-800 text-base rounded-md focus:ring-blue-500 focus:border-blue-500 block p-2.5 shadow-sm">
                                    <option value="">-- Vui lòng chọn tài xế --</option>
                                    @if(isset($drivers) && $drivers->count() > 0)
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}" {{ $issue->tai_xe_id == $driver->id ? 'selected' : '' }}>
                                                Tài xế: {{ $driver->name }} ({{ $driver->email }})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            
                            <button type="submit" class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-8 rounded-md transition-colors shadow-sm flex items-center justify-center">
                                <span>LƯU ĐIỀU PHỐI</span>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="flex justify-end gap-4 no-print border-t border-gray-200 pt-6">
                    <button onclick="window.print()" class="bg-gray-100 hover:bg-gray-200 text-gray-800 border border-gray-300 px-6 py-2 rounded shadow-sm transition-colors font-bold flex items-center">
                        <span class="mr-2">🖨️</span> In phiếu xuất
                    </button>
                    <a href="{{ route('issues.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-2 rounded font-bold shadow-sm transition-colors">
                        Xong
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS dọn dẹp khi in ấn */
    @media print {
        header, nav, .no-print, footer { display: none !important; }
        body { background-color: white !important; }
        .bg-white { box-shadow: none !important; border: none !important; }
        table, th, td { border: 1px solid #ccc !important; }
    }
</style>
</x-app-layout>