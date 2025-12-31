@extends('template.auth')
@section('auth')
    <div class="bg-card py-8 px-4 shadow-xl shadow-black/5 border border-border sm:rounded-xl sm:px-10">

        <!-- Header -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-8">
            <div
                class="mx-auto h-16 w-16 bg-emerald-50 rounded-full flex items-center justify-center mb-4 ring-1 ring-emerald-100 shadow-sm">
                <x-dynamic-component component="lucide-lock-keyhole" class="h-8 w-8 text-primary" />
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-foreground">Set New Password</h2>
            <p class="mt-2 text-sm text-muted-foreground px-4">
                Please create a new password for your account <span
                    class="font-medium text-foreground">{{ $email ?? '' }}</span>
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

        <form class="space-y-5" action="{{ route('password.reset') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div x-data="{ show: false }">
                <label for="password" class="block text-sm font-medium text-foreground mb-1">New Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-dynamic-component component="lucide-lock" class="h-5 w-5 text-muted-foreground" />
                    </div>
                    <input :type="show ? 'text' : 'password'" id="password" name="password" required
                        class="block w-full rounded-lg border bg-background pl-10 pr-10 py-2.5 text-sm placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm @error('password') border-red-500 @enderror"
                        placeholder="Min. 6 characters">

                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-muted-foreground hover:text-foreground transition-colors cursor-pointer focus:outline-none">
                        <x-dynamic-component component="lucide-eye" class="h-4 w-4" x-show="!show" />
                        <x-dynamic-component component="lucide-eye-off" class="h-4 w-4" x-show="show"
                            style="display: none;" />
                    </button>
                </div>
                @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="flex w-full justify-center rounded-lg bg-primary px-3 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-emerald-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-all active:scale-[0.98]">
                Reset Password
            </button>
        </form>
    </div>
@endsection
