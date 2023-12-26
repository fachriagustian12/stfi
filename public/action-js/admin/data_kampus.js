$(() => {
  $("#menu-data_kampus").addClass("active");
  $(".layanan_informasi").addClass("open");
  $(".select2 ").select2();

  $('#modal_add_kegiatan').on('show.bs.modal', function() {
    $("form").trigger("reset")
    $(".select2").val('0').trigger("change")
    $('.file').empty()
    $('.file').imageUploader();
  })

  load('kegiatan')
});

function load(table) {
  $.ajax({
    type: "post",
    dataType: "json",
    url: "/getdata",
    data: {
      table: table
    },
    success: function (result) {
      let data = result.data;
      let code = result.code;
      if(code != '0'){
        var dt = $("#all_kegiatan").DataTable({
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
            pageLength: 10,
            aaData: result.data,
            aoColumns: [
              { mDataProp: "id", class: 'text-center', width: "2%" },
              { mDataProp: "kegiatan",class: 'text-center' },
              { mDataProp: "tanggal_kegiatan",class: 'text-center' },
              { mDataProp: "keterangan",class: 'text-center' },
              { mDataProp: "status",class: 'text-center' },
              { mDataProp: "path",class: 'text-center' },
              { mDataProp: "id", class: 'text-center' },
            ],
            order: [[0, "ASC"]],
            fixedColumns: true,
            aoColumnDefs: [
                {
                mRender: function (data, type, row) {
                  var elem = ''
                  if(data == 1){
                    elem = '<div class="badge badge-success">aktif</div>'
                  }else{
                    elem = '<div class="badge badge-danger">tidak</div>'

                  }
                        return elem ;
                },
                aTargets: [4],
              },
              {
                mRender: function (data, type, row) {
                    var elem = `<figure class="col-lg-6" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                                  <a href="#!" onclick="viewimage('${data}')" itemprop="contentUrl" >
                                      <img class="img-thumbnail img-fluid" src="${data}" itemprop="thumbnail" alt="Image description" />
                                  </a>
                                </figure>`
                        return elem ;
                },
                aTargets: [5],
              },
              {
              mRender: function (data, type, row) {
                  var elem = '<div class="btn-group" role="group" aria-label="Basic example">'
                      elem += `<button class="btn btn-icon btn-info btn-sm" onclick="action('update', ${row.id})"><i class="la la-edit"></i></button>`
                      elem += `<button class="btn btn-icon btn-danger btn-sm" onclick="action('delete', ${row.id})"><i class="la la-trash"></i></button>`
                      elem += '</div>'
                      return elem ;
              },
              aTargets: [6],
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
      }else{
        var table = $("#all_kegiatan").DataTable()
        table.clear().draw();
      }
    },
  });
}

function action(mode, id, path) {
  if(mode == 'delete'){
      Swal.fire({
          html: `Apakah anda yakin data ini?`,
          icon: "warning",
          buttonsStyling: true,
          showCancelButton: true,
          confirmButtonText: "Ya, Hapus!",
          cancelButtonText: 'Tidak',
          customClass: {
              confirmButton: "btn btn-danger btn-sm",
              cancelButton: 'btn btn-success btn-sm'
          }
      }).then((result) => {
          if (result.isConfirmed) {
              $.ajax({
                  type: "post",
                  dataType: "json",
                  data: {
                      id: id,
                      path: path,
                      table: 'kegiatan'
                  },
                  url: "/deletedata",
                  success: function (result) {
                      load('kegiatan')
                  }
              })
          }
      })
  }else {
      $.ajax({
          type: "post",
          dataType: "json",
          url: "/getdata",
          data: {
              id: id,
              table: 'kegiatan'
          },
          success: function (result) {
              var data = result.data 
              $('#modal_add_kegiatan').modal('show')
              $('#id').val(id)
              for (let item in data) {
                if($(`#${item}`).length){
                  if(item == 'perkuliahan'){
                    if(data[item] == 'online'){
                      $(`#${item}`).prop('checked', true)
                    }else{
                      $(`#${item}`).prop('checked', false)
                    }

                  }else{
                    $(`#${item}`).val(data[item]).trigger('change');
                  }
                }
              }
              $('wrd').html('Update')

              let preloaded = [
                {id: 1, src: data.path},
              ];
              $('.file').empty()
              $('.file').imageUploader({
                preloaded: preloaded
              });
          }
      })
  }
}

function viewimage(url) {
  $('#modal_image').modal('show')
  $('#url-image').attr('src', url)
}
