
  $('#fileUserUpload').change(function () {
    console.log('File added');
    var ext = $('#fileUserUpload').val().split('.').pop().toLowerCase();
    if ($.inArray(ext, ['csv']) == -1) {
      $('#userUploadBtn').prop('disabled', true);
      $('#fileErr').html('Invalid file type');
      $('#fileErr').style.color = 'red';
    } else {
      $('#userUploadBtn').prop('disabled', false);
      $('#fileErr').html('');
    }
  });


        // when the modal is opened get the data from the row and set the values of the inputs to the data from the row
         let editModal = document.getElementById("editUserModal");
         editModal.addEventListener("show.bs.modal", function (event) {
            // Button that triggered the modal
            let button = event.relatedTarget;
            // Extract info from data-bs-* attributes
            let uId = button.getAttribute("data-bs-id")
            let uFirstname = button.getAttribute("data-bs-firstname")
            let uSurname = button.getAttribute("data-bs-surname")
            let uEmail = button.getAttribute("data-bs-email")
            let uRole = button.getAttribute("data-bs-role")
              // Update the modal content with the data
              //get all the inputs from the modal body and set them to the data from the row
              let modalBodyInputId = editModal.querySelector("#editId")
              let modalBodyInputFirstname = editModal.querySelector("#editFirstName")
              let modalBodyInputSurname = editModal.querySelector("#editSurname")
              let modalBodyInputEmail = editModal.querySelector("#editEmail")
              let modalBodyInputRole = editModal.querySelector("#editRole")
              //set the values of the inputs to the data from the row
              modalBodyInputId.value = uId
              modalBodyInputFirstname.value = uFirstname
              modalBodyInputSurname.value = uSurname
              modalBodyInputEmail.value = uEmail
              modalBodyInputRole.value = uRole
          });

          let delUserModal = document.getElementById("deleteUserConfirm");
          delUserModal.addEventListener("show.bs.modal", function (event){
             let delBtn = event.relatedTarget
             let delUId = delBtn.getAttribute("data-bs-id")
             let delUser = delUserModal.querySelector("#delUser")
             delUser.value = delUId
          })



          //ajax request to with the search bar
      /*    $('#search').keyup(function () {
            var search = $(this).val();
            if (search != '') {
              $.ajax({
                url: "search.php",
                method: "post",
                data: { query: search },
                success: function (response) {
                  $('#userTable').html(response);
                }
              });
            } else {
              load_data();
            }
          }
          );
          */  


