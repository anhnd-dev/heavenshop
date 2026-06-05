<div class="card order-card mb-3">

    <div class="card-header">

        Khách hàng

    </div>

    <div class="card-body">

        <div class="d-flex">

            <div class="customer-avatar">

                {{ strtoupper(substr($order->shipping_name, 0, 1)) }}

            </div>

            <div class="ml-3">

                <h5 class="mb-1">

                    {{ $order->shipping_name }}

                </h5>

                <div>

                    {{ $order->shipping_phone }}

                </div>

                <div class="text-muted">

                    {{ $order->shipping_email }}

                </div>

            </div>

        </div>

    </div>

</div>
