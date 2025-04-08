<nav class="bg-white shadow py-3">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-8">
        <div class="flex items-center">
            <a href="{{ route('home') }}"
                class="text-decoration-none text-indigo-600 text-xl font-semibold">{{ config('app.name', 'Germanware') }}</a>
        </div>
        <div class="flex items-center w-full gap-8">
            <form action="{{ route('search.products') }}" method="GET"
                class="relative rounded-full overflow-hidden border border-green-500 w-full text-sm">
                <input type="search" name="search"
                    class="block w-full pl-4 pr-10 py-2 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-l-full"
                    placeholder="Nach was suchst du ?">
                <button type="submit"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center bg-green-500 text-white rounded-r-full px-4 focus:outline-none">
                    Suche
                    <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </form>
            <div class="ml-4">
                @if (isset($categories))
                    <button id="categoryDropdownButton" data-dropdown-toggle="categoryDropdown"
                        class="inline-flex items-center text-gray-700 hover:text-green-500 font-medium text-nowrap">
                        @if (request()->routeIs('categories.index'))
                            Alle Kategorien
                        @elseif (isset($category))
                            {{ $category->name }}
                        @else
                            Kategorien
                        @endif
                        <svg class="h-5 w-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div id="categoryDropdown"
                        class="z-10 hidden bg-white divide-y divide-gray-100 shadow dark:bg-gray-700 right-0 overflow-y-scroll">
                        <a href="{{ route('/') }}"
                            class="block p-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Kategorie</a>
                    </div>
                @endif
            </div>
        </div>
        <div class="text-nowrap">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <span></span>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-block px-3 py-1.5 text-white bg-green-500 text-decoration-none border border-transparent rounded-sm text-sm leading-normal">
                            Log in
                        </a>
                    @endauth
                </nav>
            @endif
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dropdownButton = document.getElementById('categoryDropdownButton');
            const dropdownDiv = document.getElementById('categoryDropdown');
            if (dropdownButton && dropdownDiv) {
                dropdownButton.addEventListener('click', () => {
                    dropdownDiv.classList.toggle('hidden');
                });
                document.addEventListener('click', (event) => {
                    if (!dropdownDiv.contains(event.target) && !dropdownButton.contains(event.target)) {
                        dropdownDiv.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</nav>
