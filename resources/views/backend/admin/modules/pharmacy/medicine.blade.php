@extends('backend.admin.layouts.main')
@section('title')
medicines
@endsection
@section('main-container')
  <div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-normal mb-0">Medicines</h6>
  <div class="btns">
    <a class="btn btn-primary-600  btn-sm fw-normal mx-2 createNewBtn" data-bs-toggle="modal" data-bs-target="#medician-list-add" onclick="resetMedicineAdd()"><i class="ri-add-line"></i>Add Medicine</a>
    <a href="{{route('medicine.medicineLowInventory')}}" class="btn btn-primary-600  btn-sm fw-normal mx-2 inven"></i>Low Inventory</a>
    {{-- <a class="btn btn-warning-600  btn-sm fw-normal "><i class="ri-download-line"></i> Import</a> --}}
    {{-- <a class="btn btn-warning-600  btn-sm fw-normal "><i class="ri-file-pdf-2-line"></i> Export</a> --}}
  </div>
</div>
    
    <div class="card basic-data-table">
      <!-- <div class="card-header text-end">
        <div class="btns">
            <a class="btn btn-primary-600  btn-sm fw-normal mx-2" href="./ipd-in-patientDetail.html"><i class="ri-add-box-line"></i> Create New</a>
            <a class="btn btn-warning-600  btn-sm fw-normal "><i class="ri-file-pdf-2-line"></i> Export</a>
          </div>
      </div> -->
      <div class="card-body">
        <table class="table bordered-table mb-0" id="medicine-create-table" data-page-length='10'>
          <thead>
            <tr>
              <th scope="col" class="fw-medium">Name</th>
              <th scope="col" class="fw-medium">Category</th>
              <th scope="col" class="fw-medium">Company</th>
              <th scope="col" class="fw-medium">Composition</th>
              <th scope="col" class="fw-medium">Group</th>
              <th scope="col" class="fw-medium">Unit</th>
              {{-- <th scope="col" class="fw-medium">Min Level</th> --}}
              <th scope="col" class="fw-medium">Re-Order Level</th>
              <th scope="col" class="fw-medium">Tax</th>
              <th scope="col" class="fw-medium">Box/Packing</th>
              <th scope="col" class="fw-medium">Stock</th>
              <th scope="col" class="fw-medium">Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- Modal to medician-list-add start -->
