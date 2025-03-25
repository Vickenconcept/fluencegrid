@section('title')
    {{ 'Campaign' }}
@endsection
<div class="  space-y-8 overflow-y-auto h-screen pb-28" x-data="{ campaign: null, createCampaign: false, editCampaign: false }">
    <div class="py-5 border-b px-3 flex flex-col md:flex-row justify-between items-center space-y-2 md:space-y-0 ">
        <div class="w-full md:w-auto">
            <select id="countries1" wire:model.live="sortOrder" class="form-control ">
                <option value="latest">Latest</option>
                <option value="oldest">Oldest</option>
            </select>
        </div>

        <div class="flex flex-col md:items-center md:flex-row md:px-3   md:space-y-0 md:space-x-2  w-full ">

            <div class="relative w-full">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 " aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" id="search" wire:model.live="search"
                    class="block w-full p-3 ps-10 text-sm text-gray-900 border-0 md:border border-gray-300 rounded-lg md:bg-gray-50 focus:ring-[#0F1523] focus:border-[#0F1523]  "
                    placeholder="Search">
            </div>

            <div>

                <button type="button" @click="createCampaign = true" class="btn w-full whitespace-nowrap"><i
                        class="bx bx-plus mr-1"></i>Create
                    Campaign</button>
            </div>
        </div>
    </div>


    {{-- createCampaign modal --}}
    <div class="fixed items-center justify-center  flex -top-10 left-0 mx-auto w-full h-full bg-gray-600 bg-opacity-30 z-50 transition duration-1000 ease-in-out"
        x-show="createCampaign" x-cloak>
        <div
            class="bg-white w-[90%] md:w-[50%]  shadow-inner  border rounded-2xl overflow-auto  py-6 px-8 transition-all relative duration-700">
            <div class=" h-full ">

                <div class="flex justify-between items-center">
                    <h5 class="font-bold text-xl">Create Campaign</h5>
                    <button @click="createCampaign = false"><i class="bx bx-x text-xl font-bold"></i></button>
                </div>
                <form action="{{ route('campaigns.store') }}" method="POST" class="my-10 ">
                    @csrf

                    <div class="mb-3">
                        <lable class="text-lg font-semibold">Title <span class="text-red-600">*</span></lable> <br>
                        <input class="form-control" id="title" type="text" name="title"
                            placeholder="My Campaign">
                    </div>
                    <div class="mb-3">
                        <lable class="text-lg font-semibold">Description <span class="text-red-600">*</span></lable>
                        <br>
                        <textarea class="form-control " id="description" name="description"> </textarea>
                    </div>

                    <div class="mb-3">
                        <lable class="text-lg font-semibold">Budget <span class="text-red-600">*</span></lable> <br>
                        <input class="form-control" id="budget" type="number" name="budget"
                            placeholder="My Budget eg. $200">
                    </div>
                    <div class="mb-3  grid md:grid-cols-3 gap-4">

                        <div class=" ">
                            <lable class="text-lg font-semibold">Start Date</lable> <br>
                            <input id="startDate" name="start_date" class="form-control !block !w-full" type="text"
                                placeholder="Select a date">
                        </div>
                        <div class="">
                            <lable class="text-lg font-semibold">End Date</lable> <br>
                            <input id="endDate" name="end_date" class="form-control !block !w-full" type="text"
                                placeholder="Select a date">
                        </div>
                        <div class="">
                            <lable class="text-lg font-semibold">Invitation End Date</lable> <br>
                            <input id="inviteEndDate" name="invite_end_date" class="form-control !block !w-full"
                                type="text" placeholder="Select a date">
                        </div>

                    </div>

                    <button class="btn" type="submit">Create</button>
                </form>

            </div>
        </div>
    </div>

    {{-- edit campaign --}}
    <div class="fixed items-center justify-center  flex -top-10 left-0 mx-auto w-full h-full bg-gray-600 bg-opacity-30 z-50 transition duration-1000 ease-in-out"
        x-show="editCampaign" style="display: none;">
        <div @click.away="editCampaign = false"
            class="bg-white w-[90%] md:w-[50%]  shadow-inner  border rounded-2xl overflow-auto  py-6 px-8 transition-all relative duration-700">
            <div class=" h-full ">

                <div class="font-bold text-xl">Edit Campaign Name</div>
                <form action="{{ route('changeCampaignName') }}" method="post" class="my-10 space-y-3">
                    @csrf

                    <div>
                        <input class="form-control" id="" type="text" name="title"
                            x-model="campaign.title" placeholder="campaign title">
                        <input class="form-control " id="" type="hidden" name="id"
                            x-model="campaign.id" placeholder="">
                    </div>

                    <button
                        class="btn"
                        type="submit">
                        <span>Edit Campaign</span>

                    </button>
            </div>
        </div>
    </div>

    <section class="px-3 ">
        <!-- List Group -->
        <ul class="mt-3 flex flex-col space-y-0.5 overflow-y-auto pb-10">
            @forelse ($campaigns as $campaign)
                <li
                    class="inline-flex items-center hover:shadow  gap-x-2  px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg bg-white">
                    <div
                        class="flex flex-col md:flex-row text-center md:text-start items-center justify-between w-full ">
                        <a href="{{ route('campaigns.show', ['campaign' => $campaign->uuid]) }}"
                            class=" w-full md:w-3/4 py-3">
                            <p class="font-semibold capitalize">{{ $campaign->title }}</p>
                            <p class="truncate md:w-[60%] first-letter:capitalize">{{ $campaign->description }}</p>
                            <div class="flex justify-center md:justify-start space-x-3 mt-1">
                                <p>
                                    @if ($campaign->budget)
                                        <span
                                            class="rounded-full pt-0.5 pb-1 px-2 bg-gray-200 font-bold text-gray-600">${{ $campaign->budget }}</span>
                                    @endif
                                </p>
                                <div class="flex space-x-2 items-center">
                                    <p class="text-xs ">
                                        @if ($campaign->start_date)
                                            <span
                                                class="rounded-full pt-0.5 pb-1 px-2 bg-gray-100  text-gray-600">{{ \Carbon\Carbon::parse($campaign->start_date)->format('F j, Y') }}
                                            </span>
                                        @endif
                                    </p>

                                    <p class="text-xs ">
                                        @if ($campaign->end_date)
                                            <span class="font-bold">-</span>
                                            <span
                                                class="rounded-full pt-0.5 pb-1 px-2 bg-gray-100  text-gray-600">{{ \Carbon\Carbon::parse($campaign->end_date)->format('F j, Y') }}</span>
                                        @endif
                                    </p>
                                </div>

                            </div>
                        </a>
                        <div class="w-full md:w-1/4 flex justify-center items-center md:justify-end space-x-3 py-3 ">
                            <button @click="editCampaign = true , campaign =@js($campaign)"
                                type="button"
                                class="bg-green-100 px-4 py-1.5 rounded-lg text-md font-semibold flex items-center text-green-500 hover:bg-green-500 hover:text-green-100 transition-all duration-300">
                                <i class='bx bxs-edit text-2xl mr-1'></i>
                            </button>
                            <button data-item-id="{{ $campaign->id }}" type="button"
                                class="delete-btn bg-red-100 px-4 py-1.5 rounded-lg text-md font-semibold flex items-center text-red-500 hover:bg-red-500 hover:text-red-100 transition-all duration-300">
                                <i class='bx bxs-trash text-2xl mr-1'></i>
                            </button>
                            <a href="{{ route('campaigns.show', ['campaign' => $campaign->uuid]) }}">
                                <button type="button"
                                    class="bg-gray-100 px-4 py-1.5 rounded-lg text-md font-semibold flex items-center text-gray-500 hover:bg-gray-500 hover:text-gray-100 transition-all duration-300">
                                    <i class='bx bx-stats text-2xl '></i>
                                </button>
                            </a>

                        </div>
                    </div>
                </li>
            @empty
                <div class="bg-gray-100 text-gray-500 py-4 flex justify-center items-center rounded ">
                    <span>No Campaign Yet.</span>
                    <p><i class='bx bxs-folder-open text-4xl'></i></p>
                </div>
            @endforelse
            {{ $campaigns->links() }}



        </ul>
        <!-- End List Group -->
    </section>



    <script>
        flatpickr("#startDate", {
            // Configuration options for Flatpickr
            // You can customize the appearance and behavior here
        });
        flatpickr("#endDate", {});

        flatpickr("#inviteEndDate", {});
    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let deleteButtons = document.querySelectorAll('.delete-btn');

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
                                "{{ route('campaigns.destroy', ['campaign' => ':itemId']) }}";
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
                                        location
                                            .reload();
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
