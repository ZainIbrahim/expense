loadData();
fill_links();
fill_categories();
btnAction = "Insert";

$('#addNew').on('click', function(){
    $('#linkModal').modal('show');
});



function displayMessage(type, message){
    let success = document.querySelector(".alert-success");
    let error = document.querySelector(".alert-danger");
    if(type == 'success'){
        error.classList = 'alert alert-danger d-none';
        success.classList = 'alert alert-success';
        success.innerHTML = message;

        setTimeout(function(){
            $('#linkModal').modal('hide');
            success.classList = 'alert alert-success d-none';
            $('#linkForm')[0].reset();
        },3000);
    }else{
        error.classList = 'alert alert-danger';
        error.innerHTML = message;
    }
}

function fill_links(){
    let sendingData = {
        'action' : 'readAllLinks'
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
                    html += `<option value ='${res}'>${res}</option>`
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

function fill_categories(){
    let sendingData = {
        'action' : 'read_all_category'
    }
    $.ajax({
        method: 'POST',
        dataType: 'JSON',
        url: '../api/category.php',
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
                $("#category_id").append(html);
            }else{
                displayMessage('error', response);
            }
        },
        error: function(data){

        }
    })
}


function loadData(){
    $('#linkTable tbody').html('');
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
                    tr += '<tr>'
                    for(let r in res){
                            tr += `<td>${res[r]}</td>`;
                    }
                    tr += `<td><a class="btn btn-info update_info" update_id=${res['id']}>
                    <i class="fas fa-edit" style="color:#fff;"></i></a>&nbsp;&nbsp;<a class="btn btn-danger
                    detete_info" delete_id=${res['id']}><i class="fas fa-trash" style="color:#fff";></i></a></td>`;
                    tr += '</tr>';
                })
                $('#linkTable tbody').append(tr);
            }else{
                displayMessage('error', response);
            }
        },
        error: function(data){

        }
    })
}

$('#linkForm').on('submit', function(event){
    event.preventDefault();
    let name = $('#name').val();
    let link = $('#link_id').val();
    let category = $('#category_id').val();
    let id = $('#update_id').val();

    //console.log(id);

    let sendingData = {}

    if (btnAction == 'Insert'){
        sendingData = {
            'name': name,
            'link': link,
            'category': category,
            'action': "register_link",
        }
    }else{
        sendingData = {
            'name': name,
            'link': link,
            'category': category,
            'action': "update_link",
            'id' : bar2,
        }
    }

    $.ajax({
        method: 'POST',
        dataType: 'JSON',
        url: '../api/system_links.php',
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

function fetchlinkInfo(id){
    let sendingData = {
        'action' : 'get_link_info',
        'id': id
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
                btnAction = "Update";
                $("#update_id").val(response['id']);
                $("#name").val(response['name']);
                $("#link_id").val(response['link']);
                $("#category_id").val(response['category_id']);
                $('#linkModal').modal('show');
            }
        },
        error: function(data){

        }
    })
}

function deletelinkInfo(id){
    let sendingData = {
        'action' : 'delete_link_info',
        'id' : id
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

$("#linkTable").on("click", "a.update_info",function(){
    let id = $(this).attr("update_id");
    fetchlinkInfo(id);
    window.bar2 = id;

});


$("#linkTable").on("click", "a.detete_info",function(){
    let id = $(this).attr("delete_id");
    
    //console.log(id);    
    if(confirm('Are you sure to delete ')){
        deletelinkInfo(id);
    }
});
