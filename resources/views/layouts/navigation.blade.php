<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')">
                        {{ __('Suppliers') }}
                    </x-nav-link>
                    <x-nav-link :href="route('pharmaceutical_items.index')" :active="request()->routeIs('pharmaceutical_items.*')">
                        {{ __('Pharmaceutical Items') }}
                    </x-nav-link>
                    <x-nav-link :href="route('supply_items.index')" :active="request()->routeIs('supply_items.*')">
                        {{ __('Supply Items') }}
                    </x-nav-link>
                    <x-nav-link :href="route('requisitions.index')" :active="request()->routeIs('requisitions.*')">
                        {{ __('Requisitions') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="relative flex items-center gap-3">
                    @if (isset($module) && in_array($module, ['Staff and Department']))
                    <input type="text" id="search-bar" placeholder="Search staff or Department" class="px-3 py-1 rounded text-black text-sm w-64" />
                    <div id="search-results" class="absolute top-full mt-2 w-64 bg-white rounded-lg shadow-lg z-50 hidden"></div>
                    @endif
                </div>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchBar = document.getElementById('search-bar');
        const searchResults = document.getElementById('search-results');

        if (searchBar) {
            searchBar.addEventListener('keyup', function () {
                const query = searchBar.value;

                if (query.length < 2) {
                    searchResults.innerHTML = '';
                    searchResults.classList.add('hidden');
                    return;
                }

                fetch(`{{ route('search') }}?query=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        searchResults.innerHTML = '';
                        searchResults.classList.remove('hidden');

                        if (data.staff.length === 0 && data.departments.length === 0) {
                            const noResults = document.createElement('div');
                            noResults.classList.add('px-4', 'py-2', 'text-gray-700');
                            noResults.textContent = 'No results found';
                            searchResults.appendChild(noResults);
                            return;
                        }

                        if (data.staff.length > 0) {
                            const staffHeader = document.createElement('div');
                            staffHeader.classList.add('px-4', 'py-2', 'text-gray-500', 'text-sm', 'font-bold');
                            staffHeader.textContent = 'Staff';
                            searchResults.appendChild(staffHeader);

                            data.staff.forEach(staff => {
                                const staffLink = document.createElement('a');
                                staffLink.href = `{{ url('staff') }}/${staff.staffNumber}/edit`;
                                staffLink.classList.add('block', 'px-4', 'py-2', 'text-gray-700', 'hover:bg-gray-100');
                                staffLink.textContent = `${staff.firstName} ${staff.lastName}`;
                                searchResults.appendChild(staffLink);
                            });
                        }

                        if (data.departments.length > 0) {
                            const departmentsHeader = document.createElement('div');
                            departmentsHeader.classList.add('px-4', 'py-2', 'text-gray-500', 'text-sm', 'font-bold');
                            departmentsHeader.textContent = 'Departments';
                            searchResults.appendChild(departmentsHeader);

                            data.departments.forEach(department => {
                                const departmentLink = document.createElement('a');
                                departmentLink.href = `{{ url('departments') }}/${department.department}`;
                                departmentLink.classList.add('block', 'px-4', 'py-2', 'text-gray-700', 'hover:bg-gray-100');
                                departmentLink.textContent = department.department;
                                searchResults.appendChild(departmentLink);
                            });
                        }
                    });
            });

            document.addEventListener('click', function (event) {
                if (searchBar && !searchBar.contains(event.target)) {
                    searchResults.classList.add('hidden');
                }
            });
        }
    });
</script>
</nav>