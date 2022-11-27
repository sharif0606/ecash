@extends('layout.admin.admin_master')
@section('title', 'Add New Branch')
@section('content')
<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
          <div class="col-12">
            <h2 class="content-header-title float-start mb-0">Branch Add</h2>
            <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route(currentUser().'Dashboard')}}">{{ currentUser() }}</a></li>
				<li class="breadcrumb-item"><a href="#">Branch</a></li>
				<li class="breadcrumb-item active">Add New</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <div class="content-header-right col-md-3 col-6 mb-2">
	  </div>
    </div>
	<!--begin::Notice-->
	@if( Session::has('response') )
	<div class="alert alert-{{Session::get('response')['class']}} alert-dismissible fade show" role="alert">
		<div class="alert-body">
			{{Session::get('response')['message']}}
		</div>
		<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
	</div>
	@endif
	<!--end::Notice-->

    <!--begin::Card-->
    <div class="card card-custom gutter-b example example-compact">
        <!--begin::Form-->
        <form class="form" action="{{ route(currentUser().'.addNewBranch') }}" method="POST">
        @csrf
        <input type="hidden" value="{{ Session::get('user') }}" name="userId">
        <div class="card-body">
            <div class="form-group row">
                <div class="col-lg-4 mb-1">
                    <label>Branch Name: <span class="text-danger sup">*</span></label>
                    <input type="text" name="branch_name" value="{{ old('branch_name') }}" class="form-control @if($errors->has('branch_name')) {{ 'is-invalid' }} @endif" placeholder="Branch Name" />
                    @if($errors->has('branch_name'))
                        <small class="d-block text-danger mb-3">
                            {{ $errors->first('branch_name') }}
                        </small>
                    @endif
                </div>
                <div class="col-lg-4 mb-1">
                    <label>Mobile No:</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number') }}" class="form-control" placeholder="Mobile Number" />
                </div>
                <div class="col-lg-4 mb-1">
                    <label>Email Address:</label>
                    <input type="text" name="branch_email" value="{{ old('branch_email') }}" class="form-control" placeholder="Email Address" />
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-12 mb-1">
                    <label>Address:</label>
                    <textarea name="branch_add" class="form-control">{{ old('branch_add') }}</textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-4 mb-1">
                    <label>Country:</label>
                    <input type="text" name="country" value="{{ old('country') }}" class="form-control" />
                </div>
                <div class="col-lg-4 mb-1">
                    <label>Division:  <span class="text-danger sup">*</span></label>
                    <select name="state_id"
                        class="form-control select2" style="width: 100%; height:36px;">
                        @if(count($allState) > 0)
                            @foreach($allState as $state)
                                <option value="{{ $state->id }}">{{ $state->code.'-'. $state->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-lg-4 mb-1">
                    <label>District:  <span class="text-danger sup">*</span></label>
                    <select name="zone_id" class="form-control select2" style="width: 100%; height:36px;">
                        @if(count($allZone) > 0)
                            @foreach($allZone as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->code.'-'. $zone->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-lg-4">
                    <label class="control-label">Status: </label>
                    <select name="status"
                        class="form-control @if($errors->has('status')) {{ 'is-invalid' }} @endif">
                        <option value="1" >Active</option>
                        <option value="0" >Inactive</option>
                    </select>
                    @if($errors->has('status'))
                    <small class="d-block text-danger mb-3">
                        {{ $errors->first('status') }}
                    </small>
                    @endif
                </div>
            </div>
        </div>
        <!--end card-body-->
        <div class="card-footer">
            <div class="row">
                <div class="col-lg-4"></div>
                <div class="col-lg-8">
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <button type="reset" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </div>
        <!--end card-footer-->
        </form>    
        <!--end::Form-->
    </div>
    <!--end::Card-->
</div>					
@endsection