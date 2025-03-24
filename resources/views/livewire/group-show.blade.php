@section('title')
    {{ $group->name }}
@endsection

<div x-data="{ openModal: false, isContentModal: false, influencer_id: null, influencer_content: null }" class="px-3 pb-32 overflow-y-auto h-screen">
    <div class="flex justify-between items-center pb-10">

        <h3 class="capitalize font-bold text-3xl ">{{ $group->name }}</h3>
        <div>
            <button type="button" data-item-id="{{ $group->id }}"
                class="group-delete-btn bg-gray-50 hover:bg-red-500 group  px-3 py-2 rounded-md text-sm flex items-center delay-100 transition-all duration-500 ease-in-out">
                <i
                    class="bx bx-trash font-medium text-red-500 group-hover:text-white mr-1 text-lg delay-100 transition-all duration-500 ease-in-out"></i>

            </button>
        </div>
    </div>

    {{-- overflow-auto lg:overflow-visible --}}

    <div class=" ">
        <table class="table text-gray-700 border-separate space-y-6 text-sm w-full">
            <thead class="bg-gray-50 text-gray-800">
                <tr>
                    <th class="p-3">Brand</th>
                    <th class="p-3 text-left">Platform</th>
                    <th class="p-3 text-left">Language</th>
                    <th class="p-3 text-left">Country</th>
                    <th class="p-3 text-left">Followers</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Tags</th>
                    <th class="p-3 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @if (count($influencers) > 0)
                    @foreach ($influencers as $influencer)
                        {{-- {{ $influencer->content }} --}}
                        @php
                            $content = json_decode($influencer->content);
                        @endphp
                        <tr class="bg-gray-50" wire:key="influencer-{{ $influencer->id }}">
                            <td class="p-3 w-60">
                                <div class="flex align-items-center">
                                    <img class="rounded-full h-12 w-12  object-cover cursor-pointer"
                                        @click="isContentModal = true, influencer_id= @js($influencer->id) , influencer_content = @js($content)"
                                        src="{{ $content->avatar }}"
                                        wire:click="getEveluatedData(@js($influencer->id))" />
                                    <div class="ml-3">
                                        <div class="">
                                            <span class=" object-cover cursor-pointer"
                                                @click="isContentModal = true, influencer_id= @js($influencer->id) , influencer_content = @js($content)"
                                                class="hover:underline">
                                                {{-- name --}}
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
                                                @endisset
                                            </span>
                                        </div>
                                        <div class="text-gray-800 mt-2">
                                            @if (json_decode($influencer->emails, true) != null)
                                                @if (count(json_decode($influencer->emails, true)) > 0)
                                                    <select
                                                        class="rounded-lg border-0 py-1 w-full text-sm font-semibold">
                                                        <option value="" selected disabled>Emails</option>
                                                        @foreach (json_decode($influencer->emails, true) ?? [] as $email)
                                                            <option value="{{ $email }}">{{ $email }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            @else
                                            @endif

                                        </div>
                                    </div>

                                </div>
                                <div class="flex py-3 space-x-1">
                                    @if ($influencer->platform != 'facebook')
                                        <button
                                            wire:click="getEmails('{{ $influencer->id }}', '{{ isset($content->youtubeId) ? $content->youtubeId : '' }}')"
                                            class="bg-white rounded-lg px-2 py-1 text-xs font-semibold  focus:ring-2 focus:ring-gray-400 ">
                                            <span wire:target="getEmails" wire:loading.class="opacity-50">Get
                                                email</span>
                                            {{-- <span wire:loading wire:target="getEmails">...</span> --}}
                                        </button>
                                    @endif
                                    <button
                                        wire:click="checkContactWithAI({{ json_encode($influencer->content, true) }}, '{{ $influencer->id }}')"
                                        class="bg-white rounded-lg px-2 py-1 text-xs font-semibold  focus:ring-2 focus:ring-gray-400 ">
                                        <span wire:target="checkContactWithAI" wire:loading.class="opacity-50">Check
                                            with AI</span>
                                    </button>
                                </div>
                            </td>
                            <td class="p-3 font-semibold capitalize">
                                @isset($influencer->platform)
                                    {{ $influencer->platform }}
                                @endisset
                            </td>
                            <td class="p-3">
                                @isset($content->lang)
                                    {{ $content->lang }}
                                @endisset
                            </td>
                            <td class="p-3">
                                @isset($content->country)
                                    {{ $content->country }}
                                @endisset
                            </td>
                            <td class="p-3 font-bold">
                                @isset($content->followers)
                                    {{ format_number($content->followers) }}
                                @endisset
                                @isset($content->subscribers)
                                    {{ format_number($content->subscribers) }}
                                @endisset
                            </td>
                            <td class="p-3">
                                <span class="bg-green-400 text-gray-50 rounded-md px-2">available</span>
                            </td>
                            <td class="p-3 ">
                                @isset($content->hashtags)
                                    <span>{{ flatten_array($content->hashtags, ' | ', 4) }} </span>
                                @endisset
                            </td>
                            <td class="p-3 flex items-center space-x-1">
                                <button @click="openModal = true"
                                    wire:click="setInfluencer('{{ $influencer->id }}', '{{ isset($influencer->emails) ? $influencer->emails : '' }}')">
                                    <i
                                        class='bx bx-mail-send font-medium text-green-500 hover:text-green-700 mr-1 text-lg delay-100 transition-all duration-500 ease-in-out'></i>
                                </button>
                                <button type="button" data-item-id="{{ $influencer->id }}" class="delete-btn">
                                    <i
                                        class="bx bx-trash font-medium text-red-500 hover:text-red-700 mr-1 text-lg delay-100 transition-all duration-500 ease-in-out"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="col" colspan="8">
                            <div
                                class="bg-gray-50 text-gray-500 py-8 flex flex-col justify-center items-center rounded ">
                                <span>No Data Yet.</span>
                                <p><i class='bx bxs-folder-open text-4xl'></i></p>
                            </div>
                        </td>
                    </tr>

                @endif

            </tbody>
        </table>
    </div>
    <div class="my-8">
        {{ $influencers->links() }}
    </div>



    {{-- openModal modal --}}
    <div class="fixed items-center justify-center  flex top-0 left-0 mx-auto w-full h-full bg-gray-600 bg-opacity-30 z-50 transition duration-1000 ease-in-out"
        x-show="openModal" x-cloak>
        <div @click.away="openModal = false"
            class="bg-white w-[90%] md:w-[60%]  shadow-inner  border rounded-2xl overflow-auto  py-6 px-8 transition-all relative duration-700">
            <div class=" h-full ">

                <div class="flex justify-between items-center">
                    <h5 class="font-bold text-xl">All Campaign</h5>
                    <button @click="openModal = false"><i class="bx bx-x text-xl font-bold"></i></button>
                </div>

                <ul class="my-4 space-y-3 h-[400px] overflow-y-auto">
                    @forelse ($campaigns as $campaign)
                        <li @click.away="openDrawer = false" class="border-2 rounded-lg" x-data="{ openDrawer: false, emailType: 'default' }">
                            <div for="{{ $campaign->id }}" @click="openDrawer = !openDrawer"
                                wire:click="setCampaign('{{ $campaign->uuid }}')"
                                class=" cursor-pointer flex items-center p-3 text-base font-bold text-gray-900 rounded-lg bg-gray-200 hover:bg-gray-100 campaign  justify-between">
                                <div class="flex items-center">
                                    <i class='bx bx-task text-xl'></i>
                                    <span
                                        class="flex-1 ms-3 whitespace-nowrap capitalize">{{ $campaign->title }}</span>
                                </div>
                                <span><i class='bx '
                                        :class="openDrawer ? 'bxs-chevron-up' : 'bxs-chevron-down'"></i></span>
                            </div>
                            <div class=" p-4" x-show="openDrawer" x-cloak>

                                <div>
                                    <div class="flex justify-center mb-5">
                                        <button type="button" class="whitespace-nowrap flex flex-wrap"
                                            x-show="emailType == 'default'" style="display: none">
                                            <span
                                                class="uppercase text-white border border-[#0F1523]  bg-[#0F1523] px-5 py-2 rounded-full text-sm block md:-mr-10 z-10"
                                                @click="emailType = 'default'">default</span>
                                            <span
                                                class="uppercase border border-gray-800 pl-5 md:pl-16 pr-5 py-2  rounded-full text-sm   bg-white flex items-center space-x-4"
                                                @click="emailType = 'custom'">
                                                <i class='bx bx-customize'></i>
                                                <span>Customized</span>
                                            </span>
                                        </button>
                                        <button type="button" class="whitespace-nowrap flex flex-wrap"
                                            x-show="emailType == 'custom'" style="display: none">
                                            <span
                                                class="uppercase  border  border-gray-800  bg-white  pr-5 md:pr-16 pl-5 py-2 rounded-full text-sm block "
                                                @click="emailType = 'default'">default</span>
                                            <span
                                                class="uppercase text-white border  border-[#0F1523] bg-[#0F1523] pl-16 px-5 py-2  rounded-full text-sm   md:-ml-10 z-10  flex items-center space-x-4"
                                                @click="emailType = 'custom'">
                                                <i class='bx bx-customize'></i>
                                                <span>Customized</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                <div class="mx-auto w-[80%]" x-show="emailType == 'default'" x-cloak>
                                    <p class="text-xl font-medium mb-3">Automate Email Template</p>
                                    <div class="px-3 py-5 bg-gray-100  ">
                                        <p class="text-xl font-semibold mb-3">Hi {{ $influencerName }},</p>
                                        <p>We’re thrilled to invite you to join an exclusive campaign
                                            that’s perfectly tailored to your unique influence and
                                            style. Here’s a quick overview of the campaign:</p>
                                        <ul class="py-5">
                                            <li>
                                                <strong style="margin-right: 6px;">&#10003;</strong>
                                                <strong>🌟 Campaign Name:</strong> {{ $campaignTitle }}
                                            </li>
                                            <li>
                                                <strong style="margin-right: 6px;">&#10003;</strong>
                                                <strong>💰 Compensation:</strong> {{ $compensation }}
                                            </li>
                                            <li>
                                                <strong style="margin-right: 6px;">&#10003;</strong>
                                                <strong>🗓️ Acceptance Deadline:</strong> {{ $acceptanceDeadline }}
                                            </li>
                                        </ul>

                                        <p class="mb-4">If you’re ready to showcase your talent and make an
                                            impact,
                                            simply click the link below to review and accept the
                                            campaign details:</p>

                                        <button class="text-white bg-blue-500 text-sm rounded-lg px-4 py-2">👉
                                            Accept
                                            the
                                            Campaign Now</button>

                                        <p class="my-4">We’re excited to work with you and can’t wait to see the
                                            amazing content you’ll create for this campaign. If you
                                            have any questions or need more details, don’t hesitate to reach out to
                                            us.
                                        </p>

                                        <p style="font-size: 16px; font-family: sans-serif;">Best regards,<br>
                                            <strong>{{ env('APP_NAME') }} Team</strong>
                                        </p>
                                    </div>

                                    <div class="flex justify-between space-x-3 items-center bg-white p-3  ">
                                        <div class="">
                                            @if (json_decode($emails, true) != null)
                                                @if (count(json_decode($emails, true)) > 0)
                                                    <select
                                                        class="rounded-lg border-2 border-gray-200 py-2.5  text-sm font-semibold bg-gray-50"
                                                        wire:model="selectedEmail">
                                                        <option value="null" selected>Select email</option>
                                                        @foreach (json_decode($emails, true) ?? [] as $email)
                                                            <option value="{{ $email }}">
                                                                {{ $email }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            @else
                                                <p>No email found</p>
                                            @endif
                                        </div>
                                        <button wire:click="sendCampaignInvite()" wire:loading.class="opacity-70"
                                            wire:loading.target="sendCampaignInvite" wire:loading.attr="disabled"
                                            @if (json_decode($emails, true) == null) disabled @endif
                                            class="@if (json_decode($emails, true) == null) bg-opacity-45 @else @endif bg-[#0F1523]  rounded-lg text-white px-5 py-2 flex items-center space-x-2">
                                            <i class='bx bx-mail-send'></i>
                                            <span wire:loading.target="sendCampaignInvite" wire:loading.remove>Send
                                                mail</span>
                                            <span wire:loading.target="sendCampaignInvite" wire:loading>Send...</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="mx-auto w-[90%]" x-show="emailType == 'custom'" x-cloak>
                                    <p class="text-xl font-medium mb-3">Customize Your Email Template</p>
                                    <div wire:ignore class=" bg-gray-100  ">
                                        <x-editor :id="$campaign->id" :customEmailBody="$customEmailBody" />
                                    </div>
                                    <div class="flex justify-between space-x-3 items-center bg-white p-3 ">
                                        <div class="">
                                            @if (json_decode($emails, true) != null)
                                                @if (count(json_decode($emails, true)) > 0)
                                                    <select
                                                        class="rounded-lg border-2 border-gray-200   text-sm font-semibold bg-gray-50"
                                                        wire:model="selectedEmail">
                                                        <option value="null" selected>Select email</option>
                                                        @foreach (json_decode($emails, true) ?? [] as $email)
                                                            <option value="{{ $email }}">
                                                                {{ $email }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            @else
                                                <p>No email found</p>
                                            @endif
                                        </div>

                                        <button wire:click="sendCustomCampaignInvite()"
                                            @if (json_decode($emails, true) == null) disabled @endif
                                            wire:loading.class="opacity-70"
                                            wire:loading.target="sendCustomCampaignInvite"
                                            wire:loading.attr="disabled"
                                            class="@if (json_decode($emails, true) == null) bg-opacity-45 @else @endif bg-[#0F1523]  rounded-lg text-white px-5 py-2 flex items-center space-x-2">
                                            <i class='bx bx-mail-send'></i>
                                            <span wire:loading.target="sendCustomCampaignInvite"
                                                wire:loading.remove>Send mail</span>
                                            <span wire:loading.target="sendCustomCampaignInvite"
                                                wire:loading>Send...</span>
                                        </button>
                                    </div>
                                </div>



                            </div>
                        </li>
                    @empty
                        <div class="h-full flex justify-center items-center">

                            <div class="text-center p-5 text-gray-500">
                                <p class="text-md font-semibold">NO DATA FOUND</p>
                                <p><i class='bx bxs-folder-open text-6xl'></i></p>
                            </div>
                        </div>
                    @endforelse

                </ul>

            </div>
        </div>
    </div>

    {{-- isContent modal --}}
    <div class="fixed items-center justify-center  flex top-0 left-0 mx-auto w-full h-full bg-gray-600 bg-opacity-30 z-50 transition duration-1000 ease-in-out"
        x-show="isContentModal" x-cloak>
        <div @click.away="isContentModal = false"
            class="bg-white shadow-2xl max-w-4xl w-[90%] md:w-[60%]  h-[70dvh]   border rounded-2xl overflow-auto  py-6 px-8 transition-all relative duration-700">

            <div class="flex justify-between items-center">
                {{-- <span class="font-bold text-xl block"
                        x-text="influencer_content?.facebookName??influencer_content?.instagramName??influencer_content?.youtubeName??influencer_content?.tiktokName"></span> --}}
                <span></span>
                <button @click="isContentModal = false"><i class="bx bx-x text-xl font-bold"></i></button>
            </div>
            <div class="w-full p-8 transition-all duration-300 animate-fade-in">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/3 text-center mb-8 md:mb-0">
                        <img :src="influencer_content?.avatar" alt="Profile Picture"
                            class="rounded-full w-48 h-48 mx-auto mb-4 border-4 border-indigo-800  transition-transform duration-300 hover:scale-105">
                        {{-- <img src="https://i.pravatar.cc/300" alt="Profile Picture"
                                    class="rounded-full w-48 h-48 mx-auto mb-4 border-4 border-indigo-800  transition-transform duration-300 hover:scale-105"> --}}
                        <h1 class="text-2xl font-bold text-indigo-800  mb-2 overflow-x-hidden"
                            x-text="influencer_content?.facebookName??influencer_content?.instagramName??influencer_content?.youtubeName??influencer_content?.tiktokName">
                            John Doe</h1>
                        <ul class="py-4 mt-2 text-gray-700 flex items-center justify-around">
                            <li class="flex flex-col items-center justify-around">
                                <svg class="w-4 fill-current text-blue-900" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                </svg>
                                <div>2k</div>
                            </li>
                            <li class="flex flex-col items-center justify-between">
                                <svg class="w-4 fill-current text-blue-900" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M7 8a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0 1c2.15 0 4.2.4 6.1 1.09L12 16h-1.25L10 20H4l-.75-4H2L.9 10.09A17.93 17.93 0 0 1 7 9zm8.31.17c1.32.18 2.59.48 3.8.92L18 16h-1.25L16 20h-3.96l.37-2h1.25l1.65-8.83zM13 0a4 4 0 1 1-1.33 7.76 5.96 5.96 0 0 0 0-7.52C12.1.1 12.53 0 13 0z" />
                                </svg>
                                <div x-text="influencer_content?.followers??influencer_content?.subscribers">10k
                                </div>
                            </li>
                            <li class="flex flex-col items-center justify-around">
                                <svg class="w-4 fill-current text-blue-900" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M9 12H1v6a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-6h-8v2H9v-2zm0-1H0V5c0-1.1.9-2 2-2h4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v1h4a2 2 0 0 1 2 2v6h-9V9H9v2zm3-8V2H8v1h4z" />
                                </svg>
                                <div>15</div>
                            </li>
                        </ul>
                        <a :href="influencer_content?.facebookId ?
                            'https://facebook.com/' + influencer_content?.facebookId :
                            influencer_content?.instagramId ?
                            'https://instagram.com/' + influencer_content?.instagramId :
                            influencer_content?.youtubeId ?
                            'https://youtube.com/' + influencer_content?.youtubeId :
                            influencer_content?.tiktokId ?
                            'https://tiktok.com/@' + influencer_content?.tiktokId :
                            '#'"
                            target="_blank"
                            class="mt-4 bg-indigo-800 text-white px-4 py-2 rounded-lg hover:bg-blue-900 transition-colors duration-300">Visit
                            Social</a>
                    </div>
                    <div class="md:w-2/3 md:pl-8">
                        <h2 x-show="influencer_content?.description != '' && influencer_content?.description != null"
                            class="text-xl font-semibold text-indigo-800  mb-4">Description
                        </h2>
                        <p class="text-gray-700 h-28 overflow-y-auto mb-6 m"
                            x-show="influencer_content?.description != '' && influencer_content?.description != null"
                            x-text="influencer_content?.description">
                            Passionate software developer with 5 years of experience in web technologies.
                            I love creating user-friendly applications and solving complex problems.
                        </p>
                        <h2 x-show="influencer_content?.hashtags != '' && influencer_content?.hashtags != null"
                            class="text-xl font-semibold text-indigo-800  mb-4">HashTags
                        </h2>
                        <p class="text-gray-700  h-28 overflow-y-auto mb-6 m"
                            x-show="influencer_content?.hashtags != '' && influencer_content?.hashtags != null"
                            x-text="influencer_content?.hashtags.join(' | ')">
                        </p>
                        {{-- {{ flatten_array($content->hashtags, ' | ', 4) }} --}}
                        <h2 class="text-xl font-semibold text-indigo-800  mb-4">More</h2>
                        <div class="flex flex-wrap gap-2 mb-6">
                            <span x-text="influencer_content?.country"
                                class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm">Italy</span>
                            <span x-text="influencer_content?.lang"
                                class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm">English</span>
                        </div>
                        <h2 class="text-xl font-semibold text-indigo-800  mb-4">Contact
                            Information</h2>
                        <ul class="space-y-2 text-gray-700 ">
                            {{-- <li class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-800 "
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                    john.doe@example.com
                                </li>
                                <li class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-800 "
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                    </svg>
                                    +1 (555) 123-4567
                                </li> --}}
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-800 "
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span x-text="influencer_content?.country"></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg border">
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            User Profile
                        </h3>
                        <div>
                            <button wire:click="evaluateInfluncerWithAI(influencer_id,influencer_content)">
                                Evaluate with AI
                            </button>
                        </div>
                    </div>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        This is some information about the user.
                    </p>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-0 w-full" wire:loading
                    wire:target="evaluateInfluncerWithAI">
                    <div class='flex space-x-2 justify-center items-center bg-white h-32'>
                        <span class='sr-only'>Loading...</span>
                        <div class='h-8 w-8 bg-gray-900 rounded-full animate-bounce [animation-delay:-0.3s]'></div>
                        <div class='h-8 w-8 bg-gray-700 rounded-full animate-bounce [animation-delay:-0.15s]'>
                        </div>
                        <div class='h-8 w-8 bg-gray-600 rounded-full animate-bounce'></div>
                    </div>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-200">
                        @isset($evaluation['evaluation'])
                            @forelse ($evaluation['evaluation'] as $key => $value)
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 capitalize">
                                        {{ $key }}
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        @if (is_array($value))
                                            @foreach ($value as $subKey => $subValue)
                                                <li>{{ $subKey }}: {{ $subValue }}</li>
                                            @endforeach
                                        @else
                                            {{ $value }}
                                        @endif
                                    </dd>

                                </div>
                            @empty
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <div>
                                        Empty
                                    </div>

                                </div>
                            @endforelse
                        @endisset
                    </dl>
                </div>

                <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                    <div class="px-4 py-5 sm:px-6">
                        <h2 class="text-xl my-4 font-bold">Posts</h2>
                    </div>
                    <!-- component -->
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        @isset($evaluation['video_or_post'])
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 ">
                                    <tr>
                                        <th scope="col" class="px-16 py-3">
                                            <span class="sr-only">Image</span>
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Likes
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Views
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($evaluation['video_or_post'] as $key => $post)
                                        <tr
                                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="p-4">
                                                @isset($post['url'])
                                                    <a href="{{ $post['url'] }}" target="_blank">
                                                        <img src="https://placehold.co/600x400/gray/white?text=🎞️\nvideo"
                                                            alt="TikTok Video Snapshot" class="size-16 border rounded-sm">
                                                    </a>
                                                    {{-- <img src="$post['url']" class="w-16 md:w-32 max-w-full max-h-full"
                                                            alt="Apple Watch"> --}}
                                                    {{-- {{ $post['url'] }} --}}
                                                @endisset
                                            </td>
                                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                                @isset($post['likes'])
                                                    {{ $post['likes'] }}
                                                @endisset
                                            </td>
                                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                                @isset($post['views'])
                                                    {{ $post['views'] }}
                                                @endisset
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>



    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>

    <script>
        // Add hover effect to skill tags
        const skillTags = document.querySelectorAll('.bg-indigo-100');
        skillTags.forEach(tag => {
            tag.addEventListener('mouseover', () => {
                tag.classList.remove('bg-indigo-100', 'text-indigo-800');
                tag.classList.add('bg-blue-900', 'text-white');
            });
            tag.addEventListener('mouseout', () => {
                tag.classList.remove('bg-blue-900', 'text-white');
                tag.classList.add('bg-indigo-100', 'text-indigo-800');
            });
        });
    </script>
    <script>
        // document.addEventListener('DOMContentLoaded', function() {
        //     Livewire.on('refreshPage', () => {
        //         location.reload();
        //     });
        // });
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('email-sent', (data) => {

                console.log(data.status);
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


        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('addToEditor', function(data) {
                const content = data.content;
                const campaignId = data.id;
                var summernote = $('#summernote-' + campaignId);
                summernote.summernote('code', content);
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('click', function(event) {
                if (event.target.closest(
                    '.delete-btn')) { // Check if the clicked element is a delete button
                    let button = event.target.closest('.delete-btn');
                    let itemId = button.getAttribute('data-item-id');

                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var deleteRoute =
                                "{{ route('influencer.delete', ['influencer' => ':itemId']) }}";
                            deleteRoute = deleteRoute.replace(':itemId', itemId);

                            fetch(deleteRoute, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                })
                                .then(response => {
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: "Your item has been deleted.",
                                        icon: "success",
                                        confirmButtonColor: "#56ab2f"
                                    }).then(() => {
                                        location.reload();
                                    });
                                })
                                .catch(error => {
                                    Swal.fire({
                                        title: "Error",
                                        text: "Failed to delete the item",
                                        icon: "error",
                                        confirmButtonColor: "#d33"
                                    });
                                });
                        }
                    });
                }
            });
        });
   
        // document.addEventListener('DOMContentLoaded', function() {
        //     let deleteButtons = document.querySelectorAll('.delete-btn');

        //     deleteButtons.forEach(function(button) {
        //         button.addEventListener('click', function() {
        //             let itemId = button.getAttribute('data-item-id');

        //             Swal.fire({
        //                 title: "Are you sure?",
        //                 text: "You won't be able to revert this!",
        //                 icon: "warning",
        //                 showCancelButton: true,
        //                 confirmButtonColor: "#3085d6",
        //                 cancelButtonColor: "#d33",
        //                 confirmButtonText: "Yes, delete it!"
        //             }).then((result) => {
        //                 if (result.isConfirmed) {
        //                     var deleteRoute =
        //                         "{{ route('influencer.delete', ['influencer' => ':itemId']) }}";
        //                     deleteRoute = deleteRoute.replace(':itemId', itemId);

        //                     fetch(deleteRoute, {
        //                             method: 'POST',
        //                             headers: {
        //                                 'Content-Type': 'application/json',
        //                                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //                             }
        //                         })
        //                         .then(response => {
        //                             Swal.fire({
        //                                 title: "Deleted!",
        //                                 text: "Your item has been deleted.",
        //                                 icon: "success",
        //                                 confirmButtonColor: "#56ab2f"
        //                             }).then(() => {
        //                                 location
        //                                     .reload();
        //                             });
        //                         })
        //                         .catch(error => {
        //                             Swal.fire({
        //                                 title: "Error",
        //                                 text: "Failed to delete the item",
        //                                 icon: "error",
        //                                 confirmButtonColor: "#d33"
        //                             });
        //                         });
        //                 }
        //             });
        //         });
        //     });
        // });


        // delete group
        document.addEventListener('DOMContentLoaded', function() {
            let deleteButtons = document.querySelectorAll('.group-delete-btn');

            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    let itemId = button.getAttribute('data-item-id');

                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var deleteRoute =
                                "{{ route('groups.destroy', ['group' => ':itemId']) }}";
                            deleteRoute = deleteRoute.replace(':itemId', itemId);

                            fetch(deleteRoute, {
                                    method: 'DELETE',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                })
                                .then(response => {
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: "Your item has been deleted.",
                                        icon: "success",
                                        confirmButtonColor: "#56ab2f"
                                    }).then(() => {
                                        location.href =
                                            '{{ route('groups.index') }}';
                                    });
                                })
                                .catch(error => {
                                    Swal.fire({
                                        title: "Error",
                                        text: "Failed to delete the item",
                                        icon: "error",
                                        confirmButtonColor: "#d33"
                                    });
                                });
                        }
                    });
                });
            });
        });
    </script>

</div>
