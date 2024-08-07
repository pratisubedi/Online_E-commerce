@extends('Front.layout.app')
@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="#">Shop</a></li>
                <li class="breadcrumb-item">Checkout</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-9 pt-4">
    <div class="container">
        <form  id="orderForm" name="orderForm">
        {{-- <form  action="{{route('Front.processCheckout')}}" method="post"> --}}
            @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="sub-title">
                            <h2>Shipping Address</h2>
                        </div>
                        <div class="card shadow-lg border-0">
                            <div class="card-body checkout-form">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" value="{{(!empty($customerAddress)) ? $customerAddress->first_name : ''}}">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" value="{{(!empty($customerAddress)) ? $customerAddress->last_name : ''}}">
                                            <p></p>
                                        </div>
                                        <p></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="email" id="email" class="form-control" placeholder="Email" value="{{(!empty($customerAddress)) ? $customerAddress->email : ''}}">
                                            <p></p>
                                        </div>
                                        <p></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <select name="country" id="country" class="form-control">
                                                <option value="">Select a Country</option>
                                                @if(!empty($countries))
                                                @foreach ($countries as $country)
                                                    <option {{(!empty($customerAddress) && $customerAddress->country_id==$country->id) ? 'selected' : ''}} value="{{$country->id}}">{{$country->name}}</option>
                                                @endforeach
                                                <option value="rest_of_world">Rest of world</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" class="form-control">{{(!empty($customerAddress)) ? $customerAddress->address : ''}}</textarea>
                                            <p></p>
                                        </div>
                                        <p></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="appartment" id="appartment" class="form-control" placeholder="Apartment, suite, unit, etc. (optional)" value="{{(!empty($customerAddress)) ? $customerAddress->Appartment : ''}}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="city" id="city" class="form-control" placeholder="City" value="{{(!empty($customerAddress)) ? $customerAddress->city : ''}}">
                                            <p></p>
                                        </div>
                                        <p></p>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="state" id="state" class="form-control" placeholder="State" value="{{(!empty($customerAddress)) ? $customerAddress->state : ''}}">
                                            <p></p>
                                        </div>
                                        <p></p>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="zip" id="zip" class="form-control" placeholder="Zip" value="{{(!empty($customerAddress)) ? $customerAddress->zip : ''}}">
                                            <p></p>
                                        </div>
                                        <p></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile No." value="{{(!empty($customerAddress)) ? $customerAddress->Mobile: ''}}">
                                            <p></p>
                                        </div>
                                        <p></p>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Order Notes (optional)" class="form-control"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="sub-title">
                            <h2>Order Summery</h3>
                        </div>
                        <div class="card cart-summery">
                            <div class="card-body">
                                @foreach (Cart::content() as $item)
                                    <div class="d-flex justify-content-between pb-2">
                                        <div class="h6">{{$item->name}} X {{$item->qty}}</div>
                                        <div class="h6">${{$item->price}}</div>
                                        <div class="h6">${{$item->qty*$item->price}}</div>
                                    </div>
                                @endforeach
                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Subtotal</strong></div>
                                    <div class="h6"><strong>${{Cart::subtotal()}}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="h6"><strong>Discount Amount</strong></div>
                                    <div class="h6"><strong id="discountAmount">${{number_format($discount,2)}}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="h6"><strong>Shipping</strong></div>
                                    <div class="h6"><strong name="shippingAmount" id="shippingAmount">${{number_format($totalShippingCharges,2)}}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2 summery-end">
                                    <div class="h5"><strong>Total</strong></div>
                                    <div class="h5"><strong id="grandTotal">${{number_format($grandTotal,2)}}</strong></div>
                                </div>
                            </div>
                        </div>
                        <div class="input-group apply-coupan mt-4">
                            <input type="text" placeholder="Coupon Code" class="form-control" name="discount_code" id="discount_code" value="{{ old('discount_code') }}">
                            <button class="btn btn-dark" type="button" id="apply_discount">Apply Coupon</button>
                            <p></p>
                        </div>
                        <div id="discount-response-wrapper">
                            @if (Session::has('code'))
                                <div class="mt-4">
                                    <strong>{{Session::get('code')->code}}</strong>
                                    <a  class="btn btn-sm btn-danger" id="remove_discount"><i class="fa fa-times"></i></a>
                                </div>
                             @endif
                        </div>
                        <div class="card payment-form ">
                            <h3 class="card-title h5 mb-3">Payment Method</h3>
                            <div class="">
                                <input checked type="radio" name="payment_method" value="cod" id="payment_method_one">
                                <label for="payment_method_one" class="form-check-label">Cash on Delivery</label>
                            </div>
                            <div class="mt-3">
                                <button id="payment-button">Pay with Khalti</button>
                            </div>
                            <div class="card-body p-0 d-none mt-3" id="card-payment-form">
                                <div class="mb-3">
                                    <label for="card_number" class="mb-2">Card Number</label>
                                    <input type="text" name="card_number" id="card_number" placeholder="Valid Card Number" class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">Expiry Date</label>
                                        <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">CVV Code</label>
                                        <input type="text" name="expiry_date" id="expiry_date" placeholder="123" class="form-control">
                                    </div>
                                </div>

                            </div>
                            <div class="pt-4">
                                {{-- <a href="#" class="btn-dark btn btn-block w-100">Pay Now</a> --}}
                                <button type="submit" class="btn-dark btn btn-block w-100">Pay Now</button>
                            </div>
                        </div>


                        <!-- CREDIT CARD FORM ENDS HERE -->

                    </div>
                </div>
        </form>
    </div>
