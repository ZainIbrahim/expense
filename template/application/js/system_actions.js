loadData();
fill_links();
btnAction = "Insert";

$('#addNew').on('click', function(){
    $('#actionModal').modal('show');
});



function displayMessage(type, message){
    let success = document.querySelector(".alert-success");
    let error = document.querySelector(".alert-danger");
    if(type == 'success'){
        error.classList = 'alert alert-danger d-none';
        success.classList = 'alert alert-success';
        success.innerHTML = message;

        setTimeout(function(){
            $('#actionModal').modal('hide');
            success.classList = 'alert alert-success d-none';
            $('#actionform')[0].reset();
        },3000);
    }else{
        error.classList = 'alert alert-danger';
        error.innerHTML = message;
    }
}

function fill_links(){
    let sendingData = {
        'action' : 'read_all_db_links'
    }
    $.ajax({
        method: 'POST',
        dataType: 'JSON',
        url: '../api/system_links.php',
        data: sendingData,
        success: function(data){
            let status = data.status;
            let response = data.data;
            let html = "";
            let tr = "";
            if(status){
                response.forEach(res => {
                    html += `<option value ='${res['id']}'>${res['name']}</option>`
                })
                $("#link_id").append(html);
            }else{
                displayMessage('error', response);
            }
        },
        error: function(data){

        }
    })
}


function loadData(){
    $('#actionTable tbody').html('');
    let sendingData = {
        'action' : 'read_all_system_actions'
    }
    $.ajax({
        method: 'POST',
        dataType: 'JSON',
        url: '../api/system_actions.php',
        data: sendingData,
        success: function(data){
            let status = data.status;
            let response = data.data;
            let html = "";
            let tr = "";
            if(status){
                response.forEach(res => {
                    tr += '<tr>'
                    for(let r in res){
                            tr += `<td>${res[r]}</td>`;
                    }
                    tr += `<td><a class="btn btn-info update_info" update_id=${res['id']}>
                    <i class="fas fa-edit" style="color:#fff;"></i></a>&nbsp;&nbsp;<a class="btn btn-danger
                    detete_info" delete_id=${res['id']}><i class="fas fa-trash" style="color:#fff";></i></a></td>`;
                    tr += '</tr>';
                })
                $('#actionTable tbody').append(tr);
            }else{
                displayMessage('error', response);
            }
        },
        error: function(data){

        }
    })
}

$('#actionForm').on('submit', function(event){
    event.preventDefault();
    let name = $('#name').val();
    let link = $('#link_id').val();
    let system_action = $('#system_action').val();

    let sendingData = {}

    if (btnAction == 'Insert'){
        sendingData = {
            'name': name,
            'link_id': link,
            'system_action': system_action,
            'action': "register_system_action",
        }
    }else{
        sendingData = {
            'name': name,
            'link_id': link,
            'system_action': system_action,
            'action': "update_system_action",
            'id' : bar2,
        }
    }

    $.ajax({
        method: 'POST',
        dataType: 'JSON',
        url: '../api/system_actions.php',
        data: sendingData,
        success: function(data){
            let status = data.status;
            let response = data.data;
            if(status){
                displayMessage('success', response);
                btnAction = 'Insert';
                loadData();
            }else{
                displayMessage('error', response);
            }
        },
        error: function(data){
            displayMessage('error', data.responseText);
        }
    })
});

function fetchActionInfo(id){
    let sendingData = {
        'action' : 'get_action_info',
        'id': id
    }
    $.ajax({
        method: 'POST',
        dataType: 'JSON',
        url: '../api/system_actions.php',
        data: sendingData,
        success: function(data){
            let status = data.status;
            let response = data.data;
            let html = "";
            let tr = "";
            if(status){
                btnAction = "Update";
                $("#update_id").val(response['id']);
                $("#name").val(response['name']);
                $("#link_id").val(response['link_id']);
                $("#system_action").val(response['action']);
                $('#actionModal').modal('show');
            }
        },
        error: function(data){

        }
    })
}

function deleteActionInfo(id){
    let sendingData = {
        'action' : 'delete_action_info',
        'id' : id
    }
    $.ajax({
        method: 'POST',
        dataType: 'JSON',
        url: '../api/system_actions.php',
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

$("#actionTable").on("click", "a.update_info",function(){
    let id = $(this).attr("update_id");
    fetchActionInfo(id);
    window.bar2 = id;

});


$("#actionTable").on("click", "a.detete_info",function(){
    let id = $(this).attr("delete_id");
    if(confirm('Are you sure to delete ')){
        deleteActionInfo(id);
    }
});
