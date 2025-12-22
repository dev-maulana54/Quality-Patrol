"use strict";
const autocolors = window['chartjs-plugin-autocolors'];

let departmentSelector = $('#departmentPick');
$(document).ready(function () {
  Chart.register(ChartDataLabels);
  Chart.register(autocolors);
  if (departmentSelector.val() !== undefined) {
    let selectedDepartment = departmentSelector.val();
    workAccidentByDepartmentOrSection(selectedDepartment)
    workAccidentBySeverity(selectedDepartment)
    workAccidentByMonth(selectedDepartment)
    workAccidentByMonthLastThreeYears(selectedDepartment)
    updateCountWorkAccident(selectedDepartment)
    workAccidentByMonthToDate(selectedDepartment)
    initializedDynamicChartDepartment()
    dynamicDepartmentChoose()
  } else {
    workAccidentByDepartmentOrSection()
    workAccidentBySeverity()
    workAccidentByMonthLastThreeYears()
    workAccidentByMonth()
    workAccidentByMonthToDate()
    initializedDynamicChart()
  }
})

function initializedDynamicChart() {
  $('.yearSelectorWorkAccidentByDepartment').change(function () {
    workAccidentByDepartmentOrSection(undefined, $(this).val())
  })
  $('.yearSelectorWorkAccidentBySeverity').change(function () {
    let selectedMonth = $('.monthSelectorWorkAccidentBySeverity').val();
    workAccidentBySeverity(undefined, $(this).val(), selectedMonth)
  })

  $('.monthSelectorWorkAccidentBySeverity').change(function () {
    let selectedYear = $('.yearSelectorWorkAccidentBySeverity').val();
    workAccidentBySeverity(undefined, selectedYear, $(this).val())
  })

  $('.yearSelectorWorkAccidentByCause').change(function () {
    let selectedMonth = $('.monthSelectorWorkAccidentByCause').val();
    updateCountWorkAccident(undefined, $(this).val(), selectedMonth)
  })

  $('.monthSelectorWorkAccidentByCause').change(function () {
    let selectedYear = $('.yearSelectorWorkAccidentByCause').val();
    updateCountWorkAccident(undefined, selectedYear, $(this).val())
  })

  $('.yearSelectorWorkAccidentByMonth').change(function () {
    workAccidentByMonth(undefined, $(this).val())
  })

  $('.yearSelectorWorkAccidentMonthToDate').change(function () {
    let selectedMonth = $('.monthSelectorWorkAccidentMonthToDate').val();
    workAccidentByMonthToDate(undefined, $(this).val(), selectedMonth)
  })

  $('.monthSelectorWorkAccidentMonthToDate').change(function () {
    let selectedYear = $('.yearSelectorWorkAccidentMonthToDate').val()
    workAccidentByMonthToDate(undefined, selectedYear, $(this).val())
  })
}

function initializedDynamicChartDepartment() {
  $('.yearSelectorWorkAccidentBySection').change(function () {

    workAccidentByDepartmentOrSection(departmentSelector.val(), $(this).val())
  })
  $('.yearSelectorWorkAccidentBySeverity').change(function () {
    let selectedMonth = $('.monthSelectorWorkAccidentBySeverity').val();
    workAccidentBySeverity(departmentSelector.val(), $(this).val(), selectedMonth)
  })

  $('.monthSelectorWorkAccidentBySeverity').change(function () {
    let selectedYear = $('.yearSelectorWorkAccidentBySeverity').val();
    workAccidentBySeverity(departmentSelector.val(), selectedYear, $(this).val())
  })

  $('.yearSelectorWorkAccidentByCause').change(function () {
    let selectedMonth = $('.monthSelectorWorkAccidentByCause').val();
    updateCountWorkAccident(departmentSelector.val(), $(this).val(), selectedMonth)
  })

  $('.monthSelectorWorkAccidentByCause').change(function () {
    let selectedYear = $('.yearSelectorWorkAccidentByCause').val();
    updateCountWorkAccident(departmentSelector.val(), selectedYear, $(this).val())
  })

  $('.yearSelectorWorkAccidentByMonth').change(function () {
    workAccidentByMonth(departmentSelector.val(), $(this).val())
  })

  $('.yearSelectorWorkAccidentMonthToDate').change(function () {
    let selectedMonth = $('.monthSelectorWorkAccidentMonthToDate').val()
    workAccidentByMonthToDate(departmentSelector.val(), $(this).val(), selectedMonth)
  })

  $('.monthSelectorWorkAccidentMonthToDate').change(function () {
    let selectedYear = $('.yearSelectorWorkAccidentMonthToDate').val()
    workAccidentByMonthToDate(departmentSelector.val(), selectedYear, $(this).val())
  })
}

