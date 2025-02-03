@include('components.bootstrap')

<x-app-layout>
    <x-slot name="header">
        <h2>
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-8 offset-md-2 bg-white dark:bg-gray-800 shadow rounded">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-8 offset-md-2 bg-white dark:bg-gray-800 shadow rounded">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-8 offset-md-2 bg-white dark:bg-gray-800 shadow rounded">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
