<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-4xl">
                    <h3 class="text-lg font-semibold text-gray-900">Badge Yang Sudah Didapat</h3>
                    <p class="mt-1 text-sm text-gray-600">Semua badge hasil progres finansial kamu ditampilkan di sini.</p>

                    <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @forelse ($userBadges as $userBadge)
                            <article class="rounded-lg border border-blue-100 bg-blue-50/50 p-4">
                                <div class="flex items-center justify-between gap-2">
                                    <h4 class="font-medium text-gray-900">{{ $userBadge->badge->name ?? 'Badge' }}</h4>
                                    <span class="text-xl">{{ $userBadge->badge->icon ?? '🏅' }}</span>
                                </div>
                                <p class="mt-1 text-sm text-gray-600">{{ $userBadge->badge->description ?? 'Badge tercapai.' }}</p>
                                <p class="mt-2 text-xs text-blue-700">Didapat: {{ optional($userBadge->earned_at)->format('d M Y H:i') ?? '-' }}</p>
                            </article>
                        @empty
                            <div class="rounded-lg border border-dashed border-gray-300 p-4 text-sm text-gray-600 sm:col-span-2 lg:col-span-3">
                                Belum ada badge yang didapat. Mulai dari transaksi, top up, dan challenge untuk unlock badge pertama.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
