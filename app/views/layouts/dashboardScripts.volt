    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 2.3.8
        </div>
        <strong>Copyright &copy; 2014-2016 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights
        reserved.
    </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
{{ javascript_include('vendor/plugins/jQuery/jquery-2.2.3.min.js') }}

<!-- Bootstrap 3.3.6 -->
{{ javascript_include('vendor/bootstrap/js/bootstrap.min.js') }}

<!-- FastClick -->
{{ javascript_include('vendor/plugins/fastclick/fastclick.js') }}

<!-- AdminLTE App -->
{{ javascript_include('js/app.min.js') }}

<!-- Sparkline -->
{{ javascript_include('vendor/plugins/sparkline/jquery.sparkline.min.js') }}

<!-- jvectormap -->
{{ javascript_include('vendor/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}
{{ javascript_include('vendor/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}

<!-- SlimScroll 1.3.0 -->
{{ javascript_include('vendor/plugins/slimScroll/jquery.slimscroll.min.js') }}

<!-- ChartJS 1.0.1 -->
{{ javascript_include('vendor/plugins/chartjs/Chart.min.js') }}

<!-- Pnotify -->
{{ javascript_include('vendor/plugins/pnotify/pnotify.custom.min.js') }}

<!-- Datatables -->
{{ javascript_include('vendor/plugins/datatables/jquery.dataTables.min.js') }}
{{ javascript_include('vendor/plugins/datatables/dataTables.bootstrap.min.js') }}

<script>
    var SmartHome = {
        baseUri: "{{ baseUri }}"
    };
</script>

<!-- SmartHome scripts -->
{{ javascript_include('js/smarthome.js') }}