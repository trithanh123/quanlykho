<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Hồ Sơ Cá Nhân') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Form 1: THÔNG TIN CÁ NHÂN (Bung rộng toàn phần) --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="w-full"> 
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Tui đã xóa phần Đổi mật khẩu và Xóa tài khoản ở đây rồi --}}
            
        </div>
    </div>
</x-app-layout>