let workAccidentByDepartmentOrSectionChart;

function workAccidentByDepartmentOrSection(departmentId, year = new Date().getFullYear()) {
  let endpointUrl = `${baseUrl}api/dashboard/work-accident/by-department/${year}`;
  if (departmentId !== undefined) {
    endpointUrl = `${baseUrl}api/dashboard/work-accident/by-section/${year}/${departmentId}`
  }
  $.ajax({
    url: endpointUrl,
    method: 'GET',
    dataType: 'json',
    success: function (response) {
      if (response.length === 0) {
        if (workAccidentByDepartmentOrSectionChart !== undefined) {
          workAccidentByDepartmentOrSectionChart.destroy()
        }
        workAccidentByDepartmentOrSectionChart = undefined
        return
      }
      const ctx = document.getElementById("workAccidentByDepartment").getContext('2d');
      let namesLabel = [];
      let dataChart = {
        apparatus: [],
        big_heavy: [],
        car: [],
        drop_fall: [],
        electrical: [],
        fire: [],
        others: []
      };

      for (const data of response) {
        namesLabel.push(data.abbreviation ?? data.name)
        dataChart.apparatus.push(String(data.total_apparatus));
        dataChart.big_heavy.push(String(data.total_big_heavy));
        dataChart.car.push(String(data.total_car));
        dataChart.drop_fall.push(String(data.total_drop_fall));
        dataChart.electrical.push(String(data.total_electrical));
        dataChart.fire.push(String(data.total_electrical));
        dataChart.others.push(String(data.total_other));
      }
      if (workAccidentByDepartmentOrSectionChart !== undefined) {
        workAccidentByDepartmentOrSectionChart.data.datasets[0].data = dataChart["apparatus"];
        workAccidentByDepartmentOrSectionChart.data.datasets[1].data = dataChart["big_heavy"];
        workAccidentByDepartmentOrSectionChart.data.datasets[2].data = dataChart["car"];
        workAccidentByDepartmentOrSectionChart.data.datasets[3].data = dataChart["drop_fall"];
        workAccidentByDepartmentOrSectionChart.data.datasets[4].data = dataChart["electrical"];
        workAccidentByDepartmentOrSectionChart.data.datasets[5].data = dataChart["fire"];
        workAccidentByDepartmentOrSectionChart.data.datasets[6].data = dataChart["others"];
        workAccidentByDepartmentOrSectionChart.data.labels = namesLabel
        workAccidentByDepartmentOrSectionChart.update()
      } else {
        workAccidentByDepartmentOrSectionChart = new Chart(ctx, {
          type: 'bar',
          data: {
            datasets: [
              {
                data: dataChart["apparatus"],
                label: "Apparatus",
                backgroundColor: 'rgba(255, 99, 132)',
              },
              {
                data: dataChart["big_heavy"],
                label: "Big Heavy",
                backgroundColor: 'rgba(54, 162, 235)',
              },
              {
                data: dataChart["car"],
                label: "Car",
                backgroundColor: 'rgba(255, 206, 86)',
              },
              {
                data: dataChart["drop_fall"],
                label: "Drop Fall",
                backgroundColor: 'rgba(75, 192, 192)',
              },
              {
                data: dataChart["electrical"],
                label: "Electrical",
                backgroundColor: 'rgba(153, 102, 255)',
              },
              {
                data: dataChart["fire"],
                label: "Fire",
                backgroundColor: 'rgba(255, 159, 64)',
              },
              {
                data: dataChart["others"],
                label: "Others",
                backgroundColor: 'rgba(34,0,255, 64)',
              },],
            labels: namesLabel,
          },
          options: {
            responsive: true,
            legend: {
              position: 'top',
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
                }
              }, x: {
                stacked: true,
                ticks:
                  {
                    display: true,
                    font: {
                      size: 8
                    }
                  }
                ,
                gridLines: {
                  display: true
                }
              }
            },
            plugins: {
              datalabels: {
                color: '#fff',
                clamp:
                  true,
                font:
                  {
                    size: 14,
                  }
                ,
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
        })
        ;
      }
    },

    error: function (xhr, status, error) {
      // Handling error
      console.error(error); // Tampilkan pesan error ke konsol atau lakukan penanganan error lainnya sesuai kebutuhan
    }
  });
}

let workAccidentBySeverityChart;

function workAccidentBySeverity(departmentId, selectedYear = new Date().getFullYear(), selectedMonth = new Date().getMonth() + 1) {
  let url = `${baseUrl}api/dashboard/work-accident/by-severity/${selectedYear}/${selectedMonth}`
  if (departmentId !== undefined) {
    url = `${baseUrl}api/dashboard/work-accident/by-severity/${selectedYear}/${selectedMonth}/${departmentId}`
  }
  $.ajax({
      url: url,
      method: 'GET',
      dataType: 'json',
      success: function (response) {
        if (response.length === 0) {
          if (workAccidentBySeverityChart !== undefined) {
            workAccidentBySeverityChart.destroy()
          }
          workAccidentBySeverityChart = undefined
          return
        }
        const ctx = document.getElementById("workAccidentBySeverity").getContext('2d');
        let namesLabel = [];
        let color = [];
        let dataChart = [];
        for (const data of response) {
          namesLabel.push(data.severity);
          dataChart.push(Number(data.countData));
          switch (data.severity) {
            case "First Aid Injury":
              color.push("#ffA500")
              break
            case "Lost Time Injury":
              color.push("#ff0000")
              break
            case "Fatality":
              color.push("#000")
              break
          }
        }
        if (workAccidentBySeverityChart !== undefined) {
          workAccidentBySeverityChart.data.datasets[0].data = dataChart
          workAccidentBySeverityChart.data.datasets[0].backgroundColor = color
          workAccidentBySeverityChart.data.labels = namesLabel
          workAccidentBySeverityChart.update()
        } else {
          workAccidentBySeverityChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
              datasets: [{
                data: dataChart,
                backgroundColor: color
              }],
              labels: namesLabel,
            },
            options: {
              plugins: {
                datalabels: {
                  color: '#fff',
                  clamp: true,
                  font: {
                    size: 14,
                  },
                },
              },
              responsive: false,
              legend: {
                position: 'bottom',
              },
            }
          })
        }
      },
      error:
        function (xhr, status, error) {
          // Handling error
          console.error(error); // Tampilkan pesan error ke konsol atau lakukan penanganan error lainnya sesuai kebutuhan
        }
    }
  );
}

