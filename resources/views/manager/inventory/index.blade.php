
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<x-app-layout>
    {{-- THÊM TIÊU ĐỀ XỊN SÒ VÀO ĐÂY --}}
    <x-slot name="header">
        <div class="text-left flex flex-col justify-start">
            <h2 class="font-bold text-xl text-red-600 leading-tight flex items-center m-0">
                <span class="text-2xl mr-2">📦</span> 
                KIỂM SOÁT TỒN KHO
            </h2>
            <p class="text-sm text-gray-400 mt-1 font-medium ml-9">Quản lý mặt hàng sắp hết và thống kê hoạt động xuất/nhập</p>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white p-6 rounded-xl shadow-sm border border-red-200">
                <h3 class="text-lg font-bold text-red-600 mb-4 flex items-center">
                    <span class="mr-2">🚨</span> DANH SÁCH HÀNG SẮP HẾT (Cần nhập thêm)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($lowStockProducts as $p)
                        <div class="border-l-4 border-red-500 bg-red-50 p-4 rounded-r-lg shadow-sm">
                            <p class="font-bold text-gray-800">{{ $p->name }}</p>
                            <p class="text-sm text-red-700">Tồn kho: <span class="text-xl font-black">{{ $p->quantity }}</span> (Mức tối thiểu: {{ $p->min_stock }})</p>
                        </div>
                    @empty
                        <p class="text-gray-500 italic">Hiện tại không có hàng nào dưới mức tối thiểu.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">📊 Thống kê Nhập/Xuất 7 ngày gần nhất</h3>
                    <a href="{{ route('manager.inventory.export') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg flex items-center text-sm transition">
                        📥 Xuất File Excel Kiểm Kho
                    </a>
                </div>
                
                <div class="relative h-96 w-full">
                    <canvas id="bieuDoNhapXuat"></canvas>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('bieuDoNhapXuat').getContext('2d');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                // Nhận mảng ngày tháng thật từ Controller
                labels: {!! json_encode($chartLabels) !!},
                datasets: [
                    {
                        label: 'Số lượng Nhập Kho',
                        // Nhận số phiếu nhập thật
                        data: {!! json_encode($chartReceiptData) !!}, 
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        borderRadius: 4
                    },
                    {
                        label: 'Số lượng Xuất Kho',
                        // Nhận số phiếu xuất thật
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
                            stepSize: 1 // Ép hiển thị số nguyên
                        }
                    }
                }
            }
        });
    });
</script>