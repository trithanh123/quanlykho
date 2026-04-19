<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex flex-col items-start justify-start text-left">
            <h2 class="font-bold text-xl text-red-600 leading-tight flex items-center">
                <span class="text-2xl mr-2">🚚</span> 
                DANH SÁCH CHUYẾN XE
            </h2>
            <p class="text-sm text-gray-500 mt-1 font-medium ml-9">Các đơn hàng cần giao trong ngày</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                @forelse($assignedIssues as $issue)
                    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden relative hover:shadow-lg transition-shadow">
                        <div class="absolute top-0 left-0 w-2 h-full bg-indigo-500"></div>
                        
                        <div class="p-6 pl-8">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <span class="inline-block px-3 py-1 bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold rounded-full mb-3">
                                        MÃ: {{ $issue->issue_code }}
                                    </span>
                                    <p class="text-gray-500 text-sm font-medium flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Ngày lập: {{ \Carbon\Carbon::parse($issue->issue_date)->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 mb-5">
                                <p class="text-xs font-bold text-gray-500 uppercase mb-3 border-b border-gray-200 pb-2">Hàng hóa cần giao:</p>
                                <ul class="space-y-3">
                                    @foreach($issue->details as $detail)
                                        <li class="flex justify-between text-sm items-center">
                                            <span class="font-medium text-gray-800">{{ $detail->product->name ?? 'N/A' }}</span>
                                            <span class="font-bold text-indigo-600 bg-indigo-100 px-2 py-1 rounded">x{{ $detail->quantity }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            @if($issue->note)
                                <div class="mb-5 text-sm text-yellow-800 bg-yellow-50 p-4 rounded-lg border border-yellow-200 flex items-start">
                                    <span class="mr-3">📝</span>
                                    <span class="italic">{{ $issue->note }}</span>
                                </div>
                            @endif

                            <div class="mt-2 pt-5 border-t border-gray-100 flex flex-col sm:flex-row gap-3">
                                <form action="{{ route('driver.confirm', $issue->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn đã giao thành công đơn hàng này?');" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition-colors flex justify-center items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                        Xác nhận Đã Giao
                                    </button>
                                </form>

                                @if($issue->status != 'tam_hoan')
                                    <form action="{{ route('driver.postpone', $issue->id) }}" method="POST" onsubmit="return handlePostpone(event, this);" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="ly_do" class="ly_do_input">
                                        <button type="submit" class="w-full bg-white hover:bg-gray-50 text-gray-700 font-bold py-3 px-4 rounded-lg border border-gray-300 transition-colors flex justify-center items-center shadow-sm">
                                            Khách vắng / Dời lịch
                                        </button>
                                    </form>
                                @else
                                    <div class="flex-1 bg-orange-50 text-orange-700 font-bold py-3 px-4 rounded-lg border border-orange-200 text-center flex justify-center items-center">
                                        🕒 Đã dời lịch!
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                        <span class="text-6xl mb-4 block">☕</span>
                        <h3 class="text-xl font-bold text-gray-800">Hôm nay rảnh rỗi!</h3>
                        <p class="text-gray-500 mt-2">Bạn chưa được phân công chuyến xe nào.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function handlePostpone(e, form) {
            e.preventDefault(); 
            let reason = prompt("Vui lòng nhập lý do dời lịch:");
            if (reason !== null) {
                form.querySelector('.ly_do_input').value = reason || 'Không rõ lý do';
                form.submit();
            }
        }
    </script>
</x-app-layout>