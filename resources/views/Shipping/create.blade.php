@extends('admin.layout.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Shipping Management</h1>
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
            {{-- @include('admin.message') --}}
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
                                            <option value="{{$country->id}}"> {{$country->name}}</option>
                                            @endforeach
                                            <option value="rest_of_world">Rest of world</option>
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
                                    <input type="text" name="amount" id="amount" class="form-control">
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{route('categories.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th width="60">ID</th>
                                            <th width="60">Country Id</th>
                                            <th>Country Name</th>
                                            <th>Shipping Charges</th>
                                            <th width="100">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($shippingCharge->count()<0)
                                            <tr>
                                                <td colspan="5">
                                                    No record found.
                                                </td>
                                            </tr>
                                        @else
                                            @foreach ($shippingCharge as $shipping)
                                            <tr>
                                                <td>{{$shipping->id}}</td>
                                                <td>{{$shipping->country_id}}</td>
                                                <td>{{($shipping->country_id=='rest_of_world') ? 'Rest of the world' : $shipping->name}}</td>
                                                <td>${{$shipping->amount}}</td>
                                                <td>
                                                    <a href="{{ route('shipping.edit',$shipping->id) }}">
                                                        <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                        </svg>
                                                    </a>
                                                    <a href="javascript:void(0);" onclick="deleteRecord({{$shipping->id}})" class="text-danger w-4 h-4 mr-1">
                                                        <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                          </svg>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>

                        </div>
                    </div>
                </div>
            </div>
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
        url: '{{ route("shipping.store") }}',
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

function deleteRecord($id){
    var url='{{route("shiping.destroy","ID")}}';
    var newUrl=url.replace("ID",$id);
    if(confirm("Are you sure you want to delete?")){
        $.ajax({
            url:newUrl,
            type:'delete',
            data:{},
            dataType:'json',
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status) {
                    window.location.href = '{{ route('shipping.create') }}';  // Fixed typo in 'windows' to 'window'
                }
            },

        });
    }
}
    </script>
@endsection


