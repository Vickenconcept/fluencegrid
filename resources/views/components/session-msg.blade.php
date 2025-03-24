@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <button type="button" onclick="this.parentElement.parentElement.style.display='none';">
                <title>Close</title>
                <i class='bx bx-x text-xl'></i>
            </button>
        </span>
    </div>
@endif


@if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <button type="button" onclick="this.parentElement.parentElement.style.display='none';">
                <title>Close</title>
                <i class='bx bx-x text-xl'></i>
            </button>
        </span>
    </div>
@endif


@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-600 px-4 py-3 rounded relative mb-4">
        <ul class="">
            @foreach ($errors->all() as $error)
                <li class="block ">{{ $error }}</li>
            @endforeach
        </ul>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <button type="button" onclick="this.parentElement.parentElement.style.display='none';">
                <title>Close</title>
                <i class='bx bx-x text-xl'></i>
            </button>
        </span>
    </div>
@endif
