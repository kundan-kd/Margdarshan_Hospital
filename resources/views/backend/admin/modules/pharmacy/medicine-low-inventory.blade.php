@extends('backend.admin.layouts.main')
@section('title')
Low Inv. Medicine
@endsection
@section('main-container')
  <div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-normal mb-0">Low Inventory Medicines</h6>
   <div class="btns">
    <a href="{{route('medicine.index')}}" class="btn btn-primary-600  btn-sm fw-normal mx-2 inven"></i>Medicines</a>
  </div>
</div>
    
    <div class="card basic-data-table">
      <div class="card-body">
        <table class="table bordered-table mb-0" id="medicine-low-inventory-table" data-page-length='10'>
          <thead>
            <tr>
              <th scope="col" class="fw-medium">Name</th>
              <th scope="col" class="fw-medium">Category</th>
              <th scope="col" class="fw-medium">Company</th>
              <th scope="col" class="fw-medium">Composition</th>
              <th scope="col" class="fw-medium">Group</th>
              <th scope="col" class="fw-medium">Unit</th>
              <th scope="col" class="fw-medium">Re Order Level</th>
              <th scope="col" class="fw-medium">Current Stock</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>

@endsection
@section('extra-js')
<script>
  let low_invntory_table = $('#medicine-low-inventory-table').DataTable({
     processing:true,
     serverSide:true,
     ajax:{
        url: "{{ route('medicine.medicineLowInventoryView') }}",
        type:"GET",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        error:function(xhr,thrown){
           console.log(xhr.responseText);
           alert('Error: '+thrown);
        }
     },
     columns:[
        {
            data:'name',
            name:'name'
        },
        {
            data:'category',
            name:'category'
        },
        {
            data:'company',
            name:'company'
        },
        {
            data:'composition',
            name:'composition'
        },
        {
            data:'group',
            name:'group'
        },
        {
            data:'unit',
            name:'unit'
        },
        {
            data:'re_ordering_level',
            name:'re_ordering_level'
        },
        {
            data:'stock',
            name:'stock'
        }
     ]
});
</script>
 {{-----------external js files added for page functions------------}}
 @endsection