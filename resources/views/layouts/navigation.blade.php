<nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-xl font-semibold text-indigo-600">{{ config('app.name', 'Laravel') }}</a>
            </div>

            <div class="flex-1 flex justify-center px-2 lg:mx-6">
                <div class="max-w-lg w-full">
                    <form action="{{ route('search.products') }}" method="GET" class="relative">
                        <input
                            type="search"
                            name="search"
                            class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-full leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Suche nach Produkten..."
                        >
                        <button
                            type="submit"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-auto"
                        >
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center space-x-4">
                <a href="{{ route('categories.index') }}" class="text-gray-600 hover:text-indigo-500">Alle Kategorien</a>
                </div>
        </div>
    </div>
</nav>
