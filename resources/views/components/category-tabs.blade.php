<ul class="flex flex-wrap items-center gap-2 text-sm font-medium text-center">
    <li>

    </li>

    <li>
        <a href="{{ route('dashboard') }}"
            class="{{ request()->routeIs('dashboard')
                ? 'inline-block px-4 py-2 text-white bg-blue-600 rounded-lg active'
                : 'inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-slate-700 transition hover:border-slate-300 hover:bg-slate-100' }}">
            All
        </a>
    </li>

    @forelse ($categories as $category)
        <li>
            <a href="{{ route('post.byCategory', $category) }}" class="{{ request()->routeIs('post.byCategory') && request()->route('category')?->id === $category->id
                ? 'inline-block px-4 py-2 text-white bg-blue-600 rounded-lg active'
                : 'inline-block px-4 py-2 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-white' }}">
                {{ $category->title }}
            </a>
        </li>
    @empty
        <li>
            <p class="text-slate-500">No categories available.</p>
        </li>
    @endforelse

</ul>