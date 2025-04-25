@extends('admin.layouts.app')

@section('content')

@php

$productCounts = getProductsCount();
$categoriesCounts = getCategoriesCount();
$subCategorieCounts = getSubCategoriesCount();
$brandCounts = getBrandsCount();

@endphp

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard</h1>
            </div>
            <div class="col-sm-6">

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

            <div class="col-lg-4 col-6">
                <div class="small-box card">
                    <div class="inner">
                        <h4>Products</h4>
                        <p>Active - <b>{{ $productCounts['active'] }}</b></p>
                        <p>Inactive - <b>{{ $productCounts['inactive'] }}</b></p>
                        <p>Total Products - <b>{{ $productCounts['total'] }}</b></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{ route('products.index') }}" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box card">
                    <div class="inner">
                        <h4>Categories</h4>
                        <p>Active - <b>{{ $categoriesCounts['active'] }}</b></p>
                        <p>Inactive - <b>{{ $categoriesCounts['inactive'] }}</b></p>
                        <p>Total Categories - <b>{{ $categoriesCounts['total'] }}</b></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{ route('categories.index') }}" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box card">
                    <div class="inner">
                        <h4>Sub Categories</h4>
                        <p>Active - <b>{{ $subCategorieCounts['active'] }}</b></p>
                        <p>Inactive - <b>{{ $subCategorieCounts['inactive'] }}</b></p>
                        <p>Total Sub-Categories - <b>{{ $subCategorieCounts['total'] }}</b></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{ route('sub_categories.index') }}" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box card">
                    <div class="inner">
                        <h4> Brands </h4>
                        <p>Active - <b>{{ $brandCounts['active'] }}</b></p>
                        <p>Inactive - <b>{{ $brandCounts['inactive'] }}</b></p>
                        <p>Total Brands - <b>{{ $brandCounts['total'] }}</b></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{ route('brands.index') }}" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box card">
                    <div class="inner">
                        <h4>Orders</h4>
                        <h3>-</h3>
                        <p>Total Orders</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="#" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box card">
                    <div class="inner">
                        <h4>Customers</h4>
                        <h3>-</h3>
                        <p>Total Customers</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box card">
                    <div class="inner">
                        <h4>Sales</h4>
                        <h3>-</h3>
                        <p>Total Sale</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script>
    console.log("Hello")
</script>
@endsection