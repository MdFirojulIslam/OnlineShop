@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update Product</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('products.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <form action="" method="post" name="productFormUpdate" id="productFormUpdate">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="title">Title</label>
                                        <input type="text" name="title" value="{{ $product->title }}" id="title" class="form-control" placeholder="Title">
                                        <p class="errors"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="title">Slug</label>
                                        <input type="text" readonly name="slug" value="{{ $product->slug }}" id="slug" class="form-control" placeholder="Slug">
                                        <p class="errors"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description"> {{ !empty($product->description) ? $product->description : '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Media</h2>
                            <div id="image" class="dropzone dz-clickable">
                                <div class="dz-message needsclick">
                                    <br>Drop files here or click to upload.<br><br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="product-gallery">

                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Pricing</h2>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="price">Price</label>
                                        <input type="text" name="price" value="{{ $product->price }}" id="price" class="form-control" placeholder="Price">
                                        <p class="errors"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="compare_price">Compare at Price</label>
                                        <input type="text" name="compare_price" value="{{ $product->compare_price }}" id="compare_price" class="form-control" placeholder="Compare Price">
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
                                        <input type="text" name="sku" value="{{ $product->sku }}" id="sku" class="form-control" placeholder="sku">
                                        <p class="errors"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="barcode">Barcode</label>
                                        <input type="text" name="barcode" value="{{ $product->barcode }}" id="barcode" class="form-control" placeholder="Barcode">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="hidden" name="track_qty" value="No">
                                            <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" value="Yes" checked>
                                            <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                            <p class="errors"></p>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <input type="number" min="0" name="qty" value="{{ $product->qty }}" id="qty" class="form-control" placeholder="Qty">
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
                                    <option {{ ($product->status == '1') ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ ($product->status == '0') ? 'selected' : '' }} value="0">Block</option>
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
                                    <option value="">--Select a Category--</option>
                                    @if($categories->isNotEmpty())
                                    @foreach($categories as $category)
                                    <option {{($product->category_id==$category->id) ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <p class="errors"></p>
                            </div>
                            <div class="mb-3">
                                <label for="category">Sub category</label>
                                <select name="sub_category" id="sub_category" class="form-control">
                                    <option value="">--Select a Sub Category--</option>
                                    @if($subCategories->isNotEmpty())
                                    @foreach($subCategories as $subCategory)
                                    <option {{($product->sub_category_id==$subCategory->id) ? 'selected' : '' }} value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Product brand</h2>
                            <div class="mb-3">
                                <select name="brand" id="brand" class="form-control">
                                    <option value="">--Select a Brand--</option>
                                    @if($brands->isNotEmpty())
                                    @foreach($brands as $brand)
                                    <option {{($product->brand_id==$brand->id) ? 'selected' : '' }} value="{{ $brand->id }}">{{ $brand->name }}</option>
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
                                <p class="errors"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="products.html" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </div>
    </form>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script>
    $("#title").change(function() {
        element = $(this);
        $("button[type=submit]").prop('disabled', true);
        $.ajax({
            url: '{{ route("getSlug") }}',
            type: 'get',
            data: {
                title: element.val()
            },
            dataType: 'json',
            success: function(response) {
                $("button[type=submit]").prop('disabled', false);

                if (response["status"] == true) {
                    $("#slug").val(response["slug"])
                }
            }
        });
    });

    $("#productFormUpdate").submit(function(event) {
        event.preventDefault();
        var element = $(this);
        $("button[type=submit]").prop("disabled", true);

        $.ajax({
            url: "{{ route('products.update', $product->id) }}",
            type: "PUT",
            data: element.serializeArray(),
            dataType: "json",
            success: function(response) {
                $("button[type=submit]").prop("disabled", false);

                if (response.status === true) {
                    window.location.href = "{{ route('products.index') }}";
                } else {
                    if (response.notFound === true) {
                        window.location.href = "{{ route('products.index') }}";
                    }

                    var errors = response.errors;

                    // Clear previous errors
                    $(".error").removeClass("invalid-feedback").html("");
                    $("input, select").removeClass("is-invalid");

                    // Apply validation errors dynamically
                    $.each(errors, function(key, value) {
                        $(`#${key}`).addClass("is-invalid")
                            .siblings("p")
                            .addClass("invalid-feedback")
                            .html(value);
                    });
                }
            },
            error: function(jqXHR, exception) {
                console.log("Something went wrong");
            }
        });
    });




    $("#category").change(function() {
        var category_id = $(this).val();
        $.ajax({
            url: '{{ route("product_subcategories.index") }}',
            type: 'get',
            data: {
                category_id: category_id
            },
            dataType: 'json',
            success: function(response) {
                $("#sub_category").find("option").not(":first").remove();
                $.each(response["subCategories"], function(key, item) {
                    $("#sub_category").append(`<option value='${item.id}'>${item.name}</option>`)
                });
            },
            error: function() {
                console.log("Something went wrong");
            }
        });
    });

    Dropzone.autoDiscover = false;
    const dropzone = $("#image").dropzone({

        url: "{{ route('temp-images.create') }}",
        maxFiles: 10,
        paramName: 'image',
        addRemoveLinks: true,
        acceptedFiles: "image/jpeg,image/png,image/gif",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(file, response) {
            //$("#image_id").val(response.image_id);
            //console.log(response)
            var html = `<div class="col-md-3" id = "image-row-${response.image_id}" ><div class="card">
                <input type="hidden" name="image_array[]" value="${response.image_id}">
                <img src="${response.ImagePath}" class="card-img-top" alt="...">
                <div class="card-body">
                    <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger">Delete</a>
                </div>
            </div></div>`
            $("#product-gallery").append(html);
        },
        complete: function(file) {
            this.removeFile(file);
        }
    });

    function deleteImage(id) {
        $("#image-row" + id).remove();
    }
</script>
@endsection