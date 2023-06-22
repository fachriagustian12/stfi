console.log('You are running jQuery version: ' + $.fn.jquery);
$(document).ready(function(){
    loadbox(detail_box)
});

function loadbox(box) {
    if(box){
        $.ajax({
            type: "post",
            dataType: "json",
            data: {
                box: box
            },
            url: "/getbox",
            success: function (result) {
                let data = result.data;
                let code = result.code;
                var dt = $("#all-box-detail").DataTable({
                    dom: "<'row'" +
                            "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
                            "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                            ">" +
                        
                            "<'table-responsive'tr>" +
                        
                            "<'row'" +
                            "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                            "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                            ">",
                    processing: true,
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
                        { mDataProp: "hdno" },
                        { mDataProp: "id" },
                        { mDataProp: "id" },
                        { mDataProp: "id" },
                        { mDataProp: "id" },
                        { mDataProp: "id" },
                        { mDataProp: "id" },
                    ],
                    order: [[0, "ASC"]],
                    fixedColumns: true,
                    aoColumnDefs: [
                        {
                            mRender: function (data, type, row) {
                                var elem = '-';
                                if(row.lokasi_sk != '00.00.00.00.00' && row.lokasi_sk != ''){
                                    elem = `${row.lokasi_sk} | ${row.tgl_sk} | ${row.no_sk}`
                                }
                                return elem ;
                            },
                            aTargets: [2],
                        },
                        {
                            mRender: function (data, type, row) {
                                var elem = '-';
                                if(row.lokasi_sip != '00.00.00.00.00' && row.lokasi_sip != ''){
                                    elem = `${row.lokasi_sip} | ${row.tgl_sip} | ${row.no_sip}`
                                }
                                return elem ;
                            },
                            aTargets: [3],
                        },
                        {
                            mRender: function (data, type, row) {
                                var elem = '-';
                                if(row.lokasi_pengalihanhak != '00.00.00.00.00' && row.lokasi_pengalihanhak != ''){
                                    elem = `${row.lokasi_pengalihanhak} | ${row.tgl_pengalihanhak} | ${row.no_pengalihanhak}`
                                }
                                return elem ;
                            },
                            aTargets: [4],
                        },
                        {
                            mRender: function (data, type, row) {
                                var elem = '-';
                                if(row.lokasi_kontrak != '00.00.00.00.00' && row.lokasi_kontrak != ''){
                                    elem = `${row.lokasi_kontrak} | ${row.tgl_kontrak} | ${row.no_kontrak}`
                                }
                                return elem ;
                            },
                            aTargets: [5],
                        },
                        {
                            mRender: function (data, type, row) {
                                var elem = '-';
                                if(row.lokasi_sktl != '00.00.00.00.00' && row.lokasi_sktl != ''){
                                    elem = `${row.lokasi_sktl} | ${row.tgl_sktl} | ${row.no_sktl}`
                                }
                                return elem ;
                            },
                            aTargets: [6],
                        },
                        {
                            mRender: function (data, type, row) {
                                var elem = '-';
                                if(row.lokasi_hak != '00.00.00.00.00' && row.lokasi_hak != ''){
                                    elem = `${row.lokasi_hak} | ${row.tgl_hak} | ${row.no_hak}`
                                }
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
    }else{
        $("#all-box").DataTable({
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
            url: "getbox",
            type: "POST" 
        },
        order: [[0, "ASC"]],
        columns: [
            { data: 'box' },
            { data: 'box' },
        ],
        aoColumnDefs: [
            {
            mRender: function (data, type, row) {
                var elem = `<a href="infobox?detail=${data}">${data}</a>`
                return elem ;
            },
            aTargets: [1],
        }
        ],
        fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            var index = iDisplayIndexFull + 1;
            $("td:eq(0)", nRow).html("#" + index);
            return index;
        },
        lengthChange: false
        })
    }

}