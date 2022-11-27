@extends('layout.admin.admin_master')
@section('title', 'Owner | Dashboard')
@push('styles')
    <style>
        .congratulation-medal {
            right: 0 !important;
        }
    </style>
@endpush
@section('content')
<div class="content-body">

            <!-- Dashboard Ecommerce Starts -->
            <section id="dashboard-ecommerce" class="home-dashboard">

                <div class="row match-height">


                    <!-- Medal Card -->
                    <div class="col-xl-4 col-md-6 col-12">
                        <a href="">
                            </a><div class="card card-congratulation-medal"><a href="">
                                <div class="card-body">
                                    <h3>{{ currentUser() }}</h3>
                                    <p class="card-text font-small-3"></p>
                                    <h5 class="mb-75 mt-2 pt-50">
                                        Shop ID: <span class=" font-weight-bold text-secondary ">{{$company->shopCode}}</span><br>
                                        Shop Name: <span class=" font-weight-bold text-secondary ">{{$company->company_name}}</span><br>
                                        Email: <span class=" font-weight-bold text-secondary ">{{ $UserData->email }}</span><br>
                                        Mobile: <span class=" font-weight-bold text-secondary ">{{ $UserData->mobileNumber }}</span><br>
                                    </h5>
                                </div>
                            </a>
                        </div>
                    </div>
                    <!--/ Medal Card -->

                    <!-- Medal Card -->
                    <div class="col-xl-4 col-md-6 col-12">
                            <div class="card card-congratulation-medal">
                                <div class="card-body">
                                    <h3>Products</h3>
                                    <p class="card-text font-small-3">All the Products you can easily findout from here..
                                    </p>
                                    <h5 class="mb-75 mt-2 pt-50">
                                        Total-Product : <span class=" font-weight-bold text-secondary ">{{$products[0]->pcount}}</span>
                                    </h5>

                                    <div class=" justify-content-between bottom-button">
                                        <a href="{{route(currentUser().'.allProduct')}}" class="btn btn-gradient-primary">View</a>
                                        <a href="{{route(currentUser().'.addNewProductForm')}}" class="btn btn-gradient-success">Create</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!--/ Medal Card -->















                </div>

            </section>
            <!-- Dashboard Ecommerce ends -->

        </div> 
@endsection
@push('scripts')
@endpush
