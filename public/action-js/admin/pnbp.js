console.log('You are running jQuery version: ' + $.fn.jquery);
$(document).ready(function(){
  chartall(JSON.parse(data_chart))
});

function chartall(datas) {
  var tahun = []
  var pembayaran = []
  datas.forEach(element => {
    
    tahun.push(element.tahun == null ? "0": element.tahun)
    pembayaran.push(element.pembayaran)
  });

  var element = document.getElementById('kt_apexcharts_1');

  var height = parseInt(KTUtil.css(element, 'height'));
  var labelColor = KTUtil.getCssVariableValue('--bs-gray-500');
  var borderColor = KTUtil.getCssVariableValue('--bs-gray-200');
  var baseColor = KTUtil.getCssVariableValue('--bs-primary');
  var secondaryColor = KTUtil.getCssVariableValue('--bs-gray-300');

  if (!element) {
      return;
  }
  console.log(tahun);
  var options = {
      series: [{
          name: 'Pembayaran',
          data: pembayaran
      }],
      chart: {
          fontFamily: 'inherit',
          type: 'bar',
          height: height,
          toolbar: {
              show: false
          }
      },
      plotOptions: {
          bar: {
              horizontal: false,
              columnWidth: ['100%'],
              endingShape: 'rounded'
          },
      },
      legend: {
          show: true,
          position: "bottom",
      },
      dataLabels: {
          enabled: false
      },
      stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
      },
      xaxis: {
          categories: tahun,
          axisBorder: {
              show: false,
          },
          axisTicks: {
              show: false
          },
          labels: {
              style: {
                  colors: labelColor,
                  fontSize: '12px'
              }
          }
      },
      yaxis: {
          labels: {
              style: {
                  colors: labelColor,
                  fontSize: '12px'
              },
              formatter: function (value) {
                value = formatRupiah( value.toString(), 'Rp.' )
                return value;
              }
          }
      },
      fill: {
          opacity: 1
      },
      states: {
          normal: {
              filter: {
                  type: 'none',
                  value: 0
              }
          },
          hover: {
              filter: {
                  type: 'none',
                  value: 0
              }
          },
          active: {
              allowMultipleDataPointsSelection: false,
              filter: {
                  type: 'none',
                  value: 0
              }
          }
      },
      tooltip: {
          style: {
              fontSize: '12px'
          },
          y: {
              formatter: function (val) {
                  return formatRupiah( val.toString(), 'Rp.' )
              }
          }
      },
      colors: [baseColor, secondaryColor],
      grid: {
          borderColor: borderColor,
          strokeDashArray: 4,
          yaxis: {
              lines: {
                  show: true
              }
          }
      },
      
    
};

  var chart = new ApexCharts(element, options);
  chart.render();


  // $.ajax({
  //   type: 'post',
  //   dataType: 'json',
  //   url: 'dashload',
  //   success: function(result){

  //       let data = result.data;
  //       let pie = []
        
  //       pie['teknis'] = [parseInt(data.progres.teknis.total)-parseInt(data.progres.teknis.selesai), parseInt(data.progres.teknis.selesai)]
  //       pie['operasi'] = [parseInt(data.progres.operasi.total)-parseInt(data.progres.operasi.selesai), parseInt(data.progres.operasi.selesai)]

  //       $('#usr-all').html(data.users.userall);
  //       $('#usr-teknis').html(data.users.teknis);
  //       $('#usr-slo').html(data.users.operasi);

  //       var options = {
  //         series: [{
  //         name: 'Persetujuan Teknis',
  //         data: data.teknis
  //       }, {
  //         name: 'SLO',
  //         data: data.operasi
  //       }],
  //         chart: {
  //         type: 'bar',
  //         height: 400
  //       },
  //       plotOptions: {
  //         bar: {
  //           horizontal: false,
  //           columnWidth: '50%',
  //           endingShape: 'rounded'
  //         },
  //       },
  //       colors: ['#33b2df', '#f9851f'],
  //       dataLabels: {
  //         enabled: false
  //       },
  //       stroke: {
  //         show: true,
  //         width: 2,
  //         colors: ['transparent']
  //       },
  //       xaxis: {
  //         categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
  //       },
  //       yaxis: {
  //         title: {
  //           text: 'Total Permohonan'
  //         }
  //       },
  //       fill: {
  //         opacity: 1
  //       },
  //       tooltip: {
  //         y: {
  //           formatter: function (val) {
  //             return val
  //           }
  //         }
  //       }
  //       };
      
  //       var chart = new ApexCharts(document.querySelector("#chart-all"), options);
  //       chart.render();

  //       var options_pie_teknis = {
  //         chart: {
  //             type: 'pie',
  //             width: '100%',
  //             height: 320
  //         },
  //         dataLabels: {
  //           enabled: true,
  //         },
  //         plotOptions: {
  //           pie: {
  //             customScale: 0.8,
  //             donut: {
  //               size: '75%',
  //             },
  //             offsetY: 20,
  //           },
  //           stroke: {
  //             colors: undefined
  //           }
  //         },
  //         series: pie['teknis'],
  //         labels: ['On Progres', 'Selesai'],
  //         legend: {
  //           position: 'left',
  //           offsetY: 80
  //         }
  //       }
      
  //       var chart_teknis = new ApexCharts(document.querySelector("#chart-teknis"), options_pie_teknis);
  //       chart_teknis.render();

  //       var options_pie_operasi = {
  //         chart: {
  //             type: 'pie',
  //             width: '100%',
  //             height: 320
  //         },
  //         dataLabels: {
  //           enabled: true,
  //         },
  //         plotOptions: {
  //           pie: {
  //             customScale: 0.8,
  //             donut: {
  //               size: '75%',
  //             },
  //             offsetY: 20,
  //           },
  //           stroke: {
  //             colors: undefined
  //           }
  //         },
  //         series: pie['operasi'],
  //         labels: ['On Progres', 'Selesai'],
  //         legend: {
  //           position: 'left',
  //           offsetY: 80
  //         }
  //       }
      
  //       var chart_operasi = new ApexCharts(document.querySelector("#chart-operasi"), options_pie_operasi);
  //       chart_operasi.render();

  //   }
  // })
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

