$(() => {
  $("#menu-data_mahasiswa").addClass("active");
  $(".layanan_informasi").addClass("open");
  $(".select2 ").select2();

  $("#modal_add_mahasiswa").on("show.bs.modal", function () {
    $("form").trigger("reset");
    $(".select2").val("0").trigger("change");
  });

  load("mahasiswa");
});

function load(table) {
  $.ajax({
    type: "post",
    dataType: "json",
    url: "/getdata",
    data: {
      table: table,
    },
    success: function (result) {
      let data = result.data;
      let code = result.code;
      if (code != "0") {
        var dt = $("#all_mahasiswa").DataTable({
          dom:
            "<'row'" +
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
            { mDataProp: "id", class: "text-center", width: "2%" },
            { mDataProp: "id", class: "text-center", width: "2%" },
            { mDataProp: "nama", class: "text-center" },
            { mDataProp: "nim", class: "text-center" },
            { mDataProp: "id_angkatan", class: "text-center" },
            { mDataProp: "prodi", class: "text-center" },
            { mDataProp: "status_mahasiswa", class: "text-center" },
            { mDataProp: "status_perwalian", class: "text-center" },
            { mDataProp: "create_date", class: "text-center" },
            // { mDataProp: "id", class: "text-center" },
          ],
          order: [[0, "ASC"]],
          fixedColumns: true,
          aoColumnDefs: [
            {
              mRender: function (data, type, row) {
                var elem = "";
                if (data == "SUDAH") {
                  elem = '<div class="badge badge-success">Sudah</div>';
                } else {
                  elem = '<div class="badge badge-danger">Belum</div>';
                }
                return elem;
              },
              aTargets: [7],
            },
            {
              mRender: function (data, type, row) {
                var id = "`"+row.id+"`";
                var elem =
                  '<div class="btn-group" role="group" aria-label="Basic example">';
                elem += `<button class="btn btn-icon btn-info btn-sm" onclick="action('update', ${id})"><i class="la la-edit"></i></button>`;
                elem += `<button class="btn btn-icon btn-danger btn-sm" onclick="action('delete', ${id})"><i class="la la-trash"></i></button>`;
                elem += "</div>";
                return elem;
              },
              aTargets: [1],
            },
          ],
          fnRowCallback: function (
            nRow,
            aData,
            iDisplayIndex,
            iDisplayIndexFull
          ) {
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
      } else {
        var table = $("#all_mahasiswa").DataTable();
        table.clear().draw();
      }
    },
  });
}

function action(mode, id) {
  if (mode == "delete") {
    Swal.fire({
      html: `Apakah anda yakin data ini?`,
      icon: "warning",
      buttonsStyling: true,
      showCancelButton: true,
      confirmButtonText: "Ya, Hapus!",
      cancelButtonText: "Tidak",
      customClass: {
        confirmButton: "btn btn-danger btn-sm",
        cancelButton: "btn btn-success btn-sm",
      },
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "post",
          dataType: "json",
          data: {
            id: id,
            table: "mahasiswa",
          },
          url: "/deletedata",
          success: function (result) {
            load("mahasiswa");
          },
        });
      }
    });
  } else {
    $.ajax({
      type: "post",
      dataType: "json",
      url: "/getdata",
      data: {
        id: id,
        table: "mahasiswa",
      },
      success: function (result) {
        var data = result.data;
        $("#modal_add_mahasiswa").modal("show");
        $("#id").val(id);
        $("#userid").val(data.user_id);
        if(data.user_id){
          var nameInput = document.getElementById('password');
          nameInput.required = false;
          var nameInputnpm = document.getElementById('npm');
          nameInputnpm.required = false;
        }
        $("#nama").val(data.nama);
        $("#npm").val(data.nim);
        $("#email").val(data.user_email);
        $("#angkatan").val(data.id_angkatan);
        $("#prodi").val(data.prodi);
        $("#semester").val(data.semester);
        $("#status_mahasiswa").val(data.status_mahasiswa);
        $("#status_perwalian").val(data.status_perwalian);
        $("wrd").html("Update");
      },
    });
  }
}
