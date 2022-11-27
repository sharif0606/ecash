@extends('layout.admin.admin_master')
@section('title', 'Add new company profile')
@section('content')
<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
          <div class="col-12">
            <h2 class="content-header-title float-start mb-0">Company Profile Add</h2>
            <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route(currentUser().'Dashboard')}}">{{ currentUser() }}</a></li>
				<li class="breadcrumb-item"><a href="#">Company Profile</a></li>
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
			<form enctype="multipart/form-data" class="form-parsley" action="{{ route('owner.addNewCompany') }}"
				method="POST" enctype="multipart/form-data">
				@csrf
				<input type="hidden" value="{{ Session::get('user') }}" name="userId">
				<div class="form-group row">
					<div class="col-md-3">
						<label class="mb-3">Shop Logo</label>
						<input type="file" name="company_logo" id="input-file-now"
							class="dropify @if($errors->has('company_logo')) {{ 'is-invalid' }} @endif" />
						@if($errors->has('company_logo'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('company_logo') }}
						</small>
						@endif
					</div>
					<div class="col-md-3">
						<label class="mb-3">Billing Seal</label>
						<input type="file" name="billing_seal" id="input-file-now" class="dropify" />
					</div>
					<div class="col-md-3">
						<label class="mb-3">Billing Signature</label>
						<input type="file" name="billing_signature" id="input-file-now" class="dropify" />
					</div>
					<div class="col-md-3">
						<label class="mb-3">Trade License</label>
						<input type="file" name="trade_l" id="input-file-now" class="dropify" />
					</div>
				</div>

				<div class="form-group">
					<label>Name</label>
					<input type="text" name="company_name" value="{{ old('company_name') }}"
						class="form-control @if($errors->has('company_name')) {{ 'is-invalid' }} @endif"
						placeholder="Company Name" />
					@if($errors->has('company_name'))
					<small class="d-block text-danger mb-3">
						{{ $errors->first('company_name') }}
					</small>
					@endif
				</div>

				<div class="form-group row">
					<div class="col-md-6">
						<label>Company Slogan</label>
						<input type="text" name="company_slogan" value="{{ old('company_slogan') }}"
							class="form-control" placeholder="Company Slogan" />
					</div>
					<div class="col-md-3">
						<label>Tax/Vat/GST</label>
						<input type="text" name="tax" value="{{ old('tax') }}"
							class="form-control @if($errors->has('tax')) {{ 'is-invalid' }} @endif"
							placeholder="Number of %" />
						@if($errors->has('tax'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('tax') }}
						</small>
						@endif
					</div>
					<div class="col-md-3">
						<label>TIN</label>
						<input type="text" name="tin" value="{{ old('tin') }}" class="form-control"
							placeholder="Tax Identification Number" />
					</div>
				</div>
				<!--end form-group-->

				<div class="form-group row">
					<div class="col-md-6">
						<label>Currency</label>
						<input type="text" name="currency" value="{{ old('currency') }}" class="form-control"
							placeholder="Currency" />
					</div>
					<div class="col-md-6">
						<label>Currency</label>
						<input type="text" name="currency_symble" value="{{ old('currency_symble') }}"
							class="form-control" placeholder="Currency Symble" />
					</div>
				</div>
				<!--end form-group-->

				<div class="form-group row">
					<div class="col-md-6">
						<label>Contact Number</label>
						<input type="text" name="contact_number" value="{{ old('contact_number') }}"
							class="form-control" placeholder="Company Contact Number" />
					</div>
					<div class="col-md-6">
						<label>Email Address</label>
						<input type="text" name="company_email" value="{{ old('company_email') }}" class="form-control"
							placeholder="Company Email Address" />
					</div>
				</div>
				<!--end form-group-->

				<div class="form-group row">
					<div class="col-md-6">
						<label>Address Line 1</label>
						<input type="text" name="company_add_a" value="{{ old('company_add_a') }}" class="form-control"
							placeholder="Company Address" />
					</div>
					<div class="col-md-6">
						<label>Address Line 2</label>
						<input type="text" name="company_add_b" value="{{ old('company_add_b') }}" class="form-control"
							placeholder="Company Address" />
					</div>
				</div>
				<!--end form-group-->
				<div class="form-group">
					<label>Billing Terms</label>
					<textarea type="text" id="description" name="billing_terms" class="form-control"
						onkeyup="countChar(this)">{{ old('billing_terms') }}</textarea>
				</div>

				<div class="form-group row">
					<div class="col-md-6">
						<label>Facebook</label>
						<input type="text" name="facebook" value="{{ old('facebook') }}" class="form-control"
							placeholder="Facebook Address" />
					</div>
					<div class="col-md-6">
						<label>Twitter</label>
						<input type="text" name="twitter" value="{{ old('twitter') }}" class="form-control"
							placeholder="Twitter Address" />
					</div>
				</div>
				<!--end form-group-->
				<div class="form-group row">
					<div class="col-md-6">
						<label>Webiste</label>
						<input type="text" name="webiste" value="{{ old('webiste') }}" class="form-control"
							placeholder="Webiste Address" />
					</div>
					<div class="col-md-6">
						<label class="control-label">Status</label>
						<select name="status"
							class="form-control @if($errors->has('status')) {{ 'is-invalid' }} @endif">
							<option value="1" selected>Active</option>
							<option value="0">Inactive</option>
						</select>
						@if($errors->has('status'))
						<small class="d-block text-danger mb-3">
							{{ $errors->first('status') }}
						</small>
						@endif
					</div>
				</div>
				<!--end form-group-->

				<div class="form-group row">
					<div class="col-md-12">
						<label>Invoice Design</label><br>
						@if(invoice())
						@foreach(invoice() as $key => $inv)
						<label>
							<input type="radio" name="invoice" @if($key==2) {{'checked'}} @endif
								value="{{ $inv['link'] }}">
							<img src="{{asset("storage/images/company/invoice/".$inv['image'])}}" width="200"
								height="300">
						</label>
						@endforeach
						@endif
					</div>
				</div>
				<!--end form-group-->

				<div class="form-group mb-0">
					<button type="submit" class="btn btn-primary waves-effect waves-light">
						Submit
					</button>
					<button type="reset" class="btn btn-danger waves-effect m-l-5">
						Cancel
					</button>
				</div>
				<!--end form-group-->
			</form>
			<!--end form-->
	</div>
</div>
@endsection

@push('scripts')
<script>
	function countChar(val) {
        var len = val.value.length;
        if (len >= 550) {
          val.value = val.value.substring(0, 550);
        } else {
          $('#charNum').text(550 - len);
        }
      };
</script>
@endpush