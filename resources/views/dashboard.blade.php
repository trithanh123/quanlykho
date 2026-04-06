<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('TRANG CHỦ') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- 1. Bắt thông báo thành công / lỗi (Đã được đưa vào trong khung chuẩn) --}}
            @if (session('success'))
                <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-100 border border-green-200" role="alert">
                    <span class="font-bold">Thành công!</span> {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-100 border border-red-200" role="alert">
                    <span class="font-bold">Lỗi rồi:</span> {{ session('error') }}
                </div>
            @endif

            {{-- 2. Khối lời chào mặc định --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900 dark:text-gray-100 font-medium">
                    {{ __("Bạn Đã Đăng Nhập Thành Công!") }}
                </div>
            </div>

            {{-- 3. Khối Bảng Phiếu Nhập Kho (Đã thêm Dark Mode cho đẹp) --}}
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">
                📊 Phiếu nhập kho gần đây
            </h3>
            <a href="{{ route('receipts.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                + Lập phiếu mới
            </a>
        </div>

        <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-xl">
            <table class="w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="w-1/5 px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mã phiếu</th>
                        <th class="w-1/5 px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Người lập</th>
                        <th class="w-1/5 px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày lập</th>
                        <th class="w-1/5 px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ghi chú</th>
                        <th class="w-1/5 px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($recentReceipts as $receipt)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600 dark:text-indigo-400 truncate">
                                #PNK-{{ str_pad($receipt->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 truncate">
                                {{ $receipt->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 truncate">
                                {{ $receipt->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 italic truncate">
                                {{ $receipt->note ?? 'Không có ghi chú' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('receipts.show', $receipt->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Chi tiết</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">
                                Chưa có phiếu nhập nào được tạo. Hãy tạo mới ngay!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">
                📤 Phiếu xuất kho gần đây
            </h3>
            <a href="{{ route('issues.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                + Lập phiếu xuất
            </a>
        </div>

        <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-xl">
            <table class="w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="w-1/5 px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mã phiếu</th>
                        <th class="w-1/5 px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Người lập</th>
                        <th class="w-1/5 px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày lập</th>
                        <th class="w-1/5 px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ghi chú</th>
                        <th class="w-1/5 px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($recentIssues as $issue)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600 dark:text-indigo-400 truncate">
                                {{ $issue->issue_code }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 truncate">
                                {{ $issue->user->name ?? 'Không xác định' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 truncate">
                                {{ \Carbon\Carbon::parse($issue->issue_date)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 italic truncate">
                                {{ $issue->note ?? 'Không có ghi chú' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('issues.show', $issue->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Chi tiết</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">
                                Chưa có phiếu xuất kho nào được tạo.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
</x-app-layout>