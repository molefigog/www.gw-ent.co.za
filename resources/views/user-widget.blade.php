@php
    $user = auth()->user();

    $musicCount = DB::table('music_user')
        ->where('user_id', $user->id)
        ->count();
    $usermusic = $user->musics()->count();
@endphp

<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <div class="flex items-center gap-x-3">
            <x-filament-panels::avatar.user size="lg" :user="$user" />

            <div class="flex-1">
                <h2 class="grid flex-1 text-base font-semibold leading-6 text-gray-950">
                    {{ __('Credits') }}
                </h2>

                <p class="text-sm text-gray-500">
                   LSL {{ $user->balance }}
                </p>
            </div>

            @if ($musicCount > 0)
            <x-filament::link color="gray" href="{{ url('artist', str_replace(' ', '_', $user->name)) }}"
                icon="heroicon-m-musical-note"

                class="btn-outline"
            >
                {{ __('Music')}} ({{ $usermusic }})
            </x-filament::link>
            @endif

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
