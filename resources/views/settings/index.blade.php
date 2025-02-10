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
        <div class="row mb-4">
            <div class="col-md-8 offset-md-2 bg-white dark:bg-gray-800 shadow rounded">
                <section class="p-4">
                    <h2 class="text-lg font-medium text-dark">Settings</h2>

                    <p class="mt-1 text-sm text-muted">Update your website settings</p>

                    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                class="form-control"
                                value="{{ $name }}"
                                required
                                autofocus
                                autocomplete="name"
                            />
                        </div>
                        <div class="mb-3">
                            <label for="favicon" class="form-label">Favicon</label>
                            <div class="d-flex align-items-center">
                                <input
                                disabled
                                    id="favicon"
                                    name="favicon"
                                    type="file"
                                    class="form-control"
                                    style="display: inline-block; width: auto;"
                                    autofocus
                                />
                                <img src="{{ asset('favicons/' . $favicon) }}" alt="Current Favicon" class="img-thumbnail" style="max-height: 50px; margin-left: 10px;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="primary_color" class="form-label">Primary Color</label>
                            <input
                                id="primary_color"
                                name="primary_color"
                                type="color"
                                class="form-control form-control-color"
                                value="{{ $color }}"
                                required
                                autofocus
                                pattern="^#([A-Fa-f0-9]{6})$"
                                title="Please enter a valid hex color code"
                            />
                        </div>

                        <div class="mb-3">
                            <label for="theme" class="form-label">Theme</label>
                            <select
                                id="theme"
                                name="theme"
                                class="form-select"
                                required
                                autofocus
                            >
                                <option value="dark" {{ $theme == 'dark' ? 'selected' : '' }}>Dark</option>
                                <option value="white" {{ $theme == 'white' ? 'selected' : '' }}>White</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="lang" class="form-label">Language</label>
                            <select
                                id="lang"
                                name="lang"
                                class="form-select"
                                required
                                autofocus
                            >
                                <option value="en" {{ $lang == 'en' ? 'selected' : '' }}>English</option>
                                <option value="it" {{ $lang == 'it' ? 'selected' : '' }}>Italian</option>
                                <option value="ro" {{ $lang == 'ro' ? 'selected' : '' }}>Romanian</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
