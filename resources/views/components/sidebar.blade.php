<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen  transition-transform -translate-x-full bg-slate-200  sm:translate-x-0  p-3 "
    aria-label="Sidebar">
    <div class="bg-white h-full rounded-xl px-3 pb-4 ">
        <div class="py-5 mb-2 border-b-2 border-slate-300">
            <a href="/home" class="">
                <img src="{{ asset('images/logo.svg') }}" class="h-10 me-3" alt="FluenceGrid Logo" />
            </a>
        </div>
        <div class="h-[85%] pb-4 overflow-y-auto ">
            <ul class="space-y-1 font-medium">
                <li>
                    <a href="{{ route('home') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-gray-100 group transition duration-500 ease-in-out {{ request()->routeIs('home') ? 'bg-gradient-to-r from-[#0F1523] from-70%  to-[#B5FFAB] font-medium text-white hover:bg-gradient-to-br from-[#0F1523] from-70%  to-[#B5FFAB]' : '' }}">
                        <i class='bx bx-home-smile text-xl mr-2 text-blue-600 bg-slate-200 px-1 py-0.5 rounded-md'></i>
                        <span class="">Dashboard</span>
                    </a>
                </li>

                @php
                    $platforms = \App\Models\Platform::all();
                @endphp


                @foreach ($platforms as $platform)
                    @php
                        $isActive =
                            request()->routeIs('platform.search') &&
                            request('platform') === strtolower($platform->name);
                    @endphp
                    <li>
                        <a href="{{ route('platform.search', ['platform' => strtolower($platform->name)]) }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-gray-100 group transition duration-500 ease-in-out {{ $isActive ? 'bg-gradient-to-r from-[#0F1523] from-70%  to-[#B5FFAB] font-medium text-white hover:bg-gradient-to-br from-[#0F1523] from-70%  to-[#B5FFAB]' : '' }}">
                            @if (strtolower($platform->name) == 'facebook')
                                <i class='bx bxl-facebook-circle text-xl mr-2 text-blue-600 bg-slate-200 px-1 py-0.5 rounded-md'></i>
                            @elseif (strtolower($platform->name) == 'instagram')
                                <i class='bx bxl-instagram text-xl mr-2 text-blue-600 bg-slate-200 px-1 py-0.5 rounded-md'></i>
                            @elseif (strtolower($platform->name) == 'youtube')
                                <i class='bx bxl-youtube text-xl mr-2 text-blue-600 bg-slate-200 px-1 py-0.5 rounded-md'></i>
                            @elseif (strtolower($platform->name) == 'tiktok')
                                <i class='bx bxl-tiktok text-xl mr-2 text-blue-600 bg-slate-200 px-1 py-0.5 rounded-md'></i>
                            @else
                                <i class='bx bxl-question-mark text-xl mr-2 text-blue-600 bg-slate-200 px-1 py-0.5 rounded-md'></i> <!-- Fallback icon -->
                            @endif
                            <span class="text-sm">{{ ucfirst($platform->name) }}</span>
                        </a>
                    </li>
                @endforeach

                <li>
                    <a href="{{ route('groups.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-gray-100 group transition duration-500 ease-in-out {{ request()->routeIs('groups.index') ? 'bg-gradient-to-r from-[#0F1523] from-70%  to-[#B5FFAB] font-medium text-white hover:bg-gradient-to-br from-[#0F1523] from-70%  to-[#B5FFAB]' : '' }}">
                        <i class='bx bx-folder text-xl mr-2 text-blue-600 bg-slate-200 px-1 py-0.5 rounded-md'></i>
                        <span class="text-sm">Groups</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('campaigns.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-gray-100 group transition duration-500 ease-in-out {{ request()->routeIs('campaigns.index') ? 'bg-gradient-to-r from-[#0F1523] from-70%  to-[#B5FFAB] font-medium text-white hover:bg-gradient-to-br from-[#0F1523] from-70%  to-[#B5FFAB]' : '' }}">
                        <i class='bx bxs-building text-xl mr-2 text-blue-600 bg-slate-200 px-1 py-0.5 rounded-md'></i>
                        <span class="text-sm">Campaign</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('campaigns.response') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-gray-100 group transition duration-500 ease-in-out {{ request()->routeIs('campaigns.response') ? 'bg-gradient-to-r from-[#0F1523] from-70%  to-[#B5FFAB] font-medium text-white hover:bg-gradient-to-br from-[#0F1523] from-70%  to-[#B5FFAB]' : '' }}">
                        <i class='bx bxs-chat text-xl mr-2 text-blue-600 bg-slate-200 px-1 py-0.5 rounded-md'></i>
                        <span class="text-sm">Responses</span>
                    </a>
                </li>
                <hr class="border border-slate-300">
                <li>
                    <a href="{{ route('reseller.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-gray-100 group transition duration-500 ease-in-out {{ request()->routeIs('reseller.index') ? 'bg-gradient-to-r from-[#0F1523] from-70%  to-[#B5FFAB] font-medium text-white hover:bg-gradient-to-br from-[#0F1523] from-70%  to-[#B5FFAB]' : '' }}">
                        <i class='bx bx-refresh text-xl mr-2 text-blue-600 bg-slate-200 px-1 py-0.5 rounded-md'></i>
                        <span class="text-sm">Reseller</span>
                    </a>
                </li>
                <li class="">
                    <a href="{{ route('auth.logout') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-gray-100 group transition duration-500 ease-in-out">
                        <i class='bx bx-exit text-xl mr-2 text-blue-600 bg-slate-200 px-1 py-0.5 rounded-md'></i>
                        <span class="text-sm capitalize">Log out</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
