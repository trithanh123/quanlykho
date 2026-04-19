<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('TRANG CHỦ') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 text-sm text-green-800 rounded-lg bg-green-100 border border-green-200" role="alert">
                    <span class="font-bold">Thành công!</span> {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 text-sm text-red-800 rounded-lg bg-red-100 border border-red-200" role="alert">
                    <span class="font-bold">Lỗi rồi:</span> {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 font-medium text-lg">
                    Chào mừng <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ auth()->user()->name }}</span> quay trở lại hệ thống! 👋
                </div>
            </div>

            @if(auth()->user()->role == 'driver')
                
                {{-- GIAO DIỆN DÀNH RIÊNG CHO TÀI XẾ --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">🚚 CHUYẾN XE CẦN GIAO</h3>
                            <a href="{{ route('driver.assignments') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">Xem tất cả &rarr;</a>
                        </div>
                        <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-xl">
                            <table class="w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mã phiếu</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Ngày giao việc</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($pendingDeliveries as $issue)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                            <td class="px-6 py-4 font-bold text-indigo-600 dark:text-indigo-400">{{ $issue->issue_code }}</td>
                                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ \Carbon\Carbon::parse($issue->created_at)->format('d/m/Y H:i') }}</td>
                                            <td class="px-6 py-4">
                                                @if($issue->status == 'tam_hoan')
                                                    <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs font-bold border border-orange-200">Tạm hoãn</span>
                                                @else
                                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold border border-blue-200">Đang giao</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right"><a href="{{ route('driver.assignments') }}" class="text-indigo-600 dark:text-indigo-400 font-medium">Nhận giao</a></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">Tuyệt vời! Bạn không còn đơn hàng nào tồn đọng.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">📜 LỊCH SỬ GIAO HÀNG GẦN ĐÂY</h3>
                            <a href="{{ route('driver.history') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition shadow-sm">Xem tất cả &rarr;</a>
                        </div>
                        <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-xl">
                            <table class="w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mã phiếu</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Ngày hoàn thành</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($completedDeliveries as $issue)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                            <td class="px-6 py-4 font-bold text-green-600 dark:text-green-400">{{ $issue->issue_code }}</td>
                                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ \Carbon\Carbon::parse($issue->updated_at)->format('d/m/Y H:i') }}</td>
                                            <td class="px-6 py-4 text-right"><a href="{{ route('driver.history') }}" class="text-indigo-600 dark:text-indigo-400 font-medium">Chi tiết</a></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="px-6 py-8 text-center text-gray-400">Chưa có chuyến xe nào được hoàn thành.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            @else

                {{-- GIAO DIỆN DÀNH CHO ADMIN / THỦ KHO --}}
                
                {{-- Bảng Phiếu Nhập Kho --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">📊 Phiếu nhập kho gần đây</h3>
                            <a href="{{ route('receipts.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">+ Lập phiếu mới</a>
                        </div>
                        <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-xl">
                            <table class="w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mã phiếu</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Người lập</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Ngày lập</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Ghi chú</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($recentReceipts as $receipt)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600 dark:text-indigo-400">#PNK-{{ str_pad($receipt->id, 5, '0', STR_PAD_LEFT) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $receipt->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $receipt->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 italic truncate">{{ $receipt->note ?? 'Không có ghi chú' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="{{ route('receipts.show', $receipt->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900">Chi tiết</a></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">Chưa có phiếu nhập nào được tạo.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Bảng Phiếu Xuất Kho --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">📤 Phiếu xuất kho gần đây</h3>
                            <a href="{{ route('issues.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">+ Lập phiếu xuất</a>
                        </div>
                        <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-xl">
                            <table class="w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mã phiếu</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Người lập</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Ngày lập</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Ghi chú</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($recentIssues as $issue)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600 dark:text-indigo-400">{{ $issue->issue_code }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $issue->user->name ?? 'Không xác định' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($issue->issue_date)->format('d/m/Y H:i') }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 italic truncate">{{ $issue->note ?? 'Không có ghi chú' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="{{ route('issues.show', $issue->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900">Chi tiết</a></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">Chưa có phiếu xuất kho nào được tạo.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Hàng hóa & Tồn kho --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">📦 Hàng hóa</h3>
                                <a href="{{ route('products.index') }}" class="text-indigo-600 dark:text-indigo-400 text-sm font-medium hover:underline">Xem tất cả</a>
                            </div>
                            <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-xl">
                                <table class="w-full text-sm text-left">
                                    <thead class="bg-gray-50 dark:bg-gray-900 text-gray-500 uppercase text-xs font-bold">
                                        <tr>
                                            <th class="px-4 py-3">Tên sản phẩm</th>
                                            <th class="px-4 py-3 text-right">Tồn</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @forelse($recentProducts as $product)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                                <td class="px-4 py-3 text-gray-800 dark:text-gray-300 font-medium truncate">{{ $product->name }}</td>
                                                <td class="px-4 py-3 text-right font-bold text-indigo-600 dark:text-indigo-400">{{ $product->quantity }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="2" class="px-4 py-6 text-center text-gray-500">Chưa có dữ liệu hàng hóa.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-red-500">
                        <div class="p-6 pl-8">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-red-600 dark:text-red-500 uppercase tracking-wider flex items-center">
                                    <span class="mr-2">🚨</span> Tồn kho thấp
                                </h3>
                                <a href="{{ route('manager.inventory.index') }}" class="text-red-600 dark:text-red-400 text-sm font-bold underline">Xử lý ngay</a>
                            </div>
                            <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-xl">
                                <table class="w-full text-sm text-left">
                                    <thead class="bg-gray-50 dark:bg-gray-900 text-gray-500 uppercase text-xs font-bold">
                                        <tr>
                                            <th class="px-4 py-3">Sản phẩm</th>
                                            <th class="px-4 py-3 text-center">Còn lại</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @forelse($lowStockProducts as $low)
                                            <tr class="hover:bg-red-50 dark:hover:bg-red-900/10 transition bg-red-50/30">
                                                <td class="px-4 py-3 font-medium text-red-700 dark:text-red-400">{{ $low->name }}</td>
                                                <td class="px-4 py-3 text-center font-bold text-white bg-red-500 rounded px-2">{{ $low->quantity }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="2" class="px-4 py-6 text-center text-green-600 font-bold italic">✅ Kho hàng an toàn!</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ======================================================== --}}
                {{-- KHỐI BIỂU ĐỒ THỐNG KÊ HOẠT ĐỘNG                          --}}
                {{-- ======================================================== --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider flex items-center">
                         📊 Thống kê Nhập/Xuất 7 ngày gần nhất
                           </h3>
                            <a href="{{ route('manager.inventory.export') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow">
                                📥 Xuất File Excel Kiểm Kho
                            </a>
                        </div>
                        
                        <div class="relative h-96 w-full mt-4">
                            <canvas id="bieuDoNhapXuat"></canvas>
                        </div>
                    </div>
                </div>

            @endif

        </div>
    </div>

    {{-- SCRIPT VẼ BIỂU ĐỒ CHART.JS BẰNG DỮ LIỆU THẬT TỪ DATABASE --}}
   {{-- SCRIPT VẼ BIỂU ĐỒ CHART.JS BẰNG DỮ LIỆU THẬT TỪ DATABASE --}}
    @if(auth()->user()->role != 'driver')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('bieuDoNhapXuat').getContext('2d');
                
                new Chart(ctx, {
                    type: 'bar', 
                    data: {
                        // CHỖ NÀY LÀ QUAN TRỌNG NHẤT: Bơm mảng tên tháng thật từ web.php vào
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [
                            {
                                label: 'Số lượng Nhập Kho',
                                // Bơm số liệu Nhập thật
                                data: {!! json_encode($chartReceiptData) !!}, 
                                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1,
                                borderRadius: 4
                            },
                            {
                                label: 'Số lượng Xuất Kho',
                                // Bơm số liệu Xuất thật
                                data: {!! json_encode($chartIssueData) !!}, 
                                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1,
                                borderRadius: 4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1 // Ép trục Y chỉ hiện số nguyên (1 phiếu, 2 phiếu...)
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endif

</x-app-layout>