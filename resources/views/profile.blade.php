<x-app-layout>
    @section('title')
        {{ 'profile' }}
    @endsection
    <div class="  space-y-8 h-full">

        <div class="py-5 border-b px-3 flex space-x-5 items-center">
            <div>
                <h3 class=" font-bold">My Profile</h3>
            </div>
            <span class="text-xs">Manage your account</span>
        </div>

        <div class="px-3">
            <form action="{{ route('changeName') }}" method="POST" class=" space-y-4 ">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2  w-full gap-5">
                    <div>
                        <div class="flex items-center justify-between">
                            <label for="name" class="input-label">Name</label>
                        </div>
                        <div class="mt-2">
                            <input id="name" name="name" type="name" autocomplete="off" class="form-control"
                                value="{{ auth()->user()->name }}">
                            @error('name')
                                <span class="text-xs text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="email" class="input-label">Email</label>
                        </div>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="off" class="form-control"
                                value="{{ auth()->user()->email }}">
                            @error('email')
                                <span class="text-xs text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="mt-5">
                    <button type="submit"
                        class="bg-cyan-950 hover:bg-cyan-800 hover:shadow px-4 py-1.5 font-semibold text-blue-50 rounded-md flex items-center">
                        <i class='bx bx-save mr-2'></i>
                        save
                    </button>
                </div>


            </form>
        </div>


        <div class="space-y-5 px-3">
            <hr>
            <h1 class=" font-semibold">Changing your password will log you out of every device.</h1>
            <form action="{{ route('changePassword') }}" method="POSt" class=" space-y-4 ">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2  w-full gap-5">
                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="input-label">Password</label>
                        </div>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" autocomplete="off"
                                class="form-control">
                            @error('password')
                                <span class="text-xs text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="new_password" class="input-label">New password</label>
                        </div>
                        <div class="mt-2">
                            <input id="new_password" name="new_password" type="new_password" autocomplete="off"
                                class="form-control" value="">
                            @error('new_password')
                                <span class="text-xs text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <button type="submit"
                        class="bg-cyan-950 hover:bg-cyan-800 hover:shadow px-4 py-1.5 font-semibold text-blue-50 rounded-md flex items-center ">
                        <i class='bx bx-save mr-2'></i>
                        save
                    </button>
                </div>


            </form>
        </div>

    </div>


</x-app-layout>
