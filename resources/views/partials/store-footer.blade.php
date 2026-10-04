@include('partials.store-footer-content')
@include('partials.chat-widget')
@auth
    @include('partials.cart-sync')
@endauth
