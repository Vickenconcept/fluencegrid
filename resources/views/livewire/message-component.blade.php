<!-- component -->
<div class="flex h-full antialiased text-gray-800" x-data="{ openModal: false, openContract: false, openCancle: false }">
    <div class="flex flex-row h-full w-full overflow-x-hidden">
        @php
            $today = now();
        @endphp

        <div class="flex flex-col flex-auto h-full p-6 ">
            <div class="flex flex-col flex-auto flex-shrink-0 rounded-2xl bg-gray-100 h-full p-4 ">
                <div
                    class="p-3 bg-gray-100 border-b-2 border-slate-400 flex flex-wrap items-center space-x-0 md:space-y-0 md:space-x-2">
                    @if (!$today->between($startDate, $endDate))
                        @if (!$today->gt($endDate))
                            @auth
                                @if ($deal != 'deal')
                                    <div>
                                        <button @click="openModal = true" class="btn">
                                            create Contract
                                        </button>
                                    </div>
                                @else
                                    <div>
                                        <button @click="openModal = true" class="btn">
                                            Edit Contract
                                        </button>
                                    </div>
                                    <div>
                                        <button @click="openCancle = true" class="btn-danger">
                                            Cancle Contract
                                        </button>
                                    </div>
                                @endif

                            @endauth
                        @endif
                    @endif

                    <div>
                        <button @click="openContract = true" class="btn2">
                            View Contract
                        </button>
                    </div>

                    @if ($today->between($startDate, $endDate))
                        @auth
                            @if ($deal == 'deal')
                                <div>
                                    <button wire:click="sendCampaignReminder()" class="btn" wire:loading.attr="disabled"
                                        wire:target="sendCampaignReminder">
                                        <span wire:loading.remove wire:target="sendCampaignReminder">Send Reminder</span>
                                        <span wire:loading wire:target="sendCampaignReminder">Sending...</span>
                                    </button>

                                    <!-- Show loading text while processing -->
                                </div>
                            @endif
                        @endauth
                    @endif


                    <div>
                        @if ($today->between($startDate, $endDate))
                            <span <span
                                class="relative inline-flex size-3 rounded-full font-semibold bg-green-500">Ongoing..</span>
                        @elseif ($today->gt($endDate))
                            <span class="relative inline-flex size-3 rounded-full font-semibold bg-red-500">Ended</span>
                        @endif
                    </div>
                </div>

                @if (session()->has('success'))
                    {{-- <div class="bg-green-100 text-green-600 p-3 rounded-md">
                            {{ session('success') }}
                        </div> --}}
                    <div wire:ignore x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
                        class="bg-green-100 text-green-600 border-green-600 p-3 rounded-md transition-opacity duration-500">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div wire:ignore x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
                        class="bg-red-100 text-red-600 border-red-600 p-3 rounded-md transition-opacity duration-500">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="flex flex-col h-full overflow-x-auto mb-4 " id="chatContainer">
                    <div class="flex flex-col h-full">
                        <div class="grid grid-cols-12 gap-y-2" wire:poll.3s="fetchMessages">
                            @forelse ($messages as $message)
                                @if ($message->sender == 'owner')
                                    <div class="col-start-1 col-end-8 p-3 rounded-lg">
                                        <div class="flex flex-row items-center">
                                            <div
                                                class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 flex-shrink-0">
                                                A
                                            </div>
                                            <div class="relative ml-3 text-sm bg-white py-2 px-4 shadow rounded-xl">
                                                <div>{{ $message->message }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif ($message->sender == 'influencer')
                                    <div class="col-start-6 col-end-13 p-3 rounded-lg">
                                        <div class="flex items-center justify-start flex-row-reverse">
                                            <div
                                                class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 flex-shrink-0">
                                                A
                                            </div>
                                            <div
                                                class="relative mr-3 text-sm bg-indigo-100 py-2 px-4 shadow rounded-xl">
                                                <div>
                                                    {{ $message->message }}
                                                </div>
                                                {{-- <div class="absolute text-xs bottom-0 right-0 -mb-5 mr-2 text-gray-500">
                                                    Seen
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            @empty

                                <div class="col-span-12 mt-10">

                                    <div class="flex items-center p-4 mb-4 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50 max-w-6xl mx-auto"
                                        role="alert">
                                        <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                        </svg>
                                        <span class="sr-only">Info</span>
                                        <div>
                                            <span class="font-medium">Discuss Campaign Details:</span> Clearly define
                                            the campaign's goal (brand awareness, product promotion, engagement, etc.),
                                            share the expected deliverables (number of posts, stories, videos), and
                                            agree on the content type and format
                                        </div>
                                    </div>

                                    <div class="flex items-center p-4 mb-4 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50 max-w-5xl mx-auto"
                                        role="alert">
                                        <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                        </svg>
                                        <span class="sr-only">Info</span>
                                        <div>
                                            <span class="font-medium"> Set Expectations & Payment Terms:</span> Decide
                                            on the timeline and posting schedule, negotiate pricing and payment terms
                                            (upfront, milestone-based, or post-campaign)
                                            , and define performance
                                            expectations such as engagement rate, impressions, and conversions.
                                        </div>
                                    </div>

                                    <div class="flex items-center p-4 mb-4 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50 max-w-4xl mx-auto"
                                        role="alert">
                                        <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                        </svg>
                                        <span class="sr-only">Info</span>
                                        <div>
                                            <span class="font-medium">Confirm & Track Progress:</span> Finalize the
                                            agreement, track progress with updates, and ensure results meet
                                            expectations.
                                        </div>
                                    </div>
                                </div>
                            @endforelse


                        </div>
                    </div>
                </div>
                <div class="flex flex-row items-center h-16 rounded-xl bg-white w-full px-4">

                    <div class="flex-grow ml-4">
                        <div wire:loading.remove>
                            @if ($isTyping)
                                <p class="text-gray-500 italic">User is typing...</p>
                            @endif
                        </div>
                        <div class="relative w-full">
                            <input type="text" wire:model.live.debounce.500ms="message" id="messageField"
                                wire:keydown="typing"
                                class="flex w-full border rounded-xl focus:outline-none focus:border-indigo-300 pl-4 h-10" />
                            <button
                                class="absolute flex items-center justify-center h-full w-12 right-0 top-0 text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="ml-4">
                        <button wire:click="saveMessage" wire:loading.attr="disabled" wire:target="saveMessage"
                            :disabled="$wire.message === ''" {{-- @if ($message === '')
                              disabled
                            @endif --}}
                            class="flex items-center justify-center bg-indigo-500 hover:bg-indigo-600 rounded-xl text-white px-4 py-1 flex-shrink-0">
                            <span>Send</span>
                            <span class="ml-2">
                                <svg class="w-4 h-4 transform rotate-45 -mt-px" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>







        <div class="fixed items-center justify-center  flex top-0 left-0 mx-auto w-full h-full bg-gray-600 bg-opacity-30 z-50 transition duration-1000 ease-in-out"
            x-show="openModal" style="display: none;">
            <div @click.away="openModal = false"
                class="bg-white w-[90%] md:w-[40%]  shadow-inner  border rounded-2xl overflow-auto  py-6 px-8 transition-all relative duration-700">
                <div class=" h-full ">

                    <div class="font-bold text-xl">
                        @if ($deal != 'deal')
                            Create
                        @else
                            Edit
                        @endif Contract
                    </div>
                    <div class="my-10 space-y-3">


                        <div>
                            <label for="">Traget Url <span class="text-red-600">*</span></label>
                            <input class="form-control" id="" type="url" wire:model="url"
                                placeholder="Enter Url eg: https://example.com">
                        </div>
                        <div>
                            <label for="">Amount Paid <span class="text-red-600">*</span></label>
                            <input class="form-control" id="" type="number" wire:model="amount"
                                placeholder="Amount Paid">
                        </div>
                        <div>
                            <label for="">Description <span class="text-red-600">*</span></label>
                            <textarea class="form-control" id="" type="text" wire:model="description" placeholder="Description"></textarea>
                        </div>

                        <button wire:click="createContract()" @click="openModal = false" class="btn"
                            type="submit">
                            <span><i class='bx bxs-save'></i></span>
                            <span>Save</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- openContract --}}
        <div class="fixed items-center justify-center  flex top-0 left-0 mx-auto w-full h-full bg-gray-600 bg-opacity-30 z-50 transition duration-1000 ease-in-out"
            x-show="openContract" style="display: none;">
            <div @click.away="openContract = false"
                class="bg-white w-[90%] md:w-[70%] h-[70%] md:h-auto  shadow-inner  border rounded-2xl overflow-auto  py-6 px-8 transition-all relative duration-700">
                <div class=" h-full ">

                    <div class="flex justify-between">
                        <div class="font-bold text-xl">View Contract</div>
                        <div>
                            <button @click="openContract = false"><i class="bx bx-x text-xl"></i></button>
                        </div>
                    </div>
                    <div class="my-10 space-y-3">
                        @if ($deal == 'deal')
                            <section class="grid md:grid-cols-5  gap-4">

                                <div>
                                    @php
                                        $content = json_decode($influencer->content);
                                    @endphp
                                    <div
                                        class="flex flex-col items-center bg-indigo-100 border border-gray-200   w-full py-6 px-4 rounded-lg">
                                        <div class="h-20 w-20 rounded-full border overflow-hidden">
                                            <img src="{{ $content->avatar }}" alt="Avatar" class="h-full w-full" />
                                        </div>
                                        <div class="text-sm font-semibold mt-2">
                                            @isset($content->facebookName)
                                                {{ $content->facebookName }}
                                            @endisset
                                            @isset($content->tiktokName)
                                                {{ $content->tiktokName }}
                                            @endisset
                                            @isset($content->instagramName)
                                                {{ $content->instagramName }}
                                            @endisset
                                            @isset($content->youtubeName)
                                                {{ $content->youtubeName }}
                                            @endisset.
                                        </div>
                                        <div class="flex flex-row items-center mt-3">

                                            @php
                                                $socialUrl = '#';
                                                $socialName = '';

                                                if (!empty($content->facebookId)) {
                                                    $socialUrl = 'https://facebook.com/' . $content->facebookId;
                                                    $socialName = $content->facebookName ?? 'Facebook';
                                                } elseif (!empty($content->tiktokId)) {
                                                    $socialUrl = 'https://tiktok.com/@' . $content->tiktokId;
                                                    $socialName = $content->tiktokName ?? 'TikTok';
                                                } elseif (!empty($content->instagramId)) {
                                                    $socialUrl = 'https://instagram.com/' . $content->instagramId;
                                                    $socialName = $content->instagramName ?? 'Instagram';
                                                } elseif (!empty($content->youtubeId)) {
                                                    $socialUrl = 'https://youtube.com/' . $content->youtubeId;
                                                    $socialName = $content->youtubeName ?? 'YouTube';
                                                }
                                            @endphp

                                            @if ($socialUrl !== '#')
                                                <a href="{{ $socialUrl }}" target="_blank"
                                                    class="mt-4 bg-indigo-800 text-white px-4 py-2 rounded-lg hover:bg-blue-900 transition-colors duration-300">
                                                    {{ 'Follow' }}
                                                </a>
                                            @endif


                                        </div>

                                    </div>
                                </div>

                                <div class="md:col-span-3 p-3 bg-gray-100 border-x">
                                    @auth
                                        <div class=" flex space-x-3 border-b-2 py-2">
                                            <span class="font-semibold">Designated Url:</span>
                                            <a href="{{ $url }}" target="_blank"
                                                class="hover:text-blue-700 underline">{{ $url }} <span><i
                                                        class='bx bx-link-external'></i></span></a>
                                        </div>
                                    @endauth
                                    <div class=" flex space-x-3 border-b-2 py-2">
                                        <span class="font-semibold">Url for influencer:</span>
                                        <span class="hover:text-blue-700 underline">{{ $uniqueUrl }} <span></span>
                                    </div>
                                    <div class=" flex space-x-3  py-2">
                                        <span class="font-semibold">Amount Paid:</span>
                                        <span> ${{ $amount }}</span>
                                    </div>
                                    <div class=" flex space-x-3  py-2">
                                        <span class="font-semibold">Date Created:</span>
                                        <span> {{ $conversation->created_at }}</span>
                                    </div>
                                    <div class=" flex space-x-3 border-b-2 py-2">
                                        <span class="font-semibold">Description:</span>
                                        <span>{{ $description }} </span>
                                    </div>
                                </div>

                                <div class="px-3 ">
                                    <h6 class="text-md font-medium text-center pb-3">No of clicks</h6>
                                    <div wire:poll.2s="updateClicks"
                                        class="relative flex items-center justify-center">
                                        <svg width="100" height="100" viewBox="0 0 100 100"
                                            class="transform rotate-[-90deg]">
                                            <!-- Background Circle -->
                                            <circle cx="50" cy="50" r="46" stroke="#e5e7eb"
                                                stroke-width="8" fill="none" />
                                            <!-- Progress Circle -->
                                            <circle cx="50" cy="50" r="46" stroke="currentColor"
                                                stroke-width="8" fill="none" stroke-dasharray="289"
                                                stroke-dashoffset="{{ 289 - (289 * $percentage) / 100 }}"
                                                stroke-linecap="round"
                                                class="text-red-500 transition-all duration-500" />
                                        </svg>
                                        <span
                                            class="absolute text-black font-bold text-sm">{{ round($percentage) }}%</span>
                                    </div>


                                    <div class="flex flex-col ">
                                        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                                            <div class="inline-block min-w-full py-2 sm:px-3 lg:px-4">
                                                <div class="overflow-hidden">
                                                    <table
                                                        class="min-w-full text-left text-sm font-light text-surface ">
                                                        <thead class="border-b border-neutral-200 font-medium ">
                                                            <tr>
                                                                <th scope="col" class="px-2 py-4">#</th>
                                                                <th scope="col" class="px-2 py-4">Device</th>
                                                                <th scope="col" class="px-2 py-4">Count</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="border-b border-neutral-200">
                                                                <td class="whitespace-nowrap px-2 py-4 font-medium">#
                                                                </td>
                                                                <td class="whitespace-nowrap px-2 py-4">Users</td>
                                                                <td class="whitespace-nowrap px-2 py-4">
                                                                    {{ $totalIps }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </section>
                        @else
                            <div class="bg-orange-100 text-orange-500 py-10 text-center rounded " colspan="4">No
                                Active
                                Contract</div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        {{-- openCancle --}}
        <div class="fixed items-center justify-center  flex top-0 left-0 mx-auto w-full h-full bg-gray-600 bg-opacity-30 z-50 transition duration-1000 ease-in-out"
            x-show="openCancle" style="display: none;">
            <div @click.away="openCancle = false"
                class="bg-white w-[90%] md:w-[40%]  shadow-inner  border rounded-2xl overflow-auto  py-6 px-8 transition-all relative duration-700">
                <div class=" h-full ">


                    <div class="my-10 space-y-3">

                        <h5 class="text-center text-2xl font-semibold pb-1">Contract with influencer will be cancled!
                        </h5>
                        <p class="text-center text-md font-medium pb-3">Are you Sure?</p>

                        <div class="flex justify-center space-x-2">
                            <div>
                                <button @click="openCancle = false" wire:click="cancleContract()" class="btn-danger">
                                    Yes, Continue
                                </button>
                            </div>
                            <div>
                                <button @click="openCancle = false" class="btn2">
                                    No, Cancle
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Livewire.on('scrollUp', () => {
                    document.getElementById('messageField').value = '';

                    let chatContainer = document.getElementById(
                        'chatContainer');
                    if (chatContainer) {
                        setTimeout(() => {
                            chatContainer.scrollTop = chatContainer.scrollHeight;
                        }, 1000);

                    }
                });

            });

            document.addEventListener('DOMContentLoaded', function() {
                Livewire.on('email-sent', (data) => {
                    // console.log(data.status);
                    if (data.status == 'success') {
                        Toastify({
                            text: `${data.msg}`,
                            duration: 3000,
                            backgroundColor: "linear-gradient(to right, #56ab2f, #a8e063)"
                        }).showToast();

                    }
                    if (data.status == 'error') {
                        Toastify({
                            text: `${data.msg}`,
                            duration: 3000,
                            backgroundColor: "linear-gradient(to right, #FF5F6D, #FFC371)"
                        }).showToast();
                    }
                });
            });


            // document.addEventListener("DOMContentLoaded", function() {
            //     let typingTimer;
            //     let isTypingVisible = false; // Tracks if the div is already visible

            //     window.Echo.channel('chat.' + @js($conversation->id))
            //         .listen('.user.typing', (event) => {
            //             if (!isTypingVisible) {
            //                 isTypingVisible = true;
            //                 Livewire.dispatch('userTyping');
            //             }

            //             clearTimeout(typingTimer);

            //             typingTimer = setTimeout(() => {
            //                 isTypingVisible = false;
            //                 Livewire.dispatch('resetTyping');
            //             }, 3000);
            //         });
            // });
        </script>
    </div>
</div>