let workAccidentByMonthToDateChart;

function workAccidentByMonthToDate(departmentId, selectedYear = new Date().getFullYear(), selectedMonth = new Date().getMonth() + 1) {
  let url = `${baseUrl}api/dashboard/work-accident/by-month-to-date/${selectedYear}/${selectedMonth}`
  if (departmentId !== undefined) {
    url = `${baseUrl}api/dashboard/work-accident/by-month-to-date/${selectedYear}/${selectedMonth}/${departmentId}`
  }
  $.ajax({
      url: url,
      method: 'GET',
      dataType: 'json',
      success: function (response) {
        if (response.length === 0) {
          if (workAccidentByMonthToDateChart !== undefined) {
            workAccidentByMonthToDateChart.destroy()
          }
          workAccidentByMonthToDateChart = undefined
          return
        }

        const ctx = document.getElementById("workAccidentByMonthToDate").getContext('2d');
        if (workAccidentByMonthToDateChart !== undefined) {
          workAccidentByMonthToDateChart.data.datasets[0].data = response['count'];
          workAccidentByMonthToDateChart.data.datasets[0].label = response['year'];
          workAccidentByMonthToDateChart.data.labels = response['year'];
          workAccidentByMonthToDateChart.update()
        } else {
          workAccidentByMonthToDateChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
              datasets: [
                {
                  data: response['count'],
                  label: response['year'],
                  backgroundColor: ['rgb(0,149,255)', 'rgb(31,193,31)']
                }
              ],
              labels: response['year'],
            },
            options: {
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
              },
              responsive: false,
              legend: {
                position: 'bottom',
              },
            }
          })
        }
      },
      error:
        function (xhr, status, error) {
          // Handling error
          console.error(error); // Tampilkan pesan error ke konsol atau lakukan penanganan error lainnya sesuai kebutuhan
        }
    }
  );
}


