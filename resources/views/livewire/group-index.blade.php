<div class="px-3 pb-32 overflow-y-auto h-screen" x-data="{ group: null, editGroup: false }">
    
    <div>
        <div class="py-5 border-b md:px-3 flex flex-col md:flex-row justify-between items-center mb-8 space-y-2 md:space-y-0 ">
            <div class="w-full md:w-auto">
                <select wire:model.live="sortOrder"class="form-control ">
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
                    <button data-modal-target="authentication-modal" data-modal-toggle="authentication-modal"
                        class="btn !whitespace-nowrap" type="button">
                        <i class="bx bx-plus text-md"></i>Create Group
                    </button>
                </div>
            </div>
        </div>
        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:justify-between">

            <!-- Main modal -->
            <div id="authentication-modal" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow ">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t ">
                            <h3 class="text-xl font-semibold text-gray-900 ">
                                Create Group
                            </h3>
                            <button type="button"
                                class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center "
                                data-modal-hide="authentication-modal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-4 md:p-5">
                            <form class="space-y-4" action="{{ route('groups.store') }}" method="post">
                                @csrf
                                <div>
                                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Name
                                        *</label>
                                    <input type="text" name="name" id="name" class="form-control "
                                        placeholder="Enter Group name" required />
                                </div>
                                <div>
                                    <label for="description"
                                        class="block mb-2 text-sm font-medium text-gray-900 ">Description</label>
                                    <textarea name="description" id="description" class="form-control"></textarea>
                                </div>

                                <button type="submit" class="btn ">Create
                                    Group</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <ul class="w-full  divide-gray-200  grid sm:grid-cols-3 gap-5">
            @forelse ($groups as $group)
                <div class="p-4 bg-gray-50  rounded-2xl shadow-sm space-y-14 border-2 hover:!border-[#0F1523] ">
                    <div class="flex justify-between">
                        <span class="  rounded-full">
                            {{-- {{ $group->image }} --}}
                            @if ($group->latestInfluencer)
                                @php
                                    $content = json_decode($group->latestInfluencer->content);
                                @endphp
                                <img src="{{ $content->avatar ?: asset('images/logo.png') }}" alt=""
                                    class="rounded-full size-8">
                            @endif
                        </span>

                        <div class=" space-x-4 items-center flex ">
                            <button type="button" @click="editGroup = true , group =@js($group)"
                                class=" bg-gray-200 hover:bg-green-500 group  px-3 py-2 rounded-md text-sm flex items-center delay-100 transition-all duration-500 ease-in-out">
                                <i
                                    class="bx bx-edit font-medium group-hover:text-white mr-1 text-lg delay-100 transition-all duration-500 ease-in-out"></i>

                            </button>
                            <button type="button" data-item-id="{{ $group->id }}"
                                class="delete-btn bg-gray-200 hover:bg-red-500 group  px-3 py-2 rounded-md text-sm flex items-center delay-100 transition-all duration-500 ease-in-out">
                                <i
                                    class="bx bx-trash font-medium group-hover:text-white mr-1 text-lg delay-100 transition-all duration-500 ease-in-out"></i>

                            </button>
                            <a href="{{ route('groups.show', ['group' => $group->id]) }}"
                                class="bg-gray-200 hover:bg-[#0F1523] group  px-3 py-2 rounded-md text-sm flex items-center delay-100 transition-all duration-500 ease-in-out">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6 group-hover:text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                                </svg>
                                {{-- <i
                                    class="bx bx-show font-medium group-hover:text-white mr-1 text-lg delay-100 transition-all duration-500 ease-in-out"></i> --}}
                            </a>

                        </div>
                    </div>
                    <div class="flex justify-between mt-10">
                        <div>
                            <p class="font-medium text-md capitalize">
                                {{ $group->name }}
                            </p>
                            <p class="text-sm text-gray-500 capitalize">
                                {{ $group->description }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-3 bg-gray-50 text-gray-500 py-8 flex flex-col justify-center items-center rounded ">
                    <span>No Data Yet.</span>
                    <p><i class='bx bxs-folder-open text-4xl'></i></p>
                </div>
            @endforelse

            <div class="md:col-span-3">
                {{ $groups->links() }}
            </div>
        </ul>


        {{-- edit campaign --}}
        <div class="fixed items-center justify-center  flex -top-10 left-0 mx-auto w-full h-full bg-gray-600 bg-opacity-30 z-50 transition duration-1000 ease-in-out"
            x-show="editGroup" style="display: none;">
            <div @click.away="editGroup = false"
                class="bg-white w-[90%] md:w-[50%]  shadow-inner  border rounded-2xl overflow-auto  py-6 px-8 transition-all relative duration-700">
                <div class=" h-full ">

                    <div class="font-bold text-xl">Edit Group Name</div>
                    <form action="{{ route('changeGroupName') }}" method="post" class="my-10 space-y-3">
                        @csrf

                        <div>
                            <input class="form-control" id="" type="text" name="name"
                                x-model="group.name" placeholder="Group name">
                            <input class="form-control " id="" type="hidden" name="id"
                                x-model="group.id" placeholder="">
                        </div>

                        <button class="btn" type="submit">
                            <span>Edit Group</span>

                        </button>
                </div>
            </div>
        </div>
    </div>


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
