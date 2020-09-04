@if($produk->total() > 0)
@foreach($produk as $p)
<div class="col-2 px-2 product_box" data-produk_id="{{ $p->id }}">
    <div class="block block-link-pop block-bordered block-shadow">
        <div class="block-content block-content-full flex-grow-0 bg-gray-light">
            @if(!empty($p->foto))
            <img src="{{ asset($p->foto) }}" width="100%">
            @else
            <img src="{{ asset('assets/img/placeholder/product.png') }}" width="100%">
            @endif
        </div>
        <div class="block-content py-10">
            <h6 class="mb-0 text-center">
                <a class="text-dark" href="javascript:void(0)">
                    {{ $p->nama }}
                </a>
            </h6>
            <h6 class="mb-0 text-center">
               ({{ $p->pvd->sum('qty_tersedia') }})
            </h6>
        </div>
    </div>
</div>
{{-- @empty --}}
@endforeach
@else
<div class="col-12 text-center">
    <img src="{{ asset('assets/img/placeholder/data_not_found.png') }}">
</div>
@endif
