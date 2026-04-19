<x-app-layout>
    <x-slot name="header">
        <div class="text-left flex flex-col justify-start">
            {{-- Đổi text-indigo-600 sang text-red-600 để có màu đỏ --}}
            <h2 class="font-bold text-xl text-red-600 leading-tight flex items-center m-0">
                <span class="text-2xl mr-2">📤</span> {{-- Đổi icon sang thùng hàng đẩy ra cho hợp với "Xuất" --}}
                LẬP PHIẾU XUẤT KHO
            </h2>
            <p class="text-sm text-gray-500 mt-1 font-medium ml-9">Lập và quản lý các phiếu xuất kho</p>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-200 text-green-800 p-4 rounded-lg mb-6 shadow-sm flex items-center">
                    <span class="mr-2">✅</span> {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-indigo-900">Danh sách Phiếu xuất kho gần đây</h3>
                        <a href="{{ route('issues.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                            + Lập phiếu mới
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                       <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 font-bold">Mã Phiếu</th>
                                    <th class="px-6 py-4 font-bold">Người lập</th>
                                    <th class="px-6 py-4 font-bold">Ngày lập</th>
                                    <th class="px-6 py-4 font-bold">Ghi chú</th>
                                    <th class="px-6 py-4 font-bold text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($issues as $issue)
                                <tr class="bg-white border-b hover:bg-gray-50 transition duration-150">
                                    
                                    <td class="px-6 py-4 font-bold text-indigo-600">
                                        {{ $issue->issue_code }}
                                    </td>
                                    
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $issue->user->name ?? 'Không xác định' }}
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::parse($issue->issue_date)->format('d/m/Y H:i') }}
                                    </td>
                                    
                                    <td class="px-6 py-4 italic text-gray-500">
                                        {{ $issue->note ? $issue->note : 'Không có ghi chú' }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        <a href="{{ route('issues.show', $issue->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1.5 rounded-md border border-indigo-100 transition-colors">
                                            Chi tiết &rarr;
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        <svg style="width: 50px; height: 50px;" class="mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        Chưa có phiếu xuất kho nào. Hãy lập phiếu mới!
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $issues->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>