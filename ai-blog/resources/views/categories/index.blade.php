<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Categories') }}
            </h2>
            @auth
                @can('create', App\Models\Category::class)
                    <a href="{{ route('categories.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white transition">
                        {{ __('New Category') }}
                    </a>
                @endcan
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-flash-message />

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Name') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Posts') }}</th>
                                @auth
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Actions') }}</th>
                                @endauth
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($categories as $category)
                                <tr>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('categories.show', $category) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $category->name }}</a>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $category->posts_count }}</td>
                                    @auth
                                        <td class="px-4 py-3 text-right space-x-3">
                                            @can('update', $category)
                                                <a href="{{ route('categories.edit', $category) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">{{ __('Edit') }}</a>
                                            @endcan
                                            @can('delete', $category)
                                                <form method="POST" action="{{ route('categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Delete this category and all its posts?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:underline">{{ __('Delete') }}</button>
                                                </form>
                                            @endcan
                                        </td>
                                    @endauth
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-gray-600 dark:text-gray-300">{{ __('No categories yet.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
