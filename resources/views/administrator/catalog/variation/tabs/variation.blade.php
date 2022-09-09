@if(isset($combinations) && count($combinations))
    <form action="{{ route('admin.catalog.variation.create') }}" method="post" onsubmit="return ajaxSubmit(this);">
        @csrf
        <input type="hidden" name="product_clone_uuid" value="{{ $product->uuid }}">
        <input type="hidden" name="group_uuid" value="{{ $product->group->uuid ?? \Ramsey\Uuid\Uuid::uuid4()->toString() }}">

        <p>
            <button class="btn btn-primary">
                {{ __('catalog.variation.add') }}
            </button>
        </p>

        <hr>

        {{-- Если в группе более 1 комбинации --}}
        @if ($combinations->first()['collection']->count() > 1)
        <div class="accordion" id="accordion">
            @foreach($combinations as $combination)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading_{{ $combination['key_group_uuid'] }}">
                        <button class="accordion-button @if($loop->index)collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $combination['key_group_uuid'] }}" aria-expanded="true" aria-controls="collapse_{{ $combination['key_group_uuid'] }}">
                            <strong>{{ $combination['name'] }}</strong>
                        </button>
                    </h2>
                    <div id="collapse_{{ $combination['key_group_uuid'] }}" class="accordion-collapse collapse @if(!$loop->index)show @endif" aria-labelledby="heading_{{ $combination['key_group_uuid'] }}" data-bs-parent="#accordion">
                        <div class="accordion-body">
                            <ul class="list-group-table">
                                @foreach($combination['collection'] as $item)
                                    @include('administrator.catalog.variation.partials.variation-row')
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{-- Если только 1 комбинация в группе, то сразу её и показываем --}}
        @else
            @foreach($combinations as $combination)
                <ul class="list-group-table">
                    @foreach($combination['collection'] as $item)
                        @include('administrator.catalog.variation.partials.variation-row')
                    @endforeach
                </ul>
            @endforeach
        @endif
    </form>
@else
    <div class="alert alert-light">
        {!! __('catalog.variation.no_features_for_variants') !!}
    </div>
@endif
