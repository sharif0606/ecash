@extends('layout.admin.admin_master')
@section('title', 'Edit Product')
@section('content')
<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
          <div class="col-12">
            <h2 class="content-header-title float-start mb-0">Product Edit</h2>
            <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route(currentUser().'Dashboard')}}">{{ currentUser() }}</a></li>
				<li class="breadcrumb-item"><a href="#">Product</a></li>
				<li class="breadcrumb-item active">Edit</li>
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

	<div class="card">
	<!--begin::Form-->
	 <form class="form" action="{{ route(currentUser().'.updateProduct') }}" method="POST" enctype="multipart/form-data">
		@csrf
		<input type="hidden" value="{{Session::get('user')}}" name="userId">
		<input type="hidden" value="{{encryptor('encrypt', $data->id)}}" name="id">
		<div class="card-body">
            <div class="row form-group">
				<div class="col-md-4 mb-1">
					<label>Product Image:</label>
					<input type="file" name="thumbnail" class="form-control"/>
				</div>
                <div class="col-md-4 mb-1">
					<label> Brand Name: <span class="text-danger sup">*</span></label>
					<select name="brand" class="select2 form-control custom-select @if($errors->has('brand')) {{ 'is-invalid' }} @endif" style="width: 100%; height:36px;">
						@if(count($allBrand) > 0)
    						@foreach($allBrand as $brand)
    						    <option value="{{ $brand->id }}" @if($brand->id==$data->brandId) selected @endif>{{ $brand->name }}</option>
    						@endforeach
						@endif
					</select>
					@if($errors->has('brand'))
    					<small class="d-block text-danger mb-3">
    						{{ $errors->first('brand') }}
    					</small>
					@endif
                </div>
                <div class="col-md-4 mb-1">
					<label>Product Type / Category: <span class="text-danger sup">*</span></label>
					<select name="category" class="select2 form-control custom-select @if($errors->has('category')) {{ 'is-invalid' }} @endif" style="width: 100%; height:36px;">
						@if(count($allCategory) > 0)
    						@foreach($allCategory as $cat)
    						    <option value="{{ $cat->id }}" @if($cat->id==$data->categoryId) selected @endif>{{ $cat->name }}</option>
    						@endforeach
						@endif
					</select>
					@if($errors->has('category'))
    					<small class="d-block text-danger mb-3">
    						{{ $errors->first('category') }}
    					</small>
					@endif
                </div>
            </div>
			
			<div class="form-group row">
				<div class="col-lg-4 mb-1">
					<label> Name:  <span class="text-danger sup">*</span></label>
					<input type="text" name="name" value="{{ $data->name }}" class="form-control @if($errors->has('name')) {{ 'is-invalid' }} @endif" placeholder="Brand name" />
					@if($errors->has('name'))
					<small class="d-block text-danger mb-3">
						{{ $errors->first('name') }}
					</small>
					@endif
				</div>
				<div class="col-lg-4 mb-1">
					<label>Serial No: </label>
					<input type="text" name="serialNo" value="{{ $data->serialNo}}" class="form-control  @if($errors->has('serialNo')) {{ 'is-invalid' }} @endif" placeholder="Serial No" />
					@if($errors->has('serialNo'))
					<small class="d-block text-danger mb-3">
						{{ $errors->first('serialNo') }}
					</small>
					@endif
				</div>
				<div class="col-lg-4 mb-1">
					<label class="control-label">Status <span class="text-danger sup">*</span></label>
					<select name="status" class="form-control @if($errors->has('status')) {{ 'is-invalid' }} @endif">
						<option value="1"  @if(1==$data->status) selected @endif>Active</option>
						<option value="0" @if(0==$data->status) selected @endif>Inactive</option>
					</select>
					@if($errors->has('status'))
					<small class="d-block text-danger mb-3">
						{{ $errors->first('status') }}
					</small>
					@endif
				</div>
			</div>

			<div class="form-group row">
				<div class="col-lg-6 mb-1">
					<label> Short Description:  </label>
					<textarea name="shortDescription" class="form-control">{{ $data->shortDescription}}</textarea>
				</div>
				<div class="col-lg-6 mb-1">
					<label> Description:  </label>
					<textarea name="description" class="form-control">{{ $data->description}}</textarea>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-lg-4 mb-1">
					<label> Model Name:  </label>
					<input type="text" name="modelName" class="form-control" value="{{$data->modelName}}" />
				</div>
				<div class="col-lg-4 mb-1">
					<label> Model No:  </label>
					<input type="text" name="modelNo" class="form-control" value="{{$data->modelNo}}" />
				</div>
				<div class="col-lg-4 mb-1">
					<label> Warrenty:  </label>
					<input type="text" name="warrenty" class="form-control" value="{{$data->warrenty}}" />
				</div>
			</div>

		</div>
		<div class="card-footer">
			<div class="row">
				<div class="col-lg-4"></div>
				<div class="col-lg-8">
					<button type="submit" class="btn btn-primary mr-2">Submit</button>
					<button type="reset" class="btn btn-secondary">Cancel</button>
				</div>
			</div>
		</div>
	</form>
	<!--end::Form-->
</div>
<!--end::Card-->
@endsection

@push('scripts')
<script>
	var avatar1 = new KTImageInput('kt_image_1');
	$('.select2').select2();
</script>
@endpush