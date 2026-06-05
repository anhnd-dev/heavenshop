<div class="card-header justify-content-between align-items-center d-flex">

    <div>
        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

            <i class="fas fa-align-justify"></i>
        </button>

        <div class="dropdown-menu p-0">

            <a class="dropdown-item" href="javascript:void(0)" id="{{ $addButtonId }}" data-toggle="modal"
                data-target="#{{ $modalId }}">

                <i class="far fa-plus-square"></i>
                {{ __('admin.action.add') }}
            </a>

            <a class="dropdown-item" id="restoreAll" href="javascript:void(0)" style="display: none">

                <i class="fab fa-cloudversify"></i>
                {{ __('admin.action.restore_all') }}
            </a>

            <div class="dropdown-divider"></div>

            <a class="dropdown-item text-red" id="deleteMultiple" href="javascript:void(0)">

                <i class="fas fa-trash-alt"></i>
                {{ __('admin.action.delete_temps') }}
            </a>

            <a class="dropdown-item text-red" id="forceDeleteMultiple" href="javascript:void(0)" style="display: none">

                <i class="fas fa-trash-alt"></i>
                {{ __('admin.action.delete_permanently') }}
            </a>

        </div>
    </div>

    <div class="custom-control custom-checkbox custom-control-inline">

        <input type="checkbox" class="custom-control-input" name="include_trashed" id="includeTrashedCheckbox">

        <label class="custom-control-label" for="includeTrashedCheckbox" style="padding-top: 2px">

            {{ __('admin.action.trash_record') }}
        </label>

    </div>

</div>
