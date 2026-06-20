<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Blog') }}
            </h2>
            @auth
                @can('create', App\Models\Post::class)
                    <a href="{{ route('posts.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white transition">
                        {{ __('New Post') }}
                    </a>
                @endcan
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-flash-message />

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($posts as $post)
                    <article class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <a href="{{ route('posts.show', $post) }}">
                            <img src="{{ $post->feature_image }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                        </a>
                        <div class="p-6">
                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-2">
                                <a href="{{ route('categories.show', $post->category) }}" class="hover:underline">{{ $post->category->name }}</a>
                                <span>&middot;</span>
                                <span>{{ $post->user->name }}</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                <a href="{{ route('posts.show', $post) }}" class="hover:underline">{{ $post->title }}</a>
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3">{{ Str::limit(strip_tags($post->body), 120) }}</p>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-600 dark:text-gray-300">
                        No posts yet.
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
