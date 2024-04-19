console.log("You are running jQuery version: " + $.fn.jquery);
$(document).ready(function () {
  $("#menu-log").addClass("active");
  loaddata();

  $('#download_csv').on('click', function() {
    var table = $('#all-log').DataTable();
    var data = table.rows().data().toArray();
    var csv = '';
    console.log(data);
    for (var i = 0; i < data.length; i++) {
        var row = Object.values(data[i]);
        csv += row.join(',') + '\n';
    }
    var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    var link = document.createElement("a");
    if (link.download !== undefined) { // feature detection
        var url = URL.createObjectURL(blob);
        link.setAttribute("href", url);
        link.setAttribute("download", 'data_log_aktifitas.csv');
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
  });
});

function loaddata() {
  $.ajax({
    type: "post",
    dataType: "json",
    url: "/getalllogs",
    success: function (result) {
      let data = result.data;
      let code = result.code;
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

