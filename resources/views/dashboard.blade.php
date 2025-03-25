<x-app-layout>
    @section('title')
        <span class="italic hidden lg:block">Hey there! <span class="font-light "> Welcome to your awesome dashboard!</span></span>
        <span class="flex  lg:hidden">Dashboard</span>
    @endsection
    {{--@vite(['resources/js/drivers.js'])--}}
    <link rel="stylesheet" href="{{ asset('build/assets/drivers-DB0Q8XAf.css') }}">
     <script src="{{ asset('build/assets/drivers-80L94_2i.js') }}"></script>

    <script src="{{ asset('build/assets/drivers-80L94_2i.js') }}"></script>
    <div class="px-3 pb-32 overflow-y-auto h-screen "  >
        {{-- <h1 class="text-2xl font-medium  capitalize text-transparent">dont show</h1> --}}

        <div class="">
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4 md:gap-2 mt-10 "  >

                <div class="p-4 bg-gradient-to-br from-[#FFFFFF] from-30% to-[#B5FFAB]/50  rounded-2xl shadow-md space-y-8 border-2 hover:!border-slate-900 transition duration-500 ease-in-out "
                    id="facebook">
                    <div class="flex items-center space-x-3">
                        <span class="rounded-md bg-[#B5FFAB] p-1">
                            <img src="{{ asset('images/icons/facebook.svg') }}" alt=""
                                class="rounded-full size-8 border">
                        </span>
                        <p class=" text-xl capitalize">Facebook</p>
                    </div>
                    <p class="font-medium text-xl capitalize text-right flex justify-end">
                        <a href="/platform/facebook" class="hover:bg-white p-2 rounded-md transition duration-500 ease-in-out" title="facebook" id="start_search">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                            </svg>
                        </a>
                    </p>
                </div>
                <div class="p-4 bg-gradient-to-br from-[#FFFFFF] from-30% to-[#B5FFAB]/50  rounded-2xl shadow-md space-y-8 border-2 hover:!border-slate-900 transition duration-500 ease-in-out "
                    id="instagram">
                    <div class="flex items-center space-x-3">
                        <span class="rounded-md bg-[#B5FFAB] p-1">
                            <img src="{{ asset('images/icons/instagram.svg') }}" alt=""
                                class="rounded-full size-8 border">
                        </span>
                        <p class=" text-xl capitalize">Instagram</p>
                    </div>
                    <p class="font-medium text-xl capitalize text-right flex justify-end">
                        <a href="/platform/instagram" class="hover:bg-white p-2 rounded-md transition duration-500 ease-in-out" title="instagram">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                            </svg>
                        </a>
                    </p>
                </div>
                <div class="p-4 bg-gradient-to-br from-[#FFFFFF] from-30% to-[#B5FFAB]/50  rounded-2xl shadow-md space-y-8 border-2 hover:!border-slate-900 transition duration-500 ease-in-out "
                    id="youtube">
                    <div class="flex items-center space-x-3">
                        <span class="rounded-md bg-[#B5FFAB] p-1">
                            <img src="{{ asset('images/icons/youtube.svg') }}" alt=""
                                class="rounded-full size-8 border">
                        </span>
                        <p class=" text-xl capitalize">Youtube</p>
                    </div>
                    <p class="font-medium text-xl capitalize text-right flex justify-end">
                        <a href="/platform/youtube" class="hover:bg-white p-2 rounded-md transition duration-500 ease-in-out" title="youtube">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                            </svg>
                        </a>
                    </p>
                </div>
                <div class="p-4 bg-gradient-to-br from-[#FFFFFF] from-30% to-[#B5FFAB]/50  rounded-2xl shadow-md space-y-8 border-2 hover:!border-slate-900 transition duration-500 ease-in-out "
                    id="tiktok">
                    <div class="flex items-center space-x-3">
                        <span class="rounded-md bg-[#B5FFAB] p-1">
                            <img src="{{ asset('images/icons/tiktok.svg') }}" alt=""
                                class="rounded-full size-8 border">
                        </span>
                        <p class=" text-xl capitalize">Tiktok</p>
                    </div>
                    <p class="font-medium text-xl capitalize text-right flex justify-end">
                        <a href="/platform/tiktok" class="hover:bg-white p-2 rounded-md transition duration-500 ease-in-out" title="tiktok">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                            </svg>
                        </a>
                    </p>
                </div>
            </div>

        </div>


        <!-- component -->




        <div class=" mt-10" id="response_chart">
            <div class=" w-full bg-white rounded-2xl shadow-sm  p-4 md:p-6">
                <div>
                    <h3 class="font-medium text-2xl  mb-2">Response Chart</h3>
                </div>
               

                <div class="grid grid-cols-2">
                    <dl class="flex items-center">
                        <dt class="text-gray-500  text-sm font-normal me-1">Total Acceptance:</dt>
                        <dd class="text-gray-900 text-sm font-semibold">
                            {{ \Illuminate\Support\Facades\DB::table('campaign_influencer')
                            ->join('campaigns', 'campaign_influencer.campaign_id', '=', 'campaigns.id')
                            ->where('campaigns.user_id', auth()->id())
                            ->where('campaign_influencer.task_status', 'accepted')
                            ->count() }}
                        </dd>
                    </dl>
                    <dl class="flex items-center justify-end">
                        <dt class="text-gray-500  text-sm font-normal me-1">Total Decline:
                        </dt>
                        <dd class="text-gray-900 text-sm  font-semibold">
                            {{ \Illuminate\Support\Facades\DB::table('campaign_influencer')
                        ->join('campaigns', 'campaign_influencer.campaign_id', '=', 'campaigns.id')
                        ->where('campaigns.user_id', auth()->id())
                        ->where('campaign_influencer.task_status', 'declined')
                        ->count() }}
                         
                        </dd>
                    </dl>
                </div>

                <div id="column-chart"></div>

            </div>

        </div>
    </div>


    @php
        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];

        // Fetch accepted data
       $acceptRawData = \Illuminate\Support\Facades\DB::table('campaign_influencer')
            ->join('campaigns', 'campaign_influencer.campaign_id', '=', 'campaigns.id')
            ->selectRaw('MONTH(campaign_influencer.created_at) as month, COUNT(*) as count')
            ->where('campaigns.user_id', auth()->id()) // Filter by logged-in user
            ->where('campaign_influencer.task_status', 'accepted')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();


        // Format accepted data
        $acceptData = [];
        foreach ($months as $num => $name) {
            $acceptData[] = [
                'x' => $name,
                'y' => $acceptRawData[$num] ?? 0, // Default to 0 if no data
            ];
        }

        // Fetch declined data
        $declinedRawData = \Illuminate\Support\Facades\DB::table('campaign_influencer')
            ->join('campaigns', 'campaign_influencer.campaign_id', '=', 'campaigns.id')
            ->selectRaw('MONTH(campaign_influencer.created_at) as month, COUNT(*) as count')
            ->where('campaigns.user_id', auth()->id()) // Filter by logged-in user
            ->where('campaign_influencer.task_status', 'declined')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();


        // Format declined data
        $declinedData = [];
        foreach ($months as $num => $name) {
            $declinedData[] = [
                'x' => $name,
                'y' => $declinedRawData[$num] ?? 0, // Default to 0 if no data
            ];
        }
    @endphp


    <script>
        const options = {
            colors: ["#1A56DB", "#FDBA8C"],
            series: [{
                    name: "Declined",
                    color: "#1A56DB",

                    data: @json($declinedData),

                },
                {
                    name: "Accepted",
                    color: "#FDBA8C",
                    data: @json($acceptData),

                },
            ],
            chart: {
                type: "bar",
                height: "320px",
                fontFamily: "Inter, sans-serif",
                toolbar: {
                    show: false,
                },
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: "70%",
                    borderRadiusApplication: "end",
                    borderRadius: 8,
                },
            },
            tooltip: {
                shared: true,
                intersect: false,
                style: {
                    fontFamily: "Inter, sans-serif",
                },
            },
            states: {
                hover: {
                    filter: {
                        type: "darken",
                        value: 1,
                    },
                },
            },
            stroke: {
                show: true,
                width: 0,
                colors: ["transparent"],
            },
            grid: {
                show: false,
                strokeDashArray: 4,
                padding: {
                    left: 2,
                    right: 2,
                    top: -14
                },
            },
            dataLabels: {
                enabled: false,
            },
            legend: {
                show: false,
            },
            xaxis: {
                floating: false,
                labels: {
                    show: true,
                    style: {
                        fontFamily: "Inter, sans-serif",
                        cssClass: 'text-xs font-normal fill-gray-500'
                    }
                },
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
            },
            yaxis: {
                show: false,
            },
            fill: {
                opacity: 1,
            },
        }

        if (document.getElementById("column-chart") && typeof ApexCharts !== 'undefined') {
            const chart = new ApexCharts(document.getElementById("column-chart"), options);
            chart.render();
        }
    </script>

</x-app-layout>
