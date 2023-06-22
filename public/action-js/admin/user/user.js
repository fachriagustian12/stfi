console.log('You are running jQuery version: ' + $.fn.jquery);
$(document).ready(function(){
    loadusers()

    $('#modal_add_user').on('show.bs.modal', function() {
        $("form").trigger("reset")
        $('#password').attr('placeholder', 'Password')
        $('#ulangi-pass').attr('placeholder', 'Ulangi Password')
        $('wrd').html('Tambah')
    })
});

function loadusers() {
    $.ajax({
      type: "post",
      dataType: "json",
      url: "/getalluser",
      success: function (result) {
        let data = result.data;
        let code = result.code;
        var dt = $("#all-user").DataTable({
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
            aaData: result.data,
            aoColumns: [
              { mDataProp: "id", width: "10%" },
              { mDataProp: "username" },
              { mDataProp: "role" },
              { mDataProp: "provinsi" },
              { mDataProp: "id", width: "20%", class: 'text-center' },
            ],
            order: [[0, "ASC"]],
            fixedColumns: true,
            aoColumnDefs: [
                {
                mRender: function (data, type, row) {
                    var elem = '<div class="d-flex justify-content-end flex-shrink-0">'
                        elem += `<button class="btn btn-icon btn-primary btn-sm me-1" onclick="action('update', ${row.id})"><i class="bi bi-pencil-square"></i></button>`
                        elem += `<button class="btn btn-icon btn-danger btn-sm me-1" onclick="action('delete', ${row.id}, '${row.username}')"><i class="bi bi-trash"></i></button>`
                        elem += '</div>'
                        return elem ;
                },
                aTargets: [4],
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
      },
    });
}

function action(mode, id, username) {
    if(mode == 'delete'){
        Swal.fire({
            html: `Apakah anda yakin menghapus user ini?`,
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
                $.ajax({
                    type: "post",
                    dataType: "json",
                    data: {
                        id: id,
                        username: username
                    },
                    url: "/deleteuser",
                    success: function (result) {
                        loadusers()
                    }
                })
            }
        })
    }else {
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/getuser",
            data: {
                id: id
            },
            success: function (result) {
                var data = result.data 
                $('#modal_add_user').modal('show')
                $('#id').val(id)
                $('#username').val(data.username)
                $('#password').attr('placeholder', 'kosongkan jika tidak merubah password')
                $('#ulangi-pass').attr('placeholder', 'kosongkan jika tidak merubah password')
                $('#id_role').val(data.id_role)
                $('#id_provinsi').val(data.id_provinsi)
                $('wrd').html('Ubah')
            }
        })
    }
}