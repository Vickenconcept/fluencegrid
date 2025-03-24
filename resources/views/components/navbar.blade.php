<nav class="fixed top-0 z-40 w-full bg-slate-200 border-b border-slate-300 ">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                    type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 ">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                        </path>
                    </svg>
                </button>
                <a href="https://flowbite.com" class="flex ms-2 md:me-24 hidden md:flex">
                    <img src="{{ asset('images/logo.svg') }}" class="h-8 me-3" alt="FluenceGrid Logo" />
                </a>
                <div>
                    <h2 class="text-sm  md:text-2xl font-medium ml-5 capitalize">
                        @yield('title')
                    </h2>
                </div>
            </div>
            <div class="flex items-center space-x-5">
                @php
                    $notifications = auth()->user()->notifications;
                @endphp

                <button id="notification_bell" data-dropdown-toggle="dropdownNotification" 
                    class="relative inline-flex items-center text-sm font-medium text-center text-transparent hover:text-gray-300 focus:outline-none bg-white p-2 rounded-md"
                    type="button">
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" stroke="gray"
                        viewBox="0 0 14 20">
                        <path
                            d="M12.133 10.632v-1.8A5.406 5.406 0 0 0 7.979 3.57.946.946 0 0 0 8 3.464V1.1a1 1 0 0 0-2 0v2.364a.946.946 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C1.867 13.018 0 13.614 0 14.807 0 15.4 0 16 .538 16h12.924C14 16 14 15.4 14 14.807c0-1.193-1.867-1.789-1.867-4.175ZM3.823 17a3.453 3.453 0 0 0 6.354 0H3.823Z" />
                    </svg>

                    <div
                        class="absolute block w-5 h-5 bg-red-500 border-2 border-white text-white text-xs rounded-full -top-0.5 right-0  ">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </div>
                </button>

                <!-- Dropdown menu -->
                <div id="dropdownNotification"
                    class="z-20 hidden w-full max-w-sm  bg-white divide-y divide-gray-100 rounded-lg shadow-sm  overflow-hidden "
                    aria-labelledby="notification_bell">
                    <div class="block px-4 py-2 font-medium text-center text-gray-700 rounded-t-lg bg-gray-50 ">
                        Notifications
                    </div>
                    <div class="divide-y divide-gray-100 h-48 overflow-y-auto">

                        @foreach ($notifications as $notification)
                            @if (!$notification->read_at)
                                <a href="#" class="flex px-4 py-3 hover:bg-gray-100 ">
                                    <div class="shrink-0">
                                        <div
                                            class="absolute flex items-center justify-center w-5 h-5 ms-6 -mt-5 bg-blue-600 border border-white rounded-full ">
                                            <svg class="w-2 h-2 text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 18 18">
                                                <path
                                                    d="M1 18h16a1 1 0 0 0 1-1v-6h-4.439a.99.99 0 0 0-.908.6 3.978 3.978 0 0 1-7.306 0 .99.99 0 0 0-.908-.6H0v6a1 1 0 0 0 1 1Z" />
                                                <path
                                                    d="M4.439 9a2.99 2.99 0 0 1 2.742 1.8 1.977 1.977 0 0 0 3.638 0A2.99 2.99 0 0 1 13.561 9H17.8L15.977.783A1 1 0 0 0 15 0H3a1 1 0 0 0-.977.783L.2 9h4.239Z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="w-full ps-3">
                                        <div class="text-gray-500 text-sm mb-1.5 "><span
                                                class="text-gray-800 font-bold">New message:</span>
                                            {{ $notification->data['message'] }}</div>
                                        <div class="flex justify-between items-center">
                                            <div class="text-xs text-blue-600 ">
                                                {{ $notification->created_at->diffForHumans() }}</div>
                                            <form action="{{ route('notifications.markAsRead', $notification->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('PATCH') <!-- To use a PATCH request -->
                                                <button type="submit"
                                                    class="text-blue-600 underline hover:text-blue-800">Mark as
                                                    Read</button>
                                            </form>


                                        </div>
                                    </div>
                                </a>
                            @endif
                        @endforeach

                    </div>

                    <a href="#"
                        class="block py-2 text-sm font-medium text-center text-gray-900 rounded-b-lg bg-gray-50 hover:bg-gray-100 ">
                        <div class="inline-flex items-center ">
                            <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="hover:text-blue-700 hover:underline">
                                    Mark All as Read
                                </button>
                            </form>
                        </div>
                    </a>
                </div>

                <div class="flex items-center ms-3">
                    <div class="flex items-center space-x-2"  aria-expanded="false" data-dropdown-toggle="dropdown-user" id="profile_avatar">
                        <button type="button" id="profile_avatar"
                            class="flex text-sm bg-gray-800 rounded-md focus:ring-4 focus:ring-gray-300 ">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-10 h-10 rounded-md"
                                src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="user photo">
                        </button>
                        <div class="bg-white rounded-md px-3 py-1 w-48 truncate cursor-pointer hidden md:block" >
                            <p class="text-sm font-semibold capitalize"> {{ auth()->user()->name }}</p>
                            <p class="text-xs">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow "
                        id="dropdown-user">
                        <div class="px-4 py-3" role="none">
                            <p class="text-sm text-gray-900 " role="none">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-sm font-medium text-gray-900 truncate " role="none">
                                {{ auth()->user()->email }}
                            </p>
                        </div>
                        <ul class="py-1" role="none">
                            <li>
                                <a href="{{ route('home') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100  "
                                    role="menuitem">Dashboard</a>
                            </li>
                            <li>
                                <a href="{{ route('profile') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 ">
                                    <span class="text-sm ">Profile</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('auth.logout') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100  "
                                    role="menuitem">Sign out</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
