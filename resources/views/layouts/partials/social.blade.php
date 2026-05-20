<div class="sticky-wrap z-index-1 d-none d-xl-inline-block" data-animation-delay="100" data-shadow-animation="true">
    <div class="elements-social social-icon-style-10">
        <ul class="fs-16">
            @if ($socialIcons)
                @foreach ($socialIcons as $socialIcon)
                    @php
                        $decoded_data = json_decode($socialIcon->data_value);
                    @endphp

                    @if ($decoded_data)
                        <li class="me-30px">
                            <a class="{{ strtolower($decoded_data->title) }}" href="{{ $decoded_data->url }}"
                                target="_blank">
                                {!! $decoded_data->social_icon !!}
                                <span class="alt-font ms-10px">Facebook</span>
                            </a>
                        </li>
                    @endif
                @endforeach
            @endif
        </ul>
    </div>
</div>
