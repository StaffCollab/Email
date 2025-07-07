<mail.html.layout>
    {{-- Header --}}
    <x-slot:header>
        <mail.html.header :url="config('app.url')">
            {{ config('app.name') }}
        </mail.html.header>
    </x-slot:header>

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        <x-slot:subcopy>
            <mail.html.subcopy>
                {{ $subcopy }}
            </mail.html.subcopy>
        </x-slot:subcopy>
    @endisset

    {{-- Footer --}}
    <x-slot:footer>
        <mail.html.footer>
            © {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}
        </mail.html.footer>
    </x-slot:footer>
</mail.html.layout>
