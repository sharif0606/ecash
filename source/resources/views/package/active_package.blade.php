@extends('layout.admin.admin_master')

@section('title', 'Package List')

@section('content')
    @if( Session::has('response') )
    <div class="alert d-flex align-items-center justify-content-between alert-{{Session::get('response')['class']}}" role="alert">
        {{Session::get('response')['message']}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    <div class="page-wrapper">
        

        <!-- Page Content-->
        <div class="page-content">

            <div class="container-fluid">
                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-title-box">
                            <div class="float-right">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">{{ currentUser() }}</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Package</a></li>
                                    <li class="breadcrumb-item active">Active</li>
                                </ol><!--end breadcrumb-->
                            </div><!--end /div-->
                            <h4 class="page-title">Active Package List</h4>
                        </div><!--end page-title-box-->
                    </div><!--end col-->
                </div><!--end row-->
                <!-- end page title end breadcrumb -->
               
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>No Of Bill</th>
                                            <th>Activated  At</th>
                                            <th>Requested By</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($allPackages))
                                                @foreach($allPackages as $package)
                                                    <tr>
                                                        <td>{{$package->name}}</td>
                                                        <td>{{$package->price}}</td>
                                                        <td>{{$package->noofbill}}</td>
                                                        <td>{{ date('d M,Y h:iA',strtotime($package->updated_at)) }}</td>
                                                        <td> {{  $package->userName }} / {{  $package->mobileNumber }} </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table><!--end /table-->
                                </div><!--end /tableresponsive-->
                                <div class="d-flex align-items-center justify-content-between">
                                    {{$allPackages->links()}}
                                </div>

                             
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div> <!-- end col -->
                </div><!--end row-->    

            </div><!-- container -->
        </div>
    </div>
@endsection