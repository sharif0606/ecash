@extends('layout.admin.admin_master')
@section('title', 'Company Profile')
@section('content')
<style>
	/* HIDE RADIO */
	[type=radio] {
		position: absolute;
		opacity: 0;
		width: 0;
		height: 0;
	}

	/* IMAGE STYLES */
	[type=radio]+img {
		cursor: pointer;
	}

	/* CHECKED STYLES */
	[type=radio]:checked+img {
		outline: 2px solid #0f0 !important;
	}
</style>
<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
          <div class="col-12">
            <h2 class="content-header-title float-start mb-0">Company</h2>
            <div class="breadcrumb-wrapper">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route(currentUser().'Dashboard')}}">{{ currentUser() }}</a></li>
				<li class="breadcrumb-item"><a href="#">Company</a></li>
              </ol>
            </div>
          </div>
        </div>
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

	<div class="card card-custom">
		<!---Begin card -->
		<div class="card card-custom gutter-b">
			<div class="card-body">
				<!--begin::Top-->
				<div class="d-flex">
					<!--begin::Pic-->
					<div class="flex-shrink-0 mr-7">
						<div class="symbol symbol-50 symbol-lg-120">
							<img alt="Pic" width="100px" src="{{asset("storage/images/company/$allCompany->company_logo")}}">
						</div>
					</div>
					<!--end::Pic-->
					<!--begin: Info-->
					<div class="flex-grow-1">
						<!--begin::Title-->
						<div class="d-flex align-items-center justify-content-between flex-wrap mt-2">
							<!--begin::User-->
							<div class="mr-3">
								<!--begin::Name-->
								<a href="#"
									class="d-flex align-items-center text-dark text-hover-primary font-size-h5 font-weight-bold">
									<i class="flaticon2-dashboard text-success icon-md mr-2"></i>
									{{ $allCompany->company_name }}
								</a>
								<a href="#"
									class="d-flex align-items-center text-dark text-hover-primary font-size-h6 font-weight-bold mr-3"><i
										class="flaticon2-speaker text-success icon-md mr-2"></i> Company
									Slogan: {{ $allCompany->company_slogan }}
								</a>
								<div class="d-flex flex-wrap">
									<a href="#"
										class="d-flex align-items-center text-dark text-hover-primary font-weight-bold mr-3"><i
											class="flaticon-multimedia-2 text-success icon-md mr-2"></i>
										{{ $allCompany->company_email }}
									</a>
									<a href="#"
										class="d-flex align-items-center text-dark text-hover-primary font-weight-bold mr-3"><i
											class="flaticon2-phone text-success icon-md mr-2"></i>
										{{ $allCompany->contact_number }}
									</a>
								</div>
						</div>
						<!--begin::User-->
						<a href="#"
							class="d-flex align-items-center text-dark text-hover-primary font-size-h6 font-weight-bold mr-3">Tax/Vat/GST:
							{{$allCompany->tax}} %
						</a>
					</div>
					<!--end::Title-->
				</div>
				<!--end::Info-->
			</div>
			<!--end::Top-->
		</div>

		<div class="card-body">
			<div class="row justify-content-center">
				<div class="col-12">
					<div class="row">
						<div class="col-lg-12 mx-auto">
							<div class="card">
								<div class="card-body">
									<form enctype="multipart/form-data" class="form-parsley"
										action="{{ route('owner.updateCompany') }}" method="POST"
										enctype="multipart/form-data">
										@csrf
										<input type="hidden" value="{{ Session::get('user') }}" name="userId">
										<input type="hidden" value="{{encryptor('encrypt', $allCompany->id)}}"
											name="id">
										<div class="form-group row">
											<div class="col-md-3">
												<label class="mb-3">Shop Logo</label>
												<input type="file" name="company_logo" id="input-file-now"
													class="dropify @if($errors->has('company_logo')) {{ 'is-invalid' }} @endif"
													data-default-file="{{asset("storage/images/company/$allCompany->company_logo")}}" />
												@if($errors->has('company_logo'))
												<small class="d-block text-danger mb-3">
													{{ $errors->first('company_logo') }}
												</small>
												@endif

												<div class="company-images">
													<img class="img-fluid" width="100px"
														src="{{asset("storage/images/company/$allCompany->company_logo")}}"
														alt="">
												</div>
											</div>
											<div class="col-md-3">
												<label class="mb-3">Billing Seal</label>
												<input type="file" name="billing_seal" id="input-file-now"
													class="dropify"
													data-default-file="{{asset("storage/images/company/$allCompany->billing_seal")}}" />

												<div class="company-images">
													<img class="img-fluid"
														src="{{asset("storage/images/company/$allCompany->billing_seal")}}"
														alt="">
												</div>
											</div>
											<div class="col-md-3">
												<label class="mb-3">Billing Signature</label>
												<input type="file" name="billing_signature" id="input-file-now"
													class="dropify"
													data-default-file="{{asset("storage/images/company/$allCompany->billing_signature")}}" />
												<div class="company-images">
													<img class="img-fluid"
														src="{{asset("storage/images/company/$allCompany->billing_signature")}}"
														alt="">
												</div>

											</div>
											<div class="col-md-3">
												<label class="mb-3">Trade License</label>
												<input type="file" name="trade_l" id="input-file-now"
													class="dropify"
													data-default-file="{{asset("storage/images/company/trade_l/$allCompany->trade_l")}}" />
												<div class="company-images">
													<img class="img-fluid"
														src="{{asset("storage/images/company/trade_l/$allCompany->trade_l")}}"
														alt="">
												</div>
											</div>
										</div>

										<div class="form-group">
											<label>Name</label>
											<input type="text" name="company_name"
												value="{{ $allCompany->company_name }}"
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
												<input type="text" name="company_slogan"
													value="{{ $allCompany->company_slogan }}"
													class="form-control" placeholder="Company Slogan" />
											</div>
											<div class="col-md-3">
												<label>Tax/Vat/GST</label>
												<input type="text" name="tax" value="{{ $allCompany->tax }}"
													class="form-control" placeholder="Number of %" />
											</div>
											<div class="col-md-3">
												<label>TIN</label>
												<input type="text" name="tin" value="{{ $allCompany->tin }}"
													class="form-control"
													placeholder="Tax Identification Number" />
											</div>
										</div>
										<!--end form-group-->

										<div class="form-group row">
											<div class="col-md-6">
												<label>Currency</label>
												<input type="text" name="currency"
													value="{{ $allCompany->currency }}" class="form-control"
													placeholder="Currency" />
											</div>
											<div class="col-md-6">
												<label>Currency Symble</label>
												<input type="text" name="currency_symble"
													value="{{ $allCompany->currency_symble }}"
													class="form-control" placeholder="Currency Symble" />
											</div>
										</div>
										<!--end form-group-->
										<div class="form-group row">
											<div class="col-md-6">
												<label>Contact Number</label>
												<input type="text" name="contact_number"
													value="{{ $allCompany->contact_number }}"
													class="form-control" placeholder="Company Contact Number" />
											</div>
											<div class="col-md-6">
												<label>Email Address</label>
												<input type="text" name="company_email"
													value="{{ $allCompany->company_email }}"
													class="form-control" placeholder="Company Email Address" />
											</div>
										</div>
										<!--end form-group-->

										<div class="form-group row">
											<div class="col-md-6">
												<label>Address Line 1</label>
												<input type="text" name="company_add_a"
													value="{{ $allCompany->company_add_a }}"
													class="form-control" placeholder="Company Address" />
											</div>
											<div class="col-md-6">
												<label>Address Line 2</label>
												<input type="text" name="company_add_b"
													value="{{ $allCompany->company_add_b }}"
													class="form-control" placeholder="Company Address" />
											</div>
										</div>
										<!--end form-group-->
										<div class="form-group">
											<label>Billing Terms</label>
											<textarea type="text" id="description" name="billing_terms"
												class="form-control" onpaste="countChar(this)"
												onkeyup="countChar(this)">{{ $allCompany->billing_terms }}</textarea>
											<div style="color:red" id="charNum">character Remain-550</div>
										</div>

										<div class="form-group row">
											<div class="col-md-6">
												<label>Facebook</label>
												<input type="text" name="facebook"
													value="{{ $allCompany->facebook }}" class="form-control"
													placeholder="Facebook Address" />
											</div>
											<div class="col-md-6">
												<label>Twitter</label>
												<input type="text" name="twitter"
													value="{{ $allCompany->twitter }}" class="form-control"
													placeholder="Twitter Address" />
											</div>
										</div>
										<!--end form-group-->
										<div class="form-group row">
											<div class="col-md-6">
												<label>Webiste</label>
												<input type="text" name="webiste"
													value="{{ $allCompany->webiste }}" class="form-control"
													placeholder="Webiste Address" />
											</div>
											<div class="col-md-6">
												<label class="control-label">Status</label>
												<select name="status"
													class="form-control @if($errors->has('status')) {{ 'is-invalid' }} @endif">
													<option value="1" @if($allCompany->status==1) selected
														@endif>Active</option>
													<option value="0" @if($allCompany->status==0) selected
														@endif>Inactive</option>
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
												@foreach(invoice() as $inv)
												<label>
													<input type="radio" name="invoice"
														value="{{ $inv['link'] }}"
														@if($allCompany->invoice==$inv['link']) checked
													@endif>
													<img src="{{asset("storage/images/company/invoice/".$inv['image'])}}"
														width="200" height="300">
												</label>
												@endforeach
												@endif
											</div>
										</div>
										<!--end form-group-->
										<div class="form-group mb-0">
											<button type="submit"
												class="btn btn-primary waves-effect waves-light">
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
						</div>
						<!--end col-->
					</div><!--end row-->
				</div><!--end col-->
			</div><!--end row-->
		</div><!--end card-body-->
	</div><!--end card-->
</div><!--end col-->
@endsection

@push('scripts')
<script>
	function countChar(val) {
        var len = val.value.length;
        if (len >= 550) {
          val.value = val.value.substring(0, 550);
          $('#charNum').text('character Remain-0');
        } else {
          $('#charNum').text('character Remain-'+(550 - len));
        }
      };
</script>
@endpush