let workAccidentByMonthLastThreeYearsChart;

function workAccidentByMonthLastThreeYears(departmentId) {
  let endpointUrl = baseUrl + 'api/dashboard/work-accident/by-timestamp-last-three-years';
  if (departmentId !== undefined) {
    endpointUrl = baseUrl + 'api/dashboard/work-accident/by-timestamp-last-three-years/' + departmentId;
  }
  $.ajax({
      url: endpointUrl,
      method: 'GET',
      dataType: 'json',
      success: function (response) {
        if (response.length === 0) {
          if (workAccidentByMonthLastThreeYearsChart !== undefined) {
            workAccidentByMonthLastThreeYearsChart.destroy()
          }
          workAccidentByMonthLastThreeYearsChart = undefined
          return
        }
        let ctx = document.getElementById("workAccidentByMonthLastThreeYears").getContext('2d');
        let dataChart = []
        let namesLabel = []
        for (const data of response) {
          namesLabel.push(data.year);
          dataChart.push(data.count_data);
        }
        if (workAccidentByMonthLastThreeYearsChart !== undefined) {
          workAccidentByMonthLastThreeYearsChart.data.datasets[0].data = dataChart
          workAccidentByMonthLastThreeYearsChart.labels = namesLabel
          workAccidentByMonthLastThreeYearsChart.update()
        } else {
          workAccidentByMonthLastThreeYearsChart = new Chart(ctx, {
            type: 'bar',
            plugins: [ChartDataLabels],
            data: {
              labels: namesLabel,
              datasets: [
                {
                  borderWidth: 2,
                  pointRadius: 4,
                  data: dataChart,
                }
              ],
            },
            options: {
              plugins: {
                legend: {
                  display: false
                },
                datalabels: {
                  color: '#fff',
                  clamp: true,
                  font: {
                    size: 14,
                  }
                },
              },
              scales: {
                y: {
                  gridLines: {
                    drawBorder: false,
                    color: '#f2f2f2',
                  },
                  ticks: {
                    beginAtZero: true,
                  }
                },
                x: {
                  ticks: {
                    display: true
                  },
                  gridLines: {
                    display: true
                  }
                },
              },
            },
          });
        }
      }
      ,
      error: function (xhr, status, error) {
        // Handling error
        console.error(error); // Tampilkan pesan error ke konsol atau lakukan penanganan error lainnya sesuai kebutuhan
      }
    }
  );
}

let workAccidentByMonthChart;