<div class="modal fade" id="medician-list-add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="medician-list-addLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content border-0">
    <form id="createMed_form">
      <div class="modal-header bg-primary-600 p-11">
        <h6 class="modal-title fw-normal text-lg text-white" id="medician-list-addLabel">Add Medicine</h6>
        <button type="button" class="btn-close btn-custom text-sm" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <div class="row">
          <input type="hidden" id="createMed_id">
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal" for="createMed_name">Medician Name</label>
                <input id="createMed_name" type="text" class="form-control form-control-sm" placeholder=" Medician Name" oninput="validateField(this.id,'input')">
                  <div class="createMed_name_errorCls d-none"></div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal" for="createMed_category">Category</label>
                <select id="createMed_category" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')">
                    <option value="">Select</option>
                      @foreach ($categories as $category)
                          <option value="{{$category->id}}">{{$category->name}}</option>
                      @endforeach
                  </select>
                 <div class="createMed_category_errorCls d-none"></div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal" for="createMed_company">Company</label>
                <select id="createMed_company" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')">
                    <option value="">Select</option>
                    @foreach ($companies as $company)
                          <option value="{{$company->id}}">{{$company->name}}</option>
                    @endforeach
                  </select>
                 <div class="createMed_company_errorCls d-none"></div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal" for="createMed_group">Group</label>
                <select id="createMed_group" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')">
                    <option value="">Select</option>
                    @foreach ($groups as $group)
                          <option value="{{$group->id}}">{{$group->name}}</option>
                    @endforeach
                  </select>
                  <div class="createMed_group_errorCls d-none"></div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal" for="createMed_unit">Unit</label>
                <select id="createMed_unit" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')">
                   <option value="">Select</option>
                    @foreach ($units as $unit)
                          <option value="{{$unit->id}}">{{$unit->unit}}</option>
                    @endforeach
                  </select>
                 <div class="createMed_unit_errorCls d-none"></div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal" for="createMed_reOrderingLevel">Re-Ordering Level</label>
              <input id="createMed_reOrderingLevel" type="number" class="form-control form-control-sm" placeholder="Re-Ordering Level" oninput="validateField(this.id,'select')">
             <div class="createMed_reOrderingLevel_errorCls d-none"></div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal" for="createMed_rack">Rack</label>
                <input id="createMed_rack" type="text" class="form-control form-control-sm" placeholder="Rack">
                <div class="createMed_rack_errorCls d-none"></div>
            </div>
          
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal" for="createMed_composition">Composition</label>
               <select id="createMed_composition" name="createMed_composition[]" class="form-select form-select-sm select2-cls" multiple style="width: 100%" oninput="validateField(this.id,'select')">
                   <option value="">Select</option>
                    @foreach ($compositions as $composition)
                          <option value="{{$composition->id}}">{{$composition->name}}</option>
                    @endforeach
                  </select>
               <div class="createMed_composition_errorCls d-none"></div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal" for="createMed_taxes">Taxes</label>
                <input id="createMed_taxes" type="number" class="form-control form-control-sm" placeholder="Taxes" oninput="validateField(this.id,'select')">
                <div class="createMed_taxes_errorCls d-none"></div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal" for="createMed_boxPacking">Box / Packing</label>
                <input id="createMed_boxPacking" type="text" class="form-control form-control-sm" placeholder="Box / Packing" oninput="validateField(this.id,'select')">
                 <div class="createMed_boxPacking_errorCls d-none"></div>
            </div>
            <div class="col-md-12">
              <label class="form-label fw-normal">Narration</label>
                <textarea id="createMed_narration" name="#0" class="form-control " rows="2" placeholder="Narration"></textarea>
            </div>
        </div>
      </div>
      <div class="modal-footer pt-2 pb-3 border-top-0">
        <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary-600  btn-sm fw-normal medicineAddBtn">Save</button>
        <button type="button" class="btn btn-primary-600  btn-sm fw-normal medicineUpdateBtn d-none" onclick="medicineUpdate(document.getElementById('createMed_id').value)"><i class="ri-checkbox-circle-line"></i> Update</button>
         </form>
      </div>
    </div>
  </div>
</div>
<!-- Move to medician-list-addend -->
@endsection
@section('extra-js')

{{-- <script>
// tooltip starts
   const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]'); 
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl)); 

    // Boxed Tooltip
    $(document).ready(function() {
        $('.tooltip-button').each(function () {
            var tooltipButton = $(this);
            var tooltipContent = $(this).siblings('.my-tooltip').html(); 
    
            // Initialize the tooltip
            tooltipButton.tooltip({
                title: tooltipContent,
                trigger: 'hover',
                html: true
            });
    
            // Optionally, reinitialize the tooltip if the content might change dynamically
            tooltipButton.on('mouseenter', function() {
                tooltipButton.tooltip('dispose').tooltip({
                    title: tooltipContent,
                    trigger: 'hover',
                    html: true
                }).tooltip('show');
            });
        });
    });
    // tooltip ended
    </script> --}}
<script>
   window.addEventListener('load', () => {
    $('.select2-cls').select2({
    dropdownParent: $('#medician-list-add')
  });
});
  const medicineView = "{{route('medicine.medicineView')}}";
  const medicineAdd = "{{route('medicine.medicineAdd')}}";
  const getMedicineData = "{{route('medicine.getMedicineData')}}";
  const updateMedicineData = "{{route('medicine.updateMedicineData')}}";
  const deleteMedicineData = "{{route('medicine.deleteMedicineData')}}";
  </script>
 {{-----------external js files added for page functions------------}}
 <script src="{{asset('backend/assets/js/custom/admin/pharmacy/medicine.js')}}"></script>
 @endsection