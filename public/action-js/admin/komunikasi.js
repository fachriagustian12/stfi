console.log('You are running jQuery version: ' + $.fn.jquery);
$(document).ready(function(){
    console.log(diskusi_tiket);

    var dt = $("#all-kom").DataTable({
        dom: "<'row'" +
                "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
                "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                ">" +
            
                "<'table-responsive'tr>" +
            
                "<'row'" +
                "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                ">",
        destroy: true,
        paging: true,
        lengthChange: false,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        responsive: false,
        pageLength: 10,
        aaData: data_tiket,
        aoColumns: [
          { mDataProp: "id_tiket", width: "10%" },
          { mDataProp: "judul_tiket" },
          { mDataProp: "created_at" },
          { mDataProp: "updated_at" },
          { mDataProp: "id", width: "15%", class: 'text-center' },
        ],
        order: [[0, "ASC"]],
        fixedColumns: true,
        aoColumnDefs: [
            {
                mRender: function (data, type, row) {                    
                        var elem = '<div class="d-flex justify-content-end flex-shrink-0">'
                            elem += `<a class="btn btn-sm btn-primary me-5 mb-2" href="komunikasi?detail=${row.id_tiket}"><i class="bi bi-eye"></i> Detail</a>`
                            elem += '</div>'
                    return elem ;
                },
                aTargets: [4],
            },
            {
                mRender: function (data, type, row) {
                    var elem = data +' oleh '+row.nama1
                    return elem ;
                },
                aTargets: [2],
            },
            {
                mRender: function (data, type, row) {
                    var elem = data +' oleh '+row.nama2
                    return elem ;
                },
                aTargets: [3],
            }
        ],
        fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
          var index = iDisplayIndexFull + 1;
          $("td:eq(0)", nRow).html("#" + index);
          return index;
        },
        fnDrawCallback: function () {
          $(".update_status").change(function () {
            action("update", this.value, this.checked);
          });
        },
        fnInitComplete: function () {
          var that = this;
          var td;
          var tr;
          this.$("td").click(function () {
            td = this;
          });
          this.$("tr").click(function () {
            tr = this;
          });
        },
    });

});

function action(mode, id) {
    if(mode == 'delete'){
        Swal.fire({
            html: `Apakah anda yakin menghapus tiket ini?`,
            icon: "warning",
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: 'Tidak',
            customClass: {
                confirmButton: "btn btn-danger btn-sm",
                cancelButton: 'btn btn-success btn-sm'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `deletetiket?id=${id}`;
            }
        })
    }
}
