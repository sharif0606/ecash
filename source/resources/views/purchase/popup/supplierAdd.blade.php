<div class="modal fade" id="add-supplier-show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 class="modal-title">Add Supplier</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <!--begin::Form-->
						<form class="form" id="supplierForm" method="get">
							<input type="hidden" value="{{ Session::get('user') }}" name="userId">
							<div class="form-group row">
								<div class="col-lg-4">
									<label>Supplier Mobile No: <span class="text-danger sup">*</span></label>
									<input type="text" id="supCode" name="supCode" class="form-control" placeholder="Supplier Mobile No" />
									<small class="d-none text-danger mb-3 supCode">
										Supplier Mobile No is required
									</small>
								</div>
								<div class="col-lg-4">
									<label>Supplier Name: <span class="text-danger sup">*</span></label>
									<input type="text" id="cname" name="name" class="form-control "
										placeholder="Supplier Name" />
									<small class="d-none text-danger mb-3 cname">
										Supplier Name is required
									</small>
								</div>
								<div class="col-lg-4">
									<label>Supplier Type: <span class="text-danger sup">*</span></label>
									<select name="type" class="form-control">
										<option value="0">Common</option>
										<option value="1">Regular</option>
										<option value="2">Very Regular</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-lg-4">
									<label>Contact Person:</label>
									<input type="text" name="contact_person" class="form-control" />
								</div>
								<div class="col-lg-4">
									<label>Alternate Contact Number:</label>
									<input type="text" name="contact_no_b" value="{{ old('contact_no_b') }}" class="form-control" />
								</div>
								<div class="col-lg-4">
									<label>Email: </label>
									<input type="email" name="email" value="{{ old('email') }}" class="form-control" />
								</div>
							</div>
							<div class="form-group row">
								<div class="col-lg-4">
									<label>Country:</label>
									<input type="text" name="country" value="{{ old('country') }}" class="form-control" />
								</div>
								<div class="col-lg-4">
									<label>Division:</label>
									<select name="state_id"
										class="form-control select2" style="width: 100%; height:36px;">
										@if(count($allState) > 0)
											@foreach($allState as $state)
												<option value="{{ $state->id }}">{{ $state->code.'-'. $state->name}}</option>
											@endforeach
										@endif
									</select>
								</div>
								<div class="col-lg-4">
									<label>District:</label>
									<select name="zone_id" class="form-control select2" style="width: 100%; height:36px;">
										@if(count($allZone) > 0)
											@foreach($allZone as $zone)
												<option value="{{ $zone->id }}">{{ $zone->code.'-'. $zone->name}}</option>
											@endforeach
										@endif
									</select>
								</div>
							</div>
						</form>    
						<!--end::Form-->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary saving" onclick="saveSupplier()">Save</button>
                <button type="button" class="btn btn-info waiting">Waittig</button>
            </div>
        </div>
    </div>
</div>