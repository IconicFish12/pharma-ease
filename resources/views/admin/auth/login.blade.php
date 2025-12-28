@extends('template.auth')
@section('auth')
    <div
        class="bg-card py-8 px-4 shadow-xl shadow-black/5 border border-border sm:rounded-xl sm:px-10 relative overflow-hidden">

        <!-- Header -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-8">
            <div
                class="mx-auto h-16 w-16 bg-emerald-50 rounded-full flex items-center justify-center mb-4 ring-1 ring-emerald-100 shadow-sm">
                {{-- Logo Pulse Icon --}}
                <x-dynamic-component component="lucide-activity" class="h-8 w-8 text-primary" />
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-foreground">Pharma Ease</h2>
            <p class="mt-2 text-sm text-muted-foreground">
                Welcome back! Please login to your account
            </p>
        </div>

        @if (session('logout-success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="mb-4 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3">
                <x-dynamic-component component="lucide-check-circle" class="h-5 w-5 shrink-0 text-emerald-600" />
                <div>
                    <h4 class="font-semibold text-sm">Success</h4>
                    <p class="text-sm">{{ session('logout-success') }}</p>
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

        <form class="space-y-5" action="{{ route('login.signIn') }}" method="POST">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-foreground mb-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-dynamic-component component="lucide-mail" class="h-5 w-5 text-muted-foreground" />
                    </div>
                    <input id="email" name="email" type="email" autocomplete="email" required
                        value="{{ old('email') }}"
                        class="block w-full rounded-lg border bg-background pl-10 pr-3 py-2.5 text-sm placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm @error('email') border-red-500 @enderror"
                        placeholder="Enter your email">
                </div>
                @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div x-data="{ show: false }">
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-sm font-medium text-foreground">Password</label>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-dynamic-component component="lucide-lock" class="h-5 w-5 text-muted-foreground" />
                    </div>
                    <input :type="show ? 'text' : 'password'" id="password" name="password" required
                        class="block w-full rounded-lg border bg-background pl-10 pr-10 py-2.5 text-sm placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm @error('password') border-red-500 @enderror"
                        placeholder="Enter your password">

                    {{-- Toggle Icon --}}
                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-muted-foreground hover:text-foreground transition-colors cursor-pointer focus:outline-none">
                        <x-dynamic-component component="lucide-eye" class="h-4 w-4" x-show="!show" />
                        <x-dynamic-component component="lucide-eye-off" class="h-4 w-4" x-show="show" x-cloak />
                    </button>
                </div>
                @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember_me" type="checkbox"
                        class="h-4 w-4 rounded text-primary focus:ring-primary bg-background cursor-pointer">
                    <label for="remember-me"
                        class="ml-2 block text-sm text-muted-foreground cursor-pointer select-none">Remember me</label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-primary hover:text-emerald-700 transition-colors">
                        Forgot Password?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit"
                    class="flex w-full justify-center rounded-lg bg-primary px-3 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-emerald-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-all active:scale-[0.98]">
                    Sign In
                </button>
            </div>
        </form>
    </div>
@endsection
