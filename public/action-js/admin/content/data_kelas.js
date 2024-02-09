$(() => {
  $("#menu-data_kelas").addClass("active");
  $("#all_kelas").DataTable();

  $("#modal_add_kelas").on("show.bs.modal", function () {
    $("form").trigger("reset");
    $(".file").empty();
    $(".file").imageUploader();
  });

  load("perkuliahan");
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
        var dt = $("#all_kelas").DataTable({
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
            { mDataProp: "nama", class: "text-center" },
            { mDataProp: "no_kelas", class: "text-center" },
            { mDataProp: "nm_dosen", class: "text-center" },
            { mDataProp: "nm_kelas", class: "text-center", width: "10%" },
            { mDataProp: "matkul", class: "text-center", width: "10%" },
            { mDataProp: "jam_mulai", class: "text-center", width: "10%" },
            { mDataProp: "jam_akhir", class: "text-center", width: "10%" },
            { mDataProp: "nm_hari", class: "text-center" },
            { mDataProp: "id", class: "text-center" },
          ],
          order: [[0, "ASC"]],
          fixedColumns: true,
          aoColumnDefs: [
            {
              mRender: function (data, type, row) {
                var elem =
                  '<div class="btn-group" role="group" aria-label="Basic example">';
                elem += `<button class="btn btn-icon btn-info btn-sm" onclick="action('update', ${row.id})"><i class="la la-edit"></i></button>`;
                elem += `<button class="btn btn-icon btn-danger btn-sm" onclick="action('delete', ${row.id}, '${row.path}')"><i class="la la-trash"></i></button>`;
                elem += "</div>";
                return elem;
              },
              aTargets: [9],
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
        var table = $("#all_kelas").DataTable();
        table.clear().draw();
      }
    },
  });
}

function formatTanggalWaktu(input) {
  const tanggalWaktuAwal = new Date(input);

  const hh = String(tanggalWaktuAwal.getHours()).padStart(2, "0");
  const bb = String(tanggalWaktuAwal.getMonth() + 1).padStart(2, "0"); // Perlu ditambah 1 karena bulan dimulai dari 0
  const tttt = tanggalWaktuAwal.getFullYear();

  const hasilFormat = `${hh}/${bb}/${tttt}`;

  return hasilFormat;
}

function action(mode, id, path) {
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
            path: path,
            table: "kelas",
          },
          url: "/deletedata",
          success: function (result) {
            load("kelas");
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
        table: "kelas",
      },
      success: function (result) {
        var data = result.data;
        $("#modal_add_kelas").modal("show");
        $("#id").val(id);
        $("#status").val(data.status);

        for (let item in data) {
          if ($(`#${item}`).length) {
            if (item == "perkuliahan") {
              if (data[item] == "online") {
                $(`#${item}`).prop("checked", true);
              } else {
                $(`#${item}`).prop("checked", false);
              }
            } else {
              $(`#${item}`).val(data[item]).trigger("change");
            }
          }
        }
        $("wrd").html("Update");

        let preloaded = [{ id: 1, src: data.path }];
        $(".file").empty();
        $(".file").imageUploader({
          preloaded: preloaded,
        });
      },
    });
  }
}
