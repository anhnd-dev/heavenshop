<div class="card-body">

    <div class="table-responsive">

        <table id="{{ $tableId }}" class="display table dataTable table-striped table-bordered" style="width: 100%;">

            <thead>

                <tr>

                    {{-- Checkbox --}}
                    <th width="40">
                        <div class="form-check custom-checkbox">

                            <input type="checkbox" class="form-check-input" id="checkAll">

                            <label class="form-check-label" for="checkAll"></label>

                        </div>
                    </th>

                    {{-- Dynamic Columns --}}
                    @foreach ($columns as $column)
                        <th>{{ $column }}</th>
                    @endforeach

                </tr>

            </thead>

            <tbody></tbody>

        </table>

    </div>

</div>
