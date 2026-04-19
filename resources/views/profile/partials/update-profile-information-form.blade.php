<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Thông Tin Chi Tiết Nhân Viên') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Cập nhật đầy đủ hồ sơ cá nhân và thông tin công tác của bạn.") }}
        </p>
    </header>

    {{-- CHÚ Ý: Đã thêm enctype="multipart/form-data" để hỗ trợ upload hình ảnh --}}
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-10" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="flex flex-col items-center gap-6 p-6 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700">
            <h3 class="font-bold text-xl text-indigo-600 dark:text-indigo-400 w-full text-center">📸 ẢNH CHÂN DUNG</h3>
            
            <div class="relative w-36 h-36">
                @if ($user->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}" class="w-full h-full rounded-full object-cover border-4 border-white dark:border-gray-900 shadow-xl">
                @else
                    <div class="w-full h-full rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center border-4 border-white dark:border-gray-900 shadow-xl">
                        <span class="text-5xl">👤</span>
                    </div>
                @endif
            </div>
            
            <div class="w-full max-w-sm">
                <x-input-label for="profile_picture" :value="__('Cập nhật ảnh (Chấp nhận: .jpg, .png)')" class="text-center" />
                <x-text-input id="profile_picture" name="profile_picture" type="file" class="mt-2 block w-full bg-white dark:bg-gray-900 shadow-sm" accept=".jpg,.jpeg,.png" />
                <x-input-error class="mt-2 text-center" :messages="$errors->get('profile_picture')" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-10">
            
            <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700">
                <h3 class="font-bold text-indigo-600 mb-6 flex items-center">🪪 1. THÔNG TIN ĐỊNH DANH</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="name" :value="__('Họ và Tên')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>
                    <div>
                        <x-input-label for="employee_id" :value="__('Mã Nhân Viên (Read-only)')" />
                        <x-text-input id="employee_id" name="employee_id" type="text" class="mt-1 block w-full bg-gray-100" :value="old('employee_id', $user->employee_id)" readonly />
                        <x-input-error class="mt-2" :messages="$errors->get('employee_id')" />
                    </div>
                    <div>
                        <x-input-label for="dob" :value="__('Ngày sinh')" />
                        <x-text-input id="dob" name="dob" type="date" class="mt-1 block w-full" :value="old('dob', $user->dob)" />
                        <x-input-error class="mt-2" :messages="$errors->get('dob')" />
                    </div>
                    <div>
                        <x-input-label for="gender" :value="__('Giới tính')" />
                        {{-- Đã thêm class dark:text-white vào đây --}}
                        <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md shadow-sm">
                            <option value="Nam" {{ old('gender', $user->gender) == 'Nam' ? 'selected' : '' }}>Nam</option>
                            <option value="Nữ" {{ old('gender', $user->gender) == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                            <option value="Khác" {{ old('gender', $user->gender) == 'Khác' ? 'selected' : '' }}>Khác</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                    </div>
                </div>
            </div>

            <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700">
                <h3 class="font-bold text-green-600 mb-6 flex items-center">🏗️ 2. CÔNG VIỆC & CHUYÊN MÔN</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="position" :value="__('Chức vụ cụ thể')" />
                        <x-text-input id="position" name="position" type="text" class="mt-1 block w-full" :value="old('position', $user->position)" placeholder="VD: Thủ kho trưởng" />
                        <x-input-error class="mt-2" :messages="$errors->get('position')" />
                    </div>
                    <div>
                        <x-input-label for="join_date" :value="__('Ngày vào làm')" />
                        <x-text-input id="join_date" name="join_date" type="date" class="mt-1 block w-full" :value="old('join_date', $user->join_date)" />
                        <x-input-error class="mt-2" :messages="$errors->get('join_date')" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="skills" :value="__('Kỹ năng & Chứng chỉ đặc thù')" />
                        {{-- Đã thêm class dark:text-white vào đây --}}
                        <textarea id="skills" name="skills" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md shadow-sm" rows="3">{{ old('skills', $user->skills) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('skills')" />
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700 space-y-4">
                <h3 class="font-bold text-orange-600 mb-6 flex items-center">📞 3. THÔNG TIN LIÊN LẠC</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="email" :value="__('Email công ty')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>
                    <div>
                        <x-input-label for="phone" :value="__('Số điện thoại cá nhân')" />
                        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" placeholder="0xxx.xxx.xxx" />
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="address_current" :value="__('Địa chỉ hiện tại')" />
                        <x-text-input id="address_current" name="address_current" type="text" class="mt-1 block w-full" :value="old('address_current', $user->address_current)" />
                        <x-input-error class="mt-2" :messages="$errors->get('address_current')" />
                    </div>
                </div>
            </div>

        </div>

        <div class="flex items-center gap-4 p-4 mt-6 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700 justify-end">
            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition">
                {{ __('LƯU TOÀN BỘ HỒ SƠ') }}
            </x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 dark:text-green-400">{{ __('Đã lưu.') }}</p>
            @endif
        </div>
    </form>
</section>