{{-- prettier-ignore --}}
<mail.html.message>
@if (!empty($body))
    {{ $body }}
@endif

@if (!empty($call_to_action) && !empty($call_to_action_url))
    <mail.html.button :url="$call_to_action_url">
        {{ $call_to_action }}
    </mail.html.button>
@endif

@if (!empty($signature))
    {{ $signature }}
@endif
</mail.html.message>
