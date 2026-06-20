@props(['post'])

<div class="space-y-4">
    <div>
        <x-input-label for="title" :value="__('Title')" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $post->title ?? '')" required />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="category_id" :value="__('Category')" />
        <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
            <option value="">{{ __('Select a category') }}</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $post->category_id ?? '') == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="feature_image" :value="__('Feature Image URL')" />
        <x-text-input id="feature_image" name="feature_image" type="url" class="mt-1 block w-full" :value="old('feature_image', $post->feature_image ?? 'https://picsum.photos/seed/new-post/800/400')" required />
        <x-input-error :messages="$errors->get('feature_image')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="body" :value="__('Body')" />
        <textarea id="body" name="body" rows="10" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('body', $post->body ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('body')" class="mt-2" />
    </div>
</div>