function workAccidentByMonth(departmentId, selectedYear = new Date().getFullYear()) {
  let endpointUrl = baseUrl + `api/dashboard/work-accident/by-timestamp/${selectedYear}`;
  if (departmentId !== undefined) {
    endpointUrl = baseUrl + `api/dashboard/work-accident/by-timestamp/${selectedYear}/${departmentId}`;
  }
  $.ajax({
      url: endpointUrl,
      method: 'GET',
      dataType: 'json',
      success: function (response) {
        if (response.length === 0) {
          if (workAccidentByMonthChart !== undefined) {
            workAccidentByMonthChart.destroy()
          }
          workAccidentByMonthChart = undefined
          return
        }
        let ctx = document.getElementById("workAccidentByMonth").getContext('2d');
        let namesLabel = [];
        let dataChart = [];
        for (const data of response) {
          namesLabel.push(data.month);
          dataChart.push(Number(data.countData))
        }
        if (workAccidentByMonthChart !== undefined) {
          workAccidentByMonthChart.data.datasets[0].data = dataChart
          workAccidentByMonthChart.data.labels = namesLabel
          workAccidentByMonthChart.update()
        } else {
          workAccidentByMonthChart = new Chart(ctx, {
            type: 'bar',
            plugins: [ChartDataLabels],
            data: {
              labels: namesLabel,
              datasets: [{
                data: dataChart,
                borderWidth: 2.5,
                pointBackgroundColor: '#ffffff',
                pointRadius: 4,
              },
              ],
            },
            options: {
              plugins: {
                legend: {
                  display: false
                },
                datalabels: {
                  color: '#fff',
                  clamp: true,
                  font: {
                    size: 14,
                  },
                },
              },

              scales: {
                y: {
                  gridLines: {
                    drawBorder: false,
                    color: '#f2f2f2',
                  },
                  ticks: {
                    beginAtZero: true,
                  }
                },
                x: {
                  ticks: {
                    display: true
                  },
                  gridLines: {
                    display: false
                  }
                }
              },
            },
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

function updateCountWorkAccident(departmentId, selectedYear = new Date().getFullYear(), selectedMonth = new Date().getMonth() + 1) {
  let endpointUrl = `${baseUrl}api/dashboard/work-accident/count/${selectedYear}/${selectedMonth}`
  if (departmentId !== undefined) {
    endpointUrl = `${endpointUrl}/${departmentId}`;
  }
  $(document).ready(function () {
    $.ajax({
      url: endpointUrl,
      method: 'GET',
      dataType: 'json',
      success: function (response) {
        console.log(response)
        $(".workAccApparatus").text(response["Apparatus"] ?? 0);
        $(".workAccBigHeavy").text(response["Big Heavy"] ?? 0);
        $(".workAccCar").text(response["Car"] ?? 0);
        $(".workAccDropFall").text(response["Drop Fall"] ?? 0);
        $(".workAccElectricity").text(response["Electricity"] ?? 0);
        $(".workAccFire").text(response["Fire"] ?? 0);
        $(".workAccOthers").text(response["Others"] ?? 0);
      },
      error: function (xhr, status, error) {
        console.error('Error:', error);
        // Tangani kesalahan jika terjadi
      }
    });
  });
}


function dynamicDepartmentChoose() {
  departmentSelector.change(function () {
    $('.patrol-finding-routing').attr('href', `${baseUrl}patrol-finding/${$(this).val()}`);
    $('.audit-finding-routing').attr('href', `${baseUrl}audit-finding/${$(this).val()}`);
    $('.hazard-identification-routing').attr('href', `${baseUrl}hazard-identification/${$(this).val()}`);

    let selectedYearWorkAccidentBySection = $('.yearSelectorWorkAccidentBySection').val();
    workAccidentByDepartmentOrSection(departmentSelector.val(), selectedYearWorkAccidentBySection)

    let selectedYearWorkAccidentBySeverity = $('.yearSelectorWorkAccidentBySeverity').val();
    let selectedMonthWorkAccidentBySeverity = $('.monthSelectorWorkAccidentBySeverity').val();
    workAccidentBySeverity(departmentSelector.val(), selectedYearWorkAccidentBySeverity, selectedMonthWorkAccidentBySeverity)

    let selectedYearWorkAccidentByMonth = $('.yearSelectorWorkAccidentByMonth').val();
    workAccidentByMonth(departmentSelector.val(), selectedYearWorkAccidentByMonth)

    workAccidentByMonthLastThreeYears(departmentSelector.val())

    let selectedYearWorkAccidentByCause = $('.yearSelectorWorkAccidentByCause').val();
    let selectedMonthWorkAccidentByCause = $('.monthSelectorWorkAccidentByCause').val();
    updateCountWorkAccident(departmentSelector.val(), selectedYearWorkAccidentByCause, selectedMonthWorkAccidentByCause)

    let selectedYearWorkAccidentByMonthToDate = $('.yearSelectorWorkAccidentByMonthToDate').val();
    let selectedMonthWorkAccidentByMonthToDate = $('.monthSelectorWorkAccidentByMonthToDate').val();
    workAccidentByMonthToDate(departmentSelector.val(), selectedYearWorkAccidentByMonthToDate, selectedMonthWorkAccidentByMonthToDate)

  })

}