<?php
include 'header.php';
include 'sidebar.php';
?>
<style>
fieldset.authority-border{
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow: 0px 0px 0px 0px #000;
            box-shdow: 0px 0px 0px 0px #000;
}
legend.authority-border{
    width: inherit;
    padding: 0 10px;
    border-bottom: none;
}
input[type=checkbox]{
    transform: scale(1.5);
}
#all_authority{
    transform: scale(2);
}
</style>
<div class="pcoded-main-container">

        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <!-- [ breadcrumb ] start -->

                    <!-- [ breadcrumb ] end -->
                    <div class="main-body">
                        <div class="page-wrapper">
                            
                            <!-- [ Main Content ] start -->
                            <div class='row'>
                            <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>User Authority</h5>
                                            <span class="d-block m-t-5">use class <code>table</code> inside table element</span>
                                        </div>
                                        <div class="card-block table-border-style">
                                            <form id='userForm'>
                                        <div class='row'>

                                        <div class="col-sm-12">
                                        <div class="alert alert-success d-none" role="alert">
                                            This is a success alert—check it out!
                                            </div>
                                            <div class="alert alert-danger d-none" role="alert">
                                            This is a danger alert—check it out!
                                            </div>
                                        </div>

                                <div class='col-sm-12'>
                                    <select id='user_id' name='user_id' class='form-control'>
                                    <option value = '0'>Select User</option>
                                    </select>
                                </div>


                            </div> 

                            <div class='row'>

                                <div class='col-sm-12'>
                                    <fieldset class='authority-border'>
                                        <legend class='authority-border'>
                                            <input id='all_authority' name= 'all_authority' type='checkbox'>
                                            All Authorities
                                        </legend>


                                        <div class='row' id='authorityArea'>

                                           <!-- 
                                            <div class='col-sm-4'>
                                                <fieldset class='authority-border'>
                                                    <legend class='authority-border'>
                                                    <input id='subscriber' name= 'subscriber' type='checkbox'>
                                                        Subscriber
                                                    </legend>
                                                    <label for=''>
                                                        <input type='checkbox' id='' name='' style="margin-left:30px;">
                                                        Dashboard
                                                    </label>
                                                    <div class='link_actions'>
                                                    <label for=''>
                                                        <input type='checkbox' id='' name='' style="margin-left:45px;">
                                                        Update
                                                    </label>
                                                    </div>
                                                    <div class='link_actions'>
                                                    <label for=''>
                                                        <input type='checkbox' id='' name='' style="margin-left:45px;">
                                                        Update
                                                    </label>
                                                    </div>
                                                    <div class='link_actions'>
                                                    <label for=''>
                                                        <input type='checkbox' id='' name='' style="margin-left:45px;">
                                                        Update
                                                    </label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class='col-sm-4'>
                                                <fieldset class='authority-border'>
                                                    <legend class='authority-border'>
                                                    <input id='subscriber' name= 'subscriber' type='checkbox'>
                                                        Subscriber
                                                    </legend>
                                                    <label for=''>
                                                        <input type='checkbox' id='' name='' style="margin-left:30px;">
                                                        Dashboard
                                                    </label>
                                                    <div class='link_actions'>
                                                    <label for=''>
                                                        <input type='checkbox' id='' name='' style="margin-left:45px;">
                                                        Update
                                                    </label>
                                                    </div>
                                                    <div class='link_actions'>
                                                    <label for=''>
                                                        <input type='checkbox' id='' name='' style="margin-left:45px;">
                                                        Update
                                                    </label>
                                                    </div>
                                                    <div class='link_actions'>
                                                    <label for=''>
                                                        <input type='checkbox' id='' name='' style="margin-left:45px;">
                                                        Update
                                                    </label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class='col-sm-4'>
                                                <fieldset class='authority-border'>
                                                    <legend class='authority-border'>
                                                    <input id='subscriber' name= 'subscriber' type='checkbox'>
                                                        Subscriber
                                                    </legend>
                                                    <label for=''>
                                                        <input type='checkbox' id='' name='' style="margin-left:30px;">
                                                        Dashboard
                                                    </label>
                                                    <div class='link_actions'>
                                                    <label for=''>
                                                        <input type='checkbox' id='' name='' style="margin-left:45px;">
                                                        Update
                                                    </label>
                                                    </div>
                                                    <div class='link_actions'>
                                                    <label for=''>
                                                        <input type='checkbox' id='' name='' style="margin-left:45px;">
                                                        Update
                                                    </label>
                                                    </div>
                                                    <div class='link_actions'>
                                                    <label for=''>
                                                        <input type='checkbox' id='' name='' style="margin-left:45px;">
                                                        Update
                                                    </label>
                                                    </div>
                                                </fieldset>
                                            </div>-->
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <button type='submit' id='submit' class='btn btn-info m-3'>Authorise User</button>

                                </form>   
                                        </div>
                                    </div>
                                </div>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>






<?php
include 'footer.php';
?>
<script src="../js/user_authority.js"></script>
