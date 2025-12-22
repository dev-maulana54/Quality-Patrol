"use strict";

const autocolors = window['chartjs-plugin-autocolors']
let departmentSelector = $('#departmentPick');

$(document).ready(function () {
  Chart.register(ChartDataLabels);
  Chart.register(autocolors);
  if (departmentSelector.val() !== undefined) {
    let selectedDepartment = departmentSelector.val()
    hazardIdentificationByDepartmentOrSection(selectedDepartment)
    hazardIdentificationByCause(selectedDepartment)
    initializedDynamicChartDepartment()
    dynamicDepartmentChoose(selectedDepartment)
  } else {
    hazardIdentificationByDepartmentOrSection()
    hazardIdentificationByCause()
    initializedDynamicChart()
  }
})

function initializedDynamicChart() {
  $('.yearSelectorHazardIdentificationByDepartment').change(function () {
    hazardIdentificationByDepartmentOrSection(undefined, $(this).val())
  })

  $('.yearSelectorHazardIdentificationCause').change(function () {
    let selectedMonth = $('.monthSelectorHazardIdentificationCause').val()
    hazardIdentificationByCause(undefined, $(this).val(), selectedMonth)
  })


  $('.monthSelectorHazardIdentificationCause').change(function () {
    let selectedYear = $('.yearSelectorHazardIdentificationCause').val()
    hazardIdentificationByCause(undefined, selectedYear, $(this).val())
  })
}

function initializedDynamicChartDepartment() {
  let selectedDepartment = departmentSelector.val();
  $('.yearSelectorHazardIdentificationBySection').change(function () {
    hazardIdentificationByDepartmentOrSection(selectedDepartment, $(this).val())
  })

  $('.yearSelectorHazardIdentificationCause').change(function () {
    let selectedMonth = $('.monthSelectorHazardIdentificationCause').val()
    hazardIdentificationByCause(selectedDepartment, $(this).val(), selectedMonth)
  })


  $('.monthSelectorHazardIdentificationCause').change(function () {
    let selectedYear = $('.yearSelectorHazardIdentificationCause').val()
    hazardIdentificationByCause(selectedDepartment, selectedYear, $(this).val())
  })
}

let hazardIdentificationByDepartmentOrSectionChart;

function hazardIdentificationByDepartmentOrSection(departmentId, selectedYear = new Date().getFullYear()) {
  let namesLabel = [];
  let endpointUrl = `${baseUrl}api/dashboard/hazard-identification/by-department/${selectedYear}`;
  if (departmentId !== undefined) {
    endpointUrl = `${baseUrl}api/dashboard/hazard-identification/by-section/${selectedYear}/${departmentId}`;
  }
  $.ajax({
      url: endpointUrl,
      method: 'GET',
      dataType: 'json',
      success: function (response) {
        if (response.length === 0) {
          if (hazardIdentificationByDepartmentOrSectionChart !== undefined) {
            hazardIdentificationByDepartmentOrSectionChart.destroy()
            hazardIdentificationByDepartmentOrSectionChart = undefined
          }
          return
        }
        let ctx = document.getElementById("hazardIdentificationByDepartmentOrSection").getContext('2d');
        let dataChart = {
          open: [],
          closed: [],
          in_progress: []
        };


        for (const data of response) {
          namesLabel.push(data.abbreviation ?? data.name);
          dataChart["open"].push(String(data.total_open));
          dataChart["closed"].push(String(data.total_closed));
          dataChart["in_progress"].push(String(data.total_in_progress));
        }
        if (hazardIdentificationByDepartmentOrSectionChart !== undefined) {
          hazardIdentificationByDepartmentOrSectionChart.data.datasets[0].data = dataChart.open
          hazardIdentificationByDepartmentOrSectionChart.data.datasets[1].data = dataChart.closed
          hazardIdentificationByDepartmentOrSectionChart.data.datasets[2].data = dataChart.in_progress
          hazardIdentificationByDepartmentOrSectionChart.data.labels = namesLabel
          hazardIdentificationByDepartmentOrSectionChart.update()
        } else {
          hazardIdentificationByDepartmentOrSectionChart = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: namesLabel,
              datasets: [{
                borderWidth: 2,
                pointRadius: 4,
                data: dataChart.open,
                backgroundColor: "rgba(245, 0, 0, 1)",
                label: "Open"
              }, {
                borderWidth: 2,
                pointRadius: 4,
                data: dataChart.closed,
                label: "Closed",
                backgroundColor: "rgba(0, 255, 0, 1)"
              }, {
                borderWidth: 2,
                pointRadius: 4,
                data: dataChart.in_progress,
                label: "In Progress",
                backgroundColor: "rgba(245, 245, 0, 1)"
              }],
            },
            options: {
              responsive: true,
              legend: {
                display: true
              },
              scales: {
                y: {
                  stacked: true,
                  gridLines: {
                    drawBorder: false,
                    color: '#f2f2f2',
                  },
                  ticks: {
                    beginAtZero: true,
                    font: {
                      size: 8
                    }
                  },
                },
                x: {
                  stacked: true,
                  ticks: {
                    display: true,
                    font: {
                      size: 8
                    },
                    beginAtZero: true,
                  },
                  gridLines: {
                    display: true
                  }
                }
              },
              plugins: {
                datalabels: {
                  color: '#fff',
                  clamp: true,
                  font: {
                    size: 14,
                  },
                  formatter: function (value, index, values) {
                    if (value > 0) {
                      value = value.toString();
                      value = value.split(/(?=(?:...)*$)/);
                      value = value.join(',');
                      return value;
                    } else {
                      value = "";
                      return value;
                    }
                  }
                },
              }
            }
          });
        }
      },
      error: function (xhr, status, error) {
        // Handling error
        console.error(error); // Tampilkan pesan error ke konsol atau lakukan penanganan error lainnya sesuai kebutuhan
      }
    }
  );
}


