<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chi Tiết Phiếu Nhập Kho') }} <span class="text-indigo-600 dark:text-indigo-400">#PNK-{{ str_pad($receipt->id, 5, '0', STR_PAD_LEFT) }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-8">
                
                {{-- Phần 1: Thông tin chung của phiếu --}}
                <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Người lập phiếu:</p>
                        <p class="mt-1 text-lg font-bold text-gray-900 dark:text-gray-100">{{ $receipt->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thời gian lập:</p>
                        <p class="mt-1 text-lg font-bold text-gray-900 dark:text-gray-100">{{ $receipt->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ghi chú phiếu:</p>
                        <p class="mt-1 text-md text-gray-700 dark:text-gray-300 italic bg-gray-50 dark:bg-gray-900 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                            {{ $receipt->note ?? 'Không có ghi chú nào cho phiếu này.' }}
                        </p>
                    </div>
                </div>

                {{-- Phần 2: Bảng chi tiết hàng hóa --}}
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4 uppercase tracking-wider">Danh sách linh kiện nhập</h3>
                
                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-xl mb-8">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-indigo-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">STT</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Sản phẩm</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Số lượng</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Đơn giá</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            {{-- Biến tính tổng tiền --}}
                            @php $tongTien = 0; @endphp
                            
                            @foreach($receipt->details as $index => $detail)
                                @php 
                                    $thanhTien = $detail->quantity * $detail->price;
                                    $tongTien += $thanhTien;
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-gray-900 dark:text-gray-100">{{ $detail->product->name ?? 'Sản phẩm đã bị xóa' }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $detail->product->sku ?? '' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-indigo-600 dark:text-indigo-400 text-lg">{{ $detail->quantity }}</td>
                                    <td class="px-6 py-4 text-right text-sm font-medium text-gray-500 dark:text-gray-400">{{ number_format($detail->price) }} đ</td>
                                    <td class="px-6 py-4 text-right font-bold text-green-600 dark:text-green-400">{{ number_format($thanhTien) }} đ</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50 dark:bg-gray-900 border-t-2 border-gray-200 dark:border-gray-700">
                                <td colspan="4" class="px-6 py-5 text-right font-bold text-gray-900 dark:text-gray-100 uppercase text-sm">Tổng giá trị phiếu nhập:</td>
                                <td class="px-6 py-5 text-right font-black text-xl text-red-600 dark:text-red-400">{{ number_format($tongTien) }} đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Nút chức năng --}}
                <div class="flex justify-end gap-4 mt-6">
                    <button onclick="window.print()" class="px-6 py-2.5 bg-indigo-100 text-indigo-700 hover:bg-indigo-200 dark:bg-indigo-900 dark:text-indigo-300 dark:hover:bg-indigo-800 font-bold rounded-lg transition shadow-sm flex items-center">
                        🖨️ In Phiếu
                    </button>
                    <a href="{{ route('dashboard') }}" class="px-6 py-2.5 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg transition shadow-sm">
                        ⬅ Quay lại
                    </a>
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>