"use strict";
const autocolors = window['chartjs-plugin-autocolors'];

let departmentSelector = $('#departmentPick');
$(document).ready(function () {
  Chart.register(ChartDataLabels);
  Chart.register(autocolors);
  if (departmentSelector.val() !== undefined) {
    let selectedDepartment = departmentSelector.val()
    auditFindingByDepartmentOrSection(selectedDepartment)
    auditFindingByCause(selectedDepartment)
    initializedDynamicChartDepartment()
    dynamicDepartmentChoose()
    countCaseAuditFinding(selectedDepartment)
  } else {
    auditFindingByDepartmentOrSection()
    auditFindingByCause()
    initializedDynamicChart()
  }
})

function initializedDynamicChart() {
  $('.yearSelectorAuditFindingByDepartment').change(function () {
    auditFindingByDepartmentOrSection(undefined, $(this).val())
  })

  $('.yearSelectorAuditFindingByCause').change(function () {
    let selectedMonth = $('.monthSelectorAuditFindingByCause').val()
    auditFindingByCause(undefined, $(this).val(), selectedMonth)
  })

  $('.monthSelectorAuditFindingByCause').change(function () {
    let selectedYear = $('.yearSelectorAuditFindingByCause').val()
    auditFindingByCause(undefined, selectedYear, $(this).val())
  })
}

function initializedDynamicChartDepartment() {
  let selectedDepartment = departmentSelector.val();
  $('.yearSelectorAuditFindingBySection').change(function () {
    auditFindingByDepartmentOrSection(selectedDepartment, $(this).val())
  })

  $('.yearSelectorAuditFindingByCause').change(function () {
    let selectedMonth = $('.monthSelectorAuditFindingByCause').val()
    auditFindingByCause(selectedDepartment, $(this).val(), selectedMonth)
  })

  $('.monthSelectorAuditFindingByCause').change(function () {
    let selectedYear = $('.yearSelectorAuditFindingByCause').val()
    auditFindingByCause(selectedDepartment, selectedYear, $(this).val())
  })
}

let auditFindingByDepartmentOrSectionChart;

function auditFindingByDepartmentOrSection(departmentId, selectedYear = new Date().getFullYear()) {
  let namesLabel = [];
  let endpointUrl = `${baseUrl}api/dashboard/audit-finding/by-department/${selectedYear}`;
  if (departmentId !== undefined) {
    endpointUrl = `${baseUrl}api/dashboard/audit-finding/by-section/${selectedYear}/${departmentId}`;
  }
  $.ajax({
      url: endpointUrl,
      method: 'GET',
      dataType: 'json',
      success: function (response) {
        if (response.length === 0) {
          if (auditFindingByDepartmentOrSectionChart !== undefined) {
            auditFindingByDepartmentOrSectionChart.destroy()
            auditFindingByDepartmentOrSectionChart = undefined
          }
          return
        }
        let ctx = document.getElementById("auditFindingByDepartmentOrSection").getContext('2d');
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
        if (auditFindingByDepartmentOrSectionChart !== undefined) {
          auditFindingByDepartmentOrSectionChart.data.datasets[0].data = dataChart.open
          auditFindingByDepartmentOrSectionChart.data.datasets[1].data = dataChart.closed
          auditFindingByDepartmentOrSectionChart.data.datasets[2].data = dataChart.in_progress
          auditFindingByDepartmentOrSectionChart.data.labels = namesLabel
          auditFindingByDepartmentOrSectionChart.update()
        } else {
          auditFindingByDepartmentOrSectionChart = new Chart(ctx, {
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


let auditFindingByCauseChart;

function auditFindingByCause(departmentId, selectedYear = new Date().getFullYear(), selectedMonth = new Date().getMonth() + 1) {
  let endpointUrl = baseUrl + 'api/dashboard/audit-finding/by-cause/' + selectedYear + '/' + selectedMonth;
  if (departmentId != null) {
    endpointUrl = baseUrl + 'api/dashboard/audit-finding/by-cause/' + selectedYear + '/' + selectedMonth + '/' + departmentId;
  }
  $.ajax({
      url: endpointUrl,
      method: 'GET',
      dataType: 'json',
      success: function (response) {
        if (response.length === 0) {
          if (auditFindingByCauseChart !== undefined) {
            auditFindingByCauseChart.destroy()
            auditFindingByCauseChart = undefined
            return
          }
        }
        const color = [];
        let ctx = document.getElementById("auditFindingByCause").getContext('2d');
        let namesLabel = [];
        let dataChart = [];
        for (const data of response) {
          namesLabel.push(data.status);
          dataChart.push(Number(data.countData));
          switch (data.status) {
            case "Major":
              color.push("#ff0000")
              break;
            case "Minor":
              color.push("#FFFF00")
              break;
            case "OFI":
              color.push("#ffA500")
          }
        }
        if (auditFindingByCauseChart !== undefined) {
          auditFindingByCauseChart.data.datasets[0].data = dataChart
          auditFindingByCauseChart.data.labels = namesLabel
          auditFindingByCauseChart.update();
        } else {
          auditFindingByCauseChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
              datasets: [{
                data: dataChart,
                hoverBorderColor: 'rgba(200, 200, 200, 1)',
                backgroundColor: color
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


function countCaseAuditFinding(departmentId) {
  $.ajax({
    url: baseUrl + 'api/dashboard/audit-finding/count/' + departmentId,
    method: 'GET',
    dataType: 'json',
    success: function (response) {
      $(".totalCountAuditFindingOpen").text(Number(response.Open ?? 0));
      $(".totalCountAuditFindingClosed").text(Number(response.Closed ?? 0));
      $(".totalCountAuditFindingInProgress").text(Number(response["In Progress"] ?? 0));
      $(".totalCountAuditFindingAll").text(Number(response.open ?? 0) + Number(response.closed ?? 0) + Number(response.in_progress ?? 0));
    },
    error: function (xhr, status, error) {
      console.error('Error:', error);
      // Tangani kesalahan jika terjadi
    }
  });
}

function countCaseAuditFindingThisYear(departmentId) {
  $.ajax({
    url: `${baseUrl}api/dashboard/audit-finding/count/${departmentId}/true`,
    method: 'GET',
    dataType: 'json',
    success: function (response) {
      $(".totalCountAuditFindingThisYearOpen").text(Number(response.Open ?? 0));
      $(".totalCountAuditFindingThisYearClosed").text(Number(response.Closed ?? 0));
      $(".totalCountAuditFindingThisYearInProgress").text(Number(response["In Progress"] ?? 0));
      $(".totalCountAuditFindingThisYearAll").text(Number(response.open ?? 0) + Number(response.closed ?? 0) + Number(response.in_progress ?? 0));
    },
    error: function (xhr, status, error) {
      console.error('Error:', error);
      // Tangani kesalahan jika terjadi
    }
  });
}


function dynamicDepartmentChoose() {
  departmentSelector.change(function () {
    let selectedYearAuditFindingBySection = $('.yearSelectorAuditFindingBySection').val();
    auditFindingByDepartmentOrSection(departmentSelector.val(), selectedYearAuditFindingBySection)

    let selectedYearAuditFindingBySeverity = $('.yearSelectorAuditFindingByCause').val();
    let selectedMonthAuditFindingBySeverity = $('.monthSelectorAuditFindingByCause').val();
    auditFindingByCause(departmentSelector.val(), selectedYearAuditFindingBySeverity, selectedMonthAuditFindingBySeverity)

    countCaseAuditFinding(departmentSelector.val())
    countCaseAuditFindingThisYear(departmentSelector.val())
  })

}
