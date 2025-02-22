@include('components.bootstrap')
@include('components.head')

<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input
                id="email"
                class="form-control"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input
                id="password"
                class="form-control"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />
            @if ($errors->has('password'))
                <div class="text-danger mt-2">
                    {{ $errors->first('password') }}
                </div>
            @endif
        </div>

        <!-- Remember Me -->
        <div class="form-check mb-3">
            <input
                id="remember_me"
                type="checkbox"
                class="form-check-input"
                name="remember"
            />
            <label for="remember_me" class="form-check-label">
                {{ __('Remember me') }}
            </label>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            @if (Route::has('password.request'))
            <a
                class="text-decoration-none"
                href="{{ route('password.request') }}"
            >
                {{ __('Forgot your password?') }}
            </a>
            @endif

            <button class="btn btn-primary">{{ __('Log in') }}</button>
        </div>
    </form>
</x-guest-layout>
