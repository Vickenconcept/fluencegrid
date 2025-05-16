<div class="px-3 pb-32 overflow-y-auto h-screen" x-data="{ modalIsOpen: false }">

    <div class="flex items-center space-x-3 text-sm mt-4">
        <p class="bg-white px-5 p-2 rounded-md text-slate-500 font-medium ">Apply Filters</p>
        <p class="text-slate-500 capitalize">Use filters to further refine search</p>
    </div>

    <div class="my-6">
        <div class="rounded-3xl border border-gray-200 bg-white/60 py-6 px-4 shadow-sm">
            <div class="mt-8 grid grid-cols-1  gap-2 lg:gap-1  grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">
                <div class="flex flex-col">
                    <label for="name" class="text-slate-800 text-sm font-medium">Range</label>
                    <!-- Dropdown select for predefined ranges -->
                    <select wire:model="followersRange" wire:change="getFiltersByRange()" class="form-control"
                        :class="'!bg-white'">
                        <option value="0-10000">Less than 10k</option>
                        <option value="10000-50000">10k - 50k</option>
                        <option value="50000-500000">50k - 500k</option>
                        <option value="500000-1000000">500k - 1M</option>
                        <option value="1000000+">1M+</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label for="minRange" class="text-slate-800 text-sm font-medium">Min Followers:</label>

                    <input type="number" id="minRange" wire:model="minRange" placeholder="Min followers"
                        min="0" class="form-control" :class="'!bg-white'">
                </div>

                <div class="flex flex-col">
                    <label for="maxRange" class="text-slate-800 text-sm font-medium">Max Followers:</label>
                    <input type="number" id="maxRange" wire:model="maxRange" placeholder="Max followers"
                        min="0" class="form-control" :class="'!bg-white'">
                </div>

                <div class="flex flex-col">
                    <span class="text-sm font-medium text-transparent">Verified Accounts</span>
                    <div
                        class="bg-white border border-gray-300 rounded-lg  block w-full py-2.5 px-2  flex items-center space-x-1">
                        <label for="isVerified" class="relative inline-flex items-center  cursor-pointer">
                            <input type="checkbox" wire:model="isVerified" name="isVerified" id="isVerified"
                                class="sr-only peer" @if ($isVerified) checked @endif>
                            <div
                                class="w-11 z-0 h-6 bg-gray-400 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#79d2a6]  rounded-full peer  peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all 
                            {{ $isVerified ? 'peer-checked:bg-[#79d2a6]' : 'peer-checked:bg-[#79d2a6]' }}
                            ">
                            </div>
                        </label>
                        <span class="whitespace-nowrap text-sm">Verified Accounts</span>
                    </div>
                </div>


                <div class="flex flex-col">
                    <label for="hashtags" class="text-stone-600 text-sm font-medium">hashtags:
                    </label>
                    <input type="text" id="hashtags" class="form-control" :class="'!bg-white'"
                        wire:model="hashtags" placeholder="Enter hashtags">
                </div>


                <div class="flex flex-col">
                    <label for="category" class="text-stone-600 text-sm font-medium">Category:</label>
                    <select id="category" class="form-control" :class="'!bg-white'" wire:model="category">
                        <option value="">Select Category</option>
                        <!-- Categories that work with the API -->
                        <option value="Fashion">Fashion</option>
                        <option value="Food">Food</option>
                        <option value="Finance">Finance</option>
                        <option value="Music">Music</option>
                        <option value="Sports">Sports</option>
                        <option value="Education">Education</option>
                        <option value="Art">Art & Photography</option>
                        <option value="Crafts">Crafts</option>
                        <option value="Animals">Animals</option>
                        <option value="Science">Science</option>
                        <option value="Legal">Legal</option>
                        <option value="Home">Home</option>
                    </select>


                </div>

            </div>

            <div class="mt-10 grid w-full grid-cols-2 justify-center space-x-4 md:flex">
                <button wire:click="getInfluencer()"
                    class="active:scale-95 rounded-lg bg-gradient-to-r from-[#525FFD] via-[#CD89FF] to-[#B5FFAB] px-6 py-2 text-white outline-none focus:ring hover:opacity-90 flex items-center space-x-2">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>

                    </span>
                    <span>Search</span>
                </button>
                <button wire:click="resetData()" wire:loading.attr="disabled" wire:target="resetData"
                    class="active:scale-95 rounded-lg px-8 py-2 text-gray-600 outline-none focus:ring hover:opacity-90 border border-slate-600 relative">

                    <span wire:loading.remove wire:target="resetData">Reset</span>

                    <span wire:loading wire:target="resetData" class="">
                        Loading...
                    </span>
                </button>
            </div>
        </div>
    </div>





    <div class="grid sm:grid-cols-3 xl:grid-cols-4 gap-2">

        <!-- component -->
        @forelse ($details  as $detail)
            <div class="relative bg-white p-3 rounded-lg shadow-md max-w-md w-full group">
                <ul
                    class="absolute top-0 right-0 z-10  divide-y bg-gray-50 shadow-sm  hidden group-hover:flex transition-all duration-300 ease-in-out">
                    <li class="p-2 hover:bg-gray-200 hover:shadow-md " title="Open profile in a new tab ">
                        <a href="" target="_blank" class="hover:text-blue-500 hover:underline ">
                            <i class="bx bx-link-external text-md"></i>
                        </a>
                    </li>
                    <li class="p-2 hover:bg-gray-200 hover:shadow-md " title="Add to store">
                        <button type="button" @click="modalIsOpen = true "
                            wire:click="setInfluencer({{ json_encode($detail['data']['basicInstagram']) }})">
                            <i class='bx bx-plus'></i>
                        </button>



                    </li>
                </ul>
                <!-- Banner Profile -->
                <div class="relative">
                    {{-- <img src="/proxy-image?url={{ urlencode($detail['data']['basicInstagram']['avatar']) }}" alt="Banner Profile"
                        class="w-full rounded-t-lg h-32" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1735490246994-ea609f82f249?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8';"> 
                        <img src="/proxy-image?url={{ urlencode($detail['data']['basicInstagram']['avatar']) }}" alt="Profile Picture"
                        class="absolute bottom-0 left-2/4 transform -translate-x-1/2 translate-y-1/2 w-24 h-24 rounded-full border-4 border-white" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1735490246994-ea609f82f249?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8';"> --}}


                    <img src="{{ $detail['data']['basicInstagram']['avatar'] }}" alt="Banner Profile"
                        class="w-full rounded-t-lg h-32"
                        onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1735490246994-ea609f82f249?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8';">
                    <img src="{{ $detail['data']['basicInstagram']['avatar'] }}" alt="Profile Picture"
                        class="absolute bottom-0 left-2/4 transform -translate-x-1/2 translate-y-1/2 w-24 h-24 rounded-full border-4 border-white"
                        onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1735490246994-ea609f82f249?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyfHx8ZW58MHx8fHx8';">
                </div>
                <!-- User Info with Verified Button -->
                <div class="flex items-center mt-4">
                    <h2 class="text-xl font-bold text-gray-800 capitalize">
                        {{ $detail['data']['basicInstagram']['instagramName'] }}
                    </h2>
                    @if ($detail['data']['basicInstagram']['isVerified'])
                        <button class=" px-2 py-1 rounded-full">
                            <svg fill="#4d9aff" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="16px" height="16px"
                                viewBox="0 0 536.541 536.541" xml:space="preserve" stroke="#4d9aff">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <g>
                                        <g>
                                            <path
                                                d="M496.785,152.779c-3.305-25.085-16.549-51.934-38.826-74.205c-22.264-22.265-49.107-35.508-74.186-38.813 c-11.348-1.499-26.5-7.766-35.582-14.737C328.111,9.626,299.764,0,268.27,0s-59.841,9.626-79.921,25.024 c-9.082,6.965-24.235,13.238-35.582,14.737c-25.08,3.305-51.922,16.549-74.187,38.813c-22.277,22.271-35.521,49.119-38.825,74.205 c-1.493,11.347-7.766,26.494-14.731,35.57C9.621,208.422,0,236.776,0,268.27s9.621,59.847,25.024,79.921 c6.971,9.082,13.238,24.223,14.731,35.568c3.305,25.086,16.548,51.936,38.825,74.205c22.265,22.266,49.107,35.51,74.187,38.814 c11.347,1.498,26.5,7.771,35.582,14.736c20.073,15.398,48.421,25.025,79.921,25.025s59.841-9.627,79.921-25.025 c9.082-6.965,24.234-13.238,35.582-14.736c25.078-3.305,51.922-16.549,74.186-38.814c22.277-22.27,35.521-49.119,38.826-74.205 c1.492-11.346,7.766-26.492,14.73-35.568c15.404-20.074,25.025-48.422,25.025-79.921c0-31.494-9.621-59.848-25.025-79.921 C504.545,179.273,498.277,164.126,496.785,152.779z M439.256,180.43L246.477,373.209l-30.845,30.846 c-8.519,8.52-22.326,8.52-30.845,0l-30.845-30.846l-56.665-56.658c-8.519-8.52-8.519-22.326,0-30.846l30.845-30.844 c8.519-8.519,22.326-8.519,30.845,0l41.237,41.236L377.561,118.74c8.52-8.519,22.326-8.519,30.846,0l30.844,30.845 C447.775,158.104,447.775,171.917,439.256,180.43z">
                                            </path>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </button>
                    @endif
                </div>
                <!-- Bio -->
                <p class="text-gray-700 mt-2">
                    {{ flatten_array($detail['data']['basicInstagram']['hashtags'], ' | ', 3) }} </p>
                <!-- Social Links -->
                <div class="flex items-center mt-4 space-x-4">
                    <a href="https://instagram.com/{{ $detail['data']['basicInstagram']['instagramId'] }}"
                        target="_blank" class="text-blue-500 hover:underline">
                        Instagram
                        <i class="bx bx-link-external text-md"></i>
                    </a>
                </div>
                <!-- Separator Line -->
                <hr class="my-4 border-t border-gray-300">
                <!-- Stats -->
                <div class="flex justify-between text-gray-600 mx-2">
                    <div class="text-center">
                        <span
                            class="block font-bold text-lg">{{ format_number($detail['data']['basicInstagram']['followers']) }}</span>
                        <span class="text-xs">Followers</span>
                    </div>
                    <div class="text-center">
                        <span
                            class="block font-bold text-lg">{{ format_number($detail['data']['basicInstagram']['following']) }}</span>
                        <span class="text-xs">Following</span>
                    </div>
                    <div class="text-center">
                        <span
                            class="block font-bold text-lg">{{ format_number($detail['data']['basicInstagram']['posts']) }}</span>
                        <span class="text-xs">Posts</span>
                    </div>
                </div>
            </div>
        @empty

            <div class="flex items-center justify-center col-span-4 py-10">
                <div>
                    <div class="size-20 overflow-hidden">
                        <img src="{{ asset('images/loader-1.png') }}" alt=""
                            class="w-full h-full object-cover object-center">
                    </div>
                    <p class="text-center font-semibold text-md">No Data Found</p>
                </div>
            </div>
        @endforelse


    </div>
    @if (count($details) > 0 && count($details) < 70)
        <div class="py-20 mb-10 col-span-3 flex justify-center">
            <button wire:click="$dispatch('refreshPage')" class="active:scale-95 rounded-lg px-8 py-2 text-gray-600 outline-none focus:ring hover:opacity-90 border border-slate-600 relative bg-white">Load More</button>
        </div>
    @endif





    <div x-cloak x-show="modalIsOpen" x-transition.opacity.duration.200ms x-trap.inert.noscroll="modalIsOpen"
        @keydown.esc.window="modalIsOpen = false" @click.self="modalIsOpen = false"
        class="fixed inset-0 z-40 flex items-end justify-center bg-black/20 p-4 pb-8 backdrop-blur-md sm:items-center lg:p-8"
        role="dialog" aria-modal="true" aria-labelledby="defaultModalTitle">
        <!-- Modal Dialog -->
        <div x-show="modalIsOpen"
            x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            class="flex max-w-lg w-full flex-col gap-4 overflow-hidden rounded-md border border-neutral-300 bg-white text-neutral-600 ">
            <!-- Dialog Header -->
            <div class="flex items-center justify-between border-b border-neutral-300 bg-neutral-50/60 p-4 ">
                <h3 id="defaultModalTitle" class="font-semibold tracking-wide text-neutral-900 ">Add To Group
                </h3>
                <button @click="modalIsOpen = false" aria-label="close modal">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true"
                        stroke="currentColor" fill="none" stroke-width="1.4" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!-- Dialog Body -->
            <div class="px-4 py-8">
                <div class="relative bg-white rounded-lg shadow ">
                    <!-- Modal body -->
                    <div class="" x-data="{ tab: 'group_tab' }">
                        <ul class="my-4 space-y-3 h-[200px] overflow-y-auto" x-show="tab == 'group_tab'" x-cloak>
                            @foreach ($groups as $group)
                                <li>
                                    <label for="{{ $group->id }}"
                                        class=" cursor-pointer flex items-center p-3 text-base font-bold text-gray-900 rounded-lg bg-gray-50 hover:bg-gray-100 group hover:shadow ">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                                        </svg>
                                        <span
                                            class="flex-1 ms-3 whitespace-nowrap capitalize">{{ $group->name }}</span>
                                        <input id="{{ $group->id }}" wire:key="{{ $group->id }}"
                                            wire:model.live="selectedGroups" value="{{ $group->id }}"
                                            class="inline-flex items-center justify-center px-2 py-0.5 ms-3 text-xs font-medium text-gray-500 bg-gray-200 rounded "
                                            type="checkbox" />
                                    </label>
                                </li>
                            @endforeach

                        </ul>
                        <div>
                            <button x-show="tab == 'group_tab'" x-cloak type="button" @click="tab = 'add_group'"
                                class="inline-flex items-center text-xs font-bold text-gray-700 hover:underline"><i
                                    class="bx bx-plus"></i> Create New Group</button>
                            <button x-show="tab == 'add_group'" x-cloak type="button" @click="tab = 'group_tab'"
                                class="inline-flex items-center text-xs font-bold text-gray-700 hover:underline"><i
                                    class='bx bx-chevron-left'></i> Back</button>
                        </div>

                        <div x-show="tab == 'add_group'" x-cloak>
                            <div class="">
                                <form class="space-y-4" wire:submit="creatGroup()" method="post">
                                    <div>
                                        <label for="name"
                                            class="block mb-2 text-sm font-medium text-gray-900 ">Name *</label>
                                        <input type="text" name="name" id="name" wire:model.live="name"
                                            class="form-control" :class="'!bg-white'" placeholder="Enter Group name"
                                            required />
                                    </div>
                                    <div>
                                        <label for="description"
                                            class="block mb-2 text-sm font-medium text-gray-900 ">Description</label>
                                        <textarea name="description" id="description" wire:model.live="description" class="form-control"
                                            :class="'!bg-white'"></textarea>
                                    </div>
                                    <button type="submit" @click="tab = 'group_tab'" wire:loading.attr="disabled"
                                        wire:target="creatGroup"
                                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Create
                                        Group</button>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Dialog Footer -->
            <div
                class="flex flex-col-reverse justify-between gap-2 border-t border-neutral-300 bg-neutral-50/60 p-4  sm:flex-row sm:items-center md:justify-end">
                <button @click="modalIsOpen = false" type="button"
                    class="cursor-pointer whitespace-nowrap rounded-md px-4 py-2 text-center text-sm font-medium tracking-wide text-neutral-600 transition hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:opacity-100 active:outline-offset-0">Cancle</button>

                <button @click="modalIsOpen = false" type="button" @if (empty($selectedGroups)) disabled @endif
                    wire:click="addToGrop()"
                    class="cursor-pointer whitespace-nowrap rounded-md {{ empty($selectedGroups) ? 'bg-gray-400' : 'bg-black' }} bg-black px-4 py-2 text-center text-sm font-medium tracking-wide text-neutral-100 transition hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:opacity-100 active:outline-offset-0 ">Add
                    Now</button>
            </div>
        </div>
    </div>


    <div class="border-t border-gray-200 px-4 py-5 sm:p-0 w-full" wire:loading wire:target="getInfluencer">
        <div class="flex flex-col items-center justify-center bg-white fixed top-0 left-0 w-full h-screen z-50">
            <div class='flex space-x-2 justify-center items-center'>
                <span class='sr-only'>Loading...</span>
                <div class='h-8 w-8 bg-gray-900 rounded-full animate-bounce [animation-delay:-0.3s]'></div>
                <div class='h-8 w-8 bg-gray-700 rounded-full animate-bounce [animation-delay:-0.15s]'>
                </div>
                <div class='h-8 w-8 bg-gray-600 rounded-full animate-bounce'></div>
            </div>
            <p class="font-medium mt-3" x-data="{
                messages: [
                    'Initializing data fetch...',
                    'Retrieving influencer details...',
                    'Processing influencer information...',
                    'Fetching additional data...',
                    'Almost done loading the influencers...'
                ],
                currentIndex: 0,
                intervalId: null
            }" x-init="intervalId = setInterval(() => {
                currentIndex = (currentIndex + 1) % messages.length;
            }, 4000);"
                x-text="messages[currentIndex]">
            </p>
        </div>
    </div>


</div>