</section>
@endsection
@section('customJs')
<script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
<script>
    var config = {
        // replace the publicKey with yours
        "publicKey": "test_public_key_462ec6c4685d43ce8952b2f08b3e826f",
        "productIdentity": "1234567890",
        "productName": "Dragon",
        "productUrl": "http://gameofthrones.wikia.com/wiki/Dragons",
        "paymentPreference": [
            "KHALTI",
            "EBANKING",
            "MOBILE_BANKING",
            "CONNECT_IPS",
            "SCT",
            ],
        "eventHandler": {
            onSuccess (payload) {
                // hit merchant api for initiating verfication
                console.log(payload);
            },
            onError (error) {
                console.log(error);
            },
            onClose () {
                console.log('widget is closing');
            }
        }
    };

    var checkout = new KhaltiCheckout(config);
    var btn = document.getElementById("payment-button");
    btn.onclick = function () {
        // minimum transaction amount must be 10, i.e 1000 in paisa.
        checkout.show({amount: 1000});
    }
</script>
    <script type="text/javascript">

    //discoount coupons apply
        $("#apply_discount").click(function(){
            $.ajax({
                url:'{{route("front.applyDiscount")}}',
                data:{code:$("#discount_code").val(),country_id:$("#country").val()},
                dataType:'json',
                type:'post',
                success:function(response){
                    if(response.status==true){
                        $('#shippingAmount').html('$'+response.totalShippingCharges);
                        $('#grandTotal').html('$'+response.grandTotal);
                        $('#discountAmount').html('$'+response.discount);
                    }
                    else{
                        $("#discount-response-wrapper").html("<span class='text-danger'>"+response.message+"</span>")
                    }
                },
            });
        });
        $("#remove_discount").click(function(){
            $.ajax({
                url:'{{route("front.remove-coupons")}}',
                data:{country_id:$("#country").val()},
                dataType:'json',
                type:'post',
                success:function(response){
                    if(response.status==true){
                        $('#shippingAmount').html('$'+response.totalShippingCharges);
                        $('#grandTotal').html('$'+response.grandTotal);
                        $('#discountAmount').html('$'+response.discount);
                    }
                    else{
                        $("#discount-response-wrapper").html("<span class='text-danger'>"+response.message+"</span>")
                        // $("#discount_code").addClass('is-discount_code')
                        // .siblings('p')
                        // .addClass('invalid-feedback').html(message);
                    }
                },
            });
        });


        $('#payment_method_one').click(function(){
            if($(this).is(":checked")==true){
                $('#card-payment-form').addClass('d-none');
            }
        });
        $('#payment_method_two').click(function(){
            if($(this).is(":checked")==true){
                $('#card-payment-form').removeClass('d-none');
            }
        });
