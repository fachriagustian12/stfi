$(() => {
  $("#menu-data_buku").addClass("active");
  $("#all_buku").DataTable();
  $(".select2 ").select2();

  $("#modal_add_buku").on("show.bs.modal", function () {
    $("form").trigger("reset");
    $(".file").empty();
    $(".file").imageUploader({
      extensions: [".pdf"],
      mimes: ["application/pdf"],
    });
  });
  
  $('button[data-dismiss="modal"]').on('click', function () {
    var modal = $(this).closest('.modal');

    // Reset form di dalam modal jika ada
    modal.find('form').trigger('reset');

    // Hapus konten dinamis (misalnya daftar atau data lainnya)
    modal.find('.dynamic-list').empty();

    // Bersihkan elemen lain yang diperlukan
    modal.find('textarea').val('');
    modal.find('select').prop('selectedIndex', 0);
});

  load();
});

function load() {
  $.ajax({
    type: "post",
    dataType: "json",
    url: "/getdata",
    data: {
      table: "buku",
    },
    success: function (result) {
      let data = result.data;
      let code = result.code;
      if (code != "0") {
        var dt = $("#all_buku").DataTable({
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
          lengthChange: true,
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
            { mDataProp: "title", class: "text-center" },
            { mDataProp: "pengarang", class: "text-center", width: "12%" },
            { mDataProp: "penerbit", class: "text-center", width: "12%" },
            { mDataProp: "tempat_terbit", class: "text-center", width: "12%" },
            { mDataProp: "tahun_terbit", class: "text-center", width: "12%" },
            { mDataProp: "path", class: "text-center", width: "12%" },
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
              aTargets: [1],
            },
            {
              mRender: function (data, type, row) {
                var elem = `<button class="btn btn-sm btn-info" onclick="viewimage('${data}')"><i class="la la-image"></i></button>`;
                return elem;
              },
              aTargets: [7],
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
        var table = $("#all_buku").DataTable();
        table.clear().draw();
      }
    },
  });

  $.ajax({
    type: "post",
    dataType: "json",
    url: "/getdata",
    data: {
      table: "skripsi",
    },
    success: function (result) {
      let data = result.data;
      let code = result.code;
      if (code != "0") {
        var dt = $("#all_skripsi").DataTable({
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
          lengthChange: true,
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
            { mDataProp: "judul_buku", class: "text-center" },
            { mDataProp: "pengarang", class: "text-center", width: "10%" },
            { mDataProp: "penerbit", class: "text-center", width: "15%" },
            { mDataProp: "tempat_terbit", class: "text-center", width: "10%" },
            { mDataProp: "tahun_terbit", class: "text-center", width: "10%" },
            { mDataProp: "kondisi_buku", class: "text-center", width: "10%" },
          ],
          order: [[0, "ASC"]],
          fixedColumns: true,
          aoColumnDefs: [
            {
              mRender: function (data, type, row) {
                var elem =
                        '<div class="btn-group" role="group" aria-label="Basic example">';
                elem += `<button class="btn btn-icon btn-info btn-sm" onclick="action('updateskripsi', ${row.id})"><i class="la la-edit"></i></button>`;
                elem += `<button class="btn btn-icon btn-danger btn-sm" onclick="action('deleteskripsi', ${row.id})"><i class="la la-trash"></i></button>`;
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
        var table = $("#all_skripsi").DataTable();
        table.clear().draw();
      }
    },
  });

  $.ajax({
    type: "post",
    dataType: "json",
    url: "/getdata",
    data: {
      table: "jurnal_dosen",
    },
    success: function (result) {
      let data = result.data;
      let code = result.code;
      if (code != "0") {
        var dt = $("#all_jurnal").DataTable({
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
          lengthChange: true,
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
            { mDataProp: "nidn", class: "text-center", width: "10%" },
            { mDataProp: "nm_dosen", class: "text-center", width: "12%" },
            { mDataProp: "judul", class: "text-center" },
            { mDataProp: "jenis_jurnal", class: "text-center", width: "12%" },
            { mDataProp: "tahun", class: "text-center", width: "10%" },
          ],
          order: [[0, "ASC"]],
          fixedColumns: true,
          aoColumnDefs: [
            {
              mRender: function (data, type, row) {
                var elem =
                        '<div class="btn-group" role="group" aria-label="Basic example">';
                elem += `<button class="btn btn-icon btn-info btn-sm" onclick="action('updatejurnal', ${row.id})"><i class="la la-edit"></i></button>`;
                elem += `<button class="btn btn-icon btn-danger btn-sm" onclick="action('deletejurnal', ${row.id})"><i class="la la-trash"></i></button>`;
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
        var table = $("#all_jurnal").DataTable();
        table.clear().draw();
      }
    },
  });

  $.ajax({
    type: "post",
    dataType: "json",
    url: "/getdata",
    data: {
      table: "riset_dosen",
    },
    success: function (result) {
      let data = result.data;
      let code = result.code;
      if (code != "0") {
        var dt = $("#all_riset").DataTable({
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
          lengthChange: true,
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
            { mDataProp: "nidn", class: "text-center", width: "10%" },
            { mDataProp: "nm_dosen", class: "text-center", width: "12%" },
            { mDataProp: "judul", class: "text-center" },
            { mDataProp: "jenis_karyailmiah", class: "text-center", width: "12%" },
            { mDataProp: "tahun", class: "text-center", width: "10%" },
          ],
          order: [[0, "ASC"]],
          fixedColumns: true,
          aoColumnDefs: [
            {
              mRender: function (data, type, row) {
                var elem =
                        '<div class="btn-group" role="group" aria-label="Basic example">';
                elem += `<button class="btn btn-icon btn-info btn-sm" onclick="action('updateriset', ${row.id})"><i class="la la-edit"></i></button>`;
                elem += `<button class="btn btn-icon btn-danger btn-sm" onclick="action('deleteriset', ${row.id})"><i class="la la-trash"></i></button>`;
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
        var table = $("#all_riset").DataTable();
        table.clear().draw();
      }
    },
  });
}

function viewimage(url) {
  window.open(url);
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
            table: "buku",
          },
          url: "/deletedata",
          success: function (result) {
            load("buku");
          },
        });
      }
    });
  } 
  else if(mode == 'updateskripsi'){
    $.ajax({
      type: "post",
      dataType: "json",
      url: "/getdata",
      data: {
        id: id,
        table: "skripsi",
      },
      success: function (result) {
        var data = result.data;
        $("#modal_add_data_skripsi").modal("show");
        $("#id_skripsi").val(id);
        $("#judul_buku_skripsi").val(data.judul_buku);
        $("#pengarang_skripsi").val(data.pengarang);
        $("#penerbit_skripsi").val(data.penerbit);
        $("#tempat_terbit_skripsi").val(data.tempat_terbit);
        $("#tahun_terbit_skripsi").val(data.tahun_terbit);
        $("#kondisi_buku_skripsi").val(data.kondisi_buku);
        $("wrd").html("Update");

        let preloaded = [{ id: 1, src: data.path }];
        $(".file").empty();
        $(".file").imageUploader({
          preloaded: preloaded,
          extensions: [".pdf"],
          mimes: ["application/pdf"],
        });
      },
    });
  }
  else if(mode == "deleteskripsi"){
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
            table: "skripsi",
          },
          url: "/deletedata",
          success: function (result) {
            load("buku");
          },
        });
      }
    });
  } 
  else if(mode == 'updatejurnal'){
    $.ajax({
      type: "post",
      dataType: "json",
      url: "/getdata",
      data: {
        id: id,
        table: "jurnal_dosen",
      },
      success: function (result) {
        var data = result.data;
        $("#modal_add_data_jurnal").modal("show");
        $("#id_jurnal").val(id);
        $("#nidn_jurnal").val(data.nidn);
        $("#nm_dosen_jurnal").val(data.kd_dosen);
        $("#judul_jurnal").val(data.judul);
        $("#jenis_jurnal").val(data.jenis_jurnal);
        $("#tahun_jurnal").val(data.tahun);
        $("wrd").html("Update");

        let preloaded = [{ id: 1, src: data.path }];
        $(".file").empty();
        $(".file").imageUploader({
          preloaded: preloaded,
          extensions: [".pdf"],
          mimes: ["application/pdf"],
        });
      },
    });
  }
  else if(mode == "deletejurnal"){
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
            table: "jurnal_dosen",
          },
          url: "/deletedata",
          success: function (result) {
            load("buku");
          },
        });
      }
    });
  } 
  else if(mode == 'updateriset'){
    $.ajax({
      type: "post",
      dataType: "json",
      url: "/getdata",
      data: {
        id: id,
        table: "riset_dosen",
      },
      success: function (result) {
        var data = result.data;
        console.log(data)
        $("#modal_add_data_riset").modal("show");
        $("#id_riset").val(id);
        $("#nidn_riset").val(data.nidn);
        $("#nm_dosen_riset").val(data.kd_dosen);
        $("#judul_riset").val(data.judul);
        $("#jenis_riset").val(data.jenis_karyailmiah);
        $("#tahun_riset").val(data.tahun);
        $("wrd").html("Update");
      },
    });
  }
  else if(mode == "deleteriset"){
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
            table: "riset_dosen",
          },
          url: "/deletedata",
          success: function (result) {
            load("buku");
          },
        });
      }
    });
  } 
  else {
    $.ajax({
      type: "post",
      dataType: "json",
      url: "/getdata",
      data: {
        id: id,
        table: "buku",
      },
      success: function (result) {
        var data = result.data;
        $("#modal_add_data_buku").modal("show");
        $("#id").val(id);
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
          extensions: [".pdf"],
          mimes: ["application/pdf"],
        });
      },
    });
  }
}
