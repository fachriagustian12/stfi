$(() => {
  $("#menu-data_dosen").addClass("active");
  $(".layanan_informasi").addClass("open");
  $(".select2 ").select2();

  $("#modal_add_dosen").on("show.bs.modal", function () {
    $("form").trigger("reset");
    $(".select2").val("0").trigger("change");
    $("#id").val(null);
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
    modal.find("#id").val(null);
  });
  getajar();
});

function getajar() {
  $.ajax({
    type: "post",
    dataType: "json",
    url: "/getdatadosen",
    success: function (result) {
      let data = result.data;
      var dt = $("#all_dosen").DataTable({
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
          { mDataProp: "nm_dosen", class: "text-center" },
          { mDataProp: "nm_jab", class: "text-center" },
          { mDataProp: "nm_pangkat", class: "text-center" },
          { mDataProp: "kategori_dosen", class: "text-center" },
          { mDataProp: "alamat", class: "text-center" },
          { mDataProp: "kontak", class: "text-center" },
          { mDataProp: "id", class: "text-center" },
        ],
        order: [[0, "ASC"]],
        fixedColumns: true,
        aoColumnDefs: [
          // {
          //   mRender: function (data, type, row) {
          //     var elem = "";
          //     if (data == "online") {
          //       elem = '<div class="badge badge-success">online</div>';
          //     } else {
          //       elem = '<div class="badge badge-secondary">offline</div>';
          //     }
          //     return elem;
          //   },
          //   aTargets: [5],
          // },
          // {
          //   mRender: function (data, type, row) {
          //     console.log(row.jadwal);
          //     var jadwal = row.jadwal;
          //     var length = jadwal.length;
          //     var elem = "";
          //     for (let index = 0; index < length; index++) {
          //       elem = "<td>" + jadwal[index].nama_hari + "</td>";
          //     }
          //     return elem;
          //     // var elem = "";
          //     // if (data == 1) {
          //     //   elem = '<div class="badge badge-success">aktif</div>';
          //     // } else {
          //     //   elem = '<div class="badge badge-danger">tidak</div>';
          //     // }
          //     // return elem;
          //   },
          //   aTargets: [3],
          // },
          {
            mRender: function (data, type, row) {
              var elem =
                '<div class="btn-group" role="group" aria-label="Basic example">';
              elem += `<button class="btn btn-icon btn-info btn-sm" onclick="action('update', ${row.id})"><i class="la la-edit"></i></button>`;
              elem += `<button class="btn btn-icon btn-danger btn-sm" onclick="action('delete', ${row.id})"><i class="la la-trash"></i></button>`;
              elem += "</div>";
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
    },
  });
}

// function load(table) {
//   $.ajax({
//     type: "post",
//     dataType: "json",
//     url: "/getdata",
//     data: {
//       table: table,
//     },
//     success: function (result) {
//       let data = result.data;
//       let code = result.code;
//       console.log(data);
//       if (code != "0") {
//         var dt = $("#all_dosen").DataTable({
//           dom:
//             "<'row'" +
//             "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
//             "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
//             ">" +
//             "<'table-responsive'tr>" +
//             "<'row'" +
//             "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
//             "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
//             ">",
//           destroy: true,
//           paging: true,
//           lengthChange: false,
//           searching: true,
//           ordering: true,
//           info: true,
//           autoWidth: false,
//           responsive: false,
//           pageLength: 10,
//           aaData: result.data,
//           aoColumns: [
//             { mDataProp: "id", class: "text-center", width: "2%" },
//             { mDataProp: "nama", class: "text-center" },
//             { mDataProp: "mata_kuliah", class: "text-center" },
//             { mDataProp: "jadwal", class: "text-center" },
//             { mDataProp: "kelas", class: "text-center" },
//             { mDataProp: "perkuliahan", class: "text-center" },
//             { mDataProp: "status", class: "text-center" },
//             { mDataProp: "tugas", class: "text-center" },
//             { mDataProp: "create_date", class: "text-center" },
//             { mDataProp: "id", class: "text-center" },
//           ],
//           order: [[0, "ASC"]],
//           fixedColumns: true,
//           aoColumnDefs: [
//             {
//               mRender: function (data, type, row) {
//                 var elem = "";
//                 if (data == "online") {
//                   elem = '<div class="badge badge-success">online</div>';
//                 } else {
//                   elem = '<div class="badge badge-secondary">offline</div>';
//                 }
//                 return elem;
//               },
//               aTargets: [5],
//             },
//             {
//               mRender: function (data, type, row) {
//                 var elem = "";
//                 if (data == 1) {
//                   elem = '<div class="badge badge-success">aktif</div>';
//                 } else {
//                   elem = '<div class="badge badge-danger">tidak</div>';
//                 }
//                 return elem;
//               },
//               aTargets: [6],
//             },
//             {
//               mRender: function (data, type, row) {
//                 var elem =
//                   '<div class="btn-group" role="group" aria-label="Basic example">';
//                 elem += `<button class="btn btn-icon btn-info btn-sm" onclick="action('update', ${row.id})"><i class="la la-edit"></i></button>`;
//                 elem += `<button class="btn btn-icon btn-danger btn-sm" onclick="action('delete', ${row.id})"><i class="la la-trash"></i></button>`;
//                 elem += "</div>";
//                 return elem;
//               },
//               aTargets: [9],
//             },
//           ],
//           fnRowCallback: function (
//             nRow,
//             aData,
//             iDisplayIndex,
//             iDisplayIndexFull
//           ) {
//             var index = iDisplayIndexFull + 1;
//             $("td:eq(0)", nRow).html("#" + index);
//             return index;
//           },
//           fnDrawCallback: function () {
//             $(".update_status").change(function () {
//               action("update", this.value, this.checked);
//             });
//           },
//           fnInitComplete: function () {
//             var that = this;
//             var td;
//             var tr;
//             this.$("td").click(function () {
//               td = this;
//             });
//             this.$("tr").click(function () {
//               tr = this;
//             });
//           },
//         });
//       } else {
//         var table = $("#all_dosen").DataTable();
//         table.clear().draw();
//       }
//     },
//   });
// }

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
            table: "dosen",
          },
          url: "/deletedata",
          success: function (result) {
            location.reload();
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
        table: "dosen",
      },
      success: function (result) {
        var data = result.data;
        $("#modal_add_dosen").modal("show");
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
      },
    });
  }
}
