<script>
$(document).ready(function() {
  $('#datatable').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        /* UBAH: Arahkan route ke data assignments */
        ajax: "{{ route('admin.assignments.data') }}",
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false},
            { data: 'judul_tugas', name:'judul_tugas'},
            { data: 'kategori', name:'kategori'}, // Pertemuan 1, UTS, dll
            { data: 'tenggat_waktu', name:'tenggat_waktu'}, // Deadline
            { data: 'action', orderable: false, searchable: false}
        ],
    });
});
</script>