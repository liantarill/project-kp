<x-guest-layout>

    @if ($errors->any())
        <div class="text-red-500">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Institutions -->
        <div class="mt-4">
            <x-input-label for="institution" :value="__('Asal Instansi')" />
            <select name="institution_id" id="institution_id" required>
                <option value="">-- Pilih Departemen --</option>

                @foreach ($institutions as $institution)
                    <option value="{{ $institution->id }}">
                        {{ $institution->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('institution')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="major" :value="__('Jurusan')" />
            <x-text-input id="major" class="block mt-1 w-full" type="text" name="major" :value="old('major')"
                required autocomplete="" />
            <x-input-error :messages="$errors->get('major')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="level" :value="__('Jenjang')" />
            <x-text-input id="level" class="block mt-1 w-full" type="text" name="level" :value="old('level')"
                required autocomplete="" />
            <x-input-error :messages="$errors->get('level')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="department_id" :value="__('Divisi')" />

            <select name="department_id" id="department_id" required>
                <option value="">-- Pilih Departemen --</option>

                @foreach ($departments as $department)
                    <option value="{{ $department->id }}">
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>

            <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="start_date" :value="__('Tanggal Mulai')" />
            <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date')"
                required autocomplete="" />
            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="end_date" :value="__('Tanggal Mulai')" />
            <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date')"
                required autocomplete="" />
            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