function handleFormErrors(errors) {
    if (errors['first_name']) {
        $("#first_name").addClass('is-invalid')
            .siblings('p')
            .addClass('invalid-feedback').html(errors['first_name']);
    } else {
        $("#first_name").removeClass('is-invalid')
            .siblings('p')
            .removeClass('invalid-feedback').html('');
    }

    if (errors['email']) {
        $("#email").addClass('is-invalid')
            .siblings('p')
            .addClass('invalid-feedback').html(errors['email']);
    } else {
        $("#email").removeClass('is-invalid')
            .siblings('p')
            .removeClass('invalid-feedback').html('');
    }

    if (errors['last_name']) {
        $("#last_name").addClass('is-invalid')
            .siblings('p')
            .addClass('invalid-feedback').html(errors['last_name']);
    } else {
        $("#last_name").removeClass('is-invalid')
            .siblings('p')
            .removeClass('invalid-feedback').html('');
    }
    if (errors['address']) {
        $("#address").addClass('is-invalid')
            .siblings('p')
            .addClass('invalid-feedback').html(errors['address']);
    } else {
        $("#address").removeClass('is-invalid')
            .siblings('p')
            .removeClass('invalid-feedback').html('');
    }
    if (errors['city']) {
        $("#city").addClass('is-invalid')
            .siblings('p')
            .addClass('invalid-feedback').html(errors['city']);
    } else {
        $("#city").removeClass('is-invalid')
            .siblings('p')
            .removeClass('invalid-feedback').html('');
    }
    if (errors['state']) {
        $("#state").addClass('is-invalid')
            .siblings('p')
            .addClass('invalid-feedback').html(errors['state']);
    } else {
        $("#state").removeClass('is-invalid')
            .siblings('p')
            .removeClass('invalid-feedback').html('');
    }
    if (errors['zip']) {
        $("#zip").addClass('is-invalid')
            .siblings('p')
            .addClass('invalid-feedback').html(errors['zip']);
    } else {
        $("#zip").removeClass('is-invalid')
            .siblings('p')
            .removeClass('invalid-feedback').html('');
    }
    if (errors['mobile']) {
        $("#mobile").addClass('is-invalid')
            .siblings('p')
            .addClass('invalid-feedback').html(errors['mobile']);
    } else {
        $("#mobile").removeClass('is-invalid')
            .siblings('p')
            .removeClass('invalid-feedback').html('');
    }



}

        $('#orderForm').submit(function(event){
            event.preventDefault();
            $.ajax({
                url: '{{ route("Front.processCheckout") }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
            console.log('Response:', response);

            if (response && response.hasOwnProperty('status')) {
                if (response.status === true) {
                    var success = response.success;
                    console.log('Registration successful:', success);
                    // Redirect or perform other actions on success
                     window.location.href = "{{url('/thank/')}}/"+response.orderId;
                }

                var errors = response['errors'];
                if (errors) {
                    handleFormErrors(errors);
                }
            } else {
                console.error('Unexpected response structure:', response);
            }
        },
        error: function(jqXHR, exception) {
            console.error("AJAX Error: ", jqXHR, exception);
        }
            });
        });
        // change the value  of tota and shipping based on country choose
        $('#country').change(function(){
            $.ajax({
                url:'{{route("shipping.getOrderSummary")}}',
                data:{country_id:$(this).val()},
                dataType:'json',
                type:'post',
                success:function(response){
                    $('#shippingAmount').html('$'+response.totalShippingCharges);
                    $('#grandTotal').html('$'+response.grandTotal);
                },
            });
        });
    </script>

@endsection
