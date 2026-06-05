@if ($galleries->count())

    <div class="table-responsive">

        <table class="table table-striped table-bordered table-hover">

            <thead>

                <tr>

                    <th width="40">

                        <div class="form-check custom-checkbox">

                            <input type="checkbox" class="form-check-input" id="checkAllGallery">

                        </div>

                    </th>

                    <th width="140">
                        Media
                    </th>

                    <th>
                        Màu sắc
                    </th>

                    <th width="100">
                        Type
                    </th>

                    <th width="100">
                        Sort
                    </th>

                    <th width="140">
                        Action
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach ($galleries as $gallery)
                    <tr>

                        <td>

                            <div class="form-check custom-checkbox">

                                <input type="checkbox" class="form-check-input checkbox_gallery_ids"
                                    value="{{ $gallery->id }}">

                            </div>

                        </td>

                        <td>

                            @if ($gallery->type === 'image')
                                <img src="{{ asset('uploads/gallery/' . $gallery->file) }}" class="img-thumbnail"
                                    width="100">
                            @else
                                <video width="100" height="100" controls>

                                    <source src="{{ asset('uploads/gallery/' . $gallery->file) }}">

                                </video>
                            @endif

                        </td>

                        <td>

                            @if ($gallery->color)
                                <div
                                    style="
                                        width:25px;
                                        height:25px;
                                        border-radius:50%;
                                        background:{{ $gallery->color->code }};
                                        border:1px solid #ddd;
                                    ">
                                </div>
                            @else
                                --
                            @endif

                        </td>

                        <td>

                            <span class="badge badge-primary">

                                {{ strtoupper($gallery->type) }}

                            </span>

                        </td>

                        <td>

                            {{ $gallery->sort_order }}

                        </td>

                        <td>

                            @if ($includeTrashed)
                                <button type="button" data-id="{{ $gallery->id }}"
                                    class="restoreGallery btn btn-success shadow btn-xs sharp mr-1 btn-sm">

                                    <i class="fas fa-trash-restore"></i>

                                </button>

                                <button type="button" data-id="{{ $gallery->id }}"
                                    class="forceGallery btn btn-danger shadow btn-xs sharp btn-sm">

                                    <i class="fas fa-trash-alt"></i>

                                </button>
                            @else
                                <button type="button"
                                    class="editGallery btn btn-primary shadow btn-xs sharp mr-1 btn-sm"
                                    data-id="{{ $gallery->id }}">

                                    <i class="fas fa-pencil-alt"></i>

                                </button>

                                <button type="button" data-id="{{ $gallery->id }}"
                                    class="deleteGallery btn btn-danger shadow btn-xs sharp btn-sm">

                                    <i class="fa fa-trash"></i>

                                </button>
                            @endif

                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>

    </div>
@else
    <div class="text-center py-5 text-muted">

        Không có dữ liệu media

    </div>

@endif
