<x-layouts.app>
    <x-slot name="title">Profil</x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl space-y-6">
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                @include('profile.partials.update-password-form')
            </div>

            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-layouts.app>
