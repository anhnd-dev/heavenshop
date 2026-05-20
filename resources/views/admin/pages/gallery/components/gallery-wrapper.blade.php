<div class="gallery-wrapper container-fluid site-width" style="display: none">

    <div class="row">

        <div class="col-12 align-self-center">

            <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">

                <div class="w-sm-100 mr-auto">

                    <h4 class="mb-0" id="gallery-product-title">
                        {{ __('admin.product.gallery') }}
                    </h4>

                </div>

            </div>

        </div>

    </div>

    <div class="row mb-5">

        <div class="col-12">

            <div class="card">

                <div class="card-header" style="display:flex;justify-content:space-between">

                    <div>

                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">

                            <i class="fas fa-align-justify"></i>

                        </button>

                        <div class="dropdown-menu p-0">

                            <a class="dropdown-item" href="javascript:void(0)" id="addGallery" data-toggle="modal"
                                data-target="#addGalleryModel">

                                <i class="far fa-plus-square"></i>

                                {{ __('admin.common.add') }}

                            </a>

                            <a class="dropdown-item" id="restoreGalleryAll" href="javascript:void(0)"
                                style="display:none">

                                <i class="fab fa-cloudversify"></i>

                                {{ __('admin.common.restore_all') }}

                            </a>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item text-red" id="deleteGalleryMultiple" href="javascript:void(0)">

                                <i class="fas fa-trash-alt"></i>

                                {{ __('admin.common.delete_multiple_temps') }}

                            </a>

                            <a class="dropdown-item text-red" id="forceDeleteGalleryMultiple" href="javascript:void(0)"
                                style="display:none">

                                <i class="fas fa-trash-alt"></i>

                                {{ __('admin.common.delete_many_permanently') }}

                            </a>

                        </div>

                    </div>

                    <div class="custom-control custom-checkbox custom-control-inline">

                        <input type="checkbox" class="custom-control-input" name="include_trashed"
                            id="includeGalleryCheckboxTrash">

                        <label class="custom-control-label" for="includeGalleryCheckboxTrash">

                            {{ __('admin.common.trash_log') }}

                        </label>

                    </div>

                </div>

                <div class="card-body" id="gallery-content">


                </div>

            </div>

        </div>

    </div>

</div>
