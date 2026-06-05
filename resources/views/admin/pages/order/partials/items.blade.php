<div class="card order-card">

    <div class="card-header">

        Sản phẩm đã đặt

    </div>

    <div class="card-body p-0">

        <table class="table mb-0">

            <thead>

                <tr>

                    <th>Sản phẩm</th>
                    <th>Biến thể</th>
                    <th>Đơn giá</th>
                    <th>SL</th>
                    <th>Tổng</th>

                </tr>

            </thead>

            <tbody>

                @foreach ($order->items as $item)
                    <tr>

                        <td>

                            <div class="d-flex align-items-center">

                                <img src="{{ asset('uploads/variant/' . $item->product_image) }}"
                                    class="product-thumb mr-3">

                                <div>

                                    <div class="font-weight-bold">

                                        {{ $item->product_name }}

                                    </div>

                                    <small class="text-muted">

                                        SKU:
                                        {{ $item->product_sku }}

                                    </small>

                                </div>

                            </div>

                        </td>

                        <td>

                            {{ $item->color_name }}
                            -
                            {{ $item->size_name }}

                        </td>

                        <td>

                            {{ number_format($item->final_price) }}

                        </td>

                        <td>

                            {{ $item->quantity }}

                        </td>

                        <td>

                            <strong>

                                {{ number_format($item->total) }}

                            </strong>

                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>

        <div class="border-top p-4">

            <div class="row">

                <div class="col-md-6 offset-md-6">

                    <div class="summary-row">

                        <span>Tạm tính</span>

                        <strong>

                            {{ number_format($order->subtotal) }}

                        </strong>

                    </div>

                    <div class="summary-row">

                        <span>Giảm giá</span>

                        <span class="text-danger">

                            -{{ number_format($order->discount_amount) }}

                        </span>

                    </div>

                    <div class="summary-row">

                        <span>Phí ship</span>

                        <strong>

                            {{ number_format($order->shipping_fee) }}

                        </strong>

                    </div>

                    <hr>

                    <div class="summary-row">

                        <span>Tổng cộng</span>

                        <span class="summary-total">

                            {{ number_format($order->grand_total) }}

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
