<x-app-layout>
    @section('title')
    {{ "reseller" }}
@endsection
    <div x-data="{ openModal: false }" class="px-3 pb-32 overflow-y-auto h-screen">
        <div class=" flex justify-between">
            <div class=" flex space-x-1 items-baseline">
                <span class="text-gray-500">Manage Users</span>
            </div>

            <div class=" mb-8">
                <button
                    class="btn"
                    @click="openModal = true"><i class="bx bx-plus mr-2"></i> Register user</button>
            </div>
        </div>
        <div class=" ">
            <x-session-msg />
        </div>

        {{-- modal --}}
        <div class="fixed items-center justify-center   flex top-0 left-0 mx-auto w-full h-full bg-gray-600 bg-opacity-20 z-50 transition duration-1000 ease-in-out"
            x-show="openModal" style="display: none;">
            <div @click.away="openModal = false"
                class="bg-white w-[80%] md:w-[70%] lg:w-[40%] shadow-inner  rounded-xl overflow-auto  pb-6 px-5 transition-all relative duration-700">
                <div class="space-y-5 py-5 ">
                    <div class="flex justify-between items-center  w-full ">
                        <h4 class=" text-cl font-medium">Create User</h4>
                        <div>
                            <button type="button" @click="openModal = false"><i
                                    class="bx bx-x font-medium text-xl"></i></button>
                        </div>
                    </div>
                    <form class="space-y-3" action="{{ route('reseller.store') }}" method="POST">
                        @csrf
                        <div>
                            <label for="name" class="input-label text-slate-600 text-xs font-semibold">Name</label>
                            <div class="mt-2">
                                <input id="name" name="name" value="{{ old('name') }}" type="text"
                                    autocomplete="name" class="form-control" placeholder="Smith Joe">
                                @error('name')
                                    <span class="text-xs text-red-400">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div>
                            <label for="email" class="input-label text-slate-600 text-xs font-semibold">Email
                                Address</label>
                            <div class="mt-2">
                                <input id="email" name="email" value="{{ old('email') }}" type="email"
                                    autocomplete="email" class="form-control" placeholder="example@gmail.com">
                                @error('email')
                                    <span class="text-xs text-red-400">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between">
                                <label for="password"
                                    class="input-label text-slate-600 text-xs font-semibold">Password</label>
                            </div>
                            <div class="mt-2">
                                <input id="password" name="password" type="password" autocomplete="current-password"
                                    class="form-control" placeholder="********">
                                @error('password')
                                    <span class="text-xs text-red-400">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div>
                            <div class="flex items-center justify-between">
                                <label for="password_confirmation"
                                    class="input-label text-slate-600 text-xs font-semibold">Password
                                    Confirmation</label>
                            </div>
                            <div class="mt-2">
                                <input id="password_confirmation" name="password_confirmation" type="password"
                                    autocomplete="current-password" class="form-control" placeholder="********">
                            </div>
                        </div>

                        <div class="pt-3">
                            <button type="submit" class="btn">Create User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <h1 class=" font-bold tracking-wider">Users</h1>

        <div class="my-10">

            <livewire:resell-table />

        </div>
    </div>


</x-app-layout>
