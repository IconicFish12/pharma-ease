@extends('template.auth')
@section('auth')
    <div class="bg-card py-8 px-4 shadow-xl shadow-black/5 border border-border sm:rounded-xl sm:px-10">

        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-8">
            <div
                class="mx-auto h-16 w-16 bg-emerald-50 rounded-full flex items-center justify-center mb-4 ring-1 ring-emerald-100 shadow-sm">
                <x-dynamic-component component="lucide-mail-question" class="h-8 w-8 text-primary" />
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-foreground">Forgot Password?</h2>
            <p class="mt-2 text-sm text-muted-foreground px-4">
                Enter the email address associated with your account and we'll send you a link to reset your password.
            </p>
        </div>

        @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="mb-4 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3">
                <x-dynamic-component component="lucide-check-circle" class="h-5 w-5 shrink-0 text-emerald-600" />
                <div>
                    <h4 class="font-semibold text-sm">Success</h4>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 flex items-center gap-3">
                <x-dynamic-component component="lucide-alert-circle" class="h-5 w-5 shrink-0 text-red-600" />
                <div>
                    <h4 class="font-semibold text-sm">Error</h4>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Form -->
        <form class="space-y-6" action="{{ route('password.send') }}" method="POST">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-foreground mb-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-dynamic-component component="lucide-mail" class="h-5 w-5 text-muted-foreground" />
                    </div>
                    <input id="email" name="email" type="email" autocomplete="email" required
                        value="{{ old('email') }}"
                        class="block w-full rounded-lg border bg-background pl-10 pr-3 py-2.5 text-sm placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm @error('email') border-red-500 @enderror"
                        placeholder="Enter your email address">
                </div>
                @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="flex w-full justify-center rounded-lg bg-primary px-3 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-emerald-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-all active:scale-[0.98]">
                Send Reset Link
            </button>
        </form>

        <!-- Back Link -->
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}"
                class="text-sm font-medium text-muted-foreground hover:text-foreground flex items-center justify-center gap-2 transition-colors">
                <x-dynamic-component component="lucide-arrow-left" class="h-4 w-4" />
                Back to Login
            </a>
        </div>
    </div>
@endsection
