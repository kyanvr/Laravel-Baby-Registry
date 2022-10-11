@if (!empty($cart))
    <nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/">
                        <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out position-relative">
                            <div class="ml-1">
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $cart->getContent()->count() }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#6320EE"
                                    class="bi bi-cart" viewBox="0 0 16 16">
                                    <path
                                        d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                            @foreach ($cart->getContent() as $item)
                            <x-dropdown-link>
                                {{ $item->name }} | &#8364;{{ $item->price }}
                            </x-dropdown-link>
                        @endforeach
                        <x-dropdown-link>
                            <strong>{{ __('Total') }} = &#8364;{{ $cart->getTotal() }}</strong>
                        </x-dropdown-link>
                        <x-dropdown-link>
                            <a href="{{ route('checkout.overview') }}" class="btn btn-primary text-center">{{ __('Checkout') }}</a>
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out position-relative">
                        <span class="position-absolute start-100 translate-middle badge rounded-pill bg-danger">{{ $cart->getContent()->count() }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#6320EE"
                        class="bi bi-cart" viewBox="0 0 16 16">
                        <path
                            d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="mt-3 space-y-1">
                    @foreach ($cart->getContent() as $item)
                            <x-dropdown-link>
                                {{ $item->name }} | &#8364;{{ $item->price }}
                            </x-dropdown-link>
                        @endforeach
                        <x-dropdown-link>
                            <strong>{{ __('Total') }} = &#8364;{{ $cart->getTotal() }}</strong>
                        </x-dropdown-link>
                        <x-dropdown-link>
                            <a href="{{ route('checkout.overview') }}" class="btn btn-primary text-center">{{ __('Checkout') }}</a>
                        </x-dropdown-link>
            </div>
        </div>
    </div>
</nav>

@endif