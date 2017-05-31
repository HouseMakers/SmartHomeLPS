(function($, AdminLTE){
    
    "use strict";
    
    SmartHome.Devices = {
        oTable: null,
        
        init: function() {
            this.attachEvents();
            
            this.loadDevices();
        },
        
        attachEvents: function() {
            $("#createDevice").on('click', function(){
                SmartHome.Devices.showCreateDeviceModal();
            });
            
            $("#createDeviceForm").submit(function(e){
                e.preventDefault();
                SmartHome.Devices.createDevice();
            });
            
            $("table").on('click', '.delete-device', function(){
                SmartHome.Devices.showDeleteDeviceModal($(this).attr("data-id"), $(this).attr("data-name"));
            });
            
            $("#deleteDeviceForm").submit(function(e){
                e.preventDefault();
                SmartHome.Devices.deleteDevice();
            });
        },
        
        showCreateDeviceModal: function() {
            $('#createDeviceModal').modal('show');
            
            $("#createDeviceModal").on("hidden.bs.modal", function () {
                $("#createDeviceForm").find("input").val("")
                $("#createDeviceForm").find("textarea").val("")
            });
        },
        
        createDevice: function() {
            $.post(
                SmartHome.baseUri + 'devices/create', 
                $('#createDeviceForm').serialize()
            )
            .done(function(data) {
                $('#createDeviceModal').modal('hide');
                SmartHome.AlertManager.showAlertSuccess("Sucesso", "Dispositivo <strong>" + data.device.name + "</strong> cadastrado.");
                SmartHome.Devices.loadDevices();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                SmartHome.AlertManager.showAlertDanger("Opss", jqXHR.responseJSON.error.message);
            });
        },
        
        showDeleteDeviceModal: function(id, name) {
            $('#deleteDeviceModalTitle').find("span").text(name);
            $("#deleteDeviceId").val(id);
            $('#deleteDeviceModal').modal('show');
        },
        
        deleteDevice: function() {
            var id = $("#deleteDeviceId").val();
            
            $.get(
                SmartHome.baseUri + 'devices/delete/'  + id
            )
            .done(function(data) {
                $('#deleteDeviceModal').modal('hide');
                
                SmartHome.AlertManager.showAlertSuccess("Sucesso", "Dispositivo <strong>" + data.device.name + "</strong> removido.");
                
                SmartHome.Devices.loadDevices();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                $('#deleteDeviceModal').modal('hide');
                SmartHome.AlertManager.showAlertDanger("Opss", jqXHR.responseJSON.error.message);
            });
        },
        
        loadDevices: function() {
            if (this.oTable) {
                this.oTable.fnDestroy();
            }
            
            this.oTable = $('#devices').dataTable( {
                "drawCallback": function( settings ) {
                    $('[data-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });
                },
                "bAutoWidth": false,
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": SmartHome.baseUri + "devices/search",
                "columns": [
                    {
                        "data": "id"
                    },
                    { 
                        "data": "name" 
                    },
                    { 
                        "data": "type" 
                    },
                    { 
                        "data": "status" 
                    },
                    { 
                        "data": "opcoes", 
                        "bSortable": false,
                        "sDefaultContent": "",
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            var options = $("#devicesOptionsTemplates").clone().children();
                            
                            $(options[0]).attr({
                                "id": "device-" + oData.id,
                                "data-id": oData.id,
                                "data-name": oData.name
                            });
                            $(nTd).append($(options[0]).wrap("<div/>").parent().html());
                            
                            $(options[1]).attr({
                                "id": oData.id,
                                "data-id": oData.id,
                                "data-name": oData.name
                            });
                            $(nTd).append("     " + $(options[1]).wrap("<div/>").parent().html());
                        }
                    }
                ],
                 "oLanguage": {
                    "sProcessing": "Aguarde enquanto os dados são carregados ...",
                    "sLengthMenu": "Mostrar _MENU_ registros por pagina",
                    "sZeroRecords": "Nenhum registro correspondente ao criterio encontrado",
                    "sInfoEmpty": "Exibindo 0 a 0 de 0 registros",
                    "sInfo": "Exibindo de _START_ a _END_ de _TOTAL_ registros",
                    "sInfoFiltered": "",
                    "sSearch": '',
                    "sSearchPlaceholder": "Pesquisar",
                    "oPaginate": {
                       "sFirst":    "Primeiro",
                       "sPrevious": "Anterior",
                       "sNext":     "Próximo",
                       "sLast":     "Último"
                    },
                }
            });
        }
    };
    
    $(document).ready(function() {
        SmartHome.Devices.init();
    });
    
})(jQuery, $.AdminLTE);