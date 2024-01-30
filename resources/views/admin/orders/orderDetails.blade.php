@extends('admin.layout.app')
@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Order: #{{$orders->id}}</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('orders.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">

                @include('admin.message')
                <div class="card">
                    <div class="card-header pt-3">
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                            <h1 class="h5 mb-3">Shipping Address</h1>
                            <address>
                                <strong>{{$orders->first_name}} {{$orders->last_name}}</strong><br>
                                {{$orders->address}}<br>
                                {{$orders->city}}, {{$orders->zip}} {{$orders->countryName}}<br>
                                Phone: {{$orders->Mobile}}<br>
                                Email: {{$orders->email}}
                            </address>
                            </div>



                            <div class="col-sm-4 invoice-col">
                                <b>Invoice #007612</b><br>
                                <br>
                                <b>Order ID:</b> {{$orders->id}}<br>
                                <b>Total:</b> ${{number_format($orders->grand_total,2)}}<br>
                                <b>Status:</b>
                                @if ($orders->status=='pending')
                                <span class="text-danger">Pending</span>
                                @elseif($orders->status=='shipped')
                                <span class="text-info">Shipped</span>
                                @elseif($orders->status=='cancelled')
                                <span class="text-danger">Cancelled</span>
                                @else
                                <span class="text-success">Delivered</span>
                                @endif
                                <br>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-3">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th width="100">Price</th>
                                    <th width="100">Qty</th>
                                    <th width="100">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($orderItems))
                                    @foreach ($orderItems as $item)
                                    <tr>
                                        <td>{{$item->name}}</td>
                                        <td>${{number_format($item->price,2)}}</td>
                                        <td>{{$item->qty}}</td>
                                        <td>${{number_format($item->total)}}</td>
                                    </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <th colspan="3" class="text-right">Subtotal:</th>
                                    <td>${{number_format($orders->subtotal,2)}}</td>
                                </tr>

                                <tr>
                                    <th colspan="3" class="text-right">Shipping:</th>
                                    <td>${{number_format($orders->shipping,2)}}</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-right">Discount Amount:{{(!empty($orders->coupon_code))?$orders->coupon_code:''}}</th>
                                    <td>${{number_format($orders->discount,2)}}</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-right">Grand Total:</th>
                                    <td>${{number_format($orders->grand_total,2)}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <form action="" method="post" name="changeOrderStatusForm" id="changeOrderStatusForm">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Order Status</h2>
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control">
                                    <option value="shipped" {{ ($orders->status == 'shipped') ? 'selected' : '' }}>Shipped</option>
                                    <option value="pending" {{ ($orders->status == 'pending') ? 'selected' : '' }}>Pending</option>
                                    <option value="delivered" {{ ($orders->status == 'delivered') ? 'selected' : '' }}>Delivered</option>
                                    <option value="delivered" {{ ($orders->status == 'cancelled') ? 'selected' : '' }}>cancelled</option>
                                    {{-- <option value="">Cancelled</option> --}}
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="">Shipped Date</label>
                                <input value="{{$orders->shipped_date}}" type="text" name="shipped_date" id="shipped_date" class="form-control">
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post" name="sendInvoiceEmail" id="sendInvoiceEmail">
                            @csrf
                            <h2 class="h4 mb-3">Send Inovice Email</h2>
                            <div class="mb-3">
                                <select name="userType" id="userType" class="form-control">
                                    <option value="customer">Customer</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <button  class="btn btn-primary">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
@endsection
@section('customejs')
    <script>
       $(document).ready(function(){
            $('#shipped_date').datetimepicker({
                format:'Y-m-d H:i:s',
            });
       });

       $('#changeOrderStatusForm').submit(function(event){
            event.preventDefault();
            $.ajax({
                url:'{{route("order.changeOderStataus",$orders->id)}}',
                type:'post',
                data:$(this).serializeArray(),
                dataType:'json',
                success:function(response){
                    window.location.href='{{route("order.detail",$orders->id)}}'
                }
            });
       });
       $('#sendInvoiceEmail').submit(function(event){
            event.preventDefault();
            if(confirm("Are you sure you want to send email ?")){
                $.ajax({
                    url:'{{route("order.sendInvoiceEmail",$orders->id)}}',
                    type:'post',
                    data:$(this).serializeArray(),
                    dataType:'json',
                    success:function(response){
                        window.location.href='{{route("order.detail",$orders->id)}}'
                    }
                });
            }
       });

    </script>
@endsection
