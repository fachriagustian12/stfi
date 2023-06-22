console.log('You are running jQuery version: ' + $.fn.jquery);
$(document).ready(function(){
    loadlog()
});

function loadlog() {

    var table = $('#all-log').DataTable();
      table.clear().draw();
      table.destroy();

    $("#all-log").DataTable({
      processing: true,
      serverSide: true,
      dom: "<'row'" +
                    "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
                    "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                    ">" +
                
                    "<'table-responsive'tr>" +
                
                    "<'row'" +
                    "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                    "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                    ">",
      ajax: {
        url: "getlog",
        type: "POST" 
      },
      order: [[0, "ASC"]],
      columns: [
        { data: 'id' },
        { data: 'tgl' },
        { data: 'username' },
        { data: 'keterangan' }
      ],
      fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        var index = iDisplayIndexFull + 1;
        $("td:eq(0)", nRow).html("#" + index);
        return index;
      },
      lengthChange: false
    })

    // $.ajax({
    //   type: "post",
    //   dataType: "json",
    //   url: "getlog",
    //   success: function (result) {
    //     let data = result.data;
    //     let code = result.code;
    //     var dt = $("#all-log").DataTable({
    //         dom: "<'row'" +
    //                 "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
    //                 "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
    //                 ">" +
                
    //                 "<'table-responsive'tr>" +
                
    //                 "<'row'" +
    //                 "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
    //                 "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
    //                 ">",
    //         processing: true,
    //         destroy: true,
    //         paging: true,
    //         lengthChange: false,
    //         searching: true,
    //         ordering: true,
    //         info: true,
    //         autoWidth: false,
    //         responsive: false,
    //         pageLength: 10,
    //         aaData: result.data,
    //         aoColumns: [
    //           { mDataProp: "id", width: "10%" },
    //           { mDataProp: "tgl" },
    //           { mDataProp: "username" },
    //           { mDataProp: "keterangan" },
    //         ],
    //         order: [[0, "ASC"]],
    //         fixedColumns: true,
    //         aoColumnDefs: [],
    //         fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
    //           var index = iDisplayIndexFull + 1;
    //           $("td:eq(0)", nRow).html("#" + index);
    //           return index;
    //         },
    //         fnDrawCallback: function () {
    //           $(".update_status").change(function () {
    //             action("update", this.value, this.checked);
    //           });
    //         },
    //         fnInitComplete: function () {
    //           var that = this;
    //           var td;
    //           var tr;
    //           this.$("td").click(function () {
    //             td = this;
    //           });
    //           this.$("tr").click(function () {
    //             tr = this;
    //           });
    //         },
    //       });


    //   },
    // });
}