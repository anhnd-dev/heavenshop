<div class="modal fade" id="{{ $id }}" tabindex="-1">

    <div class="modal-dialog {{ $size ?? '' }}">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="{{ $id }}Title">
                    {{ $title }}
                </h5>

                <button type="button" class="close" data-bs-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>

            <form id="{{ $formId }}"
                @isset($enctype)
                    enctype="{{ $enctype }}"
                @endisset>

                @csrf

                @isset($method)
                    @method($method)
                @endisset

                @foreach ($hiddenFields ?? [] as $field)
                    <input type="hidden" name="{{ $field['name'] }}" id="{{ $field['id'] ?? $field['name'] }}"
                        value="{{ $field['value'] ?? '' }}">
                @endforeach

                <div class="modal-body">
                    {!! $body !!}
                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                        Đóng
                    </button>

                    <button type="submit" id="{{ $submitId }}" class="btn btn-primary">

                        {{ $submitText }}
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
