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
            
            $("table").on('click', '.act-device', function(){
                SmartHome.Devices.loadActions($(this).attr("data-id"), $(this).find(".actions"));
            });
            
            $("table").on('click', '.action', function(e){
                e.preventDefault();
                SmartHome.Devices.handleActSelection($(this).attr("data-device"), $(this).attr("data-act"));
            });
            
            $("#deviceActForm").submit(function(e){
                e.preventDefault();
                $('#createDeviceModal').modal('hide');
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
        
        loadActions: function(id, list) {
            if (list.html().trim().length == 0) {
                $.get(
                    SmartHome.baseUri + 'devices/actions/'  + id
                )
                .done(function(data) {
                    console.log(data);
                    if (data.actions.length == 0) {
                        list.append('<li class="disabled"><a href="#">Sem Ações</a></li>');
                    }
                    else {
                        for(var i = 0; i < data.actions.length; i++) {
                            var action = $('<li class="action"><a href="#">' + data.actions[i].name + '</a></li>');

                            action.attr('data-act', data.actions[i].action);
                            action.attr('data-device', id);

                            list.append(action);
                        }
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    list.append('<li class="disabled"><a href="#">Sem Ações</a></li>');
                });
            }
        },
        
        handleActSelection: function(id, device) {    
            $.post(
                SmartHome.baseUri + 'devices/actionInfo/',
                {
                    id: id,
                    action: device
                }
            )
            .done(function(data) {
                if (data.action.hasOwnProperty('parameters')) {
                    $("#actParameters").empty();
                    for(var i = 0; i < data.action.parameters.length; i++) {
                        var parameter = $('<div class="form-group"></div>');
                        var label = '<label for="' + data.action.parameters[i]['parameter'] + '">' + data.action.parameters[i]['name'] + '</label>';
                        var input = '<input type="text" class="form-control parameter" name="' + data.action.parameters[i]['parameter'] + '" placeholder="Informe o valor">';
                            
                        parameter.append(label);
                        parameter.append(input);
                        $("#actParameters").append(parameter);
                    }
                    
                    $('#deviceActModalTitle').find("span").text("");
                    $("#device").val(id);
                    $("#action").val(data.action.action);
                    $('#deviceActModal').modal('show');
                }
                else {
                    SmartHome.Devices.act(id, device);
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                
            });
        },
        
        act: function(device, action, parameters = []) {
            $.post(
                SmartHome.baseUri + 'devices/act', 
                {
                    id: device,
                    action: action,
                    parameters: parameters
                }
            )
            .done(function(data) {
                SmartHome.AlertManager.showAlertSuccess("Sucesso", data.message);
                SmartHome.Devices.loadDevices();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                alert("Deu Errado");
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
                            
                            $(options[2]).attr({
                                "id": oData.id,
                                "data-id": oData.id,
                                "data-name": oData.name
                            });
                            $(nTd).append("     " + $(options[2]).wrap("<div/>").parent().html());
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