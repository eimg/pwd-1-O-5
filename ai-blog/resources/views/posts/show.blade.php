<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $post->title }}
            </h2>
            @can('update', $post)
                <div class="flex items-center gap-2">
                    <a href="{{ route('posts.edit', $post) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('Edit') }}</a>
                    <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Delete this post?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:underline">{{ __('Delete') }}</button>
                    </form>
                </div>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />

            <article class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <img src="{{ $post->feature_image }}" alt="{{ $post->title }}" class="w-full h-64 sm:h-80 object-cover">
                <div class="p-6 sm:p-8">
                    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-4">
                        <a href="{{ route('categories.show', $post->category) }}" class="hover:underline">{{ $post->category->name }}</a>
                        <span>&middot;</span>
                        <span>{{ $post->user->name }}</span>
                        <span>&middot;</span>
                        <span>{{ $post->created_at->format('M j, Y') }}</span>
                    </div>
                    <div class="prose dark:prose-invert max-w-none text-gray-800 dark:text-gray-200 whitespace-pre-line">{{ $post->body }}</div>
                </div>
            </article>

            <section class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 sm:p-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Comments') }} ({{ $post->comments->count() }})</h3>

                @auth
                    <form method="POST" action="{{ route('comments.store', $post) }}" class="mb-8">
                        @csrf
                        <x-input-label for="content" :value="__('Add a comment')" />
                        <textarea id="content" name="content" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('content') }}</textarea>
                        <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        <div class="mt-3">
                            <x-primary-button>{{ __('Post Comment') }}</x-primary-button>
                        </div>
                    </form>
                @else
                    <p class="mb-8 text-sm text-gray-600 dark:text-gray-400">
                        <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('Log in') }}</a>
                        {{ __('to leave a comment.') }}
                    </p>
                @endauth

                <div class="space-y-6">
                    @forelse ($post->comments as $comment)
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $comment->user->name }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>

                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $comment->content }}</p>
                            @can('delete', $comment)
                                <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="mt-2" onsubmit="return confirm('Delete this comment?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 dark:text-red-400 hover:underline">{{ __('Delete') }}</button>
                                </form>
                            @endcan
                        </div>
                    @empty
                        <p class="text-gray-600 dark:text-gray-400">{{ __('No comments yet.') }}</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
