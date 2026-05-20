<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog {{ $size ?? '' }}">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    {{ $title }}
                </h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <form action="" id="{{ $formId }}"
                @isset($enctype)
                    enctype="{{ $enctype }}"
                @endisset>

                @csrf

                @isset($method)
                    @method($method)
                @endisset

                @if (!empty($hiddenFields))

                    @foreach ($hiddenFields as $field)
                        <input type="hidden" name="{{ $field['name'] }}" id="{{ $field['id'] ?? $field['name'] }}"
                            value="{{ $field['value'] ?? '' }}">
                    @endforeach

                @endif

                <div class="modal-body">

                    {!! $body !!}

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-danger light" data-dismiss="modal">

                        {{ __('admin.common.close') }}

                    </button>

                    <button type="submit" id="{{ $submitId }}" class="btn btn-primary">

                        {{ $submitText }}

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
