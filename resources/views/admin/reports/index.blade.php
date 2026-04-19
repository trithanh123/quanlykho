<x-app-layout>
    <x-slot name="header">
        <div class="text-left flex flex-col justify-start">
            {{-- Đổi sang màu tím xanh (Indigo) --}}
            <h2 class="font-bold text-xl text-indigo-600 leading-tight flex items-center m-0">
                <span class="text-2xl mr-2">📈</span> 
                TỔNG HỢP BÁO CÁO & THỐNG KÊ
            </h2>
            <p class="text-sm text-gray-500 mt-1 font-medium ml-9">Phân tích dữ liệu nhập xuất và hiệu suất kho hàng</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-lg p-6 text-white flex items-center justify-between">
                <div>
                    <p class="text-indigo-100 text-sm font-bold uppercase tracking-wider mb-1">Tổng giá trị hàng tồn trong kho</p>
                    <h3 class="text-4xl font-extrabold">{{ number_format($totalInventoryValue, 0, ',', '.') }} VNĐ</h3>
                </div>
                <div class="p-4 bg-white bg-opacity-20 rounded-full">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-green-50 px-5 py-4 border-b border-green-100 flex items-center">
                        <span class="text-xl mr-2">🔥</span>
                        <h3 class="font-bold text-green-800">Top xuất nhiều nhất</h3>
                    </div>
                    <ul class="divide-y divide-gray-100 p-2">
                        @forelse($topExported as $item)
                            <li class="p-3 flex justify-between items-center hover:bg-gray-50 rounded">
                                <span class="font-medium text-gray-700">{{ $item->name }}</span>
                                <span class="bg-green-100 text-green-700 py-1 px-3 rounded-full text-xs font-bold">{{ $item->total_exported }} SP</span>
                            </li>
                        @empty
                            <li class="p-4 text-center text-gray-500 text-sm">Chưa có dữ liệu xuất kho.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-orange-50 px-5 py-4 border-b border-orange-100 flex items-center">
                        <span class="text-xl mr-2">🕸️</span>
                        <h3 class="font-bold text-orange-800">Hàng tồn kho lâu nhất</h3>
                    </div>
                    <ul class="divide-y divide-gray-100 p-2">
                        @forelse($oldestStock as $item)
                            <li class="p-3 flex flex-col justify-center hover:bg-gray-50 rounded">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-medium text-gray-700 truncate pr-2">{{ $item->name }}</span>
                                    <span class="bg-orange-100 text-orange-700 py-1 px-2 rounded text-xs font-bold">Còn {{ $item->quantity }}</span>
                                </div>
                                <span class="text-xs text-gray-400">Lưu kho từ: {{ $item->updated_at->format('d/m/Y') }}</span>
                            </li>
                        @empty
                            <li class="p-4 text-center text-gray-500 text-sm">Kho hiện đang trống.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-blue-50 px-5 py-4 border-b border-blue-100 flex items-center">
                        <span class="text-xl mr-2">🚚</span>
                        <h3 class="font-bold text-blue-800">Tài xế giao nhiều nhất</h3>
                    </div>
                    <ul class="divide-y divide-gray-100 p-2">
                        @forelse($topDrivers as $driver)
                            <li class="p-3 flex justify-between items-center hover:bg-gray-50 rounded">
                                <span class="font-medium text-gray-700">Tài xế: {{ $driver->name }}</span>
                                <span class="bg-blue-100 text-blue-700 py-1 px-3 rounded-full text-xs font-bold">{{ $driver->total_delivered }} Chuyến</span>
                            </li>
                        @empty
                            <li class="p-4 text-center text-gray-500 text-sm">Chưa có chuyến giao thành công nào.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>