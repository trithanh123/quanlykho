<x-app-layout>
<div class="container mx-auto px-4 py-8">
    
    @if(session('success'))
        <div class="bg-green-800 text-green-100 p-4 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-[#1e2130] rounded-lg shadow-lg p-6"> <div class="flex justify-between items-center mb-6 border-b border-gray-700 pb-4">
            <h2 class="text-xl font-bold text-white uppercase flex items-center">
                <span class="mr-2">📦</span> PHIẾU XUẤT KHO GẦN ĐÂY
            </h2>
            <a href="{{ route('issues.create') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded font-medium transition-colors">
                + Lập phiếu mới
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-300">
                <thead class="text-xs text-gray-400 uppercase bg-[#161824] border-b border-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium">MÃ PHIẾU</th>
                        <th scope="col" class="px-6 py-4 font-medium">NGƯỜI LẬP</th>
                        <th scope="col" class="px-6 py-4 font-medium">NGÀY LẬP</th>
                        <th scope="col" class="px-6 py-4 font-medium">GHI CHÚ</th>
                        <th scope="col" class="px-6 py-4 font-medium text-center">HÀNH ĐỘNG</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($issues as $issue)
                        <tr class="border-b border-gray-700 hover:bg-[#252a3d] transition-colors">
                            <td class="px-6 py-4 font-medium text-indigo-400">
                                {{ $issue->issue_code }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $issue->user->name ?? 'Không xác định' }} 
                            </td>
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($issue->issue_date)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $issue->note ? $issue->note : 'Không có ghi chú' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('issues.show', $issue->id) }}" class="text-indigo-500 hover:text-indigo-400 font-medium">
                                    Chi tiết
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                Chưa có phiếu xuất kho nào.
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
</x-app-layout>