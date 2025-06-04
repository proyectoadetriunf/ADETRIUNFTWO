$(document).ready(function () {
    function abrirModalFormulario(titulo, rutaFormulario) {
        $('#modalLabel').text(titulo);
        $('#contenidoDelModal').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>');
        $('#formularioModal').modal('show');

        $.get(rutaFormulario, function (data) {
            $('#contenidoDelModal').html(data);
        });
    }

    $(document).on('click', '#abrirModalBeneficiario', function (e) {
        e.preventDefault();
        abrirModalFormulario('Registrar beneficiario', '/beneficiarios/create');
    });

    $(document).on('click', '#abrirModalAvance', function (e) {
        e.preventDefault();
        abrirModalFormulario('Registrar avance', '/avances/create');
    });

    $(document).on('click', '#abrirModalDocumentacion', function (e) {
        e.preventDefault();
        abrirModalFormulario('Subir documentación', '/documentos/upload');
    });

    $(document).on('click', '#abrirModalSolicitud', function (e) {
        e.preventDefault();
        abrirModalFormulario('Crear solicitud', '/solicitudes/create');
    });
});
