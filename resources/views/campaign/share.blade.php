<x-guest-layout>
    <h1>Share Campaign: {{ $campaign->title }}</h1>

    @foreach ($links as $link)
        <div>
            <p>Influencer: {{ $link['influencer'] }} ({{ $link['email'] }})</p>
            <p>Link: <a href="{{ $link['link'] }}" target="_blank">{{ $link['link'] }}</a></p>
        </div>
    @endforeach

</x-guest-layout>
