loadData();
btnAction = "Insert";

let fileImage = document.querySelector('#image');
let showInput = document.querySelector('#show');
const reader = new FileReader();
fileImage.addEventListener('change', function(e) {
    const selectedFile = e.target.files[0];
    reader.readAsDataURL(selectedFile);
})
reader.onload = e => {
    showInput.src = e.target.result;
}

$('#addNew').on('click', function(){
    $('#userModal').modal('show');
});


function displayMessage(type, message){
    let success = document.querySelector(".alert-success");
    let error = document.querySelector(".alert-danger");
    if(type == 'success'){
        error.classList = 'alert alert-danger d-none';
        success.classList = 'alert alert-success';
        success.innerHTML = message;

        setTimeout(function(){
            $('#expenseModal').modal('hide');
            success.classList = 'alert alert-success d-none';
            $('#expenseForm')[0].reset();
        },3000);
    }else{
        error.classList = 'alert alert-danger';
        error.innerHTML = message;
    }
}


function loadData(){
    $('#userTable tr').html('');
    let sendingData = {
        'action' : 'get_users_list'
    }
    $.ajax({
        method: 'POST',
        dataType: 'JSON',
        url: '../api/users.php',
        data: sendingData,
        success: function(data){
            let status = data.status;
            let response = data.data;
            let html = "";
            let tr = "";
            let th = "";
            if(status){
                response.forEach(res => {
                    th = '<tr>';
                    for (let i in res){
                        th += `<th>${i}</th>`;
                    }
                    th += '<th>Action</th></tr>';
                    tr += '<tr>'
                    for(let r in res){
                        if(r == 'image'){
                                tr += `<td><img style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;"
                                src="../${res[r]}"</td>`;
                            
                        }else{
                            tr += `<td>${res[r]}</td>`;
                        }
                    }
                    tr += `<td><a class="btn btn-info update_info" update_id=${res['id']}>
                    <i class="fas fa-edit" style="color:#fff;"></i></a>&nbsp;&nbsp;<a class="btn btn-danger
                    detete_info" delete_id=${res['id']}><i class="fas fa-trash" style="color:#fff";></i></a></td>`;
                    tr += '</tr>';
                })
                $('#userTable thead').append(th);
                $('#userTable tbody').append(tr);
            }else{
                displayMessage('error', response);
            }
        },
        error: function(data){

        }
    })
}

$('#userForm').on('submit', function(event){
    event.preventDefault();

    let form_data = new FormData($("#userForm")[0]);
    form_data.append("image", $("input[type=file]")[0].files[0]);

    //console.log(id);

    if (btnAction == 'Insert'){
        form_data.append("action", "register_user");
    }else{
        
    }

    $.ajax({
        method: 'POST',
        dataType: 'JSON',
        url: '../api/users.php',
        data: form_data,
        processData: false,
        contentType: false,
        success: function(data){
            let status = data.status;
            let response = data.data;
            if(status){
                displayMessage('success', response);
                btnAction = 'Insert';
                $('userForm')[0].reset();
                loadData();
            }else{
                displayMessage('error', response);
            }
        },
        error: function(data){

        }
    })
});





function fetchExpenseInfo(id){
    let sendingData = {
        'action' : 'get_expense_info',
        'id': id
    }
    $.ajax({
        method: 'POST',
        dataType: 'JSON',
        url: '../api/expense.php',
        data: sendingData,
        success: function(data){
            let status = data.status;
            let response = data.data;
            let html = "";
            let tr = "";
            if(status){
                btnAction = "Update";
                $("#update_id").val(response['id']);
                $("#amount").val(response['amount']);
                $("#type").val(response['type']);
                $("#description").val(response['description']);
                $('#expenseModal').modal('show');
            }
        },
        error: function(data){

        }
    })
}

function deleteExpenseInfo(id){
    let sendingData = {
        'action' : 'delete_expense_info',
        'id': id
    }
    $.ajax({
        method: 'POST',
        dataType: 'JSON',
        url: '../api/expense.php',
        data: sendingData,
        success: function(data){
            let status = data.status;
            let response = data.data;
            let html = "";
            let tr = "";
            if(status){
                swal("Good job!", response, "success");
                loadData();
            }else{
                swal(response);
            }
        },
        error: function(data){

        }
    })
}

$("#expenseTable").on("click", "a.update_info",function(){
    let id = $(this).attr("update_id");
    fetchExpenseInfo(id);
});


$("#expenseTable").on("click", "a.detete_info",function(){
    let id = $(this).attr("delete_id");
    //console.log(id);    
    if(confirm('Are you sure to delete ')){
        deleteExpenseInfo(id);
    }
});
