<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $category->name }}
            </h2>
            @can('update', $category)
                <div class="flex items-center gap-2">
                    <a href="{{ route('categories.edit', $category) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('Edit') }}</a>
                    @can('delete', $category)
                        <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Delete this category and all its posts?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:underline">{{ __('Delete') }}</button>
                        </form>
                    @endcan
                </div>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($category->posts as $post)
                    <article class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <a href="{{ route('posts.show', $post) }}">
                            <img src="{{ $post->feature_image }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                        </a>
                        <div class="p-6">
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ $post->user->name }}</div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                <a href="{{ route('posts.show', $post) }}" class="hover:underline">{{ $post->title }}</a>
                            </h3>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-600 dark:text-gray-300">
                        {{ __('No posts in this category yet.') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
