$(document).ready(function() {
    var dtable=$('#dtable').DataTable( {
      "columnDefs": [ {
          "targets": -1,
          "data": null,
          "defaultContent": "<button class='delete-btn btn btn-danger'>Delete</button>"
      } ],

      "order": [[ 1, 'asc' ]],
        "ajax": "server.php?task=getvhosts",
        "columns": [
            { "data": null },
            { "data": "ServerName" },
            { "data": "Directory" },
            { "data": null },
        ]
    } );
    dtable.on( 'order.dt search.dt', function () {
        dtable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    function reloadDTable(){
       dtable.ajax.reload();
    }
    $('#dtable tbody').on( 'click', 'button', function () {
        var data = dtable.row( $(this).parents('tr') ).data();
        //console.log( data.ServerName  );
        $.ajax({
          url: 'server.php?task=deletevhost&&servername='+data.ServerName,
          type: 'get',
          success: function (data, status)
          {
            console.log(data);
            reloadDTable();
          },
          error: function (xhr, desc, err)
          {
            console.log(err);
          }
        });
    } );



    $.ajax({
      url: 'server.php?task=getvhostlocation',
      type: 'get',
      success: function (data, status)
      {
        var vhostlocation = JSON.parse(data).vhost;
        $('#vhostpath').val(vhostlocation);
      },
      error: function (xhr, desc, err)
      {
        console.log(err);
      }
    });

    $('#vhost-location-form').on('submit', function(e){

        e.preventDefault();
        var fpath = $(this).find('[name=vhost]').val();
        var fdata = {vhost: fpath};
         $.ajax($(this).attr("action"), {
            type: $(this).attr("method"),
            data: fdata,

            success: function (data, status)
            {
              console.log(data);
              reloadDTable();
            },
            error: function (xhr, desc, err)
            {
              console.log(err);
            }
        });

    });
    $('#vhost-form').on('submit', function(e){

        e.preventDefault();
        var fdata = $(this).serializeArray();
         $.ajax($(this).attr("action"), {
            type: $(this).attr("method"),
            data: fdata,

            success: function (data, status)
            {
              console.log(data);
              reloadDTable();
            },
            error: function (xhr, desc, err)
            {
              console.log(err);
            }
        });

    });
} );
