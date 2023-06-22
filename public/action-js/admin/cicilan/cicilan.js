console.log('You are running jQuery version: ' + $.fn.jquery);
$(document).ready(function(){
    if(!detail){
        loadcicilan()
    }else{
        $('#harga-total').text(formatRupiah( $('#harga-total').text(), 'Rp.' ))
        $('#angsuran-pertama').text(formatRupiah( $('#angsuran-pertama').text(), 'Rp.' ))
        $('#angsuran-perbulan').text(formatRupiah( (Math.floor($('#angsuran-perbulan').text())).toString(), 'Rp.' ))
        $('#sisa-piutang').text(formatRupiah( $('#sisa-piutang').text(), 'Rp.' ))

        var dt_cil = $("#all-cicilan").DataTable({
            destroy: true,
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
            pageLength: 5,
            aaData: data_cicilan,
            aoColumns: [
              { mDataProp: "id", width: "5%" },
              { mDataProp: "tgl_bayar" },
              { mDataProp: "kode_billing" },
              { mDataProp: "ntpn" },
              { mDataProp: "ntbp" },
              { mDataProp: "kode_akun" },
              { mDataProp: "setoran",width: "10%"  },
              { mDataProp: "keterangan" },
              { mDataProp: "id", width: "20%", class: 'text-center' },
            ],
            order: [[0, "ASC"]],
            fixedColumns: true,
            aoColumnDefs: [
                {
                mRender: function (data, type, row) {
                    var elem = data ? data : '-'
                        return elem ;
                },
                aTargets: [1, 3, 4],
                },
                {
                mRender: function (data, type, row) {
                    var elem = formatRupiah(data.toString(), 'Rp.')
                        return elem ;
                },
                aTargets: [6],
                },
                {
                mRender: function (data, type, row) {
                    var elem = ''
                    switch (data) {
                        case '425123':
                            elem += 'Sewa Beli';
                            break;
                        case '423141':
                            elem = 'Sewa';
                            break;
                        case '777777':
                            elem = 'Pembayaran lama / pelunasan';
                            break;
                    }
                        return elem ;
                },
                aTargets: [5],
                },
                {
                mRender: function (data, type, row) {
                    var elem = '<div class="d-flex justify-content-end flex-shrink-0">'
                        elem += `<button class="btn btn-primary btn-sm me-1" onclick="modalubahangsuran('${row.id_rumah}', '${row.id}', '${row.tgl_bayar}','${row.kode_billing}','${row.ntpn}','${row.ntbp}','${row.kode_akun}','${row.setoran}','${row.keterangan.replace(/\r?\n|\r/g, "")}', '${row.angsuran_ke}')"><i class="bi bi-pencil-square"></i>ubah angsuran</button>`
                        elem += '</div>'
                        return elem ;
                },
                aTargets: [8],
                }
            ],
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
              var index = iDisplayIndexFull + 1;
              $("td:eq(0)", nRow).html("#" + index);
              return index;
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

        var dt_den = $("#all-denda").DataTable({
            destroy: true,
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
            pageLength: 5,
            aaData: data_denda,
            aoColumns: [
              { mDataProp: "id", width: "5%" },
              { mDataProp: "tgl_bayar" },
              { mDataProp: "kode_billing" },
              { mDataProp: "ntpn" },
              { mDataProp: "ntbp" },
              { mDataProp: "setoran", width: "10%" },
              { mDataProp: "keterangan" },
              { mDataProp: "id", width: "20%", class: 'text-center' },
            ],
            order: [[0, "ASC"]],
            fixedColumns: true,
            aoColumnDefs: [
                {
                    mRender: function (data, type, row) {
                        var elem = data ? data : '-'
                            return elem ;
                    },
                    aTargets: [1, 3, 4],
                },
                {
                    mRender: function (data, type, row) {
                        var elem = formatRupiah(data.toString(), 'Rp.')
                            return elem ;
                    },
                    aTargets: [5],
                },
                
                {
                mRender: function (data, type, row) {
                    var elem = ''
                    console.log(data);
                    switch (data) {
                        case '425123':
                            elem += 'Sewa Beli';
                            break;
                        case '423141':
                            elem = 'Sewa';
                            break;
                        case '777777':
                            elem = 'Pembayaran lama / pelunasan';
                            break;
                    }
                        return elem ;
                },
                aTargets: [4],
                },
                {
                mRender: function (data, type, row) {
                    var elem = '<div class="d-flex justify-content-end flex-shrink-0">'
                    elem += `<button class="btn btn-primary btn-sm me-1" onclick="modalubahangsuran('${row.id_rumah}', '${row.id}', '${row.tgl_bayar}','${row.kode_billing}','${row.ntpn}','${row.ntbp}','${row.kode_akun}','${row.setoran}','${row.keterangan.replace(/\r?\n|\r/g, "")}', '${row.angsuran_ke}')"><i class="bi bi-pencil-square"></i>ubah angsuran</button>`
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

        var dt_sim = $("#all-simponi").DataTable({
            destroy: true,
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
            pageLength: 5,
            aaData: data_billing,
            aoColumns: [
              { mDataProp: "id_billing", width: "5%" },
              { mDataProp: "nama_wajib_bayar" },
              { mDataProp: "created_at" },
              { mDataProp: "kode_billing" },
              { mDataProp: "tgl_jam_billing" },
              { mDataProp: "tgl_jam_expired_billing" },
              { mDataProp: "ntpn" },
              { mDataProp: "ntb" },
              { mDataProp: "tgl_jam_pembayaran" },
              { mDataProp: "total_nominal_billing" },
              { mDataProp: "id_billing", width: "15%", class: 'text-center' },
            ],
            order: [[0, "ASC"]],
            fixedColumns: true,
            aoColumnDefs: [
                {
                    mRender: function (data, type, row) {
                        var elem = formatRupiah(data.toString(), 'Rp.')
                            return elem ;
                    },
                    aTargets: [9],
                },
                {
                mRender: function (data, type, row) {
                    var elem = '<div class="d-flex justify-content-end flex-shrink-0">'
                        elem += `<a class="btn btn-primary btn-sm me-1" href="/detailbilling/${row.id_billing}"><i class="bi bi-eye"></i>detail</a>`
                        elem += '</div>'
                        return elem ;
                },
                aTargets: [10],
                }
            ],
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
              var index = iDisplayIndexFull + 1;
              $("td:eq(0)", nRow).html("#" + index);
              return index;
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
    }

    

    $('#kt_docs_repeater_basic').repeater({
        initEmpty: false,

        defaultValues: {
            'text-input': 'foo'
        },

        show: function () {
            $(this).slideDown();
        },

        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }
    });


});

function loadcicilan() {

    var formData = new FormData();

    
    var form = {
                    'hdno' : $('#hdno').val(),
                    'nama' : $('#nama').val(),
                    'alamat' : $('#alamat').val(),
                    'lembaga' : $('#lembaga').val(),
                    'provinsi' : $('#provinsi').val(),
                    'kecamatan' : $('#kecamatan').val(),
                    'kabupaten' : $('#kabupaten').val(),
                    'status' : $('#status').val()
                }

    $("#all-cicilan").DataTable({
      destroy: true,
      processing: true,
      serverSide: true,
      ajax: {
        url: "getcicilan",
        type: "POST",
        data: form
      },
      order: [[0, "ASC"]],
      columns: [
        { data: 'hdno' },
        { data: 'nama' },
        { data: 'alamat' },
        { data: 'harga',  width: 120 },
        { data: 'pembayaran', width: 150 },
        { data: 'sisa', width: 120 },
        { data: 'pnbp', width: 150 },
        { data: 'id', width: 100, class: 'text-center' }
      ],
      lengthChange: false,
      aoColumnDefs: [
            {
                mRender: function (data, type, row) {
                    var elem = '<div class="d-flex justify-content-end flex-shrink-0">'
                        elem += `<a href="/detailcicilan/${row.id}" class="btn btn-sm btn-danger"><i class="bi bi-eye"></i> Detail</a>`
                        elem += '</div>'
                     return elem ;
                },
                aTargets: [7],
            },
            {
                mRender: function (data, type, row) {
                    var elem = formatRupiah( data.toString(), 'Rp.' )
                     return elem ;
                },
                aTargets: [3, 4, 5, 6],
            }
        ],
    })

}

function getwilayah(param, isthis) {
    $.ajax({
        type: "post",
        dataType: "json",
        data: {
            param: param,
            id: isthis.value
        },
        url: "/getwilayah",
        success: function (result) {
            var opt = '<option value="0">Semua...</option>'
            result.data.forEach(element => {
                opt += `<option value="${element.id}">${element[param]}</option>`
            });

            $(`#${param}`).html(opt)
        }
    })
}

function formatRupiah(angka, prefix){
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
    split   		= number_string.split(','),
    sisa     		= split[0].length % 3,
    rupiah     		= split[0].substr(0, sisa),
    ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
  
    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if(ribuan){
      separator = sisa ? '.' : '';
      rupiah += separator + ribuan.join('.');
    }
  
    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}

function modalubahangsuran(id_rumah, id, tgl_bayar, kode_billing, ntpn, ntbp, kode_akun, setoran, keterangan, angsuran_ke) {
    $(`#ubah_angsuran`).modal('show')
    $('#id_rumah_ubah').val(id_rumah)
    $('#id_cicilan_ubah').val(id)
    
    $('#jenis_pembayaran').val(kode_akun).trigger('change')
    $('#kode_billing').val(kode_billing)
    $('#ntpn').val(ntpn)
    $('#ntbp').val(ntbp)
    $('#angsuran_ke').val(angsuran_ke)
    $('#kode_akun').val(kode_akun)
    $('#setoran').val(setoran)
    $('#keterangan').val(keterangan)
}

function ubahpembayaran() {
    $.ajax({
        type: "post",
        dataType: "json",
        data: {
            hdno : $('#is_hdno').text(),
            id_rumah : $('#id_rumah_ubah').val(),
            id : $('#id_cicilan_ubah').val(),
            kode_billing : $('#kode_billing').val(),
            ntpn : $('#ntpn').val(),
            ntbp : $('#ntbp').val(),
            setoran : $('#setoran').val(),
            kode_akun : $('#jenis_pembayaran').val(),
            keterangan : $('#keterangan').val(),
            angsuran_ke : $('#angsuran_ke').val(),
            status : $('#status').val()
        },
        url: "/ubahpembayaran",
        success: function (result) {
            location.reload()
        }
    })
}

function pembayaran(id_rumah) {
    $('[name="pembayaran"]').val(425123).trigger('change')
    $('[name="pembayaran"]').val('')
    $('#id_rumah-pembayaran').val(id_rumah)
    $('#pembayaran_cicilan').modal('show')
}

function simpanpembayaran() {
    $.ajax({
        type: "post",
        dataType: "json",
        data: {
            id_rumah: $('#id_rumah-pembayaran').val(),
            kode_billing : $('#kode_billing-pembayaran').val(),
            ntpn : $('#ntpn-pembayaran').val(),
            ntbp : $('#ntbp-pembayaran').val(),
            setoran : $('#setoran-pembayaran').val(),
            kode_akun : $('#jenis_pembayaran-pembayaran').val(),
            keterangan : $('#keterangan-pembayaran').val(),
            angsuran_ke : $('#angsuran_ke-pembayaran').val(),
            status : 1,
            hdno : $('#is_hdno').text()

        },
        url: "/pembayaran",
        success: function (result) {
            location.reload()
        }
    })
}

function kodebilling(id_rumah) {
    $('#modal_kodebilling').modal('show')
    $('#id_rumah_req').val(id_rumah)
}

function requestkodebilling(params) {
        var det_bil = $('#kt_docs_repeater_basic').repeaterVal()['kt_docs_repeater_basic']
        $.ajax({
            type: "post",
            dataType: "json",
            data: {
                id_rumah: $('#id_rumah_req').val(),
                nama: $('#nama-billing').val(),
                batas: $('#batas-billing').val(),
                total: $('#total-billing').val(),
                detail: det_bil,

            },
            url: "/kodebilling",
            success: function (result) {
                // location.reload()
            }
        })
}