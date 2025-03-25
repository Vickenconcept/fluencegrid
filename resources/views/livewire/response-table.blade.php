<div class="px-3 pb-32 overflow-y-auto h-screen">

    <div class="py-5 border-b flex flex-col md:flex-row justify-between items-center ">
        <div>
            <h3 class=" font-medium text-2xl">Response</h3>
        </div>

        <div class="flex flex-col md:items-center md:flex-row px-3   md:space-y-0 md:space-x-2  w-1/3 ">
            <select wire:model.live="deal" class="form-control ">
                <option value="deal">Deal</option>
                <option value="pending">Pending</option>
                <option value="no-deal">No deal</option>
                <option value="all">All</option>
            </select>
            <select wire:model.live="status" class="form-control ">
                <option value="all">All</option>
                <option value="accepted">Accepted</option>
                <option value="declined">Declined</option>
            </select>

        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
            <thead class="text-xs text-gray-700 uppercase ">
                <tr>
                    <th scope="col" class="px-6 py-3 bg-gray-50 ">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3 bg-gray-100">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3 bg-gray-50 text-center">
                        Contract
                    </th>
                    <th scope="col" class="px-6 py-3 bg-gray-100 ">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 bg-gray-50">

                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($responses as $response)
                    @php
                        $influencer = \App\Models\Influencer::find($response->influencer_id);

                        $conversations = \App\Models\Conversation::where('influencer_id', $response->influencer_id)
                            ->where('campaign_id', $response->campaign_id)
                            ->get();

                        $campaign = \App\Models\Campaign::findorFail($response->campaign_id);

                        $today = now();
                        $startDate = \Carbon\Carbon::parse($campaign->start_date);
                        $endDate = \Carbon\Carbon::parse($campaign->end_date);

                        $content = json_decode($influencer->content);
                        $emails = $influencer->emails;
                        $emailsList = implode(', ', json_decode($emails, true));
                    @endphp
                    <tr class="border-b border-gray-200 " wire:key="message-{{ $response->id }}">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50  ">
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
                        </th>
                        <td class="px-6 py-4 bg-gray-100">
                            {{ $emailsList }}
                        </td>
                        <td class="px-6 py-4 bg-gray-50 text-center">

                            @if ($response->task_status == 'accepted')
                                @foreach ($conversations as $conversation)
                                    @if ($conversation->status == 'pending')
                                        <span class="relative flex justify-center">
                                            <span
                                                class="px-6 rounded-2xl text-sm capitalize py-1 border border-gray-700 bg-gray-200 text-gray-700">{{ $conversation->status }}</span>

                                            @if ($today->between($startDate, $endDate))
                                                <span
                                                    class="relative inline-flex size-3 rounded-full bg-green-500"></span>
                                            @elseif ($today->gt($endDate))
                                                <span
                                                    class="relative inline-flex size-3 rounded-full bg-red-500"></span>
                                            @endif
                                        </span>
                                    @elseif ($conversation->status == 'deal')
                                        <span class="relative flex justify-center">
                                            <span
                                                class="px-6 rounded-2xl text-sm capitalize py-1 border border-green-700 bg-green-100 text-gray-700">{{ $conversation->status }}</span>


                                            @if ($today->between($startDate, $endDate))
                                                <span
                                                    class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-50"></span>
                                                <span
                                                    class="relative inline-flex size-3 rounded-full bg-green-500"></span>
                                            @elseif ($today->gt($endDate))
                                                <span
                                                    class="relative inline-flex size-3 rounded-full bg-red-500"></span>
                                            @endif
                                        </span>
                                    @endif
                                @endforeach
                            @else
                                <span
                                    class="px-6 rounded-2xl text-sm capitalize py-1 border border-red-700 bg-red-100 text-gray-700">{{ 'no deal' }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 bg-gray-100 capitalize font-semibold">
                            {{ $response->task_status }}
                        </td>
                        <td class="px-6 py-4 bg-gray-50">
                            @if ($response->task_status == 'accepted')
                                @if ($conversation->status == 'deal')
                                    @if ($today->between($startDate, $endDate))
                                        <button type="button" disabled title="Cannot Delete">
                                            <i
                                                class="bx bx-trash font-medium shadow-md hover:shadow-lg rounded-full px-2 py-1 text-white bg-red-500/70 hover:bg-red-600/70 mr-1 text-lg"></i>
                                        </button>
                                    @elseif ($today->gt($endDate))
                                        <button type="button" class="delete-btn" data-item-id="{{ $response->id }}"
                                            title="Delete Conversation">
                                            <i
                                                class="bx bx-trash font-medium shadow-md hover:shadow-lg rounded-full px-2 py-1 text-white bg-red-500 hover:bg-red-600 mr-1 text-lg"></i>
                                        </button>
                                    @else
                                        <button type="button" disabled title="Cannot Delete">
                                            <i
                                                class="bx bx-trash font-medium shadow-md hover:shadow-lg rounded-full px-2 py-1 text-white bg-red-500/70 hover:bg-red-600/70 mr-1 text-lg"></i>
                                        </button>
                                    @endif
                                @else
                                    <button type="button" class="delete-btn" data-item-id="{{ $response->id }}"
                                        title="Delete Conversation">
                                        <i
                                            class="bx bx-trash font-medium shadow-md hover:shadow-lg rounded-full px-2 py-1 text-white bg-red-500 hover:bg-red-600 mr-1 text-lg"></i>
                                    </button>
                                @endif
                            @else
                                <button type="button" class="delete-btn" data-item-id="{{ $response->id }}"
                                    title="Delete Conversation">
                                    <i
                                        class="bx bx-trash font-medium shadow-md hover:shadow-lg rounded-full px-2 py-1 text-white bg-red-500 hover:bg-red-600 mr-1 text-lg"></i>
                                </button>
                            @endif

                            {{-- @if ($conversation->status != 'deal' && $response->task_status == 'declined')
                                <button type="button" class="delete-btn" data-item-id="{{ $response->id }}">
                                    <i class="bx bx-trash font-medium text-red-500 hover:text-red-700 mr-1 text-lg"></i>
                                </button>
                                delete
                            @endif --}}



                            @if ($response->task_status !== 'declined')
                                @foreach ($conversations as $conversation)
                                    <a href="{{ route('conversation.owner', ['uuid' => optional($conversation)->uuid]) }}"
                                        class="" title="message">
                                        <i
                                            class="bx bx-message font-medium text-white shadow-md hover:shadow-lg rounded-full px-2 py-1 bg-blue-500  mr-1 text-lg"></i>
                                    </a>
                                @endforeach
                            @endif

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="bg-orange-100 text-orange-500 py-10 text-center rounded " colspan="5">No
                            Data
                            Yet.</td>
                    </tr>
                @endforelse

            </tbody>
            {{ $responses->links() }}
        </table>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('click', function(event) {
            if (event.target.closest(
                    '.delete-btn')) {

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
                        Livewire.dispatch('delete-response', {
                            id: itemId
                        });
                        Swal.fire({
                            title: "Deleted!",
                            text: "Your item has been deleted.",
                            icon: "success",
                            confirmButtonColor: "#56ab2f"
                        }).then(() => {
                            location.reload();
                        });
                    }
                });

            }
        })
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
    //                     Livewire.dispatch('delete-response', {
    //                         id: itemId
    //                     });
    //                     Swal.fire({
    //                         title: "Deleted!",
    //                         text: "Your item has been deleted.",
    //                         icon: "success",
    //                         confirmButtonColor: "#56ab2f"
    //                     }).then(() => {
    //                         location.reload();
    //                     });
    //                 }
    //             });
    //         });
    //     });
    // });
</script>
