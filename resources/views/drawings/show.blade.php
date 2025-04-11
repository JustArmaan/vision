<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $drawing->title ?? 'Untitled Drawing' }}
            </h2>
            <a href="{{ route('drawings.index') }}" class="text-blue-600 hover:text-blue-800">
                {{ __('Back to Drawings') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold">
                                {{ $drawing->title ?? 'Untitled Drawing' }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                By {{ $drawing->user->name }} - {{ $drawing->created_at->diffForHumans() }}
                            </p>
                        </div>
                        @can('delete', $drawing)
                            <form action="{{ route('drawings.destroy', $drawing) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this drawing?')">
                                    Delete
                                </button>
                            </form>
                        @endcan
                    </div>
                    <div class="mt-4 border p-2 max-w-lg mx-auto">
                        {!! $drawing->image_data !!}
                    </div>
                    <div class="mt-4 text-right">
                        <a href="{{ route('replies.create', $drawing) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                            Reply with Drawing
                        </a>
                    </div>
                </div>
            </div>

            <h3 class="text-lg font-semibold mb-4">{{ $drawing->replies->count() }} {{ Str::plural('Reply', $drawing->replies->count()) }}</h3>

            <div class="space-y-6">
                @forelse ($drawing->replies as $reply)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="flex justify-between items-start">
                                <p class="text-sm text-gray-600">
                                    By {{ $reply->user->name }} - {{ $reply->created_at->diffForHumans() }}
                                </p>
                                @can('delete', $reply)
                                    <form action="{{ route('replies.destroy', $reply) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this reply?')">
                                            Delete
                                        </button>
                                    </form>
                                @endcan
                            </div>
                            <div class="mt-4 border p-2 max-w-lg mx-auto">
                                {!! $reply->image_data !!}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <p class="text-center text-gray-600">No replies yet. Be the first to reply!</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
