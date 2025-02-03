@include('components.bootstrap')
@include('components.head')

<x-app-layout>
    <x-slot name="header">
        <h2>
            {{ __('Settings') }}
        </h2>
    </x-slot>
    <div class="container">
        @include('components.notifications')
        <div class="card">
            <div class="card-body">
                <p>Settings</p>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
