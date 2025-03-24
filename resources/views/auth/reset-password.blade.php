<x-guest-layout>
    <div class="flex justify-center items-center h-screen bg-gradient-to-b from-[#D0E8FF] to-[#B5FFAB] ">
        <div class="w-[40%] mx-auto">
            <form method="POST" action="{{ route('password.update') }}"
                class="shadow-md rounded-2xl bg-slate-200 bg-opacity-50 px-8 pt-6 pb-8 mb-4">
                @csrf
                <h4 class="text-3xl text-center">Reset Password</h4>
                <div class="mb-4">
                    @if ($errors->any())
                        <div class="bg-red-200 text-red-500 p-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="bg-green-200 text-green-500 p-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mt-4">
                        <div class="flex items-center justify-between">
                            <label for="password_confirmation" class="input-label">Email</label>
                        </div>

                        <input id="email" class="form-control" type="email" name="email" required
                            autocomplete="current-email" placeholder="Enter your email address" />

                    </div>
                    <div class="mt-4">
                        <div class="flex items-center justify-between">
                            <label for="password_confirmation" class="input-label">Password </label>
                        </div>

                        <input id="password" class="form-control" type="password" name="password" required
                            autocomplete="current-password" placeholder="Enter your password " />

                    </div>
                    <div class="mt-4">
                        <div class="flex items-center justify-between">
                            <label for="password_confirmation" class="input-label">Password Confirmation</label>
                        </div>
                        <div class="">
                            <input id="password_confirmation" name="password_confirmation" type="password" placeholder="*****"
                                class="form-control">
                        </div>
                    </div>
                    <div class="">
                        <input id="token" name="token" type="hidden" class="form-control"
                            value="{{ $token }}">
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="bg-black hover:bg-slate-900 hover:shadow px-4 py-1.5 font-semibold text-blue-50 rounded-md w-full transition duration-500 ease-in-out">
                            <span id="hiddenText" class="hidden"> <i class='bx bx-loader-alt animate-spin'></i></span>
                            <span>RESET</span>
                        </button>
                    </div>
            </form>
        </div>
    </div>
   
</x-guest-layout>
