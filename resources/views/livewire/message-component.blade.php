<!-- component -->
<div class="flex h-full antialiased text-gray-800" x-data="{ openModal: false }">
    <div class="flex flex-row h-full w-full overflow-x-hidden">
        {{-- <div class="flex flex-col py-8 pl-6 pr-2 w-64 bg-white flex-shrink-0">
        <div class="flex flex-row items-center justify-center h-12 w-full">
          <div
            class="flex items-center justify-center rounded-2xl text-indigo-700 bg-indigo-100 h-10 w-10"
          >
            <svg
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
              ></path>
            </svg>
          </div>
          <div class="ml-2 font-bold text-2xl">QuickChat</div>
        </div>
        <div
          class="flex flex-col items-center bg-indigo-100 border border-gray-200 mt-4 w-full py-6 px-4 rounded-lg"
        >
          <div class="h-20 w-20 rounded-full border overflow-hidden">
            <img
              src="https://avatars3.githubusercontent.com/u/2763884?s=128"
              alt="Avatar"
              class="h-full w-full"
            />
          </div>
          <div class="text-sm font-semibold mt-2">Aminos Co.</div>
          <div class="text-xs text-gray-500">Lead UI/UX Designer</div>
          <div class="flex flex-row items-center mt-3">
            <div
              class="flex flex-col justify-center h-4 w-8 bg-indigo-500 rounded-full"
            >
              <div class="h-3 w-3 bg-white rounded-full self-end mr-1"></div>
            </div>
            <div class="leading-none ml-1 text-xs">Active</div>
          </div>
        </div>
        <div class="flex flex-col mt-8">
          <div class="flex flex-row items-center justify-between text-xs">
            <span class="font-bold">Active Conversations</span>
            <span
              class="flex items-center justify-center bg-gray-300 h-4 w-4 rounded-full"
              >4</span
            >
          </div>
          <div class="flex flex-col space-y-1 mt-4 -mx-2 h-48 overflow-y-auto">
            <button
              class="flex flex-row items-center hover:bg-gray-100 rounded-xl p-2"
            >
              <div
                class="flex items-center justify-center h-8 w-8 bg-indigo-200 rounded-full"
              >
                H
              </div>
              <div class="ml-2 text-sm font-semibold">Henry Boyd</div>
            </button>
            <button
              class="flex flex-row items-center hover:bg-gray-100 rounded-xl p-2"
            >
              <div
                class="flex items-center justify-center h-8 w-8 bg-gray-200 rounded-full"
              >
                M
              </div>
              <div class="ml-2 text-sm font-semibold">Marta Curtis</div>
              <div
                class="flex items-center justify-center ml-auto text-xs text-white bg-red-500 h-4 w-4 rounded leading-none"
              >
                2
              </div>
            </button>
            <button
              class="flex flex-row items-center hover:bg-gray-100 rounded-xl p-2"
            >
              <div
                class="flex items-center justify-center h-8 w-8 bg-orange-200 rounded-full"
              >
                P
              </div>
              <div class="ml-2 text-sm font-semibold">Philip Tucker</div>
            </button>
            <button
              class="flex flex-row items-center hover:bg-gray-100 rounded-xl p-2"
            >
              <div
                class="flex items-center justify-center h-8 w-8 bg-pink-200 rounded-full"
              >
                C
              </div>
              <div class="ml-2 text-sm font-semibold">Christine Reid</div>
            </button>
            <button
              class="flex flex-row items-center hover:bg-gray-100 rounded-xl p-2"
            >
              <div
                class="flex items-center justify-center h-8 w-8 bg-purple-200 rounded-full"
              >
                J
              </div>
              <div class="ml-2 text-sm font-semibold">Jerry Guzman</div>
            </button>
          </div>
          <div class="flex flex-row items-center justify-between text-xs mt-6">
            <span class="font-bold">Archivied</span>
            <span
              class="flex items-center justify-center bg-gray-300 h-4 w-4 rounded-full"
              >7</span
            >
          </div>
          <div class="flex flex-col space-y-1 mt-4 -mx-2">
            <button
              class="flex flex-row items-center hover:bg-gray-100 rounded-xl p-2"
            >
              <div
                class="flex items-center justify-center h-8 w-8 bg-indigo-200 rounded-full"
              >
                H
              </div>
              <div class="ml-2 text-sm font-semibold">Henry Boyd</div>
            </button>
          </div>
        </div>
      </div> --}}
        <div class="flex flex-col flex-auto h-full p-6 ">
            <div class="flex flex-col flex-auto flex-shrink-0 rounded-2xl bg-gray-100 h-full p-4 ">
                @auth
                    <div class="p-3 bg-gray-100 border-b-2 border-slate-400 flex items-center space-x-4">
                        <div>
                            <button @click="openModal = true">
                                create Contract
                            </button>
                        </div>

                    </div>
                @endauth
                <div class="flex flex-col h-full overflow-x-auto mb-4 " id="chatContainer">
                    <div class="flex flex-col h-full">
                        <div class="grid grid-cols-12 gap-y-2" wire:poll.3s="fetchMessages">
                            @foreach ($messages as $message)
                                @if ($message->sender == 'owner')
                                    <div class="col-start-1 col-end-8 p-3 rounded-lg">
                                        <div class="flex flex-row items-center">
                                            <div
                                                class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 flex-shrink-0">
                                                A
                                            </div>
                                            <div class="relative ml-3 text-sm bg-white py-2 px-4 shadow rounded-xl">
                                                <div>{{ $message->message }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif ($message->sender == 'influencer')
                                    <div class="col-start-6 col-end-13 p-3 rounded-lg">
                                        <div class="flex items-center justify-start flex-row-reverse">
                                            <div
                                                class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 flex-shrink-0">
                                                A
                                            </div>
                                            <div
                                                class="relative mr-3 text-sm bg-indigo-100 py-2 px-4 shadow rounded-xl">
                                                <div>
                                                    {{ $message->message }}
                                                </div>
                                                {{-- <div class="absolute text-xs bottom-0 right-0 -mb-5 mr-2 text-gray-500">
                                                    Seen
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach


                        </div>
                    </div>
                </div>
                <div class="flex flex-row items-center h-16 rounded-xl bg-white w-full px-4">

                    <div class="flex-grow ml-4">
                        <div wire:loading.remove>
                            @if ($isTyping)
                                <p class="text-gray-500 italic">User is typing...</p>
                            @endif
                        </div>
                        <div class="relative w-full">
                            <input type="text" wire:model.live.debounce.500ms="message" id="messageField"
                                wire:keydown="typing"
                                class="flex w-full border rounded-xl focus:outline-none focus:border-indigo-300 pl-4 h-10" />
                            <button
                                class="absolute flex items-center justify-center h-full w-12 right-0 top-0 text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="ml-4">
                        <button wire:click="saveMessage" wire:loading.attr="disabled" wire:target="saveMessage"
                            :disabled="$wire.message === ''" {{-- @if ($message === '')
                              disabled
                            @endif --}}
                            class="flex items-center justify-center bg-indigo-500 hover:bg-indigo-600 rounded-xl text-white px-4 py-1 flex-shrink-0">
                            <span>Send</span>
                            <span class="ml-2">
                                <svg class="w-4 h-4 transform rotate-45 -mt-px" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>





    {{-- edit campaign --}}
    <div class="fixed items-center justify-center  flex top-0 left-0 mx-auto w-full h-full bg-gray-600 bg-opacity-30 z-50 transition duration-1000 ease-in-out"
        x-show="openModal" style="display: none;">
        <div @click.away="openModal = false"
            class="bg-white w-[90%] md:w-[50%]  shadow-inner  border rounded-2xl overflow-auto  py-6 px-8 transition-all relative duration-700">
            <div class=" h-full ">

                <div class="font-bold text-xl">Create Contract</div>
                <div class="my-10 space-y-3">

                    @if (session()->has('success'))
                        <div class="bg-green-500 text-white p-3 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div>
                        <label for="">Traget Url</label>
                        <input class="form-control" id="" type="text" wire:model="url"
                            placeholder="Url">
                    </div>
                    <div>
                        <label for="">Amount Paid</label>
                        <input class="form-control" id="" type="text" wire:model="amount"
                            placeholder="Amount Paid">
                    </div>
                    <div>
                        <label for="">Description</label>
                        <input class="form-control" id="" type="text"
                            wire:model="description" placeholder="Description">
                    </div>

                    <button wire:click="createContract()" class="btn" type="submit">
                        <span>Create</span>

                    </button>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Livewire.on('scrollUp', () => {
                    document.getElementById('messageField').value = '';

                    let chatContainer = document.getElementById(
                        'chatContainer');
                    if (chatContainer) {
                        setTimeout(() => {
                            chatContainer.scrollTop = chatContainer.scrollHeight;
                        }, 1000);

                    }
                });

            });


            // document.addEventListener("DOMContentLoaded", function() {
            //     let typingTimer;
            //     let isTypingVisible = false; // Tracks if the div is already visible

            //     window.Echo.channel('chat.' + @js($conversation->id))
            //         .listen('.user.typing', (event) => {
            //             if (!isTypingVisible) {
            //                 isTypingVisible = true;
            //                 Livewire.dispatch('userTyping');
            //             }

            //             clearTimeout(typingTimer);

            //             typingTimer = setTimeout(() => {
            //                 isTypingVisible = false;
            //                 Livewire.dispatch('resetTyping');
            //             }, 3000);
            //         });
            // });
        </script>
    </div>
