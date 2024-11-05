loadUserMenus();

function loadUserMenus(){
    let sendingData = {
        'action' : 'get_user_menus'
    }
    $.ajax({
        method: 'POST',
        dataType: 'JSON',
        url: '../api/user_authority.php',
        data: sendingData,
        success: function(data){
            let status = data.status;
            let response = data.data;
            let html = "";
            let menuElement = "";
            let category = "";
            if(status){
                response.forEach(menu => {
                    if(menu['category_name'] !== category){
                        if(category !== ''){
                            menuElement += '</ul></li>';
                        }
                        menuElement += `
                            <li data-username="basic components Button Alert Badges breadcrumb Paggination progress
                            Tooltip popovers Carousel Cards Collapse Tabs pills Modal Grid System Typography Extra
                            Shadows Embeds" class="nav-item pcoded-hasmenu">
                            <a href="javascript:" class = "nav-link"><span class="pcoded-micon"> <i class='feather 
                            icon-box'> </i></span><span class ="pcoded-mtext">${menu['category_name']}</span></a>
                            <ul class="pcoded-submenu">
                        `;
                        category = menu['category_name'];
                    }

                })
                $("#user_menu").append(menuElement);
                
            }else{
                displayMessage('error', response);
            }
        },
        error: function(data){

        }
    })
}

