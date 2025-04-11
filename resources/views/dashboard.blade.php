<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <a href="{{ route('drawings.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                {{ __('Create Drawing') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">{{ __("Welcome to Your Dashboard") }}</h3>
                    <p class="mb-4">{{ __("You're logged in!") }}</p>

                    <div class="mt-6">
                        <h4 class="text-md font-medium mb-2">Quick Links:</h4>
                        <ul class="list-disc pl-5 space-y-2">
                            <li><a href="{{ route('drawings.index') }}" class="text-blue-600 hover:text-blue-800">View All Drawings</a></li>
                            <li><a href="{{ route('drawings.create') }}" class="text-blue-600 hover:text-blue-800">Create New Drawing</a></li>
                            <li><a href="{{ route('profile.edit') }}" class="text-blue-600 hover:text-blue-800">Edit Your Profile</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
