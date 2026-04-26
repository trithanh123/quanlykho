<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Quản Lý Phiếu Nhập Kho') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Nút Tạo mới --}}
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Danh sách Lịch sử Nhập hàng</h3>
                        <a href="{{ route('receipts.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                            + Lập phiếu nhập mới
                        </a>
                    </div>

                    {{-- Bảng hiển thị dữ liệu --}}
                    <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-xl">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50 dark:bg-gray-900 text-gray-500 text-xs uppercase font-bold">
                                <tr>
                                    <th class="px-6 py-4 border-b dark:border-gray-700">Mã Phiếu</th>
                                    <th class="px-6 py-4 border-b dark:border-gray-700">Người Lập</th>
                                    <th class="px-6 py-4 border-b dark:border-gray-700">Kho Hàng</th>
                                    <th class="px-6 py-4 border-b dark:border-gray-700">Ngày Lập</th>
                                    <th class="px-6 py-4 border-b dark:border-gray-700 text-right">Hành động</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($receipts as $receipt)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="px-6 py-4 font-bold text-indigo-600 dark:text-indigo-400">
                                            #PNK-{{ str_pad($receipt->id, 5, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td class="px-6 py-4">{{ $receipt->user->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $receipt->warehouse->name ?? 'Chưa xác định' }}</td>
                                        <td class="px-6 py-4">{{ $receipt->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('receipts.show', $receipt->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:hover:text-indigo-400 font-medium">Xem chi tiết</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            Chưa có phiếu nhập nào trong hệ thống.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>