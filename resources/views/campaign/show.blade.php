<x-app-layout>
    @section('title')
        {{ $campaign->title }}
    @endsection

    <div class="px-3 pb-28 overflow-y-auto h-screen  ">
        <div class="flex justify-between items-center border-b pb-5 mb-5">
            <h1 class="text-2xl font-medium capitalize "></h1>
            <div>
                <button type="button" data-item-id="{{ $campaign->id }}"
                    class="delete-btn bg-gray-50 hover:bg-red-500 group  px-3 py-2 rounded-md text-sm flex items-center delay-100 transition-all duration-500 ease-in-out">
                    <i
                        class="bx bx-trash font-medium text-red-500 group-hover:text-white mr-1 text-lg delay-100 transition-all duration-500 ease-in-out"></i>

                </button>
            </div>
        </div>


        <!-- component -->
        <form action="{{ route('campaigns.update', $campaign->id) }}" method="post" class="flex flex-col  lg:flex-row">
            @csrf
            @method('PUT')
            <div class="w-full lg:w-1/3 m-1 ">
                <div class="bg-white shadow-md p-6 rounded-lg ">
                    <h3 class="text-xl font-medium capitalize mb-6">select display type</h3>
                    <div class="w-full h-96 overflow-y-auto" x-data="{ selectedType: @js($campaign->type) }">
                        <label for="type_1"
                            class="rounded-lg shadow-md border mb-6 overflow-hidden block cursor-pointer hover:grayscale-50 hover:shadow-xl"
                            :class="{ 'border-[#0F1523] border-2': selectedType == '1' }">
                            <img src="https://placehold.co/600x400?text=Hello+World" class="w-fulll h-full object-cover"
                                alt="image">
                            <input type="radio" name="type" class="hidden" id="type_1" value="1"
                                x-model="selectedType">
                        </label>
                        <label for="type_2"
                            class="rounded-lg shadow-md border mb-6 overflow-hidden block cursor-pointer hover:grayscale-50 hover:shadow-xl"
                            :class="{ 'border-[#0F1523] border-2': selectedType == '2' }">
                            <img src="https://placehold.co/600x400?text=Hello+World" class="w-fulll h-full object-cover"
                                alt="image">
                            <input type="radio" name="type" class="hidden" id="type_2" value="2"
                                x-model="selectedType">
                        </label>
                        <label for="type_3"
                            class="rounded-lg shadow-md border mb-6 overflow-hidden block cursor-pointer hover:grayscale-50 hover:shadow-xl"
                            :class="{ 'border-[#0F1523] border-2': selectedType == '3' }">
                            <img src="https://placehold.co/600x400?text=Hello+World" class="w-fulll h-full object-cover"
                                alt="image">
                            <input type="radio" name="type" class="hidden" id="type_3" value="3"
                                x-model="selectedType">
                        </label>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-2/3 m-1 bg-white shadow-lg text-lg  border border-gray-200 rounded-lg">
                <div class="overflow-x-auto rounded-lg p-3">
                    <form class="w-full  p-6">
                        <div class="flex flex-wrap -mx-3">
                            <div class="w-full md:w-full px-3 mb-6">
                                <label class="block uppercase tracking-wide text-gray-700 text-sm font-bold mb-2"
                                    htmlFor="Campaign_name">Campaign Name</label>
                                <input class="form-control " type="text" name="title" placeholder="Campaign Name"
                                    value="{{ $campaign->title }}" />
                            </div>
                            <div class="w-full px-3 mb-6">
                                <label class="block uppercase tracking-wide text-gray-700 text-sm font-bold mb-2"
                                    htmlFor="Campaign_name">Campaign Description</label>
                                <textarea textarea rows="4" class="form-control " type="text" name="description" required>{{ $campaign->description }}</textarea>
                            </div>
                            <div class="w-full px-3 mb-6">
                                <label class="block uppercase tracking-wide text-gray-700 text-sm font-bold mb-2"
                                    htmlFor="Campaign_name">Campaign Task</label>
                                {{-- <textarea textarea rows="4" class="form-control " type="text" name="task" required>{{ $campaign->task }}</textarea> --}}

                                <textarea id="content" name="task" class="w-full" rows="10">{{ $campaign->task }}</textarea>
                            </div>

                            <div class="w-full px-3 mb-6">
                                <lable class="text-lg font-semibold">Budget</lable>
                                <br>
                                <input class="form-control" id="budget" type="number" name="budget"
                                    placeholder="My Budget eg. $200" value="{{ $campaign->budget }}">
                            </div>

                            <div class="mb-6 px-3 grid md:grid-cols-3 gap-4">

                                <div class=" ">
                                    <lable class="text-lg font-semibold">Start Date</lable> <br>
                                    <input id="startDate" name="start_date" class="form-control !block !w-full"
                                        type="text" placeholder="Select a date" value="{{ $campaign->start_date }}">
                                </div>
                                <div class="">
                                    <lable class="text-lg font-semibold">End Date</lable> <br>
                                    <input id="endDate" name="end_date" class="form-control !block !w-full"
                                        type="text" placeholder="Select a date" value="{{ $campaign->end_date }}">
                                </div>
                                <div class="">
                                    <lable class="text-lg font-semibold">Invitation End Date</lable> <br>
                                    <input id="inviteEndDate" name="invite_end_date"
                                        class="form-control !block !w-full" type="text"
                                        placeholder="Select a date" value="{{ $campaign->invite_end_date }}">
                                </div>

                            </div>

                            {{-- <div class="w-full px-3 mb-8">
                                <label
                                    class="mx-auto cursor-pointer flex w-full max-w-lg flex-col items-center justify-center rounded-xl border-2 border-dashed border-green-400 bg-white p-6 text-center"
                                    htmlFor="dropzone-file">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-800"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
                                        <path strokeLinecap="round" strokeLinejoin="round"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>

                                    <h2 class="mt-4 text-xl font-medium text-gray-700 tracking-wide">Campaign image</h2>

                                    <p class="mt-2 text-gray-500 tracking-wide">Upload or drag & drop your file SVG,
                                        PNG, JPG or GIF. </p>

                                    <input id="dropzone-file" type="file" class="hidden" name="Campaign_image"
                                        accept="image/png, image/jpeg, image/webp" />
                                </label>
                            </div> --}}

                            <div class="w-full md:w-full px-3">
                                <button type="submit" class="btn">Save change</button>
                            </div>


                        </div>
                    </form>
                </div>
            </div>

        </form>




        <script>
            flatpickr("#startDate", {});
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
                                            location.href =
                                                '{{ route('campaigns.index') }}';
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

        <script>
            $('#content').summernote({
                height: 300,
                minHeight: null,
                maxHeight: null,
                focus: true,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    // ['insert', ['link']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen']]
                ],
                fontNames: [
                    'Arial', 'Arial Black', 'Comic Sans MS', 'Courier New',
                    'Helvetica', 'Impact', 'Lucida Grande', 'Tahoma',
                    'Times New Roman', 'Verdana', 'Roboto', 'Open Sans'
                ],
                fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '24', '30', '36',
                    '48'
                ],
                callbacks: {

                    onImageUpload: function(files) {
                        alert('Local image uploads are disabled. Please use an image URL.');
                    }
                }
            });
        </script>
    </div>
</x-app-layout>
