<tr>
    <td width="10%">
        <label class="css-control css-control-primary css-checkbox">
            <input type="checkbox" class="css-control-input" name="variasi_id" value="{{ $pv->id }}">
            <span class="css-control-indicator"></span>
        </label>
    </td>
    <td>
        <div class="font-w600 font-size-md">
            @if($pv->nama == '')
                Original
            @else
                {{ $pv->nama }}
            @endif
        </div>
        Rp <span class="display_currency">{{ number_format($hrg,0,",",".") }}</span>
    </td>
</tr>
