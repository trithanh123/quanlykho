<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row justify-between items-center w-full">
            
            <div class="text-left flex flex-col justify-start">
                <h2 class="font-bold text-xl text-red-600 leading-tight flex items-center m-0">
                    <span class="text-2xl mr-2">📜</span> 
                    LỊCH SỬ GIAO HÀNG
                </h2>
                <p class="text-sm text-gray-500 mt-1 font-medium ml-9">Các chuyến xe bạn đã hoàn tất</p>
            </div>
            
            <div class="text-right">
                <a href="{{ route('driver.assignments') }}" class="text-indigo-700 hover:text-indigo-900 font-bold text-sm transition-colors bg-white px-5 py-2.5 rounded-lg border border-indigo-200 shadow-sm whitespace-nowrap">
                    &larr; Về danh sách chờ giao
                </a>
            </div>
            
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                @forelse($completedIssues as $issue)
                    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden relative">
                        <div class="absolute top-0 left-0 w-2 h-full bg-green-500"></div>
                        
                        <div class="p-6 pl-8">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                                            HOÀN THÀNH
                                        </span>
                                        <span class="inline-block px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full">
                                            MÃ: {{ $issue->issue_code }}
                                        </span>
                                    </div>
                                    <p class="text-gray-500 text-sm font-medium flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Giao xong lúc: {{ \Carbon\Carbon::parse($issue->updated_at)->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                <p class="text-xs font-bold text-gray-500 uppercase mb-3 border-b border-gray-200 pb-2">Hàng hóa đã giao:</p>
                                <ul class="space-y-3">
                                    @foreach($issue->details as $detail)
                                        <li class="flex justify-between text-sm items-center">
                                            <span class="font-medium text-gray-700">{{ $detail->product->name ?? 'N/A' }}</span>
                                            <span class="font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">x{{ $detail->quantity }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                        <span class="text-6xl mb-4 block">🍃</span>
                        <h3 class="text-xl font-bold text-gray-800">Chưa có lịch sử</h3>
                        <p class="text-gray-500 mt-2">Bạn chưa hoàn thành chuyến xe nào cả.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>