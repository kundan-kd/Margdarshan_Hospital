@extends('backend.admin.layouts.main')
@section('title')
medicines
@endsection
@section('main-container')
  <div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-normal mb-0">Medicines</h6>
  <div class="btns">
    <a class="btn btn-primary-600  btn-sm fw-normal mx-2 createNewBtn" data-bs-toggle="modal" data-bs-target="#medician-list-add"><i class="ri-add-line"></i></i>Add Medicine</a>
    {{-- <a class="btn btn-warning-600  btn-sm fw-normal "><i class="ri-download-line"></i> Import</a> --}}
    <a class="btn btn-warning-600  btn-sm fw-normal "><i class="ri-file-pdf-2-line"></i> Export</a>
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
              <th scope="col" class="fw-medium">Medicines</th>
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
    <form id="createMed_form" class="needs-validation" novalidate>
      <div class="modal-header bg-primary-600 p-11">
        <h6 class="modal-title fw-normal text-lg text-white" id="medician-list-addLabel">Add Medicine</h6>
        <button type="button" class="btn-close btn-custom text-sm" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <div class="row">
          <input type="hidden" id="createMed_id">
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal">Category</label>
                <select id="createMed_category" class="form-select form-select-sm" required>
                    <option value="">Select a Category</option>
                      <option value="Syrup">Syrup</option>
                      <option value="Injection">Injection</option>
                      <option value="Capsule">Capsule</option>
                      <option value="Tablet">Tablet</option>
                      <option value="Ointment">Ointment</option>
                  </select>
                  <div class="invalid-feedback">
                    Select Category
                  </div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal">Company</label>
                <select id="createMed_company" class="form-select form-select-sm" required>
                    <option value="">Company</option>
                     <option value="Cipla">Cipla</option>
                      <option value="Lupin">Lupin</option>
                      <option value="Biocon">Biocon</option>
                      <option value="Zydus">Zydus</option>
                      <option value="Alkem">Alkem</option>
                  </select>
                    <div class="invalid-feedback">
                    Select Company
                  </div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal">Group</label>
                <select id="createMed_group" class="form-select form-select-sm" required>
                    <option value="">Group</option>
                      <option value="Analgesics">Analgesics</option>
                      <option value="Antibiotics">Antibiotics</option>
                      <option value="Antihistamines">Antihistamines</option>
                      <option value="Antacids">Antacids</option>
                      <option value="Antidepressants">Antidepressants</option>
                  </select>
                    <div class="invalid-feedback">
                    Select Group
                  </div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal">Unit</label>
                <select id="createMed_unit" class="form-select form-select-sm" required>
                    <option value="">Unit</option>
                    <option value="mg">mg</option>
                    <option value="ml">ml</option>
                    <option value="g">g</option>
                    <option value="IU">IU</option>
                    <option value="mcg">mcg</option>
                  </select>
                   <div class="invalid-feedback">
                    Select Unit
                  </div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal">Re-Ordering Level</label>
              <input id="createMed_reOrderingLevel" type="text" class="form-control form-control-sm" placeholder="Re-Ordering Level" required>
               <div class="invalid-feedback">
                    Enter Re-Ordering Level
                  </div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal">Rack</label>
                <input id="createMed_rack" type="text" class="form-control form-control-sm" placeholder="Rack" required>
                 <div class="invalid-feedback">
                    Enter Rack
                  </div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal">Medician Name</label>
                <input id="createMed_name" type="text" class="form-control form-control-sm" placeholder=" Medician Name" required>
                 <div class="invalid-feedback">
                    Enter Medicine Name
                  </div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal">Composition</label>
                <input id="createMed_composition" type="text" class="form-control form-control-sm" placeholder="Composition" required> 
                 <div class="invalid-feedback">
                    Enter Composition Name
                  </div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal">Taxes</label>
                <input id="createMed_taxes" type="number" class="form-control form-control-sm" placeholder="Taxes" required>
                 <div class="invalid-feedback">
                    Enter Tax Amount
                  </div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal">Box / Packing</label>
                <input id="createMed_boxPacking" type="text" class="form-control form-control-sm" placeholder="Box / Packing" required>
                 <div class="invalid-feedback">
                    Enter Box/Packing
                  </div>
            </div>
            <div class="col-md-12">
              <label class="form-label fw-normal">Narration</label>
                <textarea id="createMed_narration" name="#0" class="form-control " rows="2" placeholder="Narration"></textarea>
            </div>
        </div>
      </div>
      <div class="modal-footer pt-2 pb-3 border-top-0">
        <button type="submit" class="btn btn-primary-600  btn-sm fw-normal medicineAddBtn">Save</button>
        <button type="button" class="btn btn-primary-600  btn-sm fw-normal medicineUpdateBtn d-none" onclick="medicineUpdate(document.getElementById('createMed_id').value)">Update</button>
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
  const medicineView = "{{route('medicine.medicineView')}}";
  const medicineAdd = "{{route('medicine.medicineAdd')}}";
  const getMedicineData = "{{route('medicine.getMedicineData')}}";
  const updateMedicineData = "{{route('medicine.updateMedicineData')}}";
  const deleteMedicineData = "{{route('medicine.deleteMedicineData')}}";
  </script>
 {{-----------external js files added for page functions------------}}
 <script src="{{asset('backend/assets/js/custom/admin/pharmacy/medicine.js')}}"></script>
 @endsection