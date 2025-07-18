@extends('backend.admin.layouts.main')
@section('title')
Discharge Summary
@endsection
@section('main-container')
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-normal mb-0">Discharge Summary</h6>
    </div>
    @php
        // dd($medications);
    @endphp
        <div class="card">
        <div class="card-body">
            <input type="hidden" id="patient_id" value="{{$id}}">
            <form action="" id="discharge-summaryForm" class="needs-validation" novalidate>
                <div class="row gy-3 mt-2">
                    <div class="col-md-4">
                    </div>
                    <textarea name="discharge-finalDiagnosis" id="discharge-finalDiagnosis" rows="10" cols="80" style="width: 200px;">
                        <p>FINAL DIAGNOSIS:</p>
                        <p>Lorem ipsum dolor sit amet</p>
                        <p>Lorem ipsum dolor sit amet</p>
                        <p>CHIEF COMPLAINTS:</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <ul>
                            <li>Lorem ipsum dolor</li>
                            <li>Lorem ipsum dolor</li>
                            <li>Lorem ipsum dolor</li>
                        </ul>
                        <p>PAST HISTORY:</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>

                        <p>CLINICAL FINDINGS (AT THE TIME OF ADMISSION):</p>
                        @foreach ($vitals as $vital)
                        <p>{{$vital->name}} - {{$vital->value}} ({{$vital->date}})</p>                            
                        @endforeach
                        <p>INVESTIGATION:</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <p>BRIEF HISTORY &amp; HOSPITAL COURSE:</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <p>CONDITION AT DISCHARGE:</p>
                        <p>Lorem ipsum dolor sit amet</p>
                        <p>MEDICATION/DIET INSTRUCTION:</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <p>ADVICE ON DISCHARGE:</p>
                        <table style="border-collapse: separate; border-spacing: 10px;">
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach ($medications as $medicine)
                                    <tr>
                                        <td style="padding: 8px;">{{ $i }}</td>
                                        <td style="padding: 8px;">{{ $medicine->medicineNameData->name }}</td>
                                        <td style="padding: 8px;">{{ $medicine->dose }}</td>
                                    </tr>
                                    @php $i++; @endphp
                                @endforeach
                            </tbody>
                        </table>

                        <p>REVIEW ON/AFTER:</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </textarea>
                    
                    <div class="col-md-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary-600  btn-sm fw-medium m-2 dischargeSummmarySubmit"> <i class="ri-checkbox-circle-line"></i> Submit</button>
                        <button class="btn btn-primary-600  btn-sm fw-medium dischargeSummmarySpinn d-none" type="button">
                        Please Wait...
                        </button>
                    </div>
                </div>
            </form>
        </div>
        </div>
    </div>
 @endsection
@section('extra-js')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
  <script>
    CKEDITOR.replace('discharge-finalDiagnosis');
  </script>
<script>
    $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

    const dischargeSummarySubmit = "{{route('discharge.dischargeSummarySubmit')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/invoice/discharge-bill.js')}}"></script>
@endsection
 