<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'AAFT Online')
<img src="{{ asset('aaft-online-logo.png') }}" class="logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
