@if (session('success'))
    <div class="mb-4 rounded-md bg-green-50 dark:bg-green-900/20 p-4 text-sm text-green-700 dark:text-green-300">
        {{ session('success') }}
    </div>
@endif