let hazardIdentificationByCauseChart;

function hazardIdentificationByCause(departmentId, selectedYear = new Date().getFullYear(), selectedMonth = new Date().getMonth() + 1) {
  let endpointUrl = baseUrl + 'api/dashboard/hazard-identification/by-cause/' + selectedYear + '/' + selectedMonth;
  if (departmentId != null) {
    endpointUrl = baseUrl + 'api/dashboard/hazard-identification/by-cause/' + selectedYear + '/' + selectedMonth + '/' + departmentId;
  }
  $.ajax({
      url: endpointUrl,
      method: 'GET',
      dataType: 'json',
      success: function (response) {
        if (response.length === 0) {
          if (hazardIdentificationByCauseChart !== undefined) {
            hazardIdentificationByCauseChart.destroy()
            hazardIdentificationByCauseChart = undefined
            return
          }
        }
        const color = [
          'rgba(255, 99, 132)',
          'rgba(54, 162, 235)',
          'rgba(255, 206, 86)',
          'rgba(75, 192, 192)',
          'rgba(153, 102, 255)',
          'rgba(255, 159, 64)'
        ];
        let ctx = document.getElementById("hazardIdentificationByCause").getContext('2d');
        let namesLabel = [];
        let dataChart = [];
        for (const data of response) {
          namesLabel.push(data.cause);
          dataChart.push(Number(data.countData));
        }
        if (hazardIdentificationByCauseChart !== undefined) {
          hazardIdentificationByCauseChart.data.datasets[0].data = dataChart
          hazardIdentificationByCauseChart.data.labels = namesLabel
          hazardIdentificationByCauseChart.update();

        } else {
          hazardIdentificationByCauseChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
              datasets: [{
                data: dataChart,
                hoverBorderColor: 'rgba(200, 200, 200, 1)',
                backgroundColor: color,
              }],
              labels: namesLabel,
            },
            options: {
              responsive: false,
              legend: {
                position: 'bottom',
              },
              plugins: {
                datalabels: {
                  color: '#fff',
                  clamp: true,
                  font: {
                    size: 14,
                  }
                },
              }
            }

          });
        }
      },
      error: function (xhr, status, error) {
        // Handling error
        console.error(error); // Tampilkan pesan error ke konsol atau lakukan penanganan error lainnya sesuai kebutuhan
      }
    }
  );
}

function countCaseHazardIdentification(departmentId) {
  $.ajax({
    url: baseUrl + 'api/dashboard/hazard-identification/count/' + departmentId,
    method: 'GET',
    dataType: 'json',
    success: function (response) {
      $(".totalCountHazardIdentificationOpen").text(Number(response['accumulative']["Open"] ?? 0));
      $(".totalCountHazardIdentificationClosed").text(Number(response['accumulative']["Closed"] ?? 0));
      $(".totalCountHazardIdentificationInProgress").text(Number(response['accumulative']["In Progress"] ?? 0));
      $(".totalCountHazardIdentificationAll").text(Number(response['accumulative']["Open"] ?? 0) + Number(response['accumulative']["Closed"] ?? 0) + Number(response['accumulative']["In Progress"] ?? 0));

      $(".totalCountHazardIdentificationThisYearOpen").text(Number(response["year-now"].Open ?? 0));
      $(".totalCountHazardIdentificationThisYearClosed").text(Number(response["year-now"].Closed ?? 0));
      $(".totalCountHazardIdentificationThisYearInProgress").text(Number(response["year-now"]["In Progress"] ?? 0));
      $(".totalCountHazardIdentificationThisYearAll").text(Number(response["year-now"]["Open"] ?? 0) + Number(response["year-now"]["Closed"] ?? 0) + Number(response["year-now"]["In Progress"] ?? 0));

    },
    error: function (xhr, status, error) {
      console.error('Error:', error);
      // Tangani kesalahan jika terjadi
    }
  });
}


function dynamicDepartmentChoose() {
  departmentSelector.change(function () {
    let selectedYearHazardIdentificationBySection = $('.yearSelectorHazardIdentificationBySection').val();
    hazardIdentificationByDepartmentOrSection(departmentSelector.val(), selectedYearHazardIdentificationBySection)

    let selectedYearHazardIdentificationByCause = $('.yearSelectorHazardIdentificationCause').val();
    let selectedMonthHazardIdentificationByCause = $('.monthSelectorHazardIdentificationCause').val();
    hazardIdentificationByCause(departmentSelector.val(), selectedYearHazardIdentificationByCause, selectedMonthHazardIdentificationByCause)


    countCaseHazardIdentification(departmentSelector.val())
  })

}
