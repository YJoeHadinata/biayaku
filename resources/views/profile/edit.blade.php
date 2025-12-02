<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div
                class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                <i class="bi bi-person-fill text-white text-xl"></i>
            </div>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Profile Settings') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    {{ __('Manage your account information and security settings') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Profile Overview Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8">
                    <div class="flex items-center space-x-6">
                        <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="bi bi-person-fill text-white text-3xl"></i>
                        </div>
                        <div class="text-white">
                            <h3 class="text-2xl font-bold">{{ Auth::user()->name }}</h3>
                            <p class="text-blue-100">{{ Auth::user()->email }}</p>
                            <div class="flex items-center space-x-4 mt-2">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if (Auth::user()->isSuperAdmin()) bg-red-500 text-white
                                    @elseif(Auth::user()->isAdmin()) bg-blue-500 text-white
                                    @else bg-gray-500 text-white @endif">
                                    {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}
                                </span>
                                @if (Auth::user()->branch)
                                    <span class="text-blue-100 text-sm">
                                        <i class="bi bi-building mr-1"></i>{{ Auth::user()->branch->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Profile Information -->
                <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="bi bi-person-gear text-blue-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ __('Profile Information') }}</h3>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">{{ __('Update your account details and email address.') }}
                        </p>
                    </div>

                    <div class="p-6">
                        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('patch')

                            <div>
                                <x-input-label for="name" :value="__('Full Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                    :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email Address')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                    :value="old('email', $user->email)" required autocomplete="username" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                    <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <div class="flex items-center space-x-2">
                                            <i class="bi bi-exclamation-triangle text-yellow-600"></i>
                                            <p class="text-sm text-yellow-800">
                                                {{ __('Your email address is unverified.') }}
                                            </p>
                                        </div>
                                        <button form="send-verification"
                                            class="mt-2 text-sm text-blue-600 hover:text-blue-800 font-medium">
                                            {{ __('Click here to re-send the verification email.') }}
                                        </button>

                                        @if (session('status') === 'verification-link-sent')
                                            <p class="mt-2 text-sm text-green-600 font-medium">
                                                {{ __('A new verification link has been sent to your email address.') }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <x-primary-button class="flex items-center space-x-2">
                                    <i class="bi bi-check-circle"></i>
                                    <span>{{ __('Save Changes') }}</span>
                                </x-primary-button>

                                @if (session('status') === 'profile-updated')
                                    <div class="flex items-center space-x-2 text-green-600">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <span class="text-sm font-medium">{{ __('Saved successfully!') }}</span>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Password Settings -->
                <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="bi bi-shield-lock text-green-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ __('Password Settings') }}</h3>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ __('Keep your account secure with a strong password.') }}</p>
                    </div>

                    <div class="p-6">
                        <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                            @csrf
                            @method('put')

                            <div>
                                <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                                <x-text-input id="update_password_current_password" name="current_password"
                                    type="password" class="mt-1 block w-full" autocomplete="current-password" />
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="update_password_password" :value="__('New Password')" />
                                <x-text-input id="update_password_password" name="password" type="password"
                                    class="mt-1 block w-full" autocomplete="new-password" />
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="update_password_password_confirmation" :value="__('Confirm New Password')" />
                                <x-text-input id="update_password_password_confirmation" name="password_confirmation"
                                    type="password" class="mt-1 block w-full" autocomplete="new-password" />
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <x-primary-button class="flex items-center space-x-2">
                                    <i class="bi bi-key"></i>
                                    <span>{{ __('Update Password') }}</span>
                                </x-primary-button>

                                @if (session('status') === 'password-updated')
                                    <div class="flex items-center space-x-2 text-green-600">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <span class="text-sm font-medium">{{ __('Password updated!') }}</span>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>
</x-app-layout>
