$(() => {
  $("#menu-data_jadwal_praktikum").addClass("active");
  $("#all_jadwal_praktikum").DataTable();

  $("#modal_add_jadwal_praktikum").on("show.bs.modal", function () {
    $("form").trigger("reset");
    $(".file").empty();
    $(".file").imageUploader();
  });

  load("jadwal_praktikum");
  dataDosen("dosen");
});
function dataDosen(dataDosen) {
  $.ajax({
    type: "post",
    dataType: "json",
    url: "/getdata",
    data: {
      table: dataDosen,
    },
    success: function (result) {
      let data = result.data;
      var selectDosen = document.getElementById("selectDosen");
      data.forEach(function (dosen) {
        var option = document.createElement("option");
        option.value = dosen.id;
        option.text = dosen.nama;
        selectDosen.add(option);
      });
    },
  });
}

function dataMatkul() {
  var selectDosen = document.getElementById("selectDosen");
  var idselectDosen = selectDosen.value;
  var tables = "dosen";

  $.ajax({
    type: "post",
    dataType: "json",
    url: "/getdata",
    data: {
      table: tables,
      id: idselectDosen,
    },
    success: function (result) {
      let datas = result.data;
      var inputElement = document.getElementById("mata_kuliah_praktikum");
      inputElement.value = datas.mata_kuliah;
    },
  });
}

function load(table) {
  $.ajax({
    type: "post",
    dataType: "json",
    url: "/getdataPraktikum",
    data: {
      table: table,
    },
    success: function (result) {
      let data = result.data;
      let code = result.code;
      console.log(data);
      if (code != "0") {
        var dt = $("#all_jadwal_praktikum").DataTable({
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
            { mDataProp: "ruangan_praktikum", class: "text-center" },
            { mDataProp: "mata_kuliah_praktikum", class: "text-center" },
            { mDataProp: "nama_dosen", class: "text-center", width: "10%" },
            { mDataProp: "nama_kelompok", class: "text-center", width: "10%" },
            { mDataProp: "jam_mulai", class: "text-center", width: "10%" },
            { mDataProp: "jam_akhir", class: "text-center", width: "10%" },
            { mDataProp: "nama_hari", class: "text-center" },
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
              aTargets: [8],
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
        var table = $("#all_jadwal_praktikum").DataTable();
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
            table: "jadwal_praktikum",
          },
          url: "/deletedata",
          success: function (result) {
            load("jadwal_praktikum");
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
        table: "jadwal_praktikum",
      },
      success: function (result) {
        var data = result.data;
        $("#modal_add_jadwal_praktikum").modal("show");
        $("#id").val(id);
        $("#ruangan_praktikum").val(data.ruangan_praktikum);
        $("#status").val(data.status);
        $("#jam_mulai").val(data.jam_mulai);
        $("#jam_akhir").val(data.jam_akhir);
        $("#tanggal").val(data.tanggal);

        var idDosen = data.nama_dosen;

        $.ajax({
          type: "post",
          dataType: "json",
          url: "/getdata",
          data: {
            table: "dosen",
            id: idDosen,
          },
          success: function (result) {
            let datas = result.data;
            $("#selectDosen").val(datas.id);
            $("#mata_kuliah_praktikum").val(datas.mata_kuliah);
          },
        });
        $("wrd").html("Update");
      },
    });
  }
}

$("#mohon_save").on("click", function () {
  var formData = new FormData();
  id = $("#id").val();

  if (id) {
    formData.append("id", id);
  }
  formData.append("ruangan_praktikum", $("#ruangan_praktikum").val());
  formData.append("nama_dosen", $("#selectDosen").val());
  formData.append("mata_kuliah_praktikum", $("#mata_kuliah_praktikum").val());
  formData.append("status", $("#status").val());
  formData.append("jam_mulai", $("#jam_mulai").val());
  formData.append("jam_akhir", $("#jam_akhir").val());
  formData.append("tanggal", $("#tanggal").val());
  save(formData);
});

function save(formData) {
  $.ajax({
    type: "post",
    processData: false,
    contentType: false,
    url: "addjadwalpraktikum",
    data: formData,
    success: function (result) {
      let data = result.data;
      location.reload();
    },
  });
}
