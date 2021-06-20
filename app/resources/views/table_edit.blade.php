<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Main table</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-tabledit@1.0.0/jquery.tabledit.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>
        <div class="container">
            <br />
            <h3 style="text-align:center">Main table</h3>
            <br />
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">User List</h3>
                </div>
                <div class="panel-body">
                    <div style="display: flex; justify-content: center; margin-bottom: 20px">
                        <div style="display: flex; justify-content: center">
                            <input type="text" class="form-control" id="input-search">
                            <button id="search" type="button" class="btn btn-default">Search</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        @csrf
                        <table id="editable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>UserName</th>
                                <th>Email</th>
                                <th>Birthdate</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<script type="text/javascript">
    function renderTableEdit() 
    {
        $('#editable').Tabledit({
            url:'{{ route("maintable.edit") }}',
            dataType:"json",
            columns:{
                identifier:[0, 'id'],
                editable:[[1, 'name'], [2, 'email'], [3, 'birthdate']]
            },
            restoreButton: false,
            deleteButton: false,
            onFail: function(jqXHR, textStatus, errorThrown) {
                let errorList = '';

                for (const [key, value] of Object.entries(jqXHR.responseJSON.error)) {
                    errorList += `${value}\r\n`;
                }

                alert(errorList);
            },
        });
    }

    function getCustomers(key = '', isGetAll = 0)
    {   
        $.ajax({
            url: '{{ route("maintable.search") }}' + '?key=' + key + '&get_all=' + isGetAll,
            success: function(response) {
                let newMarkup = '';
                response.customers.forEach((customer) => {
                    newMarkup += `<tr>
                        <td>${customer.id}</td>
                        <td>${customer.name}</td>
                        <td>${customer.email}</td>
                        <td>${customer.birthdate}</td>
                    </tr>`
                });
                $('#editable tbody').html(newMarkup)
                renderTableEdit();
            },
            error: function (data) {
                if (data.status === 401) {
                    window.location.replace('{{route('login')}}');
                }
            },
        });
    }

    $(document).ready(function(){
        $.ajaxSetup({
            headers:{
                'X-CSRF-Token' : $("input[name=_token]").val(),
                'Authorization': 'Bearer ' + localStorage.getItem('admin_token')
            }
        });
        
        getCustomers('', 1);

        $('#search').on('click', function() {
            const value = $('#input-search').val();
            getCustomers(value);
        });


        
    });
</script>
