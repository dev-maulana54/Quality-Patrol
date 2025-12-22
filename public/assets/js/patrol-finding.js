"use strict"

const autocolors = window['chartjs-plugin-autocolors'];

let departmentSelector = $('#departmentPick');
$(document).ready(function () {
  Chart.register(ChartDataLabels);
  Chart.register(autocolors);
  if (departmentSelector.val() !== undefined) {
    patrolFindingByDepartmentOrSection(departmentSelector.val())
    patrolFindingByCause(departmentSelector.val())
    initializedDynamicChartDepartment()
    dynamicDepartmentChoose()
  } else {
    initializedDepartmentChart()
    patrolFindingByDepartmentOrSection()
    patrolFindingByCause()
    initializedDynamicChart()
  }
})

function initializedDepartmentChart() {
  let selectedDepartment = departmentSelector.val();
  if (selectedDepartment !== undefined) {
    patrolFindingByDepartmentOrSection(selectedDepartment)
    patrolFindingByCause(selectedDepartment)
    countCasePatrolFinding(selectedDepartment)
  }
}

function initializedDynamicChart() {
  $('.yearSelectorPatrolFindingByDepartment').change(function () {
    patrolFindingByDepartmentOrSection(undefined, $(this).val())
  })

  $('.yearSelectorPatrolFindingByCause').change(function () {
    let selectedMonth = $('.monthSelectorPatrolFindingByCause').val();
    patrolFindingByCause(undefined, $(this).val(), selectedMonth)
  })

  $('.monthSelectorPatrolFindingByCause').change(function () {
    let selectedYear = $('.yearSelectorPatrolFindingByCause').val();
    patrolFindingByCause(undefined, selectedYear, $(this).val())
  })
}

function initializedDynamicChartDepartment() {
  let selectedDepartment = departmentSelector.val();
  $('.yearSelectorPatrolFindingBySection').change(function () {
    patrolFindingByDepartmentOrSection(selectedDepartment, $(this).val())
  })

  $('.yearSelectorPatrolFindingByCause').change(function () {
    let selectedMonth = $('.monthSelectorPatrolFindingByCause').val();
    patrolFindingByCause(selectedDepartment, $(this).val(), selectedMonth)
  })

  $('.monthSelectorPatrolFindingByCause').change(function () {
    let selectedYear = $('.yearSelectorPatrolFindingByCause').val();
    patrolFindingByCause(selectedDepartment, selectedYear, $(this).val())
  })
}


let patrolFindingByCauseChart;

function patrolFindingByCause(departmentId, selectedYear = new Date().getFullYear(), selectedMonth = new Date().getMonth() + 1) {
  let endpointUrl = `${baseUrl}api/dashboard/patrol-finding/by-cause/${selectedYear}/${selectedMonth}`;
  if (departmentId != null) {
    endpointUrl = `${baseUrl}api/dashboard/patrol-finding/by-cause/${selectedYear}/${selectedMonth}/${departmentId}`;
  }
  $.ajax({
      url: endpointUrl,
      method: 'GET',
      dataType: 'json',
      success: function (response) {
        if (response.length === 0) {
          if (patrolFindingByCauseChart !== undefined) {
            patrolFindingByCauseChart.destroy()
            patrolFindingByCauseChart = undefined
            return
          }
        }
        let ctx = document.getElementById("patrolFindingByCause").getContext('2d');
        let namesLabel = [];
        let dataChart = [];
        const color = [
          'rgba(255, 99, 132)',
          'rgba(54, 162, 235)',
          'rgba(255, 206, 86)',
          'rgba(75, 192, 192)',
          'rgba(153, 102, 255)',
          'rgba(255, 159, 64)',
          'rgba(34,0,255, 64)',
        ];
        for (const data of response) {
          namesLabel.push(data.cause);
          dataChart.push(Number(data.countData));

        }
        if (patrolFindingByCauseChart !== undefined) {
          patrolFindingByCauseChart.data.datasets[0].data = dataChart
          patrolFindingByCauseChart.data.labels = namesLabel
          patrolFindingByCauseChart.update();

        } else {
          patrolFindingByCauseChart = new Chart(ctx, {
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

let patrolFindingByDepartmentOrSectionChart;

function patrolFindingByDepartmentOrSection(departmentId, selectedYear = new Date().getFullYear()) {
  let namesLabel = [];
  let endpointUrl = `${baseUrl}api/dashboard/patrol-finding/by-department/${selectedYear}`;
  if (departmentId !== undefined) {
    endpointUrl = `${baseUrl}api/dashboard/patrol-finding/by-section/${selectedYear}/${departmentId}`
  }
  $.ajax({
      url: endpointUrl,
      method: 'GET',
      dataType: 'json',
      success: function (response) {
        if (response.length === 0) {
          if (patrolFindingByDepartmentOrSectionChart !== undefined) {
            patrolFindingByDepartmentOrSectionChart.destroy()
            patrolFindingByDepartmentOrSectionChart = undefined
          }
          return
        }
        let ctx = document.getElementById("patrolFindingByDepartmentOrSection").getContext('2d');
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
        if (patrolFindingByDepartmentOrSectionChart !== undefined) {
          patrolFindingByDepartmentOrSectionChart.data.datasets[0].data = dataChart.open
          patrolFindingByDepartmentOrSectionChart.data.datasets[1].data = dataChart.closed
          patrolFindingByDepartmentOrSectionChart.data.datasets[2].data = dataChart.in_progress
          patrolFindingByDepartmentOrSectionChart.data.labels = namesLabel
          patrolFindingByDepartmentOrSectionChart.update()
        } else {
          patrolFindingByDepartmentOrSectionChart = new Chart(ctx, {
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
                      size: 10
                    }
                  },
                },
                x: {
                  stacked: true,
                  ticks: {
                    display: true,
                    font: {
                      size: 10
                    },
                    beginAtZero: true,
                  },
                  gridLines: {
                    display: true
                  }
                }
              },
              plugins: {
                legend: {
                  position: 'top',
                  labels: {
                    font: {
                      size: 10
                    }
                  }
                },
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


function countCasePatrolFinding(departmentId) {
  $.ajax({
    url: baseUrl + 'api/dashboard/patrol-finding/count/' + departmentId,
    method: 'GET',
    dataType: 'json',
    success: function (response) {
      $(".countPatrolOpenThisYear").text(Number(response["Open"] ?? 0));
      $(".countPatrolClosedThisYear").text(Number(response["Closed"] ?? 0));
      $(".countPatrolInProgressThisYear").text(Number(response["In Progress"] ?? 0));
      $(".countPatrolAllThisYear").text(Number(response.open ?? 0) + Number(response.closed ?? 0) + Number(response["In Progress"] ?? 0));
    },
    error: function (xhr, status, error) {
      console.error('Error:', error);
      // Tangani kesalahan jika terjadi
    }
  });
}


function dynamicDepartmentChoose() {
  departmentSelector.change(function () {

    let selectedYearPatrolFindingBySection = $('.yearSelectorPatrolFindingBySection').val();
    patrolFindingByDepartmentOrSection(departmentSelector.val(), selectedYearPatrolFindingBySection)

    let yearSelectorPatrolFindingByCause = $('.yearSelectorPatrolFindingByCause').val();
    let monthSelectorPatrolFindingByCause = $('.monthSelectorPatrolFindingByCause').val();
    patrolFindingByCause(departmentSelector.val(), yearSelectorPatrolFindingByCause, monthSelectorPatrolFindingByCause)
    countCasePatrolFinding(departmentSelector.val())
  })

}
