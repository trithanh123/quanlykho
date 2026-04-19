<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        
        <div class="mb-6 no-print">
            <a href="{{ route('issues.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center transition-colors">
                <span class="mr-2">←</span> Quay lại danh sách
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center border-b border-indigo-700">
                <h2 class="text-xl font-bold text-white uppercase flex items-center m-0">
                    <span class="mr-2">📄</span> Chi tiết phiếu xuất: {{ $issue->issue_code }}
                </h2>
                <span class="bg-green-100 text-green-800 border border-green-200 px-3 py-1 rounded-full text-xs font-bold shadow-sm">
                    HOÀN THÀNH
                </span>
            </div>          
            
            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 bg-gray-50 p-6 rounded-lg border border-gray-200 shadow-sm">
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
                        <p class="italic text-gray-700">
                            {{ $issue->note ? $issue->note : 'Không có ghi chú kèm theo.' }}
                        </p>
                    </div>
                </div>
                
                <div class="mb-10">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center border-b-2 border-gray-100 pb-2">
                        <span class="mr-2">📋</span> DANH SÁCH SẢN PHẨM XUẤT
                    </h3>
                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                        <table class="w-full text-left text-sm text-gray-700">
                            <thead class="bg-gray-100 text-gray-600 uppercase text-xs border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-4 font-bold">STT</th>
                                    <th class="px-4 py-4 font-bold">Mã SKU</th>
                                    <th class="px-4 py-4 font-bold">Tên sản phẩm</th>
                                    <th class="px-4 py-4 font-bold text-center">Số lượng</th>
                                    <th class="px-4 py-4 font-bold text-right">Đơn giá</th>
                                    <th class="px-4 py-4 font-bold text-right">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @php $totalAmount = 0; @endphp
                                @foreach($issue->details as $index => $detail)
                                    @php 
                                        $subTotal = $detail->quantity * ($detail->price ?? 0);
                                        $totalAmount += $subTotal;
                                    @endphp
                                    <tr class="hover:bg-indigo-50 transition-colors bg-white">
                                        <td class="px-4 py-4">{{ $index + 1 }}</td>
                                        <td class="px-4 py-4 font-mono text-indigo-600 font-medium">
                                            {{ $detail->product->sku ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-4 font-bold text-gray-800">
                                            {{ $detail->product->name ?? 'Sản phẩm đã bị xóa' }}
                                        </td>
                                        <td class="px-4 py-4 text-center font-medium">
                                            {{ number_format($detail->quantity) }}
                                        </td>
                                        <td class="px-4 py-4 text-right">
                                            {{ number_format($detail->price) }}đ
                                        </td>
                                        <td class="px-4 py-4 text-right font-bold text-red-600">
                                            {{ number_format($subTotal) }}đ
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 font-bold border-t-2 border-gray-200">
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-right text-gray-600 uppercase">TỔNG GIÁ TRỊ XUẤT:</td>
                                    <td class="px-4 py-4 text-right text-red-600 text-lg">
                                        {{ number_format($totalAmount) }}đ
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="bg-indigo-50 rounded-lg border border-indigo-200 p-6 mb-8 relative overflow-hidden no-print shadow-sm">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-indigo-500"></div>
                    
                    <h3 class="text-lg font-bold text-indigo-800 mb-4 flex items-center">
                        <span class="mr-2">🚚</span> ĐIỀU PHỐI GIAO NHẬN
                    </h3>
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-md mb-4 text-sm font-medium flex items-center">
                            <span class="mr-2">✅</span> {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('issues.assign', $issue->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="flex flex-col sm:flex-row items-end gap-4">
                            <div class="flex-1 w-full">
                                <label for="tai_xe_id" class="block text-sm font-bold text-gray-700 mb-2">Chọn tài xế phụ trách đơn hàng này:</label>
                                <select name="tai_xe_id" id="tai_xe_id" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors outline-none shadow-sm" required>
                                    <option value="" class="text-gray-500">-- Vui lòng chọn tài xế --</option>
                                    @if(isset($drivers))
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}" {{ $issue->tai_xe_id == $driver->id ? 'selected' : '' }}>
                                                Tài xế: {{ $driver->name }} ({{ $driver->email }})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            
                            <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-8 py-3 rounded-md shadow-md transition-all focus:ring-4 focus:ring-indigo-300 flex items-center justify-center outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7h-3v7h.05a2.5 2.5 0 004.9 0H17a1 1 0 001-1V9.672a1 1 0 00-.293-.707l-2.672-2.672A1 1 0 0014.293 6H14v1z" />
                                </svg>
                                LƯU ĐIỀU PHỐI
                            </button>
                        </div>
                    </form>
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-200 pt-6 no-print">
                    <button onclick="window.print()" class="bg-gray-100 hover:bg-gray-200 text-gray-800 border border-gray-300 px-6 py-2.5 rounded-md flex items-center transition-colors font-bold outline-none shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        In phiếu
                    </button>
                    <a href="{{ route('issues.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-2.5 rounded-md font-bold transition-colors shadow-sm">
                        Lưu Phiếu & Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS dọn dẹp khi in ấn */
    @media print {
        header, nav, .no-print, footer, .mb-6 { display: none !important; }
        body { background-color: white !important; color: black !important; }
        .bg-white { box-shadow: none !important; border: none !important; }
        table, th, td { border: 1px solid #ccc !important; }
    }
</style>
</x-app-layout>