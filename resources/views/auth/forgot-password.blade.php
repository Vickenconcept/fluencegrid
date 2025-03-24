@seo([
     'title' => 'FluenceGrid',
     'description' => 'Influencers Management Hub',
     'image' => asset('images/login-image.png'),
     'site_name' => config('app.name'),
     'favicon' => asset('images/fav-image.png'),
 ])

<x-guest-layout>
    <x-notification />
    <div class="flex justify-center items-center h-screen bg-gradient-to-b from-[#D0E8FF] to-[#B5FFAB]">
        <div class="w-full md:w-[40%] mx-auto px-3">
            <form method="POST" action="{{ route('password.email') }}"
                class=" shadow-md rounded-2xl bg-slate-200 bg-opacity-50 px-8 pt-6 pb-8 mb-4">
                @csrf
                <div class="mb-4">
                    <x-session-msg />
                    @if (session('status'))
                        <div class="bg-green-200 text-green-500 p-4">
                            {{ session('status') }}
                        </div>
                    @endif
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="url">
                        ADD EMAIL
                    </label>
                    <input class="form-control" id="email" type="email" name="email" required
                        placeholder="Enter email">
                </div>

                <button type="submit"
                    class= "bg-black hover:bg-slate-900 hover:shadow px-4 py-1.5 font-semibold text-blue-50 rounded-md w-full transition duration-500 ease-in-out">
                    <span id="hiddenText" class="hidden"> <i class='bx bx-loader-alt animate-spin'></i></span>
                    <span>SEND</span>
                </button>
                <div
                    class=" text-gray-600 font-semibold  hover:text-gray-900  text-sm py-2 flex items-center justify-end">
                    <i class='bx bxs-chevron-left-circle mr-1 text-xl'></i><a href="{{ route('home') }}"
                        class="hover:underline">GO BACK</a>
                </div>


            </form>
        </div>
    </div>


</x-guest-layout>
