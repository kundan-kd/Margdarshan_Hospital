<footer class="d-footer">
    <div class="row align-items-center justify-content-between">
      <div class="col-auto">
        <p class="mb-0">© 2025 Margdarshan Hospital. All Rights Reserved.</p>
      </div>
      <div class="col-auto">
        <p class="mb-0">Curated by <a href="https://knack.media/" target="_blank"><span style="color:#487fff;">Knack Media</span></a></p>
      </div>
    </div>
  </footer>
  </main>
    
    <!-- jQuery library js -->
    <script src="{{asset('backend/assets/js/lib/jquery-3.7.1.min.js')}}"></script>
    <!-- Bootstrap js -->
    <script src="{{asset('backend/assets/js/lib/bootstrap.bundle.min.js')}}"></script>
    <!-- Apex Chart js -->
    <script src="{{asset('backend/assets/js/lib/apexcharts.min.js')}}"></script>
    <!-- Data Table js -->
    <script src="{{asset('backend/assets/js/lib/dataTables.min.js')}}"></script>
    <!-- Iconify Font js -->
    <script src="{{asset('backend/assets/js/lib/iconify-icon.min.js')}}"></script>
    <!-- jQuery UI js -->
    <script src="{{asset('backend/assets/js/lib/jquery-ui.min.js')}}"></script>
    <!-- Vector Map js -->
    <script src="{{asset('backend/assets/js/lib/jquery-jvectormap-2.0.5.min.js')}}"></script>
    <script src="{{asset('backend/assets/js/lib/jquery-jvectormap-world-mill-en.js')}}"></script>
    <!-- Popup js -->
    <script src="{{asset('backend/assets/js/lib/magnifc-popup.min.js')}}"></script>
    <!-- Slick Slider js -->
    <script src="{{asset('backend/assets/js/lib/slick.min.js')}}"></script>
    <!-- prism js -->
    <script src="{{asset('backend/assets/js/lib/prism.js')}}"></script>
    <!-- file upload js -->
    <script src="{{asset('backend/assets/js/lib/file-upload.js')}}"></script>
    <!-- audioplayer -->
    <script src="{{asset('backend/assets/js/lib/audioplayer.js')}}"></script>
    <!-- main js -->
    <script src="{{asset('backend/assets/js/app.js')}}"></script>
    <script src="{{asset('backend/assets/js/homeOneChart.js')}}"></script>
    <script src="{{asset('backend/assets/js/flatpickr.js')}}"></script>
    
    {{-- sweetalert cdn --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{-- for date month format --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

{{-- <!-- Buttons Extension for Datatable to perform extra functions -->
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script> --}}

  {{-----------common js for common function in all pages------------}}
  <script src="{{asset('backend/assets/js/custom/common.js')}}"></script>
    {{-- add extra js for the particular files --}}
    @yield('extra-js')
  </body>
  </html>