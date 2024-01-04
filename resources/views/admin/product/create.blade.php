@extends('admin.layout.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="products.html" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    @include('/admin.message')
    <section class="content">
        <!-- Default box productForm -->
        <form action="{{route('products.store')}}" id="" name="productForm" method="POST">
        {{-- <form  id="productForm" name="productForm" method="POST"> --}}
            @csrf
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title" class="form-control" placeholder="Title">
                                            <p class="errors"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="slug">Slug</label>
                                            <input readonly type="text" name="slug" id="slug" class="form-control" placeholder="slug">
                                            <p class="errors"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Short Description</label>
                                            <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote" placeholder="Description" ></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Shipping and Returns</label>
                                            <textarea name="shipping_return" id="shipping_return" cols="30" rows="10" class="summernote" placeholder="Description" ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Media</h2>
                                <div id="image" name="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">
                                        <br>Drop files here or click to upload.<br><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row " id="product-gallery">

                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Pricing</h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="price">Price</label>
                                            <input type="text" name="price" id="price" class="form-control" placeholder="Price">
                                            <p class="errors"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="compare_price">Compare at Price</label>
                                            <input type="text" name="compare_price" id="compare_price" class="form-control" placeholder="Compare Price">
                                            <p class="text-muted mt-3">
                                                To show a reduced price, move the product’s original price into Compare at price. Enter a lower value into Price.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Inventory</h2>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sku">SKU (Stock Keeping Unit)</label>
                                            <input type="text" name="sku" id="sku" class="form-control" placeholder="sku">
                                            <p class="errors"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox">
                                                {{-- <input type="hidden" name="track_qty" value="No"> --}}
                                                <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" value="Yes" checked>
                                                <p class="errors"></p>
                                                <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty">
                                            <p class="errors"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product status</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Block</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h2 class="h4  mb-3">Product category</h2>
                                <div class="mb-3">
                                    <label for="category">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select a Category</option>
                                        @if ($categories->isNotEmpty())
                                            @foreach($categories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="category">Sub category</label>
                                    <select name="sub_category" id="sub_category" class="form-control">

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product brand</h2>
                                <div class="mb-3">
                                    <select name="brand" id="brand" class="form-control">
                                        <option value="">Select a brand</option>
                                        @if ($brands->isNotEmpty())
                                        @foreach($brands as $brand)
                                            <option value="{{$brand->id}}">{{$brand->name}}</option>
                                        @endforeach
                                    @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Featured product</h2>
                                <div class="mb-3">
                                    <select name="is_featured" id="is_featured" class="form-control">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Related products</h2>
                                <div class="mb-3">
                                    <select multiple  class="related-product w-100" name="related_products[]" id="related_products">
                                    </select>
                                   <p class="error"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="products.html" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
        </form>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
@section('customejs')
<script>
    $('.related-product').select2({
    ajax: {
        url: '{{ route("product-getProducts") }}',
        dataType: 'json',
        tags: true,
        multiple: true,
        minimumInputLength: 3,
        processResults: function (data) {
            return {
                results: data.tags
            };
        }
    }
});
   Dropzone.autoDiscover = false; // Disable automatic discovery of Dropzone

        $(document).ready(function() {
        const dropzone = new Dropzone("#image", {
            url: "{{ route('temp-image.create') }}", // URL for image upload
            type: "post", // HTTP method for upload
            maxFiles: 2, // Maximum number of files allowed
            paramName: "image", // Name of the file input parameter
            addRemoveLinks: true, // Allow file removal
            acceptedFiles: "image/jpeg,image/png,image/gif", // Allowed file types
            headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            }, // CSRF token for security
            init: function() {
            // Limit the number of files to 5 and remove excess files
            this.on("addedfile", function(file) {
                if (this.files.length > this.options.maxFiles) {
                this.removeFile(file);
                alert("Maximum number of files reached.");
                }
            });
            },
            success: function(file, response) {
            // Update product gallery with uploaded image information
            const html = `
                <div class="col-md-3">
                    <div class="card">
                        <input  name="image_array[]" value="${response.image_id}">
                        <img src="${response.ImagePath}" class="card-img-top" alt="">
                        <div class="card-body">
                            <a href="#" class="btn btn-danger">Delete</a>
                        </div>
                    </div>
                </div>`;

            $("#product-gallery").append(html);
            },
        });
        });
    $("#category").change(function(){
        var category_id = $(this).val();
        $.ajax({
            url: '{{ route("product-subcategories.index") }}',
            type: 'get',
            data: { category_id: category_id },
            dataType: 'json',
            success: function(response){
                if(response.status == true){
                    var subCategories = response.subCategories;
                    var optionsHtml = '<option value="">Select a Sub Category</option>';

                    if(subCategories.length > 0){
                        $.each(subCategories, function(index, subCategory){
                            optionsHtml += '<option value="' + subCategory.id + '">' + subCategory.name + '</option>';
                        });
                    }

                    $("#sub_category").html(optionsHtml);
                }
                console.log(response);
            },
            error: function(){
                console.log('Something Went Wrong');
            }
        });
    })
    $("#productForm").submit(function(event) {
            event.preventDefault();
            var element = $(this);
            $.ajax({
                url: '{{ route("products.store") }}', // Update URL if needed
                type: 'post',
                data: element.serializeArray(),
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                success: function(response) {

                    if(response["status"]==true){
                        window.location.href="{{route('products.create')}}"
                        //window.location.href="{{route('categories.index')}}";
                    }else{
                        var errors = response['errors'];
                        $(".errors").removeClass('invalid-feedback').html('');
                        $("input[type='text'],select,input[type='number']").removeClass('is-invalid');
                        $.each(errors,function(key,value){
                            $(`#${key}`).addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(value);
                        });
                    }
                },
                error: function(jqXHR, exception) {
                        console.log("AJAX Error: ", jqXHR, exception);
                    }

            });
        });
    $('#title').change(function(){
            element=$(this);
            $.ajax({
                url:'{{route("getSlug")}}',
                type:'get',
                data:{title: element.val()},
                dataType:'json',
                success:function(response){
                    if(response["status"]==true){
                        $("#slug").val(response["slug"]);
                    }
                }
            });
       });

</script>
@endsection
