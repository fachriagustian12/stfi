console.log("You are running jQuery version: " + $.fn.jquery);
$(document).ready(function () {
  $("#menu-log").addClass("active");
  loaddata();
});

function loaddata() {
  $.ajax({
    type: "post",
    dataType: "json",
    url: "/getalllogs",
    success: function (result) {
      let data = result.data;
      let code = result.code;
      console.log(data);
      var dt = $("#all-log").DataTable({
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
          { mDataProp: "tanggal", class: "text-center" },
          { mDataProp: "nama", class: "text-center" },
          { mDataProp: "aktifitas", class: "text-center" },
          { mDataProp: "keterangan", class: "text-center" },
        ],
        order: [[0, "ASC"]],
        fixedColumns: true,
        aoColumnDefs: [],
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
