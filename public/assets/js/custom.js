/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";
let baseUrl = $('.baseUrl').val();

function sweetAlertDeleteOperation(id, reqPath) {
  swal({
    title: 'Apakah Anda yakin ingin hapus?',
    text: 'Ketika dihapus, data tidak dapat dikembalikan lagi!',
    icon: 'warning',
    buttons: true,
    dangerMode: true,
  })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          url: baseUrl + 'admin/' + reqPath + '/delete/' + id,
          type: 'DELETE',
          error: function () {
            willDelete = false;
          },
          success: function () {
            willDelete = true;
          }
        });
        swal('Poof! Data telah hilang!', {
          icon: 'success',
        }).then((confirm) => {
          if (confirm) {
            // Tindakan setelah penghapusan berhasil
            // Misalnya, memuat ulang halaman atau menavigasi ke halaman lain
            location.reload(); // Contoh: memuat ulang halaman
          }
        });
      } else {
        swal('Data batal dihapus!');
      }
    });
}

function defaultPreviewLoad() {
  // Fungsi untuk menampilkan gambar default
  let oldImage = $('.old_image_path').val();
  if (oldImage !== undefined) {
    $('#image-preview').css({
      'background-image': `url(${baseUrl}/${oldImage})`,
      'background-size': 'cover',
      'background-position': 'center center',
      'display': 'block'
    });
    // Mengubah label
    $('#image-label').text('Change File');
  }

  let secondOldImagePath = $('.second_old_image_path').val()
  if (secondOldImagePath !== undefined) {
    $('#image-preview2').css({
      'background-image': `url(${baseUrl}/${secondOldImagePath})`,
      'background-size': 'cover',
      'background-position': 'center center',
      'display': 'block'
    });
    // Mengubah label
    $('#image-label').text('Change File');
  }
  console.log(oldImage, secondOldImagePath)

}

$(document).ready(function () {
  dynamicDepartmentInput()
  dynamicImprovementRecommendation()
  exportFilter()
  checkNotifications();
  datepickerFunc()
  defaultPreviewLoad()
});

function exportFilter() {
  let exportFilterRadio = $('input[name="value"]');
  if (exportFilterRadio.length !== 0) {
    $('.export-filter').hide();
    $('.export-filter.date').show();
    // Ketika nilai radio button berubah
    exportFilterRadio.change(function () {
      // Semua form-group disembunyikan
      $('.export-filter').hide();

      // Mengambil nilai radio button yang dipilih
      var selectedValue = $(this).val();

      // Menampilkan form-group yang sesuai dengan nilai radio button yang dipilih
      $('.' + selectedValue).show();
    });
  }
}

function dynamicDepartmentInput() {
  let departmentInput = $('.departmentInput');
  if (departmentInput.length !== 0) {
    departmentInput.on('change', function () {
      $.ajax({
        url: baseUrl + "api/department/section/" + $(this).val(),
        type: "GET",
        dataType: "json",
        success: function (response) {
          $('#section').empty()
          // Ketika panggilan berhasil
          // Lakukan sesuatu dengan data yang diterima
          // Misalnya, tampilkan data dalam bentuk daftar
          $.each(response, function (index, item) {
            $('#section').append($('<option>', {
              value: item.sectionId,
              text: item.sectionName
            }));
          });
        },
        error: function (xhr, status, error) {
          // Ketika terjadi kesalahan dalam panggilan
          console.error("Terjadi kesalahan:", error);
          // Lakukan penanganan kesalahan, misalnya tampilkan pesan kepada pengguna
        }
      });
    })
  }
}

function dynamicImprovementRecommendation() {
  let addRecommendationSelector = $('.add-recommendation');
  if (addRecommendationSelector.length !== 0) {
    addRecommendationSelector.click(function () {
      // Mendapatkan elemen rekomendasi tindakan perbaikan
      const recommendationForm = $('.form-group.row.mb-4.improvementRecommendation');
      const newRecommendationForm = recommendationForm[0].cloneNode(true);

      // Ensure the input field in the cloned form is empty
      newRecommendationForm.querySelector('input[name="improvement_recommendation[]"]').value = '';

      // Menghapus tombol tambah pada form yang diduplikasi
      newRecommendationForm.querySelector('.add-recommendation')?.remove();

      // Create delete button
      const deleteButton = document.createElement('button');
      deleteButton.type = 'button';
      deleteButton.className = 'btn btn-danger delete-recommendation';
      deleteButton.textContent = 'Hapus';

      // Append the delete button to the cloned form
      newRecommendationForm.querySelector('.col-sm-12.col-md-2').appendChild(deleteButton);

      // Menambahkan elemen form yang diduplikasi ke dalam div tambahan
      document.getElementById('additional-recommendations').appendChild(newRecommendationForm);

      // Add event listener to the delete button
      deleteButton.addEventListener('click', function () {
        newRecommendationForm.remove();
      });
    });
  }
}

// Handle delete for existing forms generated by PHP
$(document).on('click', '.delete-recommendation', function () {
  $(this).closest('.form-group.row.mb-4.improvementRecommendation').remove();
});


$('#exampleModalHazard').on('show.bs.modal', function (event) {
  let hazardId = $(event.relatedTarget).data('hazard-id')
  $(this).find('.modal-content form').attr('action', baseUrl + "admin/hazard-identification/edit/" + hazardId)
})

function datepickerFunc() {
  if (jQuery().datepicker) {
    $('#date-pick-month').datepicker
    ({
      format: "yyyy-mm",
      minViewMode: 1,
      autoclose: true,
    });
    $('#date-pick-year').datepicker
    ({
      format: "yyyy",
      minViewMode: 2,
      autoclose: true,
    });

    $('#date-pick-month-1').datepicker
    ({
      format: "yyyy-mm",
      minViewMode: 1,
      autoclose: true,
    });
    $('#date-pick-year-1').datepicker
    ({
      format: "yyyy",
      minViewMode: 2,
      autoclose: true,
    });
  }
}

function checkNotifications() {
  let notificationContainer = $('.notifications');
  if (notificationContainer.length !== 0) {
    $.ajax({
      url: `${baseUrl}notifications/check-notifications`,
      method: 'GET',
      success: function (data) {
        if (data.status === 'success') {
          let notifications = data.data;
          let notificationIcon = $('#notificationIcon');
          let notificationContainer = $('.dropdown-list-content');

          // Hapus notifikasi lama
          notificationContainer.empty();

          // Tambahkan notifikasi baru
          if (notifications.length > 0) {
            notifications.forEach(notification => {
              let notificationElement = `
                            <a href="${baseUrl}/external/hazard-identification/view/${notification.hazard_identification_id}/${notification.notification_id}" class="dropdown-item dropdown-item-unread">
                                <div class="dropdown-item-icon bg-primary text-white">
                                    <i class="fas fa-code"></i>
                                </div>
                                <div class="dropdown-item-desc">
                                    ${notification.message}
                                    <div class="time text-primary">${notification.created_at}</div>
                                </div>
                            </a>
                        `;
              notificationContainer.append(notificationElement);
            });

            // Tambahkan class 'beep' jika ada notifikasi baru
            notificationIcon.addClass('beep');
          } else {
            // Hapus class 'beep' jika tidak ada notifikasi baru
            notificationIcon.removeClass('beep');
          }
        }
      },
      error: function (error) {
        console.error('Error:', error);
      }
    });
  }
}

// Set interval untuk polling setiap 6 detik
setInterval(checkNotifications, 6000);

