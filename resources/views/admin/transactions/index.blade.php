<x-app-layout>
    <x-slot name="header">
    <div class="text-left flex flex-col justify-start">
        {{-- Đã đổi text-orange-600 sang text-blue-600 để có màu xanh dương --}}
        <h2 class="font-bold text-xl text-blue-600 leading-tight flex items-center m-0">
            <span class="text-2xl mr-2">🔄</span> 
            KIỂM SOÁT LỊCH SỬ NHẬP / XUẤT
        </h2>
        <p class="text-sm text-gray-500 mt-1 font-medium ml-9">Theo dõi và quản lý toàn bộ biến động kho hàng (Quyền Admin)</p>
    </div>
</x-slot>


    <div class="py-12" x-data="{ tab: 'receipts' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-lg mb-6 shadow-sm flex items-center">
                    <span class="mr-2">🛡️</span> {{ session('success') }}
                </div>
            @endif

            <div class="flex border-b border-gray-200 mb-6 bg-white rounded-t-lg shadow-sm">
                <button @click="tab = 'receipts'" 
                        :class="tab === 'receipts' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="w-1/2 py-4 px-1 text-center border-b-2 font-bold text-sm transition-all duration-200 rounded-tl-lg">
                    📥 LỊCH SỬ PHIẾU NHẬP
                </button>
                <button @click="tab = 'issues'" 
                        :class="tab === 'issues' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="w-1/2 py-4 px-1 text-center border-b-2 font-bold text-sm transition-all duration-200 rounded-tr-lg">
                    📤 LỊCH SỬ PHIẾU XUẤT
                </button>
            </div>

            <div x-show="tab === 'receipts'" x-transition>
                <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-4">Mã Phiếu Nhập</th>
                                <th class="px-6 py-4">Người Lập (Thủ kho)</th>
                                <th class="px-6 py-4">Ngày giờ lập</th>
                                <th class="px-6 py-4">Hoạt động sửa đổi</th>
                                <th class="px-6 py-4 text-center">Hành động Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($receipts as $receipt)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold text-indigo-600">PN-{{ $receipt->id }}</td>
                                <td class="px-6 py-4 font-medium">{{ $receipt->user->name ?? 'Không rõ' }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $receipt->created_at->format('d/m/Y H:i') }}</td>
                                
                                <td class="px-6 py-4 {{ $receipt->updated_at->gt($receipt->created_at) ? 'text-orange-600 font-bold' : 'text-gray-500' }}">
                                    @if($receipt->updated_at->gt($receipt->created_at))
                                        ⚠️ Đã sửa lúc: <br>{{ $receipt->updated_at->format('d/m/Y H:i') }}
                                    @else
                                        ✔️ Gốc (Chưa sửa)
                                    @endif
                                </td>

                                <td class="px-6 py-4 flex justify-center gap-2">
                                    <form action="{{ route('admin.transactions.unlockReceipt', $receipt->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <button class="text-blue-600 hover:text-blue-800 font-bold text-xs bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-1.5 rounded transition">Mở khóa</button>
                                    </form>
                                    <form action="{{ route('admin.transactions.destroyReceipt', $receipt->id) }}" method="POST" onsubmit="return confirm('Admin có chắc chắn muốn XÓA vĩnh viễn Phiếu Nhập này không?');">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:text-red-800 font-bold text-xs bg-red-50 hover:bg-red-100 border border-red-200 px-3 py-1.5 rounded transition">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">Chưa có phiếu nhập nào trên hệ thống.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div x-show="tab === 'issues'" x-transition style="display: none;">
                <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-4">Mã Phiếu Xuất</th>
                                <th class="px-6 py-4">Người Lập (Thủ kho)</th>
                                <th class="px-6 py-4">Ngày giờ lập</th>
                                <th class="px-6 py-4">Hoạt động sửa đổi</th>
                                <th class="px-6 py-4 text-center">Hành động Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($issues as $issue)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold text-green-600">{{ $issue->issue_code ?? 'PX-'.$issue->id }}</td>
                                <td class="px-6 py-4 font-medium">{{ $issue->user->name ?? 'Không rõ' }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $issue->created_at->format('d/m/Y H:i') }}</td>
                                
                                <td class="px-6 py-4 {{ $issue->updated_at->gt($issue->created_at) ? 'text-orange-600 font-bold' : 'text-gray-500' }}">
                                    @if($issue->updated_at->gt($issue->created_at))
                                        ⚠️ Đã sửa lúc: <br>{{ $issue->updated_at->format('d/m/Y H:i') }}
                                    @else
                                        ✔️ Gốc (Chưa sửa)
                                    @endif
                                </td>

                                <td class="px-6 py-4 flex justify-center gap-2">
                                    <form action="{{ route('admin.transactions.unlockIssue', $issue->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <button class="text-blue-600 hover:text-blue-800 font-bold text-xs bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-1.5 rounded transition">Mở khóa</button>
                                    </form>
                                    <form action="{{ route('admin.transactions.destroyIssue', $issue->id) }}" method="POST" onsubmit="return confirm('Admin có chắc chắn muốn XÓA vĩnh viễn Phiếu Xuất này không?');">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:text-red-800 font-bold text-xs bg-red-50 hover:bg-red-100 border border-red-200 px-3 py-1.5 rounded transition">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">Chưa có phiếu xuất nào trên hệ thống.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>