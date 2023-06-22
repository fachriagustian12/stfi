console.log("You are running jQuery version: " + $.fn.jquery);
$(document).ready(function () {
  pie(JSON.parse(data_pie));
  area(JSON.parse(data_area));

  var date = new Date();
  var tahun = date.getFullYear();
  var bulan = date.getMonth();
  var tanggal = date.getDate();
  var hari = date.getDay();
  var jam = date.getHours();
  var menit = date.getMinutes();
  var detik = date.getSeconds();
  switch (hari) {
    case 0:
      hari = "Minggu";
      break;
    case 1:
      hari = "Senin";
      break;
    case 2:
      hari = "Selasa";
      break;
    case 3:
      hari = "Rabu";
      break;
    case 4:
      hari = "Kamis";
      break;
    case 5:
      hari = "Jum'at";
      break;
    case 6:
      hari = "Sabtu";
      break;
  }
  switch (bulan) {
    case 0:
      bulan = "Januari";
      break;
    case 1:
      bulan = "Februari";
      break;
    case 2:
      bulan = "Maret";
      break;
    case 3:
      bulan = "April";
      break;
    case 4:
      bulan = "Mei";
      break;
    case 5:
      bulan = "Juni";
      break;
    case 6:
      bulan = "Juli";
      break;
    case 7:
      bulan = "Agustus";
      break;
    case 8:
      bulan = "September";
      break;
    case 9:
      bulan = "Oktober";
      break;
    case 10:
      bulan = "November";
      break;
    case 11:
      bulan = "Desember";
      break;
  }
  var tampilTanggal = hari + ", " + tanggal + " " + bulan + " " + tahun;
  var tampilWaktu = "Jam: " + jam + ":" + menit + ":" + detik;
  $("#sekarang").html(tampilTanggal);
});

function pie(datas) {
  var sewa = 0;
  var sewapengalihan = 0;
  var sewabeli = 0;
  var hak = 0;

  sewa = datas["status1"];
  sewapengalihan = datas["status2"];
  sewabeli = datas["status3"];
  hak = datas["status4"];

  $("#labelsewa").html(datas["status1"]);
  $("#labelsewabeli").html(datas["status2"]);
  $("#labellunas").html(datas["status3"]);
  $("#labelhakmilik").html(datas["status4"]);
  $("#labeltotal").html(
    datas["status1"] + datas["status2"] + datas["status3"] + datas["status4"]
  );

  var options = {
    series: [sewa, sewapengalihan, sewabeli, hak],
    chart: {
      width: 370,
      type: "pie",
    },
    legend: {
      position: "right",
    },
    labels: ["Sewa", "Sewa Beli", "Lunas", "Hak Milik"],
    colors: ["#388e3c", "#fbc02d", "#c2185b", "#303f9f"],
    responsive: [
      {
        breakpoint: 480,
        options: {
          chart: {
            width: 200,
          },
          legend: {
            position: "bottom",
          },
        },
      },
    ],
  };

  var chart = new ApexCharts(document.querySelector("#chart"), options);
  chart.render();
}

function area(datas) {
  var pembayaran = [];
  var baseline = [];
  for (let i = 0; i < datas.length; i++) {
    const element = datas[i];
    var bulan = i + 1;
    pembayaran.push([
      bulan,
      element.pembayaran !== undefined ? element.pembayaran : null,
    ]);
    baseline.push([
      bulan,
      element.baseline !== undefined ? element.baseline : null,
    ]);
  }

  var options = {
    series: [
      {
        name: "Pembayaran",
        data: pembayaran,
      },
      {
        name: "Baseline",
        data: baseline,
      },
    ],
    markers: {
      size: [5, 5],
    },
    colors: ["#1a237e", "#b71c1c"],
    chart: {
      type: "area",
      height: 350,
      zoom: {
        enabled: false,
      },
      toolbar: {
        show: false,
      },
    },
    dataLabels: {
      enabled: false,
    },
    stroke: {
      curve: "straight",
    },
    xaxis: {
      type: "month",
      labels: {
        formatter: function (value) {
          var bul = [
            "",
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "Mei",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Okt",
            "Nov",
            "Des",
          ];
          return bul[value];
        },
      },
    },
    yaxis: {
      labels: {
        formatter: function (value) {
          value = value != null ? formatRupiah(value.toString(), "Rp.") : value;
          return value;
        },
      },
    },
    legend: {
      horizontalAlign: "center",
    },
    grid: {
      yaxis: {
        lines: {
          show: true,
        },
      },
      xaxis: {
        lines: {
          show: true,
        },
      },
    },
  };

  var chart = new ApexCharts(document.querySelector("#chart_area"), options);
  chart.render();
}

function formatRupiah(angka, prefix) {
  var number_string = angka.replace(/[^,\d]/g, "").toString(),
    split = number_string.split(","),
    sisa = split[0].length % 3,
    rupiah = split[0].substr(0, sisa),
    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

  // tambahkan titik jika yang di input sudah menjadi angka ribuan
  if (ribuan) {
    separator = sisa ? "." : "";
    rupiah += separator + ribuan.join(".");
  }

  rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
  return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
}
