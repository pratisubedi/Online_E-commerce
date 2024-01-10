@extends('admin.layout.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit shipping </h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('categories.index')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    @include('/admin.message')
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            @include('admin.message')
            <form  id="shippingForm" name="shippingForm" method="POST">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <select name="country" id="country" class="form-control">
                                        <option value=""> Select a country</option>
                                        @if(!empty($countries))
                                            @foreach ($countries as $country)
                                            <option {{($shippingCharge->country_id==$country->id)?'selected':''}} value="{{$country->id}}"> {{$country->name}}</option>
                                            @endforeach
                                            <option {{($shippingCharge->country_id=='rest_of_world')?'selected':''}} value="rest_of_world">Rest of world</option>
                                        @endif
                                    </select>
                                    <p></p>
                                    @error('name')
                                        <div class="d-block text-danger invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount">Amount</label>
                                    <input value="{{$shippingCharge->amount}}" type="text" name="amount" id="amount" class="form-control">
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">update</button>
                    <a href="{{route('shipping.create')}}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
@section('customejs')
    <script type="text/javascript">
       $('#shippingForm').submit(function(event) {
    event.preventDefault();
    $.ajax({
        url: '{{ route("shipping.update",$shippingCharge->id) }}',
        type: 'post',
        data: $(this).serializeArray(),
        dataType: 'json',
        success: function(response) {
            if (response.status == true) {
             window.location.href = '{{ route("shipping.create") }}';
            } else {
                var errors = response.message;
                if (errors['amount']) {
                    $('#amount').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors['amount']);
                } else {
                    $('#amount').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html("");
                }
            }
        },
        error: function(error) {

        }
    });
});

    </script>
@endsection


