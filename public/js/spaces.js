(function($, AdminLTE){
    
    "use strict";
    
    SmartHome.Spaces = {
        oTable: null,
        
        init: function() {
            this.attachEvents();
            
            this.loadSpaces();
        },
        
        attachEvents: function() {
            $("#createSpace").on('click', function(){
                SmartHome.Spaces.showCreateSpaceModal();
            });
            
            $("#createSpaceForm").submit(function(e){
                e.preventDefault();
                SmartHome.Spaces.createSpace();
            });
            
            $("table").on('click', '.delete-space', function(){
                SmartHome.Spaces.showDeleteSpaceModal($(this).attr("data-id"), $(this).attr("data-name"));
            });
            
            $("#deleteSpaceForm").submit(function(e){
                e.preventDefault();
                SmartHome.Spaces.deleteSpace();
            });
        },
        
        showCreateSpaceModal: function() {
            $('#createSpaceModal').modal('show');
            
            $("#createSpaceModal").on("hidden.bs.modal", function () {
                $("#createSpaceForm").find("input").val("")
                $("#createSpaceForm").find("textarea").val("")
            });
        },
        
        createSpace: function() {
            $.post(
                SmartHome.baseUri + 'spaces/create', 
                $('#createSpaceForm').serialize()
            )
            .done(function(data) {
                $('#createSpaceModal').modal('hide');
                SmartHome.AlertManager.showAlertSuccess("Sucesso", "Espaço <strong>" + data.space.name + "</strong> cadastrado.");
                SmartHome.Spaces.loadSpaces();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                SmartHome.AlertManager.showAlertDanger("Opss", jqXHR.responseJSON.error.message);
            });
        },
        
        showDeleteSpaceModal: function(id, name) {
            $('#deleteSpaceModalTitle').find("span").text(name);
            $("#deleteSpaceId").val(id);
            $('#deleteSpaceModal').modal('show');
        },
        
        deleteSpace: function() {
            var id = $("#deleteSpaceId").val();
            
            $.get(
                SmartHome.baseUri + 'spaces/delete/'  + id
            )
            .done(function(data) {
                $('#deleteSpaceModal').modal('hide');
                
                SmartHome.AlertManager.showAlertSuccess("Sucesso", "Spaço <strong>" + data.space.name + "</strong> removido.");
                
                SmartHome.Spaces.loadSpaces();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                $('#deleteSpaceModal').modal('hide');
                SmartHome.AlertManager.showAlertDanger("Opss", jqXHR.responseJSON.error.message);
            });
        },
        
        loadSpaces: function() {
            if (this.oTable) {
                this.oTable.fnDestroy();
            }
            
            this.oTable = $('#spaces').dataTable( {
                "drawCallback": function( settings ) {
                    $('[data-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    });
                },
                "bAutoWidth": false,
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": SmartHome.baseUri + "spaces/search",
                "columns": [
                    {
                        "data": "id"
                    },
                    { 
                        "data": "name" 
                    },
                    { 
                        "data": "opcoes", 
                        "bSortable": false,
                        "sDefaultContent": "",
                        "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            var options = $("#spacesOptionsTemplates").clone().children();
                            
                            $(options[0]).attr({
                                "id": "space-" + oData.id,
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
        SmartHome.Spaces.init();
    });
    
})(jQuery, $.AdminLTE);