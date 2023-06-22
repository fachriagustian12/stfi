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
      data: {
        param: 'simponi'
      },
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
              { mDataProp: "id_usersim", width: "10%" },
              { mDataProp: "usernm" },
              { mDataProp: "pass" },
              { mDataProp: "email" },
              { mDataProp: "nama" },
              { mDataProp: "kontak" },
              { mDataProp: "provinsi" },
              { mDataProp: "id_usersim", class: 'text-center' },
            ],
            order: [[0, "ASC"]],
            fixedColumns: true,
            aoColumnDefs: [
                {
                mRender: function (data, type, row) {
                  var elem = '<div class="d-flex justify-content-end flex-shrink-0">'
                      elem += `<a class="btn btn-icon btn-primary btn-sm me-1" onclick="action('update', ${row.id_usersim})"><i class="bi bi-pencil-square"></i></a>`
                      elem += `<a class="btn btn-icon btn-danger btn-sm me-1" onclick="action('delete', ${row.id_usersim}, '${row.usernm}')"><i class="bi bi-trash"></i></a>`
                      elem += '</div>'
                      return elem ;
                },
                aTargets: [7],
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
                        username: username,
                        param: 'simponi'
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
                id: id,
                param: 'simponi'
            },
            success: function (result) {
                var data = result.data 
                $('#modal_add_user').modal('show')
                $('#id').val(id)
                $('#username').val(data.usernm)
                $('#pass').val(data.pass)
                $('#email').val(data.email)
                $('#nama').val(data.nama)
                $('#kontak').val(data.kontak)
                $('#id_provinsi').val(data.id_provinsi)
                $('wrd').html('Ubah')
            }
        })
    }
}