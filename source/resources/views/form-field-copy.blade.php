@extends('layout.admin.admin_master')

@section('title', 'Add new user')
@section('content')
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
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Admin</a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">User</a></li>
                                        <li class="breadcrumb-item active">Add new user form</li>
                                    </ol><!--end breadcrumb-->
                                </div><!--end /div-->
                                <h4 class="page-title">Add new user form</h4>
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div><!--end row-->
                    <!-- end page title end breadcrumb -->

                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <div class="card">
                                <div class="card-body">        
                                <form class="form-parsley" action="{{route('addNewUser')}}" method="POST" enctype="multipart/form-data">

                                    <div class="form-group">
                                        <label class="mb-3">User Role</label>
                                        <select class="select2 form-control mb-3 custom-select" style="width: 100%; height:36px;">
                                            <option>Select role</option>
                                            <option value="CA">California</option>
                                            <option value="NV">Nevada</option>
                                            <option value="OR">Oregon</option>
                                            <option value="WA">Washington</option>
                                        </select>
                                    </div>
                                    {{-- <div class="form-group">
                                        <label class="mb-3">Multiple Select</label>
                                        <select class="select2 mb-3 select2-multiple" style="width: 100%" multiple="multiple" data-placeholder="Choose">
                                            <option value="CA">California</option>
                                            <option value="NV">Nevada</option>
                                            <option value="OR">Oregon</option>
                                            <option value="WA">Washington</option>
                                        </select> 
                                    </div> --}}

                                        <div class="form-group">
                                            <label>Required</label>
                                            <input type="text" class="form-control" required placeholder="Type something"/>
                                        </div><!--end form-group-->
    
                                        <div class="form-group">
                                            <label>Equal To</label>
                                            <input type="password" id="pass2" class="form-control" required
                                                        placeholder="Password"/>
                                            <div class="mt-2">
                                                <input type="password" class="form-control" required
                                                        data-parsley-equalto="#pass2"
                                                        placeholder="Re-Type Password"/>
                                            </div>
                                        </div><!--end form-group-->
    
                                        <div class="form-group">
                                            <label>E-Mail</label>
                                            <div>
                                                <input type="email" class="form-control" required
                                                        parsley-type="email" placeholder="Enter a valid e-mail"/>
                                            </div>
                                        </div><!--end form-group-->

                                        <div class="form-group">
                                            <label>URL</label>
                                            <input parsley-type="url" type="url" class="form-control"
                                                        required placeholder="URL"/>
                                        </div><!--end form-group-->

                                        <div class="form-group">
                                            <label>Digits</label>
                                            <input data-parsley-type="digits" type="text"
                                            class="form-control" required
                                            placeholder="Enter only digits"/>
                                        </div><!--end form-group-->

                                        <div class="form-group">
                                            <label>Number</label>
                                            <div>
                                                <input data-parsley-type="number" type="text"
                                                        class="form-control" required
                                                        placeholder="Enter only numbers"/>
                                            </div>
                                        </div><!--end form-group-->

                                        <div class="form-group">
                                            <label>Alphanumeric</label>
                                            <input data-parsley-type="alphanum" type="text"
                                                        class="form-control" required
                                                        placeholder="Enter alphanumeric value"/>
                                        </div><!--end form-group-->

                                        <div class="form-group">
                                            <label>Textarea</label>
                                            <textarea required class="form-control" rows="5"></textarea>
                                        </div><!--end form-group-->

                                        <div class="form-group mb-0">
                                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                Submit
                                            </button>
                                            <button type="reset" class="btn btn-danger waves-effect m-l-5">
                                                Cancel
                                            </button>
                                        </div><!--end form-group-->
                                    </form><!--end form-->        
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div> <!-- end col -->
                    </div> <!-- end row -->    

                </div><!-- container -->
            </div>
            <!-- end page content -->
            <footer class="footer text-center text-sm-left">
               <div class="boxed-footer">
                    &copy; 2019 Metrica <span class="text-muted d-none d-sm-inline-block float-right">Crafted with <i class="mdi mdi-heart text-danger"></i> by Mannatthemes</span>
               </div>
            </footer><!--end footer-->
        </div>
        <!-- end page-wrapper -->


    
@endsection