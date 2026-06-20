@props(['category'])

<div>
    <x-input-label for="name" :value="__('Name')" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $category->name ?? '')" required />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>
