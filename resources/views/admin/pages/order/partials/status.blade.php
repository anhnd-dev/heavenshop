<div class="card order-card">

    <div class="card-header">

        Cập nhật trạng thái

    </div>

    <div class="card-body">

        <input type="hidden" id="order_id" value="{{ $order->id }}">

        <div class="form-group">

            <label>Trạng thái đơn hàng</label>

            <select id="order_status" class="form-control">

                @foreach ($order->availableOrderStatusOptions() as $value => $label)
                    <option value="{{ $value }}">
                        {{ $label }}
                    </option>
                @endforeach

            </select>

        </div>

        <button class="btn btn-primary btn-block" id="updateOrderBtn">
            Cập nhật trạng thái đơn
        </button>

        <hr>

        <div class="form-group">

            <label>Trạng thái thanh toán</label>

            <select id="payment_status" class="form-control">

                @foreach ($order->availablePaymentStatusOptions() as $value => $label)
                    <option value="{{ $value }}">

                        {{ $label }}

                    </option>
                @endforeach

            </select>

        </div>

        <button class="btn btn-success btn-block" id="updatePaymentBtn">
            Cập nhật thanh toán
        </button>


    </div>

</div>
