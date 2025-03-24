<x-guest-layout>

    @if ($campaign->type == 1)
    <div class="bg-[#000828] flex items-center justify-center h-screen overflow-auto">
        <div class="bg-yellow-300 sm:w-[80%] md:w-[60%] py-20 px-10 rounded-xl ">
            <div class="flex flex-col items-center">
                <h1 class="text-[#000828] font-bold text-4xl max-w-lg uppercase text-center mb-2 ">{{ $campaign->title }}</h1>
                <p class="text-[#000828] text-md max-w-lg font-medium text-center first-letter:capitalize">{{ $campaign->description }}</p>
                <p class="font-bold text-[#000828] ">Budget: ${{ $campaign->budget }}</p>

                <div class="w-full bg-[#000828] rounded-xl h-72 p-10  mt-5 overflow-auto text-white">
                    {!! $campaign->task !!}
                </div>
                {{-- <div class="w-full grid grid-cols-2 gap-3 mt-5">
                    <div class=" bg-[#000828] rounded-xl p-10">

                    </div>
                </div> --}}

                <form method="POST" action="{{ route('campaign.respond') }}" class="mt-5">
                    @csrf
                    <input type="hidden" name="token" value="{{ request('token') }}">
                    <button type="submit" name="response" class="text-white px-8 py-2 border border-[#000828] bg-[#000828] hover:text-[#000828] hover:bg-transparent transition-all duration-300 delay-75 rounded-lg font-semibold text-md" value="accepted">Accept</button>
                    <button type="submit" name="response" class="text-white px-8 py-2 border border-[#000828] bg-[#000828] hover:text-[#000828] hover:bg-transparent transition-all duration-300 delay-75 rounded-lg font-semibold text-md" value="declined">Decline</button>
                </form>

            </div>
        </div>
    </div>

    @elseif ($campaign->type == 2)
    <div class=" flex items-center justify-center h-screen overflow-auto">

    </div>
        
    @endif
</x-guest-layout>
