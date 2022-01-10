<?php $mollie = app('Webkul\Mollie\Payment\Molliepayment') ?>
<body data-gr-c-s-loaded="true" cz-shortcut-listen="true">
    You will be redirected to the Mollie website in a few seconds.
    @if(isset($mollie->preparePayment()['false']) && $mollie->preparePayment()['false'] == 'false')
        @if (empty($mollie->preparePayment()['error']))
            @php 
                $mollie->preparePayment()['error'] = trans('mollie::app.shop.went-wrong');
            @endphp
        @endif
            {{session()->flash('error',$mollie->preparePayment()['error'])}}
         <script type="text/javascript">
           window.location.href = "{{route('shop.checkout.cart.index')}}";
        </script>
    @else
        <form action="{{ $mollie->preparePayment() }}" id="mollie_checkout" method="POST">
            <input value="Click here if you are not redirected within 10 seconds..." type="submit">
        </form>
    @endif
    <script type="text/javascript">
        document.getElementById("mollie_checkout").submit();
    </script>
</